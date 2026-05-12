<?php

namespace app\controllers;

use app\models\Submission;
use app\models\Vote;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\db\Expression;

class VoteController extends Controller
{
    public function actionVote()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Авторизуйтесь'];
        }

        $submissionId = Yii::$app->request->post('submissionId');
        $userId = Yii::$app->user->id;
        $submission = Submission::findOne($submissionId);

        if (!$submission) {
            return [
                'success' => false,
                'message' => 'Работа не найдена'
            ];
        }

// 🚫 запрет голосовать за себя
        if ($submission->user_id == Yii::$app->user->id) {
            return [
                'success' => false,
                'message' => 'Нельзя голосовать за свою работу'
            ];
        }
        if (!$submissionId) {
            return ['success' => false, 'message' => 'Нет ID'];
        }

        $existing = Vote::findOne([
            'user_id' => $userId,
            'submission_id' => $submissionId
        ]);

        if ($existing) {
            $existing->delete();
            $voted = false;
        } else {
            $vote = new Vote();
            $vote->user_id = $userId;
            $vote->submission_id = $submissionId;
            $vote->created_at = date('Y-m-d H:i:s');
            $vote->save();
            $voted = true;
        }

        $count = (int)Vote::find()
            ->where(['submission_id' => $submissionId])
            ->count();

        return [
            'success' => true,
            'voted' => $voted,
            'voteCount' => $count
        ];
    }
    public function actionVotePage($id)
    {
        $submission = Submission::findOne($id);

        if (!$submission) {
            return $this->render('error', ['message' => 'Работа не найдена']);
        }

        return $this->render('vote', ['submission' => $submission]);
    }
}