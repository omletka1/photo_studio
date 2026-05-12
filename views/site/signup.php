<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
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
                max-width: 480px;
                margin: 48px auto;
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
            .form-label .required { color: #ef4444; margin-left: 4px; font-weight: 400; }

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

            .form-hint {
                font-size: 0.8rem;
                color: #6b6b80;
                margin-top: 6px;
            }

            .checkbox-group {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                margin: 24px 0 32px;
            }
            .checkbox-input {
                width: 18px;
                height: 18px;
                margin-top: 2px;
                accent-color: #8b77b3;
                cursor: pointer;
            }
            .checkbox-label {
                font-size: 0.9rem;
                color: #111118;
                line-height: 1.5;
            }
            .checkbox-label a {
                color: #8b77b3;
                text-decoration: none;
                font-weight: 500;
            }
            .checkbox-label a:hover { text-decoration: underline; }

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
                font-weight: 500;
            }
            .auth-footer a:hover { text-decoration: underline; }

            .error-message {
                color: #ef4444;
                font-size: 0.85rem;
                margin-top: 6px;
                display: none;
            }
            .error-message.visible { display: block; }

            @media (max-width: 640px) {
                .auth-card { margin: 32px auto; padding: 0 16px; }
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
                <h1 class="auth-title">Регистрация</h1>
                <p class="auth-subtitle">Создайте аккаунт для участия в фотоконкурсах</p>
                <hr class="form-divider">

                <?php $form = ActiveForm::begin([
                    'id' => 'signup-form',
                    'enableClientValidation' => false,
                    'enableAjaxValidation' => false,
                    'options' => ['class' => 'signup-form', 'novalidate' => true],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{hint}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-input'],
                        'errorOptions' => ['class' => 'error-message'],
                    ],
                ]); ?>

                <div class="form-group">
                    <?= $form->field($us, 'surname')->textInput([
                        'autofocus' => true,
                        'placeholder' => 'Ваша фамилия',
                        'required' => true,
                    ])->label('Фамилия <span class="required">*</span>', ['encode' => false]) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($us, 'name')->textInput([
                        'placeholder' => 'Ваше имя',
                        'required' => true,
                    ])->label('Имя <span class="required">*</span>', ['encode' => false]) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($us, 'username')->textInput([
                        'placeholder' => 'Логин для входа',
                        'required' => true,
                        'pattern' => '[a-zA-Z0-9_]{3,20}',
                        'title' => '3-20 символов, только латиница, цифры и _',
                    ])->label('Логин <span class="required">*</span>', ['encode' => false]) ?>
                    <div class="form-hint">3-20 символов, только латиница, цифры и _</div>
                </div>

                <div class="form-group">
                    <?= $form->field($us, 'email')->textInput([
                        'type' => 'email',
                        'placeholder' => 'example@email.com',
                        'required' => true,
                    ])->label('Email <span class="required">*</span>', ['encode' => false]) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($us, 'password')->passwordInput([
                        'placeholder' => 'Минимум 8 символов',
                        'required' => true,
                        'minlength' => 8,
                    ])->label('Пароль <span class="required">*</span>', ['encode' => false]) ?>
                    <div class="form-hint">Минимум 8 символов, используйте цифры и буквы</div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="agree-rules" class="checkbox-input" required>
                    <label for="agree-rules" class="checkbox-label">
                        Я согласен с <a href="/site/rules" target="_blank">правилами платформы</a> и <a href="/site/privacy" target="_blank">политикой конфиденциальности</a> <span class="required">*</span>
                    </label>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn-submit', 'name' => 'signup-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <div class="auth-footer">
                    Уже есть аккаунт? <?= Html::a('Войти', ['/site/login']) ?>
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


            const form = document.getElementById('signup-form');
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

                const checkbox = document.getElementById('agree-rules');
                if (checkbox && !checkbox.checked) {
                    e.preventDefault();
                    checkbox.closest('.checkbox-group')?.classList.add('error');
                    hasError = true;
                }

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
    <?php
    $this->registerJs(<<<JS
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('signup-form');
        const btn = form?.querySelector('button[type="submit"]');
        
        if (form && btn) {
            btn.addEventListener('click', function(e) {
                console.log('🔘 Submit clicked');
                setTimeout(() => {
                    if (!form.classList.contains('submitting')) {
                        console.warn('⚠️ Form stuck, forcing native submit');
                        form.classList.add('submitting');
                        form.submit();
                    }
                }, 2000);
            });
        }
    });
JS
    );
    ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>