<?php
namespace app\modules\admin\controllers;

use app\models\KonkursNominations;
use app\models\Nomination;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use app\models\Konkurs;
use app\models\Submission;
use app\models\User;
use app\models\KonkursJury;

class KonkursController extends Controller
{
    // 🔐 Проверка прав доступа
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [[
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => fn() => in_array(Yii::$app->user->identity->role ?? 0, [-1, 1, 2]),
                ]],
            ],
        ];
    }

    // 🔥 Список конкурсов
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Konkurs::find()->orderBy(['id' => SORT_DESC]),
            'pagination' => ['pageSize' => 10],
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        $model = new Konkurs();

        if ($model->load(Yii::$app->request->post())) {
            // Конвертируем даты
            $model->start_date = date('Y-m-d', strtotime($model->start_date));
            $model->end_date = date('Y-m-d', strtotime($model->end_date));

            // 🔥 Получаем данные номинаций
            $nominationTitles = Yii::$app->request->post('NominationTitle', []);
            $nominationDescs = Yii::$app->request->post('NominationDesc', []);
            $nominationImages = UploadedFile::getInstancesByName('NominationImage');

            // 🔥 Дебаг-лог (удали после отладки)
            Yii::info('=== NOMINATION DEBUG ===', __METHOD__);
            Yii::info('Titles: ' . print_r($nominationTitles, true), __METHOD__);
            Yii::info('Images count: ' . count($nominationImages), __METHOD__);

            // 🔥 Собираем валидные записи с синхронизированными данными
            $valid = [];
            foreach ($nominationTitles as $i => $title) {
                $t = trim($title);
                $d = trim($nominationDescs[$i] ?? '');

                if ($t !== '') {
                    $valid[] = [
                        'title' => $t,
                        'desc' => $d,
                        'image_index' => $i
                    ];
                }
            }

            // 🔥 Валидация количества
            $count = count($valid);
            if ($count < 3) {
                $model->addError('title', "Добавьте минимум 3 номинации (заполнено: {$count})");
                return $this->render('form', [
                    'model' => $model,
                    'nominationRows' => max(3, $count + 1),
                    'oldTitles' => $nominationTitles,
                    'oldDescs' => $nominationDescs,
                ]);
            }
            if ($count > 5) {
                $model->addError('title', "Максимум 5 номинаций (заполнено: {$count})");
                return $this->render('form', [
                    'model' => $model,
                    'nominationRows' => 5,
                    'oldTitles' => $nominationTitles,
                    'oldDescs' => $nominationDescs,
                ]);
            }

            // 🔥 Сохраняем конкурс и номинации
            if ($model->save()) {
                $created = 0;

                foreach ($valid as $item) {
                    $desc = trim($item['desc']);
                    $hasImage = isset($nominationImages[$item['image_index']]);

                    // 🔥 Проверяем, что описание и изображение заполнены
                    if ($desc === '' || !$hasImage) {
                        $missing = [];
                        if ($desc === '') $missing[] = 'описание';
                        if (!$hasImage) $missing[] = 'изображение';

                        $model->addError('title',
                            "Номинация «{$item['title']}»: не заполнены " . implode(' и ', $missing));

                        // Возвращаем форму с ошибками (без попытки сохранить в БД!)
                        return $this->render('form', [
                            'model' => $model,
                            'nominationRows' => max(3, $count + 1),
                            'oldTitles' => $nominationTitles,
                            'oldDescs' => $nominationDescs,
                        ]);
                    }

                    // 🔥 Только если всё заполнено → создаём номинацию
                    $nom = new \app\models\Nomination();
                    $nom->title = $item['title'];
                    $nom->description = $desc;
                    $nom->image = null; // Явно обнуляем, чтобы не было сюрпризов

                    $file = $nominationImages[$item['image_index']];
                    $folder = Yii::getAlias('@webroot/images');
                    if (!is_dir($folder)) mkdir($folder, 0777, true);

                    $fileName = 'nomination_' . time() . '_' . uniqid() . '.' . $file->extension;
                    if ($file->saveAs($folder . '/' . $fileName)) {
                        $nom->image = $fileName;
                    }

                    if ($nom->save()) {
                        $rel = new \app\models\KonkursNominations();
                        $rel->konkurs_id = $model->id;
                        $rel->nomination_id = $nom->id;
                        if ($rel->save()) {
                            $created++;
                        }
                    }
                }

                Yii::$app->session->setFlash('success', "✅ Конкурс создан с {$created} номинациями");
                return $this->redirect(['index']);
            }
        }

        return $this->render('form', [
            'model' => $model,
            'nominationRows' => 3,
            'oldTitles' => [],
            'oldDescs' => [],
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // 🔥 Получаем текущие номинации
        $existingNominations = Nomination::find()
            ->innerJoin('konkurs_nominations kn', 'kn.nomination_id = nominations.id')
            ->where(['kn.konkurs_id' => $id])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->start_date = date('Y-m-d', strtotime($model->start_date));
            $model->end_date = date('Y-m-d', strtotime($model->end_date));

            // 🔥 Валидация НОВЫХ номинаций (существующие не считаем)
            $newTitles = Yii::$app->request->post('NominationTitle', []);
            $newDescs = Yii::$app->request->post('NominationDesc', []);
            $newImages = UploadedFile::getInstancesByName('NominationImage');

            $validNewTitles = array_filter(array_map('trim', $newTitles), fn($t) => $t !== '');
            $newCount = count($validNewTitles);

            // 🔥 Считаем общее количество: существующие + новые
            $totalCount = count($existingNominations) + $newCount;

            if ($totalCount < 3) {
                $model->addError('title', 'В конкурсе должно быть минимум 3 номинации (сейчас: ' . $totalCount . ')');
            } elseif ($totalCount > 5) {
                $model->addError('title', 'В конкурсе максимум 5 номинаций (сейчас: ' . $totalCount . ')');
            } else {
                if ($model->save()) {
                    // Создаём новые номинации
                    foreach ($validNewTitles as $index => $title) {
                        $nom = new Nomination();
                        $nom->title = $title;
                        $nom->description = trim($newDescs[$index] ?? '');

                        if (isset($newImages[$index])) {
                            $file = $newImages[$index];
                            $folder = Yii::getAlias('@webroot/images');
                            $fileName = 'nomination_' . time() . '_' . uniqid() . '.' . $file->extension;

                            if ($file->saveAs($folder . '/' . $fileName)) {
                                $nom->image = $fileName;
                            }
                        }

                        if ($nom->save()) {
                            $rel = new KonkursNominations();
                            $rel->konkurs_id = $model->id;
                            $rel->nomination_id = $nom->id;
                            $rel->save();
                        }
                    }

                    Yii::$app->session->setFlash('success', '✅ Конкурс обновлён');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('form', [
            'model' => $model,
            'existingNominations' => $existingNominations,
            'nominationRows' => max(0, 5 - count($existingNominations)), // Сколько ещё можно добавить
            'oldTitles' => $newTitles ?? [],
            'oldDescs' => $newDescs ?? [],
        ]);
    }


    // 🔥 Удаление конкурса
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // 🔥 Проверка: нельзя удалить конкурс с работами
        $hasWorks = Submission::find()->where(['konkurs_id' => $id])->exists();
        if ($hasWorks) {
            Yii::$app->session->setFlash('error', 'Нельзя удалить конкурс с работами. Сначала удалите все работы.');
            return $this->redirect(['index']);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Конкурс удалён');
        return $this->redirect(['index']);
    }

    // 🔥 Просмотр конкурса с работами
    public function actionView($id)
    {
        $konkurs = $this->findModel($id);

        // 🔥 Оптимизированный запрос с жадной загрузкой
        $works = Submission::find()
            ->where(['konkurs_id' => $id])
            ->with([
                'user',
                'juryRatings' => fn($q) => $q->with('nomination')
            ])
            ->orderBy(['status' => SORT_ASC, 'created_at' => SORT_DESC])
            ->all();

        // 🔥 Статистика в PHP (быстро, т.к. данные в памяти)
        $stats = [];
        foreach ($works as $work) {
            $ratings = $work->juryRatings;
            $avg = $ratings ? array_sum(array_column($ratings, 'score')) / count($ratings) : 0;

            // 🔥 Считаем голоса (если есть связь getVotes())
            $votes = method_exists($work, 'getVotes') ? $work->getVotes()->count() : 0;

            $stats[$work->id] = [
                'avg' => round($avg, 2),
                'votes' => $votes,
                'final' => round(($avg * 0.7) + ($votes * 0.3), 2),
            ];
        }

        // 🔥 ТОП-3
        uasort($stats, fn($a, $b) => $b['final'] <=> $a['final']);
        $top3 = array_slice($stats, 0, 3, true);

        return $this->render('view', [
            'konkurs' => $konkurs,
            'works' => $works,
            'stats' => $stats,
            'top3' => $top3,
        ]);
    }
    /**
     * Список конкурсов для назначения жюри
     */
    public function actionAssignJuryList()
    {
        $konkurs = Konkurs::find()
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->render('assign-jury-list', [
            'konkurs' => $konkurs
        ]);
    }
    // 🔥 Назначение жюри
    public function actionAssignJury($id)
    {
        $konkurs = $this->findModel($id);
        $users = User::find()->where(['role' => 2])->all();
        $selected = KonkursJury::find()
            ->where(['konkurs_id' => $id])
            ->select('user_id')
            ->column();

        if (Yii::$app->request->isPost) {
            // Удаляем старые связи
            KonkursJury::deleteAll(['konkurs_id' => $id]);

            // Создаём новые
            foreach (Yii::$app->request->post('jury', []) as $userId) {
                $rel = new KonkursJury();
                $rel->konkurs_id = $id;
                $rel->user_id = $userId;
                $rel->save();
            }

            Yii::$app->session->setFlash('success', 'Жюри назначено');
            return $this->redirect(['index']);
        }

        return $this->render('assign-jury', [
            'konkurs' => $konkurs,
            'users' => $users,
            'selected' => $selected,
        ]);
    }

    // 🔥 Вспомогательный метод
    protected function findModel($id)
    {
        if (($model = Konkurs::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Конкурс не найден');
    }
}