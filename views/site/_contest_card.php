<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $konkurs app\models\Konkurs */
/** @var $highlight bool */
/** @var $daysLeft int|null */

$cardClass = $highlight
    ? 'border-2 border-red-300 bg-gradient-to-br from-red-50 to-orange-50 shadow-md'
    : 'bg-white border border-gray-100 shadow-sm hover:shadow-md';
?>

<div class="<?= $cardClass ?> rounded-2xl overflow-hidden transition-all duration-300">
    <div class="p-6">
        <div class="flex items-start justify-between mb-3">
            <h3 class="font-bold text-lg text-gray-800 leading-tight">
                <?= Html::encode($konkurs->title) ?>
            </h3>
            <?php if ($highlight && $daysLeft !== null): ?>
                <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded-lg whitespace-nowrap">
                    Осталось: <?= $daysLeft ?> дн.
                </span>
            <?php else: ?>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                    Открыт
                </span>
            <?php endif; ?>
        </div>

        <p class="text-gray-600 text-sm mb-4 line-clamp-2 min-h-[40px]">
            <?= Html::encode($konkurs->description) ?>
        </p>

        <div class="flex items-center text-sm text-gray-500 mb-4 bg-gray-50 rounded-lg p-3">
            <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <div>
                <span class="font-medium">
                    <?= Yii::$app->formatter->asDate($konkurs->start_date, 'php:d.m.Y') ?>
                </span>
                <span class="mx-1">—</span>
                <span class="<?= $highlight ? 'text-red-600 font-semibold' : '' ?>">
                    <?= Yii::$app->formatter->asDate($konkurs->end_date, 'php:d.m.Y') ?>
                </span>
            </div>
        </div>

        <div class="flex gap-2">
            <?= Html::a('Участвовать', ['/submission/submission', 'konkurs_id' => $konkurs->id], [
                'class' => 'flex-1 px-4 py-2.5 ' .
                    ($highlight
                        ? 'bg-red-500 hover:bg-red-600'
                        : 'bg-orange-500 hover:bg-orange-600') .
                    ' text-white text-sm font-semibold rounded-xl transition-colors text-center shadow-sm hover:shadow'
            ]) ?>
            <?= Html::a('Номинации', ['/contest/nominations', 'konkurs_id' => $konkurs->id], [
                'class' => 'flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors text-center'
            ]) ?>
        </div>
    </div>
</div>