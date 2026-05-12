<?php
use yii\helpers\Html;

/** @var $stats array */
/** @var $recent array */
$this->title = 'Админ-панель';
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
                            'sys-warning': '#f59e0b',
                            'sys-danger': '#ef4444',
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
            .stagger-1 { transition-delay: 0.08s; }
            .stagger-2 { transition-delay: 0.16s; }
            .stagger-3 { transition-delay: 0.24s; }

            .admin-card {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                padding: 20px;
                transition: transform 0.3s var(--ease), box-shadow 0.3s var(--ease), border-color 0.2s ease;
            }
            .admin-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 24px rgba(0,0,0,0.06);
                border-color: #d5d0e3;
            }
            .stat-badge {
                width: 48px; height: 48px;
                border-radius: 12px;
                display: flex; align-items: center; justify-content: center;
                background: #f0eaf5;
                color: #8b77b3;
                flex-shrink: 0;
            }
            .stat-badge.success { background: #ecfdf5; color: #10b981; }
            .stat-badge.warning { background: #fef3c7; color: #f59e0b; }
            .stat-badge.info { background: #eff6ff; color: #3b82f6; }

            .section-link {
                display: flex;
                align-items: flex-start;
                gap: 16px;
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                padding: 20px;
                text-decoration: none;
                transition: transform 0.3s var(--ease), box-shadow 0.3s var(--ease), border-color 0.2s ease;
            }
            .section-link:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 24px rgba(0,0,0,0.06);
                border-color: #8b77b3;
            }
            .section-link:hover .link-icon { transform: scale(1.05); }
            .section-link:hover .link-title { color: #8b77b3; }
            .link-icon {
                width: 40px; height: 40px;
                border-radius: 10px;
                background: #f0eaf5;
                color: #8b77b3;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
                transition: transform 0.2s var(--ease);
            }
            .link-title { font-weight: 600; color: #111118; transition: color 0.2s ease; }
            .link-arrow { font-size: 0.85rem; color: #8b77b3; margin-top: 8px; display: inline-block; transition: transform 0.2s ease; }
            .section-link:hover .link-arrow { transform: translateX(4px); }

            .activity-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 14px 16px;
                background: #fafaf8;
                border-radius: 10px;
                transition: background 0.2s ease;
            }
            .activity-item:hover { background: #f0eaf5; }
            .activity-link {
                color: #6b6b80;
                transition: color 0.2s ease;
            }
            .activity-link:hover { color: #8b77b3; }

            .disabled-card {
                background: #f9f9fb;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                padding: 20px;
                opacity: 0.6;
                cursor: not-allowed;
            }

            .pulse-dot {
                width: 8px; height: 8px;
                background: #ef4444;
                border-radius: 50%;
                position: absolute;
                top: 16px; right: 16px;
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            @keyframes pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.2); } }

            @media (max-width: 640px) {
                .admin-card, .section-link { padding: 16px; }
            }
            ::-webkit-scrollbar { width: 5px; }
            ::-webkit-scrollbar-track { background: #f7f6f9; }
            ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="min-h-screen py-8 px-4">
        <div class="max-w-6xl mx-auto">

            <!-- Header -->
            <div class="mb-8 rv active">
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-sys-text">
                    Добро пожаловать, <?= Html::encode(Yii::$app->user->identity->name ?? 'Администратор') ?>
                </h1>
                <p class="text-sys-text-muted mt-1">Управление фотоконкурсом и модерация контента</p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="admin-card rv stagger-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-sys-text-muted mb-1">Всего конкурсов</p>
                            <p class="text-2xl font-bold text-sys-text"><?= $stats['konkurs_total'] ?></p>
                        </div>
                        <div class="stat-badge">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="admin-card rv stagger-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-sys-text-muted mb-1">Активные</p>
                            <p class="text-2xl font-bold text-sys-success"><?= $stats['konkurs_active'] ?></p>
                        </div>
                        <div class="stat-badge success">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="8"/><path d="M12 8v4M12 16h.01"/></svg>
                        </div>
                    </div>
                </div>

                <div class="admin-card rv stagger-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-sys-text-muted mb-1">Работы участников</p>
                            <p class="text-2xl font-bold text-sys-text"><?= $stats['submissions_total'] ?></p>
                        </div>
                        <div class="stat-badge info">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="admin-card rv stagger-4 relative overflow-hidden">
                    <?php if ($stats['support_new'] > 0): ?>
                        <span class="pulse-dot"></span>
                    <?php endif; ?>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-sys-text-muted mb-1">Новые обращения</p>
                            <p class="text-2xl font-bold <?= $stats['support_new'] > 0 ? 'text-sys-danger' : 'text-sys-text' ?>"><?= $stats['support_new'] ?></p>
                        </div>
                        <div class="stat-badge">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sections -->
            <h2 class="text-lg font-bold tracking-tight text-sys-text mb-4 rv">Разделы</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

                <?= Html::a('
                <div class="link-icon">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="link-title text-base">Конкурсы</h3>
                    <p class="text-sm text-sys-text-muted mt-1">Создание, редактирование, закрытие</p>
                    <span class="link-arrow">Перейти &rarr;</span>
                </div>
            ', ['/admin/konkurs/index'], ['class' => 'section-link rv stagger-1']) ?>

                <?= Html::a('
                <div class="link-icon">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="link-title text-base">Жюри</h3>
                    <p class="text-sm text-sys-text-muted mt-1">Назначение судей на конкурсы</p>
                    <span class="link-arrow">Перейти &rarr;</span>
                </div>
            ', ['/admin/konkurs/assign-jury-list'], ['class' => 'section-link rv stagger-2']) ?>

                <?= Html::a('
                <div class="link-icon">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="link-title text-base">Работы</h3>
                    <p class="text-sm text-sys-text-muted mt-1">Просмотр, статусы, экспорт</p>
                    <span class="link-arrow">Перейти &rarr;</span>
                </div>
            ', ['/admin/submission/index'], ['class' => 'section-link rv stagger-3']) ?>

                <?= Html::a('
                <div class="link-icon">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1">
                    <h3 class="link-title text-base">Поддержка</h3>
                    <p class="text-sm text-sys-text-muted mt-1">Обращения пользователей' . ($stats['support_new'] > 0 ? ' <span class="text-sys-danger font-semibold">(' . $stats['support_new'] . ' новых)</span>' : '') . '</p>
                    <span class="link-arrow">Перейти &rarr;</span>
                </div>
            ', ['/admin/admin/support'], ['class' => 'section-link rv stagger-4']) ?>

                <div class="disabled-card">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-500">Настройки</h3>
                            <p class="text-sm text-gray-400 mt-1">Скоро будет доступно</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity -->
            <?php if (!empty($recent['new_submissions']) || !empty($recent['new_requests'])): ?>
                <h2 class="text-lg font-bold tracking-tight text-sys-text mb-4 rv">Недавняя активность</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <?php if (!empty($recent['new_submissions'])): ?>
                        <div class="admin-card rv">
                            <h3 class="font-semibold text-sys-text mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-sys-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Новые работы
                            </h3>
                            <div class="space-y-3">
                                <?php foreach ($recent['new_submissions'] as $work): ?>
                                    <div class="activity-item">
                                        <div class="min-w-0">
                                            <div class="font-medium text-sys-text text-sm truncate"><?= Html::encode($work->title) ?></div>
                                            <div class="text-xs text-sys-text-muted">
                                                <?= Html::encode($work->user?->name ?? '—') ?> &bull; <?= Yii::$app->formatter->asRelativeTime($work->created_at) ?>
                                            </div>
                                        </div>
                                        <?= Html::a('<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>', ['/admin/konkurs/view', 'id' => $work->konkurs_id], ['class' => 'activity-link', 'encode' => false]) ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($recent['new_requests'])): ?>
                        <div class="admin-card rv">
                            <h3 class="font-semibold text-sys-text mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-sys-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Новые обращения
                            </h3>
                            <div class="space-y-3">
                                <?php foreach ($recent['new_requests'] as $req): ?>
                                    <div class="activity-item">
                                        <div class="min-w-0">
                                            <div class="font-medium text-sys-text text-sm truncate"><?= Html::encode($req->question) ?></div>
                                            <div class="text-xs text-sys-text-muted">
                                                <?= Html::encode($req->user?->name ?? $req->contacts) ?> &bull; <?= Yii::$app->formatter->asRelativeTime($req->created_at) ?>
                                            </div>
                                        </div>
                                        <?= Html::a('<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>', ['/admin/support'], ['class' => 'activity-link', 'encode' => false]) ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

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