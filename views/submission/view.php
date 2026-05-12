<?php
use yii\helpers\Html;

/** @var $work app\models\Submission */
/** @var $images string[] */
/** @var $voteCount int */
/** @var $comments app\models\JuryComment[] */

$this->title = $work->title . ' — Работа участника';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJs("lightbox.option({resizeDuration: 200, wrapAround: true, showImageNumberLabel: false});");
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
                margin: 0;
                line-height: 1.6;
            }
            .rv { opacity: 0; transform: translateY(12px); transition: opacity 0.6s var(--ease), transform 0.6s var(--ease); }
            .rv.active { opacity: 1; transform: translateY(0); }

            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 12px;
                color: #6b6b80;
                text-decoration: none;
                font-size: 0.9rem;
                font-weight: 500;
                border-radius: 8px;
                transition: all 0.2s var(--ease);
            }
            .back-link:hover {
                background: #f0eaf5;
                color: #8b77b3;
            }
            .back-link svg { width: 14px; height: 14px; }

            .work-header {
                padding: 0 0 24px 0;
                border-bottom: 1px solid #e5e3eb;
                margin-bottom: 32px;
            }
            .work-title {
                font-size: clamp(1.5rem, 2.5vw, 2rem);
                font-weight: 700;
                color: #111118;
                margin: 0 0 12px 0;
                letter-spacing: -0.02em;
                line-height: 1.25;
            }
            .work-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 16px 24px;
                font-size: 0.9rem;
                color: #6b6b80;
            }
            .work-meta-item {
                display: flex;
                align-items: center;
                gap: 6px;
            }
            .work-meta-item svg {
                width: 14px;
                height: 14px;
                color: #8b77b3;
                flex-shrink: 0;
            }
            .work-meta-item a {
                color: #111118;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
            }
            .work-meta-item a:hover { color: #8b77b3; }

            /* Gallery */
            .gallery {
                margin-bottom: 40px;
            }
            .gallery-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
            @media (min-width: 768px) { .gallery-grid { grid-template-columns: repeat(3, 1fr); } }
            @media (min-width: 1024px) { .gallery-grid { grid-template-columns: repeat(4, 1fr); } }

            .gallery-item {
                position: relative;
                border-radius: 14px;
                overflow: hidden;
                aspect-ratio: 1;
                background: #f0eaf5;
                display: block;
                cursor: zoom-in;
            }
            .gallery-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s var(--ease);
                display: block;
            }
            .gallery-item:hover img { transform: scale(1.03); }

            .gallery-empty {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 320px;
                background: #fafaf8;
                border-radius: 14px;
                border: 1px dashed #e5e3eb;
                color: #8b8b9e;
            }
            .gallery-empty svg { width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.6; }

            /* Description */
            .description {
                padding: 24px 0;
                border-top: 1px solid #e5e3eb;
                border-bottom: 1px solid #e5e3eb;
                margin-bottom: 40px;
            }
            .description-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #111118;
                margin: 0 0 16px 0;
            }
            .description-text {
                color: #111118;
                line-height: 1.75;
                white-space: pre-wrap;
                margin: 0;
                font-size: 1rem;
            }

            /* Vote section */
            .vote-section {
                text-align: center;
                padding: 32px 0;
                margin-bottom: 40px;
            }
            .vote-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                background: #111118;
                color: #fff;
                border: none;
                border-radius: 12px;
                padding: 14px 32px;
                font-size: 0.95rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.25s var(--ease);
                min-width: 260px;
            }
            .vote-btn:hover {
                background: #222;
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            }
            .vote-btn.voted {
                background: #f0eaf5;
                color: #8b77b3;
                border: 1.5px solid #8b77b3;
            }
            .vote-btn.voted:hover {
                background: #e8e0f2;
                transform: translateY(-1px);
            }
            .vote-btn svg {
                width: 18px;
                height: 18px;
                transition: transform 0.2s ease;
            }
            .vote-btn:hover svg { transform: scale(1.1); }
            .vote-btn.voted svg { fill: currentColor; }
            .vote-count {
                display: block;
                margin-top: 16px;
                font-size: 0.9rem;
                color: #6b6b80;
            }
            .vote-count strong { color: #111118; font-weight: 600; }

            .own-notice {
                text-align: center;
                padding: 20px;
                background: #f5f3f9;
                border-radius: 12px;
                color: #6b6b80;
                font-size: 0.9rem;
                margin: 32px 0 40px;
            }

            /* Comments */
            .comments {
                padding-top: 24px;
            }
            .comments-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #111118;
                margin: 0 0 20px 0;
            }
            .comment-list {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            .comment {
                padding: 18px 20px;
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 12px;
                transition: border-color 0.2s ease;
            }
            .comment:hover { border-color: #d5d0e3; }
            .comment-header {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 10px;
            }
            .comment-author {
                font-weight: 600;
                color: #111118;
                font-size: 0.95rem;
            }
            .comment-time {
                font-size: 0.8rem;
                color: #8b8b9e;
            }
            .comment-text {
                color: #111118;
                line-height: 1.6;
                margin: 0;
                font-size: 0.95rem;
            }

            @media (max-width: 640px) {
                .work-header { padding-bottom: 20px; margin-bottom: 24px; }
                .work-title { font-size: 1.4rem; }
                .work-meta { gap: 12px 16px; font-size: 0.85rem; }
                .gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
                .vote-btn { width: 100%; min-width: auto; padding: 12px 24px; }
                .description, .vote-section, .comments { padding: 20px 0; }
            }

            ::-webkit-scrollbar { width: 5px; }
            ::-webkit-scrollbar-track { background: #f7f6f9; }
            ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="min-h-screen py-8 px-4">
        <div class="max-w-4xl mx-auto">

            <!-- Back -->
            <div class="rv active">
                <?= Html::a('← Назад', ['/submission/submissions'], ['class' => 'back-link']) ?>
            </div>

            <header class="work-header rv">
                <h1 class="work-title"><?= Html::encode($work->title) ?></h1>
                <div class="work-meta">
                    <div class="work-meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <?= Html::encode($work->konkurs?->title ?? '—') ?>
                    </div>
                    <?php if ($work->nomination): ?>
                        <div class="work-meta-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <?= Html::encode($work->nomination->title) ?>
                        </div>
                    <?php endif; ?>
                    <div class="work-meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <?= Html::a(Html::encode($work->user?->surname . ' ' . $work->user?->name ?? '—'), ['/account/view-profile', 'id' => $work->user_id]) ?>
                    </div>
                    <div class="work-meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <strong><?= $voteCount ?></strong> голосов
                    </div>
                </div>
            </header>

            <section class="gallery rv">
                <?php if (!empty($images)): ?>
                    <div class="gallery-grid">
                        <?php foreach ($images as $img): ?>
                            <a href="<?= Html::encode($img) ?>" data-lightbox="work-<?= $work->id ?>" class="gallery-item">
                                <img src="<?= Html::encode($img) ?>" alt="" loading="lazy">
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="gallery-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </section>

            <?php if (!empty($work->description)): ?>
                <section class="description rv">
                    <h2 class="description-title">Описание</h2>
                    <p class="description-text"><?= Html::encode($work->description) ?></p>
                </section>
            <?php endif; ?>

            <?php
            $isVoted = \app\models\Vote::find()->where(['user_id' => Yii::$app->user->id, 'submission_id' => $work->id])->exists();
            $isOwnWork = !Yii::$app->user->isGuest && $work->user_id == Yii::$app->user->id;
            ?>

            <?php if (!Yii::$app->user->isGuest && !$isOwnWork): ?>
                <section class="vote-section rv">
                    <button class="vote-btn <?= $isVoted ? 'voted' : '' ?>" data-id="<?= $work->id ?>" data-voted="<?= $isVoted ? 'true' : 'false' ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span class="vote-text"><?= $isVoted ? 'Вы уже проголосовали' : 'Проголосовать' ?></span>
                    </button>
                    <span class="vote-count">Всего голосов: <strong id="vote-count-detail"><?= $voteCount ?></strong></span>
                </section>
            <?php elseif ($isOwnWork): ?>
                <div class="own-notice rv">Это ваша работа — голосование недоступно</div>
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
            }, { threshold: 0.1 });
            document.querySelectorAll('.rv').forEach(el => observer.observe(el));

            const voteBtn = document.querySelector('.vote-btn');
            if (voteBtn) {
                voteBtn.addEventListener('click', async function() {
                    if (this.classList.contains('processing')) return;
                    this.classList.add('processing');
                    const submissionId = this.dataset.id;
                    try {
                        const formData = new FormData();
                        formData.append('submissionId', submissionId);
                        const response = await fetch('<?= \yii\helpers\Url::to(['/vote/vote']) ?>', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.dataset.voted = data.voted ? 'true' : 'false';
                            this.classList.toggle('voted', data.voted);
                            this.querySelector('.vote-text').textContent = data.voted ? 'Вы уже проголосовали' : 'Проголосовать';
                            const countEl = document.getElementById('vote-count-detail');
                            if (countEl) countEl.textContent = data.voteCount;
                        } else {
                            alert(data.message || 'Не удалось проголосовать');
                        }
                    } catch (error) {
                        console.error('Vote error:', error);
                        alert('Произошла ошибка. Проверьте соединение и попробуйте снова.');
                    } finally {
                        this.classList.remove('processing');
                    }
                });
            }
        });
    </script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>