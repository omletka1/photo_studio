<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\models\Submission;

class SubmissionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [[
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => fn() => in_array(Yii::$app->user->identity->role, [-1, 1, 2]),
                ]],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => Submission::find()
                ->with('user')
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 20],
        ]);

        // 🔥 Массовое обновление статусов
        if (Yii::$app->request->isPost && Yii::$app->request->post('statuses')) {
            foreach (Yii::$app->request->post('statuses') as $id => $status) {
                $submission = Submission::findOne($id);
                if ($submission) {
                    $submission->status = (int)$status;
                    $submission->save(false);
                }
            }
            Yii::$app->session->setFlash('success', 'Статусы обновлены');
            return $this->refresh();
        }

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    // 🔥 Экспорт в CSV
    public function actionExport()
    {
        $models = Submission::find()->with('user')->all();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="submissions_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Название', 'Автор', 'Конкурс', 'Голоса', 'Статус'], ';');

        foreach ($models as $m) {
            fputcsv($output, [
                $m->id,
                $m->title,
                $m->user?->name . ' ' . $m->user?->surname,
                $m->konkurs?->title,
                $m->getVotes()->count(),
                $m->getStatusText(),
            ], ';');
        }
        fclose($output);
        exit;
    }

    /**
     * Детальный просмотр работы
     */
    public function actionView($id)
    {
        $work = Submission::findOne($id);
        if (!$work) {
            throw new NotFoundHttpException('Работа не найдена');
        }

        // 🔥 Подгружаем связанные данные
        $work = Submission::find()->with(['user', 'konkurs', 'nomination'])->where(['id' => $id])->one();

        // 🔥 Статистика: голоса, оценки жюри, комментарии
        $voteCount = \app\models\Vote::find()->where(['submission_id' => $id])->count();

        $juryRatings = \app\models\JuryRating::find()
            ->where(['submission_id' => $id])
            ->with(['user', 'nomination'])
            ->all();

        $avgScore = $juryRatings ? round(array_sum(array_column($juryRatings, 'score')) / count($juryRatings), 2) : 0;

        $comments = \app\models\JuryComment::find()
            ->where(['submission_id' => $id, 'parent_id' => null])
            ->with(['user', 'replies.user'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        // 🔥 Список изображений
        $images = [];
        for ($i = 1; $i <= 5; $i++) {
            $field = 'image' . $i;
            if (!empty($work->$field)) {
                $images[] = Yii::getAlias('@web/' . ltrim($work->$field, '/'));
            }
        }

        $comments = \app\models\JuryComment::find()
            ->where(['submission_id' => $id, 'parent_id' => null])
            ->with(['user', 'replies.user'])
            ->orderBy(['created_at' => SORT_ASC])
            ->all();

        return $this->render('view', [
            'work' => $work,
            'images' => $images,
            'voteCount' => $voteCount,
            'juryRatings' => $juryRatings,
            'avgScore' => $avgScore,
            'comments' => $comments,  // ← 🔥 Передаём комментарии
        ]);
    }
}