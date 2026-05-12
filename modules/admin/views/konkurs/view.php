<?php
use yii\helpers\Html;

/** @var $konkurs app\models\Konkurs */
/** @var $works app\models\Submission[] */
/** @var $stats array */
/** @var $top3 array */

$this->title = 'Конкурс: ' . $konkurs->title;
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
                            'sys-warning': '#f59e0b',
                            'sys-warning-bg': '#fef3c7',
                            'sys-danger': '#ef4444',
                            'sys-danger-bg': '#fef2f2',
                            'medal-gold': '#f59e0b',
                            'medal-silver': '#94a3b8',
                            'medal-bronze': '#b45309',
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

            .btn-action {
                display: inline-flex; align-items: center; gap: 8px;
                padding: 10px 18px; border-radius: 10px;
                font-weight: 500; font-size: 0.9rem;
                text-decoration: none; transition: all 0.25s var(--ease);
            }
            .btn-action.secondary {
                background: #fff; color: #111118;
                border: 1.5px solid #e5e3eb;
            }
            .btn-action.secondary:hover {
                border-color: #8b77b3; background: #f0eaf5; color: #8b77b3;
            }
            .btn-action.primary {
                background: #8b77b3; color: #fff; border: none;
            }
            .btn-action.primary:hover {
                background: #75639c; transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(139,119,179,0.25);
            }
            .btn-action svg { width: 16px; height: 16px; }

            .winners-card {
                background: linear-gradient(135deg, #fafaf8 0%, #f0eaf5 100%);
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                padding: 24px;
                margin-bottom: 32px;
            }
            .winners-title {
                font-size: 1.1rem; font-weight: 700; color: #111118;
                margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;
            }
            .winners-title svg { width: 20px; height: 20px; color: #8b77b3; }
            .winners-grid {
                display: grid; grid-template-columns: repeat(1, 1fr); gap: 12px;
            }
            @media (min-width: 768px) { .winners-grid { grid-template-columns: repeat(3, 1fr); } }

            .winner-item {
                background: #fff; border: 1px solid #e5e3eb;
                border-radius: 12px; padding: 16px;
                transition: border-color 0.2s ease;
            }
            .winner-item:hover { border-color: #d5d0e3; }
            .winner-header {
                display: flex; align-items: center; gap: 10px; margin-bottom: 8px;
            }
            .winner-rank {
                width: 28px; height: 28px; border-radius: 8px;
                display: flex; align-items: center; justify-content: center;
                font-size: 0.85rem; font-weight: 700; flex-shrink: 0;
            }
            .winner-rank.gold { background: #fef3c7; color: #92400e; }
            .winner-rank.silver { background: #f1f5f9; color: #334155; }
            .winner-rank.bronze { background: #ffedd5; color: #7c2d12; }
            .winner-rank svg { width: 14px; height: 14px; }
            .winner-title {
                font-weight: 600; color: #111118; font-size: 0.95rem;
            }
            .winner-stats {
                display: flex; gap: 16px; font-size: 0.85rem; color: #6b6b80;
            }
            .winner-stats strong { color: #111118; font-weight: 600; }

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
            .work-link {
                font-weight: 500; color: #111118; text-decoration: none;
                transition: color 0.2s ease; display: block;
            }
            .work-link:hover { color: #8b77b3; }
            .work-id { font-size: 0.8rem; color: #8b8b9e; font-family: ui-monospace, monospace; }

            .score-badge {
                display: inline-flex; align-items: center; justify-content: center;
                padding: 4px 10px; border-radius: 999px;
                font-size: 0.75rem; font-weight: 600; white-space: nowrap;
            }
            .score-high { background: #ecfdf5; color: #059669; }
            .score-mid { background: #fef3c7; color: #92400e; }
            .score-low { background: #fef2f2; color: #b91c1c; }

            .action-btn {
                display: inline-flex; align-items: center; justify-content: center;
                width: 32px; height: 32px; border-radius: 8px; color: #6b6b80;
                transition: all 0.2s var(--ease); text-decoration: none;
            }
            .action-btn svg { width: 16px; height: 16px; }
            .action-btn:hover { background: #f0eaf5; color: #8b77b3; }
            .action-btn.rate { background: #f0eaf5; color: #8b77b3; width: auto; padding: 6px 14px; font-size: 0.8rem; font-weight: 500; }
            .action-btn.rate:hover { background: #e8e0f2; }

            .modal {
                position: fixed; inset: 0; background: rgba(0,0,0,0.5);
                display: none; align-items: center; justify-content: center;
                z-index: 1000; padding: 20px;
            }
            .modal.active { display: flex; }
            .modal-card {
                background: #fff; border-radius: 16px;
                max-width: 560px; width: 100%;
                box-shadow: 0 20px 60px rgba(0,0,0,0.2);
                animation: modalIn 0.25s var(--ease);
            }
            @keyframes modalIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
            .modal-header {
                padding: 16px 20px; border-bottom: 1px solid #e5e3eb;
                display: flex; align-items: center; justify-content: space-between;
            }
            .modal-title { font-weight: 600; color: #111118; font-size: 1.05rem; }
            .modal-close {
                width: 32px; height: 32px; border-radius: 8px;
                display: flex; align-items: center; justify-content: center;
                color: #6b6b80; background: transparent; border: none;
                cursor: pointer; transition: all 0.2s ease;
            }
            .modal-close:hover { background: #f0eaf5; color: #8b77b3; }
            .modal-body { padding: 20px; }
            .ratings-list { display: flex; flex-direction: column; gap: 12px; }
            .rating-item {
                display: flex; align-items: center; justify-content: space-between;
                padding: 12px 14px; background: #fafaf8; border-radius: 10px;
            }
            .rating-author { font-weight: 500; color: #111118; font-size: 0.9rem; }
            .rating-score {
                font-weight: 600; color: #8b77b3; font-size: 0.95rem;
            }
            .rating-empty { text-align: center; color: #6b6b80; padding: 24px; }

            .empty-row td { padding: 40px 16px; text-align: center; color: #6b6b80; }

            @media (max-width: 768px) {
                .admin-table th:nth-child(1), .admin-table td:nth-child(1) { display: none; }
                .admin-table th:nth-child(5), .admin-table td:nth-child(5) { display: none; }
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
                    <h1 class="text-2xl font-bold tracking-tight text-sys-text"><?= Html::encode($konkurs->title) ?></h1>
                    <p class="text-sys-text-muted mt-1"><?= Html::encode($konkurs->description) ?></p>
                </div>
                <div class="flex gap-3">
                    <?= Html::a('
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Редактировать
                ', ['update', 'id' => $konkurs->id], ['class' => 'btn-action secondary', 'encode' => false]) ?>
                    <?= Html::a('
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    Жюри
                ', ['assign-jury', 'id' => $konkurs->id], ['class' => 'btn-action secondary', 'encode' => false]) ?>
                </div>
            </div>

            <?php if (!empty($top3)): ?>
                <div class="winners-card rv">
                    <h3 class="winners-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        Победители
                    </h3>
                    <div class="winners-grid">
                        <?php
                        $rankIndex = 0;
                        // ✅ Правильный порядок: $workId = ключ массива (ID работы), $s = значение (статистика)
                        foreach ($top3 as $workId => $s):
                            // ✅ Приводим к int для безопасности
                            $work = \app\models\Submission::findOne((int)$workId);
                            // ✅ Берём статистику из отдельного массива $stats
                            $statsData = $stats[$workId] ?? ['avg' => 0, 'final' => 0];
                            $rankClass = $rankIndex === 0 ? 'gold' : ($rankIndex === 1 ? 'silver' : 'bronze');
                            $rankIcon = $rankIndex === 0
                                ? '<path d="M12 2l2.4 7.2h7.6l-6 4.8 2.4 7.2-6-4.8-6 4.8 2.4-7.2-6-4.8h7.6z"/>'
                                : ($rankIndex === 1
                                    ? '<circle cx="12" cy="12" r="8"/><path d="M12 8v4M12 16h.01"/>'
                                    : '<path d="M12 2l3 6 6 1-4 4 1 6-6-3-6 3 1-6-4-4 6-1z"/>');
                            ?>
                            <div class="winner-item rv stagger-<?= $rankIndex + 1 ?>">
                                <div class="winner-header">
                                    <div class="winner-rank <?= $rankClass ?>">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><?= $rankIcon ?></svg>
                                    </div>
                                    <div class="winner-title"><?= Html::encode($work?->title ?? 'Работа #' . $workId) ?></div>
                                </div>
                                <div class="winner-stats">
                                    <span>Балл: <strong><?= number_format($statsData['avg'] ?? 0, 1) ?></strong>/5</span>
                                    <span>Рейтинг: <strong><?= $statsData['final'] ?? 0 ?></strong></span>
                                </div>
                            </div>
                            <?php
                            $rankIndex++;
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Works Table -->
            <h3 class="text-lg font-bold tracking-tight text-sys-text mb-4 rv">Все работы (<?= count($works) ?>)</h3>
            <div class="bg-white rounded-xl border border-sys-border shadow-sm overflow-hidden rv">
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Работа</th>
                            <th>Автор</th>
                            <th class="text-center">Балл</th>
                            <th class="text-center">Голоса</th>
                            <th class="text-center">Оценки</th>
                            <th class="text-center">Рейтинг</th>
                            <th class="text-right">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($works)): ?>
                            <tr class="empty-row"><td colspan="7">Работы пока не добавлены</td></tr>
                        <?php else: ?>
                            <?php foreach ($works as $work):
                                $avg = $stats[$work->id]['avg'] ?? 0;
                                $scoreClass = $avg >= 4 ? 'score-high' : ($avg >= 2.5 ? 'score-mid' : 'score-low');
                                ?>
                                <tr>
                                    <td>
                                        <?= Html::a(Html::encode($work->title), ['/admin/submission/view', 'id' => $work->id], ['class' => 'work-link']) ?>
                                        <div class="work-id">#<?= $work->id ?></div>
                                    </td>
                                    <td class="text-sys-text-muted"><?= Html::encode($work->user?->surname . ' ' . $work->user?->name ?? '—') ?></td>
                                    <td class="text-center">
                                        <span class="score-badge <?= $scoreClass ?>"><?= number_format($avg, 1) ?>/5</span>
                                    </td>
                                    <td class="text-center font-semibold"><?= $stats[$work->id]['votes'] ?? 0 ?></td>
                                    <td class="text-center">
                                        <button type="button" onclick="openRatingsModal(<?= $work->id ?>)" class="action-btn" title="Просмотреть оценки">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span class="ml-1 text-xs"><?= count($work->juryRatings ?? []) ?></span>
                                        </button>
                                    </td>
                                    <td class="text-center font-bold"><?= $stats[$work->id]['final'] ?? 0 ?></td>
                                    <td class="text-right">
                                        <?= Html::a('Оценить', ['/admin/jury/rate', 'submission_id' => $work->id], ['class' => 'action-btn rate']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="ratings-modal">
        <div class="modal-card">
            <div class="modal-header">
                <div class="modal-title">Оценки жюри</div>
                <button class="modal-close" onclick="closeRatingsModal()">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body" id="ratings-modal-content">
                <div class="rating-empty">Загрузка...</div>
            </div>
        </div>
    </div>

    <script>
        function openRatingsModal(submissionId) {
            const modal = document.getElementById('ratings-modal');
            const content = document.getElementById('ratings-modal-content');
            modal.classList.add('active');
            content.innerHTML = '<div class="rating-empty">Загрузка...</div>';
            document.body.style.overflow = 'hidden';

            fetch('<?= \yii\helpers\Url::to(['/admin/admin/view-ratings']) ?>?submission_id=' + submissionId, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(r => r.text())
                .then(html => { content.innerHTML = html || '<div class="rating-empty">Оценок пока нет</div>'; })
                .catch(() => { content.innerHTML = '<div class="rating-empty" style="color:#ef4444">Ошибка загрузки</div>'; });
        }
        function closeRatingsModal() {
            document.getElementById('ratings-modal').classList.remove('active');
            document.body.style.overflow = '';
        }
        document.getElementById('ratings-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeRatingsModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRatingsModal();
        });

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