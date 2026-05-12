<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Работы участников';
?>

<!-- Убираем лишние обёртки — .main-container уже в main.php -->
<div class="submissions-admin">

    <header class="page-header">
        <h1 class="page-title">Работы участников</h1>
        <div class="page-actions">
            <?= Html::a('
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Экспорт CSV
            ', ['export'], ['class' => 'btn-secondary', 'encode' => false]) ?>
        </div>
    </header>

    <?php if (!empty($dataProvider->models)): ?>
        <form method="post" class="bulk-form">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Работа</th>
                        <th>Автор</th>
                        <th>Конкурс</th>
                        <th>Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($dataProvider->models as $work): ?>
                        <tr>
                            <td>
                                <?= Html::a(Html::encode($work->title), ['view', 'id' => $work->id], ['class' => 'work-link']) ?>
                                <div class="work-id">#<?= $work->id ?></div>
                            </td>
                            <td class="text-muted"><?= Html::encode($work->user?->name ?? '—') ?></td>
                            <td class="text-muted"><?= Html::encode($work->konkurs?->title ?? '—') ?></td>
                            <td>
                                <select name="statuses[<?= $work->id ?>]" class="status-select">
                                    <option value="0" <?= $work->status == 0 ? 'selected' : '' ?>>Не оценено</option>
                                    <option value="1" <?= $work->status == 1 ? 'selected' : '' ?>>🥇 1 место</option>
                                    <option value="2" <?= $work->status == 2 ? 'selected' : '' ?>>🥈 2 место</option>
                                    <option value="3" <?= $work->status == 3 ? 'selected' : '' ?>>🥉 3 место</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Сохранить изменения
                </button>
            </div>
        </form>
    <?php else: ?>
        <div class="empty-state">Работы пока не добавлены</div>
    <?php endif; ?>

    <!-- Pagination -->
    <?= LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'options' => ['class' => 'pagination'],
        'linkOptions' => ['class' => ''],
        'activePageCssClass' => 'active',
        'disabledPageCssClass' => 'disabled',
        'prevPageLabel' => false,
        'nextPageLabel' => false,
    ]) ?>
</div>

<style>
    :root { --ease: cubic-bezier(0.16, 1, 0.3, 1); }

    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-title {
        font-size: 1.5rem; font-weight: 700; color: #111118;
        margin: 0; letter-spacing: -0.02em;
    }
    .page-actions { display: flex; gap: 8px; }

    .btn-primary, .btn-secondary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px; border-radius: 10px;
        font-weight: 600; font-size: 0.9rem;
        text-decoration: none; transition: all 0.25s var(--ease);
        cursor: pointer; border: none;
    }
    .btn-primary {
        background: #111118; color: #fff;
    }
    .btn-primary:hover {
        background: #222; transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .btn-secondary {
        background: #fff; color: #111118;
        border: 1.5px solid #e5e3eb;
    }
    .btn-secondary:hover {
        border-color: #8b77b3; background: #f0eaf5; color: #8b77b3;
    }
    .btn-icon { width: 16px; height: 16px; }

    .bulk-form { margin-bottom: 24px; }

    .table-wrapper {
        background: #fff; border: 1px solid #e5e3eb;
        border-radius: 16px; overflow: hidden;
        overflow-x: auto; -webkit-overflow-scrolling: touch;
    }
    .data-table { width: 100%; border-collapse: collapse; min-width: 560px; }
    .data-table th {
        padding: 14px 16px; text-align: left; font-size: 0.72rem;
        font-weight: 600; color: #6b6b80; text-transform: uppercase;
        letter-spacing: 0.04em; background: #fafaf8;
        border-bottom: 1px solid #e5e3eb; white-space: nowrap;
    }
    .data-table td {
        padding: 14px 16px; border-bottom: 1px solid #f0eef5;
        font-size: 0.9rem; color: #111118; vertical-align: middle;
    }
    .data-table tbody tr { transition: background 0.2s ease; }
    .data-table tbody tr:hover { background: #f9f8fc; }

    .work-link {
        font-weight: 500; color: #111118; text-decoration: none;
        display: block; transition: color 0.2s ease;
    }
    .work-link:hover { color: #8b77b3; }
    .work-id { font-size: 0.8rem; color: #8b8b9e; font-family: ui-monospace, monospace; margin-top: 4px; }
    .text-muted { color: #6b6b80; }

    .status-select {
        padding: 8px 12px; border-radius: 8px;
        border: 1px solid #e5e3eb; background: #fff;
        font-size: 0.85rem; color: #111118;
        min-width: 140px; cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .status-select:focus {
        outline: none; border-color: #8b77b3;
        box-shadow: 0 0 0 3px rgba(139, 119, 179, 0.15);
    }

    .form-actions {
        display: flex; justify-content: flex-end;
        margin-top: 16px;
    }

    .empty-state {
        text-align: center; padding: 48px 24px; background: #fff;
        border: 1px dashed #e5e3eb; border-radius: 16px;
        color: #6b6b80; font-size: 1rem;
    }

    .pagination {
        display: flex; justify-content: center; gap: 6px;
        margin: 24px 0 8px; list-style: none; padding: 0; flex-wrap: wrap;
    }
    .pagination li a {
        display: flex; align-items: center; justify-content: center;
        min-width: 36px; height: 36px; padding: 0 8px; border-radius: 8px;
        font-size: 0.9rem; font-weight: 500; color: #6b6b80;
        border: 1px solid #e5e3eb; transition: all 0.2s ease;
        text-decoration: none;
    }
    .pagination li a:hover { background: #f0eaf5; border-color: #8b77b3; color: #8b77b3; }
    .pagination li.active a { background: #8b77b3; border-color: #8b77b3; color: #fff; font-weight: 600; }
    .pagination li.disabled a { opacity: 0.5; cursor: not-allowed; }

    @media (max-width: 640px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .page-actions { width: 100%; justify-content: flex-end; }
        .data-table th:nth-child(2), .data-table td:nth-child(2) { display: none; }
        .form-actions { justify-content: center; }
        .btn-primary, .btn-secondary { width: 100%; justify-content: center; }
    }
</style>