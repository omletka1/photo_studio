<?php
/** @var int $index Индекс строки (0, 1, 2...) */
/** @var array $oldTitles Массив введённых названий */
/** @var array $oldDescs Массив введённых описаний */

$oldTitle = $oldTitles[$index] ?? '';
$oldDesc = $oldDescs[$index] ?? '';
?>

<div class="nomination-row p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="flex items-start justify-between mb-3">
        <span class="font-medium text-gray-700">Номинация #<?= $index + 1 ?></span>
        <button type="button" class="remove-nomination text-red-500 hover:text-red-700 text-sm">✕ Удалить</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
        <input type="text"
               name="NominationTitle[]"
               value="<?= \yii\helpers\Html::encode($oldTitle) ?>"
               placeholder="Название номинации *"
               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500"
               required>

        <input type="text"
               name="NominationDesc[]"
               value="<?= \yii\helpers\Html::encode($oldDesc) ?>"
               placeholder="Описание номинации *"
               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500"
               required> <!-- 🔥 Обязательно -->  </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Изображение-баннер</label>
        <input type="file"
               name="NominationImage[]"
               accept="image/*"
               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"
               required> <!-- 🔥 Обязательно -->
        <p class="text-xs text-gray-400 mt-1">PNG, JPG до 2 МБ</p>
    </div>
</div>