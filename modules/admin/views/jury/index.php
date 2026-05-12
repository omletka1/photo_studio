<?php
use yii\helpers\Html;

/** @var $konkurs app\models\Konkurs[] */

$this->title = 'Кабинет жюри';
?>

<!-- Убираем лишние обёртки — .main-container уже в main.php -->
<div class="jury-dashboard">

    <header class="page-header">
        <h1 class="page-title">Кабинет жюри</h1>
        <p class="page-subtitle">Доступные конкурсы для оценивания</p>
    </header>

    <?php if (empty($konkurs)): ?>
        <div class="empty-state rv active">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                <path d="M9 22h6M12 17v5"/>
            </svg>
            <p>У вас пока нет назначенных конкурсов</p>
        </div>
    <?php else: ?>
        <div class="konkurs-list">
            <?php foreach ($konkurs as $index => $k): ?>
                <div class="konkurs-card rv" style="transition-delay: <?= $index * 0.05 ?>s">
                    <div class="konkurs-info">
                        <h3 class="konkurs-title"><?= Html::encode($k->title) ?></h3>
                        <?php if ($k->description): ?>
                            <p class="konkurs-desc"><?= Html::encode($k->description) ?></p>
                        <?php endif; ?>
                        <div class="konkurs-dates">
                            <svg class="date-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span><?= Yii::$app->formatter->asDate($k->start_date) ?> — <?= Yii::$app->formatter->asDate($k->end_date) ?></span>
                        </div>
                    </div>
                    <div class="konkurs-actions">
                        <?= Html::a('
                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            Смотреть работы
                        ', ['/admin/jury/submissions', 'konkurs_id' => $k->id], ['class' => 'btn-view', 'encode' => false]) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    :root { --ease: cubic-bezier(0.16, 1, 0.3, 1); }

    .page-header { margin-bottom: 24px; }
    .page-title {
        font-size: 1.5rem; font-weight: 700; color: #111118;
        margin: 0 0 4px 0; letter-spacing: -0.02em;
    }
    .page-subtitle { color: #6b6b80; font-size: 0.95rem; margin: 0; }

    .empty-state {
        text-align: center; padding: 48px 24px; background: #fff;
        border: 1px dashed #e5e3eb; border-radius: 16px;
        color: #6b6b80; font-size: 0.95rem;
    }
    .empty-icon {
        width: 48px; height: 48px; color: #d5d0e3;
        margin: 0 auto 12px; display: block;
    }

    .konkurs-list { display: flex; flex-direction: column; gap: 12px; }

    .konkurs-card {
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px; padding: 20px;
        background: #fff; border: 1px solid #e5e3eb; border-radius: 12px;
        transition: all 0.25s var(--ease);
        opacity: 0; transform: translateY(16px);
    }
    .konkurs-card.active { opacity: 1; transform: translateY(0); }
    .konkurs-card:hover {
        border-color: #d5d0e3; box-shadow: 0 8px 24px rgba(0,0,0,0.04);
        transform: translateY(-2px);
    }

    .konkurs-info { flex: 1; min-width: 0; }
    .konkurs-title {
        font-size: 1.05rem; font-weight: 600; color: #111118;
        margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .konkurs-desc {
        font-size: 0.9rem; color: #6b6b80; margin: 0 0 8px 0;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden; line-height: 1.5;
    }
    .konkurs-dates {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.85rem; color: #8b8b9e;
    }
    .date-icon { width: 14px; height: 14px; color: #8b77b3; flex-shrink: 0; }

    .konkurs-actions { flex-shrink: 0; }
    .btn-view {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; background: #111118; color: #fff;
        border: none; border-radius: 8px;
        font-size: 0.85rem; font-weight: 500; text-decoration: none;
        transition: all 0.25s var(--ease); white-space: nowrap;
    }
    .btn-view:hover {
        background: #222; transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .btn-icon { width: 14px; height: 14px; }

    .rv { opacity: 0; transform: translateY(16px); transition: opacity 0.7s var(--ease), transform 0.7s var(--ease); }
    .rv.active { opacity: 1; transform: translateY(0); }

    @media (max-width: 640px) {
        .konkurs-card { flex-direction: column; align-items: flex-start; }
        .konkurs-actions { width: 100%; display: flex; justify-content: flex-end; }
        .btn-view { width: 100%; justify-content: center; }
    }
</style>

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