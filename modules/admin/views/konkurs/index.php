<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Управление конкурсами';
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="scroll-smooth">
    <head>
        <?php $this->registerCsrfMetaTags() ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'sys-bg': '#f7f6f9',
                            'sys-surface': '#ffffff',
                            'sys-border': '#e5e3eb',
                            'sys-text': '#111118',
                            'sys-text-muted': '#6b6b80',
                            'sys-accent': '#8b77b3',
                            'sys-accent-hover': '#75639c',
                            'sys-accent-light': '#f0eaf5',
                            'sys-success': '#10b981',
                            'sys-success-bg': '#ecfdf5',
                            'sys-gray': '#6b7280',
                            'sys-gray-bg': '#f3f4f6',
                            'sys-warning': '#d97706',
                            'sys-warning-bg': '#fef3c7',
                        },
                        fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    }
                }
            }
        </script>
        <style>
            :root { --ease: cubic-bezier(0.16, 1, 0.3, 1); }
            * { box-sizing: border-box; }
            body {
                font-family: 'Inter', system-ui, sans-serif;
                background: #f7f6f9;
                color: #111118;
                -webkit-font-smoothing: antialiased;
                margin: 0;
                line-height: 1.6;
            }
            .rv { opacity: 0; transform: translateY(16px); transition: opacity 0.7s var(--ease), transform 0.7s var(--ease); }
            .rv.active { opacity: 1; transform: translateY(0); }

            .btn-create {
                display: inline-flex; align-items: center; gap: 8px;
                padding: 10px 20px; background: #111118; color: #fff;
                font-weight: 600; font-size: 0.9rem; border-radius: 10px;
                text-decoration: none; transition: all 0.25s var(--ease);
            }
            .btn-create:hover {
                background: #222; transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            }

            .admin-table { width: 100%; border-collapse: collapse; }
            .admin-table th {
                padding: 14px 16px; text-align: left; font-size: 0.75rem;
                font-weight: 600; color: #6b6b80; text-transform: uppercase;
                letter-spacing: 0.05em; background: #fafaf8; border-bottom: 1px solid #e5e3eb;
                white-space: nowrap;
            }
            .admin-table td {
                padding: 16px; vertical-align: middle; border-bottom: 1px solid #f0eef5;
                font-size: 0.9rem; color: #111118;
            }
            .admin-table tbody tr { transition: background 0.2s ease; }
            .admin-table tbody tr:hover { background: #f9f8fc; }

            .status-badge {
                display: inline-block; padding: 4px 10px; border-radius: 999px;
                font-size: 0.75rem; font-weight: 600; white-space: nowrap;
            }
            .status-open { background: #ecfdf5; color: #059669; }
            .status-closed { background: #f3f4f6; color: #4b5563; }
            .status-draft { background: #fef3c7; color: #b45309; }

            .action-btn {
                display: inline-flex; align-items: center; justify-content: center;
                width: 32px; height: 32px; border-radius: 8px; color: #6b6b80;
                transition: all 0.2s var(--ease); text-decoration: none;
            }
            .action-btn svg { width: 16px; height: 16px; }
            .action-btn:hover { background: #f0eaf5; color: #8b77b3; }
            .action-btn.delete:hover { background: #fef2f2; color: #ef4444; }

            .empty-row td { padding: 40px 16px; text-align: center; color: #6b6b80; }

            .pagination { display: flex; justify-content: center; gap: 6px; margin-top: 24px; }
            .pagination li a {
                display: flex; align-items: center; justify-content: center;
                min-width: 36px; height: 36px; padding: 0 8px; border-radius: 8px;
                font-size: 0.9rem; font-weight: 500; color: #6b6b80;
                border: 1px solid #e5e3eb; transition: all 0.2s ease; text-decoration: none;
            }
            .pagination li a:hover { background: #f0eaf5; border-color: #8b77b3; color: #8b77b3; }
            .pagination li.active a { background: #8b77b3; border-color: #8b77b3; color: #fff; }
            .pagination li.disabled a { opacity: 0.5; cursor: not-allowed; }

            @media (max-width: 768px) {
                .admin-table th:nth-child(1), .admin-table td:nth-child(1) { display: none; }
                .admin-table th:nth-child(3), .admin-table td:nth-child(3) { display: none; }
            }
            ::-webkit-scrollbar { width: 5px; }
            ::-webkit-scrollbar-track { background: #f7f6f9; }
            ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="min-h-screen py-8 px-4">
        <div class="max-w-7xl mx-auto">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 rv active">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-sys-text">Управление конкурсами</h1>
                    <p class="text-sys-text-muted mt-1">Создание, редактирование и модерация фотоконкурсов</p>
                </div>
                <?= Html::a('
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Создать конкурс
            ', ['create'], ['class' => 'btn-create']) ?>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl border border-sys-border shadow-sm overflow-hidden rv">
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Период</th>
                            <th>Статус</th>
                            <th class="text-right">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($dataProvider->models)): ?>
                            <tr class="empty-row"><td colspan="5">Конкурсы пока не добавлены</td></tr>
                        <?php else: ?>
                            <?php foreach ($dataProvider->models as $k): ?>
                                <tr>
                                    <td class="text-sys-text-muted font-mono text-sm">#<?= $k->id ?></td>
                                    <td>
                                        <div class="font-semibold text-sys-text"><?= Html::encode($k->title) ?></div>
                                        <div class="text-sm text-sys-text-muted truncate max-w-xs mt-1"><?= Html::encode($k->description) ?></div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-sys-text"><?= Yii::$app->formatter->asDate($k->start_date) ?></div>
                                        <div class="text-xs text-sys-text-muted mt-1">—</div>
                                        <div class="text-sm text-sys-text"><?= Yii::$app->formatter->asDate($k->end_date) ?></div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match($k->status) {
                                            'открыт' => 'status-open',
                                            'закрыт' => 'status-closed',
                                            default => 'status-draft'
                                        };
                                        ?>
                                        <span class="status-badge <?= $statusClass ?>"><?= Html::encode($k->status) ?></span>
                                    </td>
                                    <td class="text-right whitespace-nowrap">
                                        <?= Html::a('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>', ['view', 'id' => $k->id], ['class' => 'action-btn', 'title' => 'Просмотр', 'encode' => false]) ?>
                                        <?= Html::a('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>', ['update', 'id' => $k->id], ['class' => 'action-btn', 'title' => 'Редактировать', 'encode' => false]) ?>
                                        <?= Html::a('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>', ['assign-jury', 'id' => $k->id], ['class' => 'action-btn', 'title' => 'Назначить жюри', 'encode' => false]) ?>
                                        <?= Html::a('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><path d="M10 11v6M14 11v6"/></svg>', ['delete', 'id' => $k->id], ['class' => 'action-btn delete', 'title' => 'Удалить', 'data-confirm' => 'Удалить конкурс?', 'data-method' => 'post', 'encode' => false]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <?= LinkPager::widget([
                'pagination' => $dataProvider->pagination,
                'options' => ['class' => 'pagination rv'],
                'linkOptions' => ['class' => ''],
                'activePageCssClass' => 'active',
                'disabledPageCssClass' => 'disabled',
                'prevPageLabel' => false,
                'nextPageLabel' => false,
            ]) ?>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) { entry.target.classList.add('active'); observer.unobserve(entry.target); }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });
            document.querySelectorAll('.rv').forEach(el => observer.observe(el));
        });
    </script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>