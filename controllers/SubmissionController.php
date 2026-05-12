<?php

namespace app\controllers;

use app\models\JuryComment;
use app\models\Konkurs;
use app\models\Nomination;
use app\models\Submission;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\UploadedFile;

class SubmissionController extends Controller
{
    public function actionSubmission()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $model = new Submission();
        $model->user_id = Yii::$app->user->id;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->imageFile = UploadedFile::getInstances($model, 'imageFile');

            // 🔥 ПРОВЕРКА: не подавал ли пользователь уже работу в этот конкурс?
            if ($model->konkurs_id) {
                $existing = Submission::find()
                    ->where([
                        'user_id' => Yii::$app->user->id,
                        'konkurs_id' => $model->konkurs_id,
                    ])
                    ->one();

                if ($existing) {
                    $konkurs = \app\models\Konkurs::findOne($model->konkurs_id);
                    $konkursTitle = $konkurs ? $konkurs->title : 'этот конкурс';

                    Yii::$app->session->setFlash('error',
                        "Вы уже подали работу в конкурс «{$konkursTitle}». 
                    Можно подать только одну работу на конкурс.");
                    return $this->refresh();
                }
            }

            if ($model->validate() && $model->upload()) {
                Yii::$app->session->setFlash('success',
                    'Работа успешно сохранена! Загружено ' .
                    count($model->imageFile) . ' файлов.');
                return $this->redirect(['/submission/submissions']);
            } else {
                Yii::$app->session->setFlash('error',
                    'Ошибка сохранения: ' .
                    print_r($model->errors, true));
            }
        }

        return $this->render('submission', ['model' => $model]);
    }

    public function actionSubmissions()
    {
        $konkursFilter = Yii::$app->request->get('konkurs');
        $baseImageUrl = Yii::getAlias('@web/uploads/');

        // 🔥 Получаем список конкурсов ДО запроса (нужен и для AJAX)
        $konkursList = Konkurs::find()->where(['status' => 'открыт'])->all();

        $query = (new Query())
            ->select([
                'submission.*',
                'user.name as user_name',
                'user.surname as user_surname',
                'konkurs.title as konkurs_title',
                'COUNT(vote.id) as vote_count'
            ])
            ->from('submission')
            ->leftJoin('user', 'user.id = submission.user_id')
            ->leftJoin('konkurs', 'konkurs.id = submission.konkurs_id')
            ->leftJoin('vote', 'vote.submission_id = submission.id')
            ->where(['konkurs.status' => 'открыт'])
            ->groupBy('submission.id')
            ->orderBy([
                'vote_count' => SORT_DESC,
                'submission.created_at' => SORT_DESC
            ]);

        if ($konkursFilter) {
            $query->andWhere(['submission.konkurs_id' => $konkursFilter]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12],
        ]);

        // 🔥 AJAX-ответ: рендерим только часть контента
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('submissions', [
                'dataProvider' => $dataProvider,
                'baseImageUrl' => $baseImageUrl,
                'konkursList' => $konkursList,  // ✅ Теперь определена
                'konkursFilter' => $konkursFilter,
            ]);
        }

        return $this->render('submissions', [
            'dataProvider' => $dataProvider,
            'baseImageUrl' => $baseImageUrl,
            'konkursList' => $konkursList,
            'konkursFilter' => $konkursFilter,
        ]);
    }

    /**
     * Детальный просмотр работы
     */
    public function actionView($id)
    {
        $work = \app\models\Submission::findOne($id);
        if (!$work) {
            throw new \yii\web\NotFoundHttpException('Работа не найдена');
        }

        // 🔥 Подгружаем связанные данные
        $work = \app\models\Submission::find()
            ->with(['user', 'konkurs', 'nomination'])
            ->where(['id' => $id])
            ->one();

        // 🔥 Статистика
        $voteCount = \app\models\Vote::find()->where(['submission_id' => $id])->count();

        // 🔥 Изображения
        $images = [];
        for ($i = 1; $i <= 5; $i++) {
            $field = 'image' . $i;
            if (!empty($work->$field)) {
                $images[] = Yii::getAlias('@web/' . ltrim($work->$field, '/'));
            }
        }

        // 🔥 Комментарии (опционально, последние 5)
        $comments = \app\models\JuryComment::find()
            ->where(['submission_id' => $id, 'parent_id' => null])
            ->with(['user', 'replies.user'])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        return $this->render('view', [
            'work' => $work,
            'images' => $images,
            'voteCount' => $voteCount,
            'comments' => $comments,
        ]);
    }
}