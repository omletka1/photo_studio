<?php

namespace app\modules\admin\controllers;

use app\models\JuryComment;
use app\models\Konkurs;
use app\models\KonkursNominations;
use app\models\Nomination;
use app\models\Submission;
use Yii;
use app\models\JuryRating;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class AdminController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (in_array($action->id, ['admin-submissions'])) {
            if (Yii::$app->user->isGuest || Yii::$app->user->identity->role != 1) {
                throw new \yii\web\ForbiddenHttpException('Доступ запрещён.');
            }
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        // 🔥 Быстрая статистика (оптимизированные запросы)
        $stats = [
            'konkurs_total' => Konkurs::find()->count(),
            'konkurs_active' => Konkurs::find()->where(['status' => 'открыт'])->count(),
            'submissions_total' => Submission::find()->count(),
            'submissions_new' => Submission::find()->where(['status' => 0])->count(),
            'support_new' => \app\models\ContactRequest::find()
                ->where(['>=', 'created_at', date('Y-m-d', strtotime('-7 days'))])
                ->count(),
            'jury_count' => \app\models\User::find()->where(['role' => 2])->count(),
        ];

        // 🔥 Последние активности (для секции "Недавние события")
        $recent = [
            'new_submissions' => Submission::find()
                ->with(['user', 'konkurs'])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(5)
                ->all(),
            'new_requests' => \app\models\ContactRequest::find()
                ->with('user')
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(3)
                ->all(),
        ];

        return $this->render('index', [
            'stats' => $stats,
            'recent' => $recent,
        ]);
    }

    public function actionKonkurs()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Konkurs::find(),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('konkurs/index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKonkursCreate()
    {
        $model = new Konkurs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['konkurs']);
        }

        return $this->render('konkurs/form', ['model' => $model]);
    }

    public function actionKonkursUpdate($id)
    {
        $model = $this->findKonkursModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['konkurs']);
        }

        return $this->render('konkurs/form', ['model' => $model]);
    }

    public function actionKonkursDelete($id)
    {
        $this->findKonkursModel($id)->delete();
        return $this->redirect(['konkurs']);
    }

    protected function findKonkursModel($id)
    {
        if (($model = Konkurs::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Конкурс не найден.');
    }
    public function actionAdminSubmissions()
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => \app\models\Submission::find()->with('user')->orderBy(['created_at' => SORT_DESC]),
        ]);
        if (Yii::$app->request->isPost && Yii::$app->request->post('statuses')) {
            $statuses = Yii::$app->request->post('statuses');
            foreach ($statuses as $id => $status) {
                $submission = \app\models\Submission::findOne($id);
                if ($submission) {
                    $submission->status = (int)$status;
                    $submission->save(false);
                }
            }
            Yii::$app->session->setFlash('success', 'Статусы обновлены.');
            return $this->refresh();
        }
        $query = Submission::find()
            ->with(['user', 'nomination'])
            ->orderBy(['created_at' => SORT_DESC]);

        $query = Submission::find()
            ->select('submission.*')
            ->joinWith('votes', false)
            ->groupBy('submission.id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'vote_count' => [
                        'asc' => ['vote_count' => SORT_ASC],
                        'desc' => ['vote_count' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Голоса',
                    ],
                    'title',
                    'status',
                ],
                'defaultOrder' => ['vote_count' => SORT_DESC],
            ],
        ]);


        return $this->render('admin-submissions', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKonkurses()
    {
        $konkurses = Konkurs::find()->all();

        return $this->render('konkurses', [
            'konkurses' => $konkurses
        ]);
    }

    public function actionWorks($id)
    {
        $konkurs = Konkurs::findOne($id);

        if (!$konkurs) {
            throw new NotFoundHttpException('Конкурс не найден');
        }

        $works = Submission::find()
            ->where(['konkurs_id' => $id])
            ->all();

        $stats = [];
        $ratingData = [];

        foreach ($works as $work) {

            $ratings = JuryRating::find()
                ->where(['submission_id' => $work->id])
                ->all();

            $count = count($ratings);

            $sum = 0;
            foreach ($ratings as $r) {
                $sum += $r->score;
            }

            $avg = $count ? $sum / $count : 0;

            // голосов можно считать отдельно
            $votes = $work->voteCount ?? 0;

            // итоговый рейтинг (можешь менять формулу)
            $final = ($avg * 0.7) + ($votes * 0.3);

            $stats[$work->id] = [
                'avg' => round($avg, 2),
                'count' => $count,
                'votes' => $votes,
                'final' => round($final, 2),
            ];
        }

        // 🔥 ТОП-3
        $top3 = $stats;

        uasort($top3, function ($a, $b) {
            return $b['final'] <=> $a['final'];
        });

        $top3 = array_slice($top3, 0, 3, true);

        return $this->render('works', [
            'konkurs' => $konkurs,
            'works' => $works,
            'stats' => $stats,
            'top3' => $top3, // 🔥 ВОТ ЭТО БЫЛО ПРОПУЩЕНО
        ]);
    }
    /**
     * Просмотр всех оценок работы (для админа)
     */
    public function actionViewRatings($submission_id)
    {
        $submission = Submission::findOne($submission_id);
        if (!$submission) {
            throw new NotFoundHttpException('Работа не найдена');
        }

        // 🔥 Получаем все оценки с данными жюри и номинаций
        $ratings = JuryRating::find()
            ->where(['submission_id' => $submission_id])
            ->with(['user', 'nomination'])
            ->orderBy(['nomination_id' => SORT_ASC, 'user_id' => SORT_ASC])
            ->all();

        // 🔥 Группируем по номинациям для удобного вывода
        $grouped = [];
        foreach ($ratings as $r) {
            $nomName = $r->nomination?->title ?? 'Без номинации';
            $grouped[$nomName][] = $r;
        }

        return $this->renderPartial('@app/modules/admin/views/konkurs/_ratings-modal', [
            'submission' => $submission,
            'grouped' => $grouped,
        ]);
    }
    public function actionSupport()
    {
      $allowedRoles = [1, 2];

        if (Yii::$app->user->isGuest || !in_array(Yii::$app->user->identity->role, $allowedRoles)) {
            throw new \yii\web\ForbiddenHttpException('Доступ запрещён');
        }

        $query = \app\models\ContactRequest::find()
            ->with('user')
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 15],
        ]);

        return $this->render('support', ['dataProvider' => $dataProvider]);
    }
}