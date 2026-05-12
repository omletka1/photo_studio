<?php use yii\helpers\Html; use yii\widgets\LinkPager; ?>
<h1 class="text-2xl font-bold text-gray-800 mb-6">📩 Обращения в поддержку</h1>

<div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Пользователь</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Тема</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Контакты</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
        <?php foreach ($dataProvider->models as $req): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-sm text-gray-500">#<?= $req->id ?></td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                    <?= $req->user ? Html::encode($req->user->surname . ' ' . $req->user->name) : 'Гость' ?>
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate" title="<?= Html::encode($req->question) ?>">
                    <?= Html::encode($req->question) ?>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600"><?= Html::encode($req->contacts) ?></td>
                <td class="px-4 py-3 text-sm text-gray-500"><?= Yii::$app->formatter->asDatetime($req->created_at) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($dataProvider->models)): ?>
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Обращений пока нет</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-4 flex justify-center">
    <?= LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'options' => ['class' => 'flex gap-1'],
        'linkOptions' => ['class' => 'px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100'],
        'activePageCssClass' => 'bg-orange-500 text-white hover:bg-orange-600',
    ]) ?>
</div>