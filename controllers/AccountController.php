<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Submission;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

class AccountController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    // 🔥 Просмотр чужих профилей — всем авторизованным
                    [
                        'actions' => ['view-profile'],
                        'allow' => true,
                        'roles' => ['@'], // только авторизованные
                    ],
                    // 🔥 Редактирование своего профиля — только себе
                    [
                        'actions' => ['profile', 'dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return Yii::$app->user->id == Yii::$app->user->identity->id;
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionMyWorks()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Submission::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('my-works', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionProfile()
    {
        $model = Yii::$app->user->identity;

        if ($model === null) {
            return $this->redirect(['/site/login']);
        }

        if ($model->load(Yii::$app->request->post())) {

            // 🔥 Загрузка аватара
            $model->avatarFile = UploadedFile::getInstance($model, 'avatarFile');

            // 🔥 Валидация и сохранение
            // beforeSave() автоматически захеширует new_password, если он есть
            if ($model->validate() && $model->save()) {

                // Если аватар загружен — сохраняем файл на диск
                if ($model->avatarFile && $model->uploadAvatar()) {
                    // Обновляем только поле avatar (без повторной валидации)
                    $model->update(false, ['avatar']);
                }

                Yii::$app->session->setFlash('success', 'Профиль обновлён');
                return $this->redirect(['dashboard']);
            }
        }

        return $this->render('profile', [
            'model' => $model
        ]);
    }

    public function actionDashboard()
    {
        $user = Yii::$app->user->identity;
        if (!$user) return $this->redirect(['/site/login']);

        $works = Submission::find()
            ->with('konkurs')
            ->where(['user_id' => $user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $history = [];
        foreach ($works as $work) {
            // 🔥 Средний балл жюри
            $jury = (new \yii\db\Query())
                ->select(['AVG(score) as avg'])
                ->from('jury_rating')
                ->where(['submission_id' => $work->id])
                ->one();

            $avgScore = $jury['avg'] ? round((float)$jury['avg'], 1) : 0;

            // 🔥 Публичные голоса
            $voteCount = \app\models\Vote::find()
                ->where(['submission_id' => $work->id])
                ->count();

            // 🔥 Рейтинг (точно как у админа: 70% жюри + 30% народное)
            $rating = round(($avgScore * 0.7) + ($voteCount * 0.3), 1);

            $history[] = [
                'id' => $work->id,
                'title' => $work->title,
                'konkurs_title' => $work->konkurs ? $work->konkurs->title : '—',
                'votes' => $voteCount,
                'avg_score' => $avgScore,
                'rating' => $rating,
            ];
        }

        return $this->render('dashboard', [
            'user' => $user,
            'history' => $history,
        ]);
    }

    public function actionViewProfile($id)
    {
        $user = \app\models\User::findOne($id);

        if (!$user) {
            throw new \yii\web\NotFoundHttpException('Пользователь не найден');
        }

        // 🔥 Проверка прав: можно смотреть, если:
        // 1. Это свой профиль
        // 2. Или пользователь авторизован (для соц. взаимодействия)
        // 3. Админ/жюри видят всех
        $currentUser = Yii::$app->user->identity;
        $isOwnProfile = $currentUser && $currentUser->id == $user->id;
        $isAdminOrJury = $currentUser && in_array($currentUser->role, [-1, -2]); // админ или жюри

        // Если гость пытается смотреть чужой профиль — можно разрешить или нет (на твой выбор)
        // Сейчас разрешаем всем авторизованным + админам/жюри
        if (!$isOwnProfile && !$isAdminOrJury && Yii::$app->user->isGuest) {
            // Вариант А: запретить гостям
            // return $this->redirect(['/site/login']);

            // Вариант Б: разрешить, но скрыть чувствительные данные (например, email)
            // (ничего не делаем, просто продолжаем)
        }

        // 🔥 Загружаем статистику пользователя (как в дашборде)
        $works = \app\models\Submission::find()
            ->with('konkurs')
            ->where(['user_id' => $user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $history = [];
        foreach ($works as $work) {
            $jury = (new \yii\db\Query())
                ->select(['AVG(score) as avg'])
                ->from('jury_rating')
                ->where(['submission_id' => $work->id])
                ->one();

            $avgScore = $jury['avg'] ? round((float)$jury['avg'], 1) : 0;
            $voteCount = \app\models\Vote::find()->where(['submission_id' => $work->id])->count();
            $rating = round(($avgScore * 0.7) + ($voteCount * 0.3), 1);

            $history[] = [
                'id' => $work->id,
                'title' => $work->title,
                'konkurs_title' => $work->konkurs ? $work->konkurs->title : '—',
                'votes' => $voteCount,
                'avg_score' => $avgScore,
                'rating' => $rating,
            ];
        }

        return $this->render('view-profile', [
            'profileUser' => $user,        // ← чужой пользователь
            'isOwnProfile' => $isOwnProfile,
            'history' => $history,
        ]);
    }
}