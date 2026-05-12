<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Оценка работы: ' . $model->title;
?>

    <h2><?= Html::encode($this->title) ?></h2>

    <p><b>Описание:</b> <?= $model->description ?></p>

<?php $form = ActiveForm::begin([
    'action' => ['admin/save-rate', 'id' => $model->id],
]); ?>

    <table class="table table-bordered">
        <tr>
            <th>Номинация</th>
            <th>Оценка (0–5)</th>
            <th>Комментарий</th>
        </tr>

        <?php foreach ($nominations as $nomination): ?>

            <?php
            $existing = \app\models\JuryRating::find()
                ->where([
                    'submission_id' => $model->id,
                    'nomination_id' => $nomination->id,
                    'user_id' => Yii::$app->user->id
                ])
                ->one();
            ?>

            <tr>
                <td><?= $nomination->title ?></td>

                <td>
                    <input type="number"
                           name="score[<?= $nomination->id ?>]"
                           value="<?= $existing->score ?? 0 ?>"
                           min="0"
                           max="5"
                           class="form-control">
                </td>

                <td>
                <textarea name="comment[<?= $nomination->id ?>]"
                          class="form-control"><?= $existing->comment ?? '' ?></textarea>
                </td>
            </tr>

        <?php endforeach; ?>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Сохранить оценки', [
            'class' => 'btn btn-primary'
        ]) ?>
    </div>

<?php ActiveForm::end(); ?>