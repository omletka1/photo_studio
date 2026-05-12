<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerCssFile('@web/css/adminkon.css');
$this->title = 'Работы участников';
$this->registerCssFile('@web/css/admin.css');
?>

<div class="portfolio-form">
    <div class="form-header">
        <h2><?= Html::encode($this->title) ?></h2>
        <div class="form-divider"></div>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered table-striped table-custom'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'title',
                    'label' => 'Название',
                    'contentOptions' => ['style' => 'vertical-align: middle'],
                ],
                [
                    'label' => 'Конкурс',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->konkurs->title ?? '<span style="color:#999">не указан</span>';
                    },
                    'contentOptions' => ['style' => 'vertical-align: middle; font-weight: 500'],
                ],
                [
                    'label' => 'Работа',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $images = [];

                        for ($i = 1; $i <= 5; $i++) {
                            $field = 'image' . $i;
                            if (!empty($model->$field)) {
                                $images[] = $model->$field;
                            }
                        }

                        if (empty($images)) {
                            return '<span style="color:#999">Нет фото</span>';
                        }

                        $html = '<div style="display:flex; gap:5px; flex-wrap:wrap;">';

                        foreach ($images as $img) {
                            $url = Yii::getAlias('@web/') . $img;

                            $html .= Html::a(
                                Html::img($url, [
                                    'style' => 'width:60px;height:60px;object-fit:cover;border-radius:6px;border:1px solid #ddd'
                                ]),
                                $url,
                                [
                                    'data-lightbox' => 'admin-' . $model->id
                                ]
                            );
                        }

                        $html .= '</div>';

                        return $html;
                    },
                ],
                [
                    'attribute' => 'vote_count',
                    'label' => 'Голоса',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->vote_count ?? 0;
                    },
                    'contentOptions' => ['style' => 'vertical-align: middle; font-weight: bold'],
                ],
                [
                    'attribute' => 'status',
                    'label' => 'Статус (место)',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::dropDownList(
                            "statuses[{$model->id}]",
                            $model->status,
                            [
                                0 => 'Участник',
                                1 => '🥇 1 место',
                                2 => '🥈 2 место',
                                3 => '🥉 3 место',
                            ],
                            ['class' => 'form-control custom-select']
                        );
                    },
                    'contentOptions' => ['style' => 'vertical-align: middle'],
                ],
                [
                    'label' => 'Оценка',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a('Оценить', ['admin/rate', 'id' => $model->id], [
                            'class' => 'btn btn-primary'
                        ]);
                    }
                ],

            ],
        ]); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить изменения', ['class' => 'submit-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
