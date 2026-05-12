<?php
use yii\helpers\Html;

$this->registerCssFile('@web/css/result.css');
$this->title = 'Итоги закрытых конкурсов';

$this->registerJs("lightbox.option({resizeDuration:200,wrapAround:true,fadeDuration:200,showImageNumberLabel:false});");
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
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

            .page-header {
                text-align: center;
                padding: 40px 20px 24px;
            }
            .page-title {
                font-size: clamp(1.8rem, 3vw, 2.3rem);
                font-weight: 700;
                color: #111118;
                margin: 0 0 8px 0;
                letter-spacing: -0.02em;
            }
            .page-subtitle {
                color: #6b6b80;
                font-size: 1rem;
                max-width: 480px;
                margin: 0 auto;
            }

            .results-list {
                display: flex;
                flex-direction: column;
                gap: 24px;
                max-width: 900px;
                margin: 0 auto;
            }

            .contest-card {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                overflow: hidden;
                transition: box-shadow 0.3s var(--ease), border-color 0.2s ease;
            }
            .contest-card:hover {
                box-shadow: 0 12px 32px rgba(0,0,0,0.06);
                border-color: #d5d0e3;
            }
            .contest-header {
                padding: 20px 24px;
                border-bottom: 1px solid #f0eef5;
                background: #fafaf8;
            }
            .contest-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: #111118;
                margin: 0 0 6px 0;
                line-height: 1.3;
            }
            .contest-desc {
                font-size: 0.9rem;
                color: #6b6b80;
                margin: 0;
                line-height: 1.5;
            }

            .works-list {
                display: flex;
                flex-direction: column;
                gap: 16px;
                padding: 20px 24px;
            }

            .work-card {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 12px;
                padding: 16px;
                transition: border-color 0.2s ease;
            }
            .work-card:hover { border-color: #d5d0e3; }

            .work-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 12px;
                flex-wrap: wrap;
            }
            .work-author {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 0.9rem;
                font-weight: 500;
                color: #111118;
            }
            .work-author svg {
                width: 16px;
                height: 16px;
                color: #8b77b3;
                flex-shrink: 0;
            }

            .rank-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 12px;
                border-radius: 8px;
                font-size: 0.8rem;
                font-weight: 600;
                white-space: nowrap;
            }
            .rank-1 {
                background: #fef3c7;
                color: #92400e;
                border: 1px solid #fcd34d;
            }
            .rank-1 svg { color: #f59e0b; }
            .rank-2 {
                background: #f1f5f9;
                color: #334155;
                border: 1px solid #cbd5e1;
            }
            .rank-2 svg { color: #94a3b8; }
            .rank-3 {
                background: #ffedd5;
                color: #7c2d12;
                border: 1px solid #fdba74;
            }
            .rank-3 svg { color: #b45309; }
            .rank-participant {
                background: #f0eaf5;
                color: #6b6b80;
                border: 1px solid #d5d0e3;
            }
            .rank-badge svg { width: 14px; height: 14px; flex-shrink: 0; }

            .work-title {
                font-size: 1.05rem;
                font-weight: 600;
                color: #111118;
                margin: 0 0 8px 0;
                line-height: 1.3;
            }
            .work-desc {
                font-size: 0.9rem;
                color: #6b6b80;
                margin: 0 0 16px 0;
                line-height: 1.5;
            }

            .work-images {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 12px;
                margin-top: 12px;
            }
            .work-image {
                position: relative;
                border-radius: 10px;
                overflow: hidden;
                aspect-ratio: 1;
                background: #f0eaf5;
                cursor: pointer;
            }
            .work-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s var(--ease);
                display: block;
            }
            .work-image:hover img { transform: scale(1.04); }
            .work-image-placeholder {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
                color: #c5c0d0;
            }
            .work-image-placeholder svg { width: 36px; height: 36px; }

            .empty-state {
                text-align: center;
                padding: 56px 24px;
                background: #fff;
                border: 1px dashed #e5e3eb;
                border-radius: 16px;
                max-width: 480px;
                margin: 32px auto;
            }
            .empty-state svg { width: 56px; height: 56px; color: #d5d0e3; margin-bottom: 14px; }
            .empty-state p { color: #6b6b80; font-size: 1rem; margin: 0; }

            @media (max-width: 640px) {
                .page-header { padding: 32px 20px 20px; }
                .page-title { font-size: 1.5rem; }
                .contest-header, .works-list { padding: 16px 20px; }
                .work-header { flex-direction: column; align-items: flex-start; }
                .work-images { grid-template-columns: repeat(2, 1fr); }
            }

            /* Lightbox override */
            .lb-data .lb-caption { font-family: 'Inter', sans-serif; color: #111118; }
            .lb-close { opacity: 0.7; transition: opacity 0.2s ease; }
            .lb-close:hover { opacity: 1; }

            ::-webkit-scrollbar { width: 5px; }
            ::-webkit-scrollbar-track { background: #f7f6f9; }
            ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="min-h-screen py-6 px-4">
        <div class="max-w-6xl mx-auto">

            <header class="page-header rv active">
                <h1 class="page-title">Итоги закрытых конкурсов</h1>
                <p class="page-subtitle">Победители и лучшие работы завершённых фотоконкурсов</p>
            </header>

            <?php if (empty($results)): ?>
                <div class="empty-state rv">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        <path d="M9 22h6M12 17v5"/>
                    </svg>
                    <p>Нет закрытых конкурсов с результатами</p>
                </div>
            <?php else: ?>
                <div class="results-list">
                    <?php foreach ($results as $resultIndex => $result): ?>
                        <div class="contest-card rv" style="transition-delay: <?= $resultIndex * 0.08 ?>s">
                            <div class="contest-header">
                                <h2 class="contest-title"><?= Html::encode($result['konkurs']->title) ?></h2>
                                <p class="contest-desc"><?= Html::encode($result['konkurs']->description) ?></p>
                            </div>

                            <?php if (empty($result['works'])): ?>
                                <div class="works-list">
                                    <p class="empty-state" style="margin:0;padding:24px;background:#fafaf8;border-radius:12px">
                                        Работы для этого конкурса ещё не добавлены
                                    </p>
                                </div>
                            <?php else: ?>
                                <div class="works-list">
                                    <?php foreach ($result['works'] as $workIndex => $work): ?>
                                        <div class="work-card rv" style="transition-delay: <?= ($resultIndex * 0.08 + $workIndex * 0.04) ?>s">
                                            <div class="work-header">
                                                <div class="work-author">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                                    </svg>
                                                    <?= Html::encode($work->user->surname . ' ' . $work->user->name) ?>
                                                </div>
                                                <?php
                                                $rankClass = match($work->status) {
                                                    1 => 'rank-1',
                                                    2 => 'rank-2',
                                                    3 => 'rank-3',
                                                    default => 'rank-participant',
                                                };
                                                $rankIcon = match($work->status) {
                                                    1 => '<path d="M12 2l2.4 7.2h7.6l-6 4.8 2.4 7.2-6-4.8-6 4.8 2.4-7.2-6-4.8h7.6z"/>',
                                                    2 => '<path d="M12 2v20M2 12h20"/>',
                                                    3 => '<path d="M12 2l3 6 6 1-4 4 1 6-6-3-6 3 1-6-4-4 6-1z"/>',
                                                    default => '<circle cx="12" cy="12" r="8"/>',
                                                };
                                                $rankLabel = match($work->status) {
                                                    1 => '1 место',
                                                    2 => '2 место',
                                                    3 => '3 место',
                                                    default => 'Участник',
                                                };
                                                ?>
                                                <span class="rank-badge <?= $rankClass ?>">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <?= $rankIcon ?>
                                                </svg>
                                                <?= $rankLabel ?>
                                            </span>
                                            </div>

                                            <h3 class="work-title"><?= Html::encode($work->title) ?></h3>
                                            <?php if (!empty($work->description)): ?>
                                                <p class="work-desc"><?= Html::encode($work->description) ?></p>
                                            <?php endif; ?>

                                            <?php
                                            $images = [];
                                            for ($i = 1; $i <= 5; $i++) {
                                                $imgField = 'image' . $i;
                                                if (!empty($work->$imgField)) {
                                                    $images[] = $work->$imgField;
                                                }
                                            }
                                            ?>
                                            <?php if (!empty($images)): ?>
                                                <div class="work-images">
                                                    <?php foreach ($images as $image): ?>
                                                        <?php $imagePath = ($baseImageUrl ?? '') . ltrim($image, '/'); ?>
                                                        <a href="<?= Html::encode($imagePath) ?>" data-lightbox="work-<?= $work->id ?>" class="work-image">
                                                            <img src="<?= Html::encode($imagePath) ?>" alt="" loading="lazy">
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
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