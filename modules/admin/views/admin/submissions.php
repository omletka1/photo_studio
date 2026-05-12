<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="portfolio-form">
    <div class="form-header">
        <h2><?= Html::encode($this->title) ?></h2>
        <div class="form-divider"></div>
    </div>

    <?php $form = ActiveForm::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user.username',
                'label' => 'Участник',
            ],
            [
                'attribute' => 'title',
                'label' => 'Название работы',
            ],
            [
                'attribute' => 'votes',
                'label' => 'Голоса',
                'value' => function ($model) {
                    return \app\models\Vote::find()
                        ->where(['submission_id' => $model->id])
                        ->count();
                },
                'contentOptions' => ['style' => 'vertical-align: middle; font-weight: bold'],
            ],
            [
                'label' => 'Место',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::dropDownList(
                        "statuses[{$model->id}]",
                        $model->status,
                        [
                            0 => 'Участник',
                            1 => '1 место',
                            2 => '2 место',
                            3 => '3 место',
                        ],
                        ['class' => 'form-control']
                    );
                }
            ],
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить статусы', ['class' => 'submit-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
