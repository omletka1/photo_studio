<?php
use yii\helpers\Html;

/** @var $submission app\models\Submission */
/** @var $grouped array */
?>

<div class="p-4">
    <h4 class="font-bold text-gray-800 mb-3">
        Оценки работы: <?= Html::encode($submission->title) ?>
    </h4>

    <?php if (empty($grouped)): ?>
        <p class="text-gray-500 text-center py-4">Оценок пока нет</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($grouped as $nomName => $ratings): ?>
                <div class="bg-gray-50 rounded-lg p-3">
                    <h5 class="font-medium text-gray-700 mb-2"><?= Html::encode($nomName) ?></h5>
                    <div class="space-y-2">
                        <?php foreach ($ratings as $r): ?>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">
                                    <?= Html::encode($r->user?->surname . ' ' . $r->user?->name ?? 'Пользователь') ?>
                                </span>
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-orange-600"><?= $r->score ?>/5</span>
                                    <?php if (!empty($r->comment)): ?>
                                        <button type="button"
                                                class="text-gray-400 hover:text-gray-600"
                                                title="Комментарий"
                                                onclick="alert('<?= Html::encode($r->comment) ?>')">
                                            💬
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Кнопка редактирования (ведёт в форму жюри) -->
        <div class="mt-4 pt-3 border-t border-gray-200 text-right">
            <?= Html::a('✏️ Изменить оценки',
                ['/admin/jury/rate', 'submission_id' => $submission->id],
                ['class' => 'px-4 py-2 bg-orange-100 hover:bg-orange-200 text-orange-700 rounded-lg text-sm font-medium transition']
            ) ?>
        </div>
    <?php endif; ?>
</div>