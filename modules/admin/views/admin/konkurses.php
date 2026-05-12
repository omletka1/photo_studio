<?php

use yii\helpers\Html;

$this->title = 'Конкурсы (админ)';
?>

<h2><?= Html::encode($this->title) ?></h2>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Статус</th>
        <th>Действие</th>
    </tr>
    <?php foreach ($konkurses as $k): ?>

        <div class="card">
            <h3><?= $k->title ?></h3>

            <?= \yii\helpers\Html::a(
                'Работы',
                ['admin/works', 'id' => $k->id],
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

    <?php endforeach; ?>
</table>