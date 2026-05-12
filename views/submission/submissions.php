<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ListView;

$this->title = 'Работы участников';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
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
            }
            .rv { opacity: 0; transform: translateY(16px); transition: opacity 0.7s var(--ease), transform 0.7s var(--ease); }
            .rv.active { opacity: 1; transform: translateY(0); }
            .stagger-1 { transition-delay: 0.08s; }
            .stagger-2 { transition-delay: 0.16s; }
            .stagger-3 { transition-delay: 0.24s; }
            .stagger-4 { transition-delay: 0.32s; }
            @keyframes pulse-border {
                0%, 100% { box-shadow: 0 0 0 2px #8b77b3, 0 0 24px rgba(139,119,179,0.25); }
                50% { box-shadow: 0 0 0 3px #8b77b3, 0 0 32px rgba(139,119,179,0.4); }
            }
            .submission-card {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                overflow: hidden;
                transition: transform 0.35s var(--ease), box-shadow 0.35s var(--ease), border-color 0.25s ease;
                display: flex;
                flex-direction: column;
                height: 100%;
            }
            .submission-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 16px 40px rgba(0,0,0,0.06);
                border-color: #d5d0e3;
            }
            .image-container {
                position: relative;
                aspect-ratio: 4/3;
                overflow: hidden;
                background: #f0eaf5;
                display: block;
            }
            .image-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s var(--ease);
                display: block;
            }
            .image-container:hover img { transform: scale(1.04); }
            .image-placeholder {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
                color: #c5c0d0;
                background: #f5f3f9;
            }
            .image-placeholder svg { width: 48px; height: 48px; }
            .image-overlay {
                position: absolute;
                inset: 0;
                background: rgba(139, 119, 179, 0.92);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.25s var(--ease);
                pointer-events: none;
            }
            .image-container:hover .image-overlay { opacity: 1; }
            .overlay-text {
                color: #fff;
                font-weight: 600;
                font-size: 0.9rem;
                text-align: center;
                padding: 0 16px;
            }
            .submission-header {
                padding: 16px 20px;
                border-bottom: 1px solid #f0eef5;
                flex-shrink: 0;
            }
            .submission-title {
                font-size: 1.05rem;
                font-weight: 700;
                color: #111118;
                margin: 0 0 8px 0;
                line-height: 1.3;
            }
            .submission-meta {
                display: flex;
                flex-direction: column;
                gap: 6px;
                font-size: 0.8rem;
                color: #6b6b80;
            }
            .meta-item {
                display: flex;
                align-items: center;
                gap: 6px;
                flex-wrap: wrap;
            }
            .meta-icon { width: 14px; height: 14px; color: #8b77b3; flex-shrink: 0; }
            .meta-link {
                color: #111118;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
            }
            .meta-link:hover { color: #8b77b3; }
            .own-badge {
                background: #f0eaf5;
                color: #8b77b3;
                font-size: 0.7rem;
                font-weight: 600;
                padding: 2px 8px;
                border-radius: 6px;
                margin-left: 4px;
                white-space: nowrap;
            }
            .vote-section {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 20px 16px;
                border-top: 1px solid #f0eef5;
                flex-shrink: 0;
                gap: 12px;
            }
            .vote-count {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 0.9rem;
                font-weight: 600;
                color: #111118;
                min-width: 0;
            }
            .vote-count svg { width: 18px; height: 18px; color: #8b77b3; flex-shrink: 0; }
            .vote-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: transparent;
                border: 1.5px solid #e5e3eb;
                border-radius: 10px;
                padding: 8px 16px;
                font-size: 0.85rem;
                font-weight: 500;
                color: #111118;
                cursor: pointer;
                transition: all 0.2s var(--ease);
                white-space: nowrap;
                flex-shrink: 0;
            }
            .vote-btn:hover { border-color: #8b77b3; background: #f0eaf5; color: #8b77b3; }
            .vote-btn[data-voted="true"] { background: #8b77b3; border-color: #8b77b3; color: #fff; }
            .vote-btn[data-voted="true"] .vote-icon { fill: currentColor; }
            .vote-icon { width: 16px; height: 16px; transition: transform 0.2s ease; flex-shrink: 0; }
            .vote-btn:hover .vote-icon { transform: scale(1.15); }
            .vote-disabled { font-size: 0.82rem; color: #8b8b9e; white-space: nowrap; }
            .vote-link { color: #8b77b3; text-decoration: none; font-weight: 500; }
            .vote-link:hover { text-decoration: underline; }

            .filter-bar { display: flex; justify-content: center; margin-bottom: 24px; }
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
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b6b80' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                padding-right: 36px;
            }
            .filter-select:focus { outline: none; border-color: #8b77b3; }

            .page-header { text-align: center; padding: 32px 20px 16px; }
            .page-header h1 {
                font-size: clamp(1.8rem, 3vw, 2.3rem);
                font-weight: 700;
                margin: 0 0 8px 0;
                letter-spacing: -0.02em;
            }
            .page-header p {
                color: #6b6b80;
                font-size: 1rem;
                max-width: 480px;
                margin: 0 auto;
            }


            /* Стало: */
            .grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr); /* ← строго 3 колонки на десктопе */
                gap: 20px;
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
            .grid-wrapper {
                display: contents;
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
            .summary {
                text-align: center;
                color: #6b6b80;
                font-size: 0.9rem;
                margin: 16px 0 8px;
            }

            #submissions-list .summary {
                grid-column: 1 / -1;
                margin: 0 0 16px 0;
            }

            #submissions-list > .grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
            @media (max-width: 1280px) {
                #submissions-list > .grid { grid-template-columns: repeat(2, 1fr); }
            }
            @media (max-width: 768px) {
                #submissions-list > .grid { grid-template-columns: 1fr; }
            }
            @media (max-width: 768px) {
                .image-grid { grid-template-columns: repeat(2, 1fr); }
                .filter-select { width: 100%; max-width: 320px; }
                .vote-section { flex-wrap: wrap; }
                .vote-count { order: 2; }
                .vote-btn, .vote-disabled { order: 1; margin-left: auto; }
            }
            @media (max-width: 480px) {
                .image-grid { grid-template-columns: 1fr; }
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
            <div class="page-header rv active">
                <h1>Работы участников</h1>
                <p>Галерея лучших фотографий наших конкурсов</p>
            </div>

            <div class="filter-bar rv">
                <form method="get">
                    <select name="konkurs" id="konkurs-filter" class="filter-select">
                        <option value="">Все конкурсы</option>
                        <?php foreach ($konkursList as $konkurs): ?>
                            <option value="<?= Html::encode($konkurs->id) ?>" <?= $konkursFilter == $konkurs->id ? 'selected' : '' ?>>
                                <?= Html::encode($konkurs->title) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <div id="submissions-list">
                <div class="summary" style="display: block; text-align: center; color: #6b6b80; font-size: 0.9rem; margin: 16px 0 8px;">
                    Показано <?= $dataProvider->getCount() ?> из <?= $dataProvider->getTotalCount() ?> работ
                </div>


                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_submission_item',
                    'viewParams' => ['baseImageUrl' => $baseImageUrl ?? ''],
                    'summary' => false,
                    'emptyText' => '<div class="empty-state rv"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p>Работы участников пока не добавлены</p></div>',
                    'options' => ['class' => 'grid', 'tag' => 'div'],
                    'itemOptions' => ['class' => 'submission-card rv', 'tag' => 'div'],
                    'pager' => [
                        'class' => LinkPager::class,
                        'options' => ['class' => 'pagination'],
                        'activePageCssClass' => 'active',
                        'disabledPageCssClass' => 'disabled',
                        'nextPageLabel' => false,
                        'prevPageLabel' => false,
                    ]
                ]) ?>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 🔥 IntersectionObserver для анимации .rv
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Не добавляем класс, если элемент — целевой (из хеша)
                        if (!entry.target.matches(':target')) {
                            entry.target.classList.add('active');
                        }
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });

            document.querySelectorAll('.rv').forEach(el => observer.observe(el));

            // 🔥 Фильтр по конкурсу
            const filterSelect = document.getElementById('konkurs-filter');
            const listContainer = document.getElementById('submissions-list');
            const originalUrl = window.location.pathname;

            if (filterSelect) {
                filterSelect.addEventListener('change', function(e) {
                    e.preventDefault();
                    const konkursId = this.value;
                    const params = new URLSearchParams();
                    if (konkursId) params.append('konkurs', konkursId);
                    if (listContainer) listContainer.style.opacity = '0.7';
                    fetch(`${originalUrl}?${params.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(r => r.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newList = doc.getElementById('submissions-list');
                            if (newList && listContainer) {
                                listContainer.innerHTML = newList.innerHTML;
                                const newUrl = params.toString() ? `${originalUrl}?${params.toString()}` : originalUrl;
                                history.pushState({filter: konkursId}, '', newUrl);
                                document.querySelectorAll('.rv').forEach(el => observer.observe(el));
                            }
                        })
                        .catch(() => { if (params.toString()) window.location.search = params.toString(); })
                        .finally(() => { if (listContainer) listContainer.style.opacity = '1'; });
                });
            }

            // 🔥 Пагинация через AJAX
            if (listContainer) {
                listContainer.addEventListener('click', function(e) {
                    const pagerLink = e.target.closest('.pagination a');
                    if (pagerLink && !pagerLink.closest('.active, .disabled')) {
                        e.preventDefault();
                        const url = pagerLink.href;
                        if (listContainer) listContainer.style.opacity = '0.7';
                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(r => r.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newList = doc.getElementById('submissions-list');
                                if (newList && listContainer) {
                                    listContainer.innerHTML = newList.innerHTML;
                                    history.pushState({page: url}, '', url);
                                    document.querySelectorAll('.rv').forEach(el => observer.observe(el));
                                }
                            })
                            .catch(() => { if (url) window.location.href = url; })
                            .finally(() => { if (listContainer) listContainer.style.opacity = '1'; });
                    }
                });
            }

            // 🔥 Обработка back/forward кнопок браузера
            window.addEventListener('popstate', function() {
                if (filterSelect && location.search) {
                    const params = new URLSearchParams(location.search);
                    const konkursId = params.get('konkurs');
                    if (konkursId !== null) {
                        filterSelect.value = konkursId;
                        filterSelect.dispatchEvent(new Event('change'));
                    }
                }
            });

            // 🔥 Голосование за работу
            document.body.addEventListener('click', async function(e) {
                const button = e.target.closest('.vote-btn');
                if (!button || button.classList.contains('processing')) return;
                button.classList.add('processing');
                const submissionId = button.dataset.id;
                try {
                    const formData = new FormData();
                    formData.append('submissionId', submissionId);
                    const response = await fetch('<?= Url::to(['/vote/vote']) ?>', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    const data = await response.json();
                    if (data.success) {
                        button.dataset.voted = data.voted ? 'true' : 'false';
                        const voteCountEl = button.closest('.vote-section')?.querySelector('.vote-count span');
                        if (voteCountEl) voteCountEl.textContent = data.voteCount;
                        const heartIcon = button.querySelector('.vote-icon');
                        if (heartIcon) {
                            heartIcon.style.transition = 'transform 0.2s ease';
                            heartIcon.style.transform = 'scale(1.3)';
                            setTimeout(() => { heartIcon.style.transform = 'scale(1)'; }, 200);
                        }
                        const voteText = button.querySelector('.vote-text');
                        if (voteText) voteText.textContent = data.voted ? 'Проголосовано' : 'Голосовать';
                    } else {
                        alert(data.message || 'Не удалось проголосовать');
                    }
                } catch (error) {
                    console.error('Vote error:', error);
                    alert('Произошла ошибка. Проверьте соединение и попробуйте снова.');
                } finally {
                    button.classList.remove('processing');
                }
            });

            // 🔥 АНИМАЦИЯ ДЛЯ ЦЕЛЕВОЙ РАБОТЫ (по хешу #work-XXX)
            const hash = window.location.hash;
            if (hash && hash.startsWith('#work-')) {
                const target = document.querySelector(hash);
                if (target) {
                    // Сохраняем исходные стили
                    const originalTransition = target.style.transition;

                    // Применяем анимацию появления
                    target.style.opacity = '0';
                    target.style.transform = 'scale(0.98)';
                    target.style.transition = 'opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), transform 0.4s cubic-bezier(0.16, 1, 0.3, 1)';

                    // Запускаем анимацию
                    requestAnimationFrame(() => {
                        target.style.opacity = '1';
                        target.style.transform = 'scale(1)';

                        // Добавляем подсветку через 400мс
                        setTimeout(() => {
// Добавляем пульсирующую подсветку
                            target.style.animation = 'pulse-border 2s ease-in-out infinite';
// Убираем через 3 секунды
                            setTimeout(() => {
                                target.style.animation = '';
                                target.style.boxShadow = '';
                                target.style.transition = originalTransition;
                            }, 3000);
                        }, 400);
                    });

                    // Плавный скролл к элементу
                    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    </script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>