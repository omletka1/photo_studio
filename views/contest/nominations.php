<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use app\models\Konkurs;
use yii\helpers\ArrayHelper;

$konkursList = ArrayHelper::map(Konkurs::find()->orderBy('title')->all(), 'id', 'title');
$currentKonkurs = Yii::$app->request->get('konkurs_id');

$this->title = 'Номинации фотоконкурсов';
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

            .filter-bar {
                display: flex;
                justify-content: center;
                margin-bottom: 32px;
            }
            .filter-select {
                background: #fff;
                border: 1.5px solid #e5e3eb;
                border-radius: 10px;
                padding: 10px 16px;
                font-size: 0.95rem;
                font-weight: 500;
                color: #111118;
                min-width: 240px;
                cursor: pointer;
                transition: border-color 0.2s ease;
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b6b80' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                padding-right: 36px;
            }
            .filter-select:focus {
                outline: none;
                border-color: #8b77b3;
            }

            .nominations-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
            @media (max-width: 1024px) { .nominations-grid { grid-template-columns: repeat(2, 1fr); } }
            @media (max-width: 640px) { .nominations-grid { grid-template-columns: 1fr; } }

            .nomination-card {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                overflow: hidden;
                transition: transform 0.35s var(--ease), box-shadow 0.35s var(--ease), border-color 0.25s ease;
                display: flex;
                flex-direction: column;
                height: 100%;
            }
            .nomination-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 16px 40px rgba(0,0,0,0.06);
                border-color: #d5d0e3;
            }
            .nomination-image {
                position: relative;
                aspect-ratio: 4/3;
                overflow: hidden;
                background: #f0eaf5;
                cursor: pointer;
            }
            .nomination-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s var(--ease);
                display: block;
            }
            .nomination-card:hover .nomination-image img { transform: scale(1.04); }
            .nomination-overlay {
                position: absolute;
                inset: 0;
                background: rgba(139, 119, 179, 0.92);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.25s var(--ease);
            }
            .nomination-image:hover .nomination-overlay { opacity: 1; }
            .overlay-text {
                color: #fff;
                font-weight: 600;
                font-size: 0.9rem;
            }
            .nomination-content {
                padding: 20px;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
            }
            .nomination-title {
                font-size: 1.1rem;
                font-weight: 700;
                color: #111118;
                margin: 0 0 8px 0;
                line-height: 1.3;
            }
            .nomination-desc {
                font-size: 0.9rem;
                color: #6b6b80;
                line-height: 1.6;
                margin: 0 0 16px 0;
                flex-grow: 1;
            }
            .nomination-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 10px 20px;
                background: #111118;
                color: #fff;
                border: none;
                border-radius: 10px;
                font-size: 0.9rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.25s var(--ease);
            }
            .nomination-btn:hover {
                background: #222;
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            }

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

            .pagination {
                display: flex;
                justify-content: center;
                gap: 6px;
                margin: 32px 0 16px;
                list-style: none;
                padding: 0;
                flex-wrap: wrap;
            }
            .pagination li a {
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 36px;
                height: 36px;
                padding: 0 8px;
                border-radius: 8px;
                font-size: 0.9rem;
                font-weight: 500;
                color: #6b6b80;
                text-decoration: none;
                border: 1px solid #e5e3eb;
                transition: all 0.2s ease;
            }
            .pagination li a:hover {
                background: #f0eaf5;
                border-color: #8b77b3;
                color: #8b77b3;
            }
            .pagination li.active a {
                background: #8b77b3;
                border-color: #8b77b3;
                color: #fff;
                font-weight: 600;
            }
            .pagination li.disabled a { opacity: 0.5; cursor: not-allowed; }

            .extra-info {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                padding: 32px;
                margin-top: 40px;
                text-align: center;
            }
            .extra-info h2 {
                font-size: 1.3rem;
                font-weight: 700;
                color: #111118;
                margin: 0 0 12px 0;
            }
            .extra-info p {
                color: #6b6b80;
                font-size: 0.95rem;
                margin: 0;
                max-width: 520px;
                margin-inline: auto;
            }

            .lightbox {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.92);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                padding: 20px;
            }
            .lightbox.active { display: flex; }
            .lightbox img {
                max-width: 100%;
                max-height: 90vh;
                border-radius: 8px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            }
            .lightbox-close {
                position: absolute;
                top: 20px;
                right: 24px;
                color: #fff;
                font-size: 28px;
                cursor: pointer;
                transition: color 0.2s ease;
            }
            .lightbox-close:hover { color: #8b77b3; }

            .loader {
                display: none;
                text-align: center;
                padding: 24px;
            }
            .loader-spinner {
                width: 28px;
                height: 28px;
                border: 2.5px solid #e5e3eb;
                border-top-color: #8b77b3;
                border-radius: 50%;
                animation: spin 0.8s linear infinite;
                margin: 0 auto 12px;
            }
            @keyframes spin { to { transform: rotate(360deg); } }
            .loader-text { color: #6b6b80; font-size: 0.9rem; margin: 0; }

            @media (max-width: 640px) {
                .page-header { padding: 32px 20px 20px; }
                .page-title { font-size: 1.5rem; }
                .filter-select { width: 100%; max-width: 320px; }
                .extra-info { padding: 24px 20px; }
            }

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
                <h1 class="page-title">Номинации фотоконкурсов</h1>
                <p class="page-subtitle">Выберите номинацию и покажите своё мастерство</p>
            </header>

            <div class="filter-bar rv">
                <form id="filter-form" method="get">
                    <select name="konkurs_id" id="konkurs-filter" class="filter-select">
                        <option value="">Все конкурсы</option>
                        <?php foreach ($konkursList as $id => $title): ?>
                            <option value="<?= $id ?>" <?= $currentKonkurs == $id ? 'selected' : '' ?>>
                                <?= Html::encode($title) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <div id="nominations-list">
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_nomination_item',
                    'summary' => false,
                    'emptyText' => '<div class="empty-state rv"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg><p>Номинации пока не добавлены</p></div>',
                    'options' => ['class' => 'nominations-grid'],
                    'itemOptions' => ['class' => 'nomination-card rv', 'tag' => 'div'],
                    'layout' => "{items}\n{pager}",
                    'pager' => [
                        'class' => 'yii\widgets\LinkPager',
                        'options' => ['class' => 'pagination'],
                        'activePageCssClass' => 'active',
                        'disabledPageCssClass' => 'disabled',
                        'nextPageLabel' => false,
                        'prevPageLabel' => false,
                    ]
                ]) ?>
            </div>

            <div class="extra-info rv">
                <h2>Почему выбирают нас</h2>
                <p>Простой интерфейс, современный дизайн и возможность проявить себя — всё в одном месте.</p>
            </div>

        </div>
    </div>

    <div class="lightbox" id="lightbox">
        <span class="lightbox-close">&times;</span>
        <img src="" alt="">
    </div>

    <div class="loader" id="loader">
        <div class="loader-spinner"></div>
        <p class="loader-text">Загрузка...</p>
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

            function initReveal() {
                document.querySelectorAll('.rv').forEach(el => observer.observe(el));
            }
            initReveal();


            const lightbox = document.getElementById('lightbox');

            function initLightbox(container = document) {
                container.querySelectorAll('.nomination-image').forEach(img => {
                    const newImg = img.cloneNode(true);
                    img.parentNode.replaceChild(newImg, img);

                    newImg.addEventListener('click', function(e) {
                        e.preventDefault();
                        const src = this.querySelector('img')?.src;
                        if (src && lightbox) {
                            lightbox.querySelector('img').src = src;
                            lightbox.classList.add('active');
                            document.body.style.overflow = 'hidden';
                        }
                    });
                });
            }
            initLightbox();

            if (lightbox) {
                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox || e.target.classList.contains('lightbox-close')) {
                        lightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                        lightbox.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            }

            const filterSelect = document.getElementById('konkurs-filter');
            const listContainer = document.getElementById('nominations-list');
            const loader = document.getElementById('loader');
            const originalUrl = window.location.pathname;

            function setLoading(state) {
                if (loader) loader.style.display = state ? 'block' : 'none';
                if (listContainer) listContainer.style.opacity = state ? '0.6' : '1';
            }

            function updateList(html) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newList = doc.getElementById('nominations-list');
                if (newList && listContainer) {
                    listContainer.innerHTML = newList.innerHTML;
                    initReveal();
                    initLightbox(listContainer);
                }
            }

            if (filterSelect) {
                filterSelect.addEventListener('change', function() {
                    const konkursId = this.value;
                    const params = new URLSearchParams();
                    if (konkursId) params.append('konkurs_id', konkursId);

                    setLoading(true);
                    fetch(`${originalUrl}?${params.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(r => r.text())
                        .then(html => {
                            updateList(html);
                            const newUrl = params.toString() ? `${originalUrl}?${params.toString()}` : originalUrl;
                            history.pushState({filter: konkursId}, '', newUrl);
                        })
                        .catch(() => { if (params.toString()) window.location.search = params.toString(); })
                        .finally(() => setLoading(false));
                });
            }

            if (listContainer) {
                listContainer.addEventListener('click', function(e) {
                    const pagerLink = e.target.closest('.pagination a');
                    if (pagerLink && !pagerLink.closest('.active, .disabled')) {
                        e.preventDefault();
                        const url = pagerLink.href;
                        setLoading(true);
                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(r => r.text())
                            .then(html => {
                                updateList(html);
                                history.pushState({page: url}, '', url);
                            })
                            .catch(() => { if (url) window.location.href = url; })
                            .finally(() => setLoading(false));
                    }
                });
            }

            window.addEventListener('popstate', function() {
                if (filterSelect && location.search) {
                    const params = new URLSearchParams(location.search);
                    const konkursId = params.get('konkurs_id');
                    if (konkursId !== null) {
                        filterSelect.value = konkursId;
                        filterSelect.dispatchEvent(new Event('change'));
                    }
                }
            });
        });
    </script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>