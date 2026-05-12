<?php
use yii\helpers\Html;

/** @var app\models\Konkurs $model */
?>

<div class="contest-card">
    <h3><?= Html::encode($model->title) ?></h3>

    <p><?= Html::encode($model->description) ?></p>

    <p>
        📅 <?= $model->start_date ?> — <?= $model->end_date ?>
    </p>

    <div style="margin-top: 15px; display:flex; gap:10px;">

        <?= Html::a('Участвовать',
            ['submission/submission', 'id' => $model->id],
            ['class' => 'btn btn-warning']
        ) ?>

        <?= Html::a('Номинации',
            ['site/nominations', 'konkurs_id' => $model->id],
            ['class' => 'btn btn-outline-warning']
        ) ?>

    </div>
</div>