<?php
use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);
$this->registerJs("lightbox.option({resizeDuration:200,wrapAround:true,fadeDuration:200,showImageNumberLabel:false});", \yii\web\View::POS_READY);
$this->registerCsrfMetaTags();
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="scroll-smooth">
    <head>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'sys-bg': '#f7f6f9', 'sys-surface': '#ffffff', 'sys-border': '#e5e3eb',
                            'sys-text': '#111118', 'sys-text-muted': '#6b6b80', 'sys-accent': '#8b77b3',
                            'sys-accent-hover': '#75639c', 'sys-accent-light': '#f0eaf5', 'header-bg': '#1a1a24',
                        },
                        fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    }
                }
            }
        </script>
        <style>
            :root { --ease: cubic-bezier(0.16, 1, 0.3, 1); }
            * { box-sizing: border-box; }
            html, body { height: auto; min-height: 100vh; }
            body {
                font-family: 'Inter', system-ui, sans-serif;
                background: #f7f6f9; color: #111118;
                -webkit-font-smoothing: antialiased;
                margin: 0; line-height: 1.6;
                display: flex; flex-direction: column;
            }
            .nav-link {
                display: flex; align-items: center; gap: 10px;
                padding: 11px 14px; border-radius: 10px;
                font-weight: 500; font-size: 0.92rem;
                text-decoration: none; transition: all 0.2s var(--ease);
                color: #111118;
            }
            .nav-link:hover { background: #f0eaf5; color: #8b77b3; transform: translateX(3px); }
            .nav-link.active { background: #f0eaf5; color: #8b77b3; font-weight: 600; }
            #sidebarMenu .nav-link { color: #fff; }
            #sidebarMenu .nav-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
            #sidebarMenu .nav-link.active { background: rgba(139,119,179,0.3); color: #fff; font-weight: 600; }
            .nav-title { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding: 16px 14px 8px; }
            .nav-title.muted { color: rgba(255,255,255,0.5); }
            .sidebar-divider { height: 1px; margin: 12px 14px; }
            .sidebar-divider.light { background: rgba(255,255,255,0.15); }
            .btn-participate {
                background: #111118; color: #fff; padding: 10px 22px; border-radius: 10px;
                font-weight: 600; font-size: 0.9rem; text-decoration: none;
                transition: all 0.25s var(--ease); display: inline-flex; align-items: center; gap: 8px;
            }
            .btn-participate:hover { background: #222; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }
            .logo-text { font-weight: 700; font-size: 1.05rem; color: #fff; letter-spacing: -0.02em; }
            .offcanvas { transition: transform 0.35s var(--ease); }
            .offcanvas-backdrop { background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); }
            .user-badge { background: rgba(255,255,255,0.12); border-radius: 10px; padding: 12px 14px; margin-bottom: 16px; }
            .role-tag { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; }
            .role-admin { background: rgba(251,146,60,0.2); color: #fbbf24; }
            .role-jury { background: rgba(167,139,250,0.2); color: #c4b5fd; }
            .role-user { background: rgba(139,119,179,0.2); color: #a78bfa; }
            .btn-logout {
                width: 100%; padding: 11px; background: transparent;
                border: 1.5px solid rgba(255,255,255,0.2); color: #fff;
                border-radius: 10px; font-weight: 500; font-size: 0.9rem;
                transition: all 0.2s ease; cursor: pointer;
            }
            .btn-logout:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.4); }
            .nav-desktop { display: none !important; }
            ::-webkit-scrollbar { width: 5px; }
            ::-webkit-scrollbar-track { background: #f7f6f9; }
            ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
            .breadcrumb { padding: 0; margin: 0 0 24px 0; background: transparent; border-radius: 0; display: flex; flex-wrap: wrap; list-style: none; }
            .breadcrumb-item { color: #6b6b80; font-size: 0.9rem; display: flex; align-items: center; }
            .breadcrumb-item a { color: #111118; text-decoration: none; font-weight: 500; }
            .breadcrumb-item a:hover { color: #8b77b3; }
            .breadcrumb-item + .breadcrumb-item::before { content: '/'; color: #c5c0d0; margin: 0 8px; }

            /* ===== ГЛАВНЫЙ КОНТЕЙНЕР — ГИБКАЯ ШИРИНА ===== */
            .main-container {
                width: 100%;
                max-width: 1300px; /* ← увеличено с 900px до 1200px для таблиц/галерей */
                margin: 0 auto;
                padding: 24px 16px;
                flex: 1;
            }
            @media (min-width: 768px) { .main-container { padding: 32px 24px; } }
            @media (min-width: 1024px) { .main-container { padding: 40px 32px; } }

            /* ===== ОПЦИОНАЛЬНО: узкий контейнер для форм/профилей ===== */
            .main-container.narrow {
                max-width: 720px;
            }
            /* ===== ОПЦИОНАЛЬНО: широкий контейнер для таблиц/админки ===== */
            .main-container.wide {
                max-width: 1400px;
            }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <header class="sticky top-0 z-50 bg-header-bg border-b border-white/10">
        <div class="main-container" style="padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;">
            <button class="text-white p-2 hover:bg-white/10 rounded-lg transition"
                    type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-label="Меню">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center gap-3">
                <?= Html::a(

                    '<span class="logo-text ml-2 hidden sm:inline">PHOTO STUDIO</span>',
                    ['/site/index'], ['class' => 'flex items-center', 'style' => 'text-decoration: none;']
                ) ?>
            </div>
            <div>
                <?= Html::a('Участвовать', ['/submission/submission'], ['class' => 'btn-participate']) ?>
            </div>
        </div>
    </header>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel"
         style="width: 300px; background: #1a1a24; border-right: 1px solid rgba(255,255,255,0.1);">
        <div class="offcanvas-header border-b border-white/10 pb-4">
            <div class="d-flex align-items-center gap-2">
                <?= Html::img(Yii::getAlias('@web/image/1.png'), ['alt' => 'Logo', 'style' => 'height: 28px;']) ?>
                <span class="logo-text" style="font-size: 1rem;">PHOTO STUDIO</span>
            </div>
            <button type="button" class="btn-close btn-close-white opacity-70" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column px-2 overflow-y-auto">
            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="user-badge">
                    <div class="text-xs text-white/60 mb-1">Вы вошли как</div>
                    <div class="font-semibold text-white"><?= Html::encode(Yii::$app->user->identity->username) ?></div>
                    <div class="mt-2">
                        <?php
                        $role = Yii::$app->user->identity->role ?? 0;
                        $roleClass = $role == 1 ? 'role-admin' : ($role == 2 ? 'role-jury' : 'role-user');
                        $roleLabel = $role == 1 ? 'Админ' : ($role == 2 ? 'Жюри' : 'Участник');
                        ?>
                        <span class="role-tag <?= $roleClass ?>"><?= $roleLabel ?></span>
                    </div>
                </div>
            <?php endif; ?>
            <div class="nav-title muted">Навигация</div>
<?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Профиль', ['/account/dashboard'], ['class' => 'nav-link']) ?>
<?php endif; ?>
            <?= Html::a('Конкурсы', ['/contest/contests'], ['class' => 'nav-link']) ?>
            <?= Html::a('Галерея работ', ['/submission/submissions'], ['class' => 'nav-link']) ?>
            <?= Html::a('Результаты', ['/contest/result'], ['class' => 'nav-link']) ?>
            <?= Html::a('Правила', ['/contest/rules'], ['class' => 'nav-link']) ?>
            <?= Html::a('Поддержка', ['/site/contacts'], ['class' => 'nav-link']) ?>
            <div class="sidebar-divider light"></div>
            <?php if (!Yii::$app->user->isGuest): ?>
                <?php if (Yii::$app->user->identity->role == 1): ?>
                    <div class="nav-title muted">Администрирование</div>
                    <?= Html::a('Дашборд', ['/admin/admin'], ['class' => 'nav-link']) ?>
                    <?= Html::a('Конкурсы', ['/admin/konkurs/index'], ['class' => 'nav-link']) ?>
                    <?= Html::a('Работы', ['/admin/submission/index'], ['class' => 'nav-link']) ?>
                    <?= Html::a('Поддержка', ['/admin/admin/support'], ['class' => 'nav-link']) ?>
                    <div class="sidebar-divider light"></div>
                <?php endif; ?>
                <?php if (Yii::$app->user->identity->role == 2): ?>
                    <div class="nav-title muted">Кабинет жюри</div>
                    <?= Html::a('Мои конкурсы', ['/admin/jury'], ['class' => 'nav-link']) ?>
                    <div class="sidebar-divider light"></div>
                <?php endif; ?>
                <?php if (Yii::$app->user->identity->role == 0): ?>
                    <div class="nav-title muted">Личный кабинет</div>
                    <?= Html::a('Мои работы', ['/account/dashboard'], ['class' => 'nav-link']) ?>
                    <?= Html::a('Настройки', ['/account/settings'], ['class' => 'nav-link']) ?>
                    <div class="sidebar-divider light"></div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="mt-auto pt-4">
                <?php if (Yii::$app->user->isGuest): ?>
                    <?= Html::a('Войти', ['/site/login'], ['class' => 'nav-link mb-1']) ?>
                    <?= Html::a('Регистрация', ['/site/signup'], ['class' => 'nav-link']) ?>
                <?php else: ?>
                    <?= Html::beginForm(['/site/logout'], 'post') ?>
                    <?= Html::submitButton('Выйти', ['class' => 'btn-logout']) ?>
                    <?= Html::endForm() ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebarMenu');
            const links = sidebar?.querySelectorAll('a[href]');
            links?.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && !href.startsWith('#') && !href.startsWith('http') && !href.startsWith('//')) {
                        setTimeout(() => {
                            const bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebar);
                            if (bsOffcanvas) bsOffcanvas.hide();
                        }, 150);
                    }
                });
            });
        });
    </script>

    <main id="main" role="main" style="flex: 1;">
        <div class="main-container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'],
                    'options' => ['class' => 'breadcrumb'],
                    'activeItemTemplate' => '<li class="breadcrumb-item" aria-current="page">{label}</li>',
                    'encodeLabels' => false
                ]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>