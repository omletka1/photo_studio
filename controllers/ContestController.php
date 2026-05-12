<?php

namespace app\controllers;

use app\models\Konkurs;
use app\models\Nomination;
use app\models\Organizers;
use app\models\Submission;
use Yii;
use yii\data\ActiveDataProvider;

class ContestController extends \yii\web\Controller
{
    public function actionResult()
    {
        // 🔥 Получаем все закрытые конкурсы
        $closedContests = Konkurs::find()
            ->where(['status' => 'закрыт'])
            ->orderBy(['end_date' => SORT_DESC])
            ->all();

        $results = [];

        foreach ($closedContests as $konkurs) {
            // 🔥 Получаем работы конкурса, подгружаем автора
            $works = Submission::find()
                ->where(['konkurs_id' => $konkurs->id])
                ->with('user')  // 🔥 Жадная загрузка автора
                ->orderBy(['status' => SORT_ASC])
                ->all();

            $results[] = [
                'konkurs' => $konkurs,
                'works' => $works,
            ];
        }

        $baseImageUrl = Yii::getAlias('@web/');

        return $this->render('result', [
            'results' => $results,
            'baseImageUrl' => $baseImageUrl,
            // 🔥 Убрали: 'dataProvider' и 'konkursId' — они не нужны
        ]);
    }


    public function actionRules()
    {
        $nominations = Nomination::find()->all();

        return $this->render('rules', [
            'nominations' => $nominations,
        ]);
    }
    public function actionOrganizers()
    {
        $organizers = Organizers::find()->all();
        return $this->render('organizers', [
            'organizers' => $organizers,
        ]);
    }

    public function actionNominations()
    {
        $konkursy = Konkurs::find()
            ->orderBy(['start_date' => SORT_DESC])
            ->limit(2)
            ->all();

        $query = Nomination::find();

        $konkursId = Yii::$app->request->get('konkurs_id');

        if (!empty($konkursId)) {
            $query->joinWith('konkursNominations')
                ->andWhere(['konkurs_nominations.konkurs_id' => $konkursId]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'title' => SORT_ASC,
                ]
            ],
        ]);
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('nominations', [
                'konkursy' => $konkursy,
                'dataProvider' => $dataProvider,
            ]);
        }
        return $this->render('nominations', [
            'konkursy' => $konkursy,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionContests()
    {
        // Получаем только открытые конкурсы
        $openContests = Konkurs::find()
            ->where(['status' => 'открыт'])
            ->orderBy(['start_date' => SORT_DESC])
            ->all();

        return $this->render('contests', [
            'openContests' => $openContests,
        ]);
    }

}