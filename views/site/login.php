<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вход в систему';
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
                            'sys-danger': '#ef4444',
                            'sys-danger-bg': '#fef2f2',
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

            .auth-card {
                max-width: 420px;
                margin: 56px auto;
                padding: 0 20px;
            }
            .auth-form {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                padding: 32px;
                transition: box-shadow 0.3s var(--ease), border-color 0.2s ease;
            }
            .auth-form:hover {
                box-shadow: 0 12px 32px rgba(0,0,0,0.06);
                border-color: #d5d0e3;
            }
            .auth-title {
                font-size: 1.75rem;
                font-weight: 700;
                color: #111118;
                margin: 0 0 8px 0;
                letter-spacing: -0.02em;
                text-align: center;
            }
            .auth-subtitle {
                text-align: center;
                color: #6b6b80;
                font-size: 0.95rem;
                margin: 0 0 24px 0;
            }
            .form-divider {
                height: 1px;
                background: #e5e3eb;
                margin: 24px 0 32px;
                border: none;
            }

            .form-group { margin-bottom: 20px; }
            .form-label {
                display: block;
                font-weight: 600;
                font-size: 0.9rem;
                color: #111118;
                margin-bottom: 8px;
            }
            .form-input {
                width: 100%;
                padding: 12px 14px;
                font-size: 0.95rem;
                font-family: inherit;
                color: #111118;
                background: #fff;
                border: 1.5px solid #e5e3eb;
                border-radius: 10px;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }
            .form-input:focus {
                outline: none;
                border-color: #8b77b3;
                box-shadow: 0 0 0 3px rgba(139, 119, 179, 0.15);
            }
            .form-input::placeholder { color: #8b8b9e; }
            .form-input.error {
                border-color: #ef4444;
            }
            .form-input.error:focus {
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
            }
            .error-message {
                color: #ef4444;
                font-size: 0.85rem;
                margin-top: 6px;
                display: none;
            }
            .error-message.visible { display: block; }

            .checkbox-group {
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 24px 0 32px;
            }
            .checkbox-input {
                width: 18px;
                height: 18px;
                accent-color: #8b77b3;
                cursor: pointer;
            }
            .checkbox-label {
                font-size: 0.9rem;
                color: #111118;
                cursor: pointer;
                user-select: none;
            }

            .btn-submit {
                width: 100%;
                padding: 14px 24px;
                background: #111118;
                color: #fff;
                border: none;
                border-radius: 12px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.25s var(--ease);
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            .btn-submit:hover {
                background: #222;
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            }
            .btn-submit:active { transform: translateY(0); }
            .btn-submit:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }

            .auth-footer {
                text-align: center;
                margin-top: 24px;
                color: #6b6b80;
                font-size: 0.9rem;
            }
            .auth-footer a {
                color: #8b77b3;
                text-decoration: none;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                transition: color 0.2s ease;
            }
            .auth-footer a:hover { color: #75639c; }

            @media (max-width: 640px) {
                .auth-card { margin: 40px auto; padding: 0 16px; }
                .auth-form { padding: 24px 20px; }
                .auth-title { font-size: 1.4rem; }
            }

            ::-webkit-scrollbar { width: 5px; }
            ::-webkit-scrollbar-track { background: #f7f6f9; }
            ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="min-h-screen py-6 px-4">
        <div class="auth-card rv active">
            <div class="auth-form">
                <h1 class="auth-title">Вход в систему</h1>
                <p class="auth-subtitle">Введите данные для доступа к аккаунту</p>
                <hr class="form-divider">

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['class' => 'login-form', 'novalidate' => true],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-input'],
                        'errorOptions' => ['class' => 'error-message'],
                    ],
                ]); ?>

                <div class="form-group">
                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder' => 'Введите ваш логин',
                        'required' => true,
                    ])->label('Логин') ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => 'Введите ваш пароль',
                        'required' => true,
                    ])->label('Пароль') ?>
                </div>

                <div class="checkbox-group">
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'labelOptions' => ['class' => 'checkbox-label'],
                        'inputOptions' => ['class' => 'checkbox-input'],
                    ], false) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn-submit', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <div class="auth-footer">
                    Нет аккаунта? <?= Html::a('Зарегистрироваться', ['site/signup']) ?>
                </div>
            </div>
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


            const form = document.getElementById('login-form');
            const inputs = form?.querySelectorAll('.form-input');

            inputs?.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.validity.valid) {
                        this.classList.remove('error');
                    }
                });
                input.addEventListener('input', function() {
                    if (this.classList.contains('error') && this.validity.valid) {
                        this.classList.remove('error');
                        const errorEl = this.closest('.form-group')?.querySelector('.error-message');
                        if (errorEl) errorEl.classList.remove('visible');
                    }
                });
            });

            form?.addEventListener('submit', function(e) {
                let hasError = false;

                inputs?.forEach(input => {
                    if (input.required && !input.value.trim()) {
                        e.preventDefault();
                        input.classList.add('error');
                        const errorEl = input.closest('.form-group')?.querySelector('.error-message');
                        if (errorEl) {
                            errorEl.textContent = 'Это поле обязательно';
                            errorEl.classList.add('visible');
                        }
                        hasError = true;
                    }
                });

                if (hasError) {
                    const firstError = form.querySelector('.error');
                    firstError?.focus();
                    firstError?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    </script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>