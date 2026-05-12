<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $submission app\models\Submission */
/** @var $nominations app\models\Nomination[] */

$this->title = 'Оценка: ' . $submission->title;
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">⭐ Оценка работы</h1>
        <p class="text-gray-500 mt-1">
            <?= Html::encode($submission->title) ?>
            <span class="text-gray-400">•</span>
            Автор: <?= Html::encode($submission->user?->name ?? '—') ?>
        </p>
    </div>

    <?php $form = ActiveForm::begin(['options' => ['class' => 'space-y-6']]); ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Номинация</th>
                <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Оценка (0–5)</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Комментарий</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            <?php foreach ($nominations as $nom):
                $existing = \app\models\JuryRating::find()
                    ->where([
                        'submission_id' => $submission->id,
                        'nomination_id' => $nom->id,
                        'user_id' => Yii::$app->user->id
                    ])->one();
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900"><?= Html::encode($nom->title) ?></td>
                    <td class="px-4 py-3 text-center">
                        <input type="number"
                               name="score[<?= $nom->id ?>]"
                               value="<?= $existing?->score ?? '' ?>"
                               min="0" max="5" step="0.5"
                               class="w-20 text-center border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                    </td>

                    <td class="px-4 py-3">
    <textarea name="comment[<?= $nom->id ?>]"
              rows="2"
              class="w-full border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 text-sm"
              placeholder="Комментарий (необязательно)"><?= $existing?->comment ?? '' ?></textarea>

                        <!-- 🔥 Показываем сохранённый комментарий, только если это автор -->
                        <?php if ($existing && $existing->user_id == Yii::$app->user->id && !empty($existing->comment)): ?>
                            <div class="mt-2 text-xs text-gray-500 bg-gray-50 p-2 rounded">
                                <strong>Ваш комментарий:</strong>
                                <p class="italic"><?= Html::encode($existing->comment) ?></p>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($nominations)): ?>
                <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">Номинации не назначены</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex gap-3">
        <?= Html::submitButton('💾 Сохранить оценки', [
            'class' => 'px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition shadow-sm'
        ]) ?>
        <?= Html::a('← Отмена', ['/admin/konkurs/view', 'id' => $submission->konkurs_id], [
            'class' => 'px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>