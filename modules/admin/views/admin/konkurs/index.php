<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->registerCssFile('@web/css/konindex.css');
$this->title = 'УПРАВЛЕНИЕ КОНКУРСАМИ';

?>

<div class="admin-container">
    <div class="admin-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="admin-divider"></div>
    </div>

    <p>
        <?= Html::a('СОЗДАТЬ КОНКУРС', ['konkurs-create'], ['class' => 'admin-create-btn']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'admin-table'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '№'],
            'title:text:НАЗВАНИЕ',
            'description:ntext:ОПИСАНИЕ',
            'start_date:date:ДАТА НАЧАЛА',
            'end_date:date:ДАТА ОКОНЧАНИЯ',
            'status:text:СТАТУС',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('ИЗМЕНИТЬ', ['konkurs-update', 'id' => $model->id],
                            ['class' => 'admin-action-btn admin-edit-btn']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('УДАЛИТЬ', ['konkurs-delete', 'id' => $model->id], [
                            'class' => 'admin-action-btn admin-delete-btn',
                            'data' => [
                                'confirm' => 'ТОЧНО УДАЛИТЬ?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
                'contentOptions' => ['style' => 'white-space: nowrap;'],
            ],
        ],
    ]); ?>
</div>