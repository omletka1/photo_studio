<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Открытые конкурсы';
?>
<?php
// 🔥 Функция для русского формата дат (работает БЕЗ расширения intl)
if (!function_exists('ruDate')) {
    function ruDate($date) {
        $months = [
            1=>'января', 2=>'февраля', 3=>'марта', 4=>'апреля', 5=>'мая', 6=>'июня',
            7=>'июля', 8=>'августа', 9=>'сентября', 10=>'октября', 11=>'ноября', 12=>'декабря'
        ];
        $ts = is_numeric($date) ? (int)$date : strtotime($date);
        if (!$ts) return $date; // если дата невалидна — вернём как есть
        $d = date('j', $ts);
        $m = $months[(int)date('n', $ts)];
        $y = date('Y', $ts);
        return "$d $m $y";
    }
}
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
            }
            .rv { opacity: 0; transform: translateY(16px); transition: opacity 0.7s var(--ease), transform 0.7s var(--ease); }
            .rv.active { opacity: 1; transform: translateY(0); }
            .stagger-1 { transition-delay: 0.08s; }
            .stagger-2 { transition-delay: 0.16s; }
            .stagger-3 { transition-delay: 0.24s; }
            .stagger-4 { transition-delay: 0.32s; }
            .contest-card {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                padding: 24px;
                transition: transform 0.35s var(--ease), box-shadow 0.35s var(--ease), border-color 0.25s ease;
                display: flex;
                flex-direction: column;
            }
            .contest-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 16px 40px rgba(0,0,0,0.06);
                border-color: #d5d0e3;
            }
            .contest-title {
                font-size: 1.25rem;
                font-weight: 700;
                line-height: 1.3;
                margin-bottom: 8px;
                color: #111118;
            }
            .contest-desc {
                font-size: 0.95rem;
                color: #6b6b80;
                line-height: 1.6;
                margin-bottom: 16px;
                flex-grow: 1;
            }
            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 4px 10px;
                border-radius: 999px;
                font-size: 0.72rem;
                font-weight: 600;
                background: #ecfdf5;
                color: #059669;
                border: 1px solid #d1fae5;
            }
            .date-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 12px;
                background: #f0eaf5;
                border-radius: 10px;
                font-size: 0.82rem;
                color: #6b6b80;
                margin: 12px 0 20px;
            }
            .date-badge svg { width: 14px; height: 14px; color: #8b77b3; }
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 10px 20px;
                border-radius: 10px;
                font-weight: 600;
                font-size: 0.9rem;
                text-decoration: none;
                transition: all 0.25s var(--ease);
                cursor: pointer;
            }
            .btn-primary {
                background: #111118;
                color: #fff;
            }
            .btn-primary:hover {
                background: #222;
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            }
            .btn-outline {
                background: transparent;
                color: #111118;
                border: 1.5px solid #e5e3eb;
            }
            .btn-outline:hover {
                border-color: #8b77b3;
                background: #f0eaf5;
                color: #8b77b3;
                transform: translateY(-2px);
            }
            .btn-group {
                display: flex;
                gap: 10px;
                margin-top: auto;
            }
            .btn-group .btn { flex: 1; }
            .empty-state {
                text-align: center;
                padding: 60px 20px;
                background: #fff;
                border: 1px dashed #e5e3eb;
                border-radius: 16px;
                max-width: 520px;
                margin: 40px auto;
            }
            .empty-state svg { width: 64px; height: 64px; color: #d5d0e3; margin-bottom: 16px; }
            .empty-state p { color: #6b6b80; font-size: 1.05rem; }
            .page-header {
                text-align: center;
                padding: 40px 20px 24px;
            }
            .page-header h1 {
                font-size: clamp(1.8rem, 3vw, 2.5rem);
                font-weight: 700;
                margin-bottom: 8px;
                letter-spacing: -0.02em;
            }
            .page-header p {
                color: #6b6b80;
                font-size: 1.05rem;
                max-width: 520px;
                margin: 0 auto;
            }
            .grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
                gap: 20px;
            }
            @media (max-width: 768px) {
                .grid { grid-template-columns: 1fr; }
                .btn-group { flex-direction: column; }
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
            <div class="page-header rv active">
                <h1>Открытые конкурсы</h1>
                <p>Выберите конкурс и покажите своё мастерство</p>
            </div>

            <?php if (empty($openContests)): ?>
                <div class="empty-state rv">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p>В настоящее время нет открытых конкурсов. Загляните позже!</p>
                </div>
            <?php else: ?>
                <div class="grid">
                    <?php foreach ($openContests as $index => $konkurs): ?>
                        <div class="contest-card rv stagger-<?= ($index % 4) + 1 ?>">
                            <div class="flex items-start justify-between mb-3">
                                <h2 class="contest-title"><?= Html::encode($konkurs->title) ?></h2>
                                <span class="status-badge">Открыт</span>
                            </div>
                            <p class="contest-desc line-clamp-3"><?= Html::encode($konkurs->description) ?></p>
                            <div class="date-badge">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <?= ruDate($konkurs->start_date) ?> — <?= ruDate($konkurs->end_date) ?>
                            </div>
                            <div class="btn-group">
                                <?= Html::a('Участвовать', ['/submission/submission', 'konkurs_id' => $konkurs->id], ['class' => 'btn btn-primary']) ?>
                                <?= Html::a('Номинации', ['/contest/nominations', 'konkurs_id' => $konkurs->id], ['class' => 'btn btn-outline']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });
            document.querySelectorAll('.rv').forEach(el => observer.observe(el));
        });
    </script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>