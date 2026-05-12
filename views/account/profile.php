<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Профиль';
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

            .form-container { max-width: 640px; margin: 0 auto; }
            .form-card {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                padding: 24px;
                margin-bottom: 24px;
            }
            .form-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: #111118;
                margin: 0 0 8px 0;
                letter-spacing: -0.02em;
            }
            .form-subtitle {
                color: #6b6b80;
                font-size: 0.9rem;
                margin: 0 0 24px 0;
            }
            .form-divider {
                height: 1px;
                background: #e5e3eb;
                margin: 24px 0;
                border: none;
            }
            .section-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #111118;
                margin: 0 0 16px 0;
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
            .form-input.error { border-color: #ef4444; }
            .form-input.error:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15); }
            .form-textarea { min-height: 100px; resize: vertical; line-height: 1.6; }
            .form-hint { font-size: 0.8rem; color: #6b6b80; margin-top: 6px; }
            .error-message { color: #ef4444; font-size: 0.85rem; margin-top: 6px; display: none; }
            .error-message.visible { display: block; }

            .avatar-preview {
                width: 96px; height: 96px; border-radius: 50%;
                overflow: hidden; border: 3px solid #f0eaf5;
                background: #f5f3f9; margin-bottom: 12px;
                display: flex; align-items: center; justify-content: center;
            }
            .avatar-preview img { width: 100%; height: 100%; object-fit: cover; display: block; }
            .avatar-placeholder {
                width: 100%; height: 100%;
                display: flex; align-items: center; justify-content: center;
                background: #f0eaf5; color: #8b77b3;
                font-size: 2rem; font-weight: 600;
            }
            .file-input {
                display: block; width: 100%;
                padding: 10px 14px;
                background: #fff; border: 1.5px dashed #e5e3eb;
                border-radius: 10px; font-size: 0.9rem; color: #6b6b80;
                cursor: pointer; transition: border-color 0.2s ease, background 0.2s ease;
            }
            .file-input:hover { border-color: #8b77b3; background: #f0eaf5; }
            .file-input::file-selector-button {
                margin-right: 12px; padding: 8px 14px;
                background: #f0eaf5; color: #8b77b3;
                border: none; border-radius: 8px;
                font-weight: 500; font-size: 0.85rem; cursor: pointer;
                transition: background 0.2s ease;
            }
            .file-input::file-selector-button:hover { background: #e8e0f2; }

            .password-field { position: relative; }
            .password-toggle {
                position: absolute; right: 12px; top: 50%;
                transform: translateY(-50%);
                background: transparent; border: none;
                color: #6b6b80; cursor: pointer;
                padding: 4px; display: flex; align-items: center;
                transition: color 0.2s ease;
            }
            .password-toggle:hover { color: #8b77b3; }
            .password-toggle svg { width: 18px; height: 18px; }
            .password-toggle .eye-closed { display: none; }
            .password-toggle.active .eye-open { display: none; }
            .password-toggle.active .eye-closed { display: block; }

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
                margin-top: 8px;
            }
            .btn-submit:hover {
                background: #222;
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            }
            .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

            @media (max-width: 640px) {
                .form-card { padding: 20px; }
                .form-title { font-size: 1.25rem; }
            }
            ::-webkit-scrollbar { width: 5px; }
            ::-webkit-scrollbar-track { background: #f7f6f9; }
            ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="min-h-screen py-8 px-4">
        <div class="form-container">

            <div class="form-card rv active">
                <h1 class="form-title">Профиль</h1>
                <p class="form-subtitle">Управление личной информацией и настройками аккаунта</p>
                <hr class="form-divider">

                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'profile-form', 'enctype' => 'multipart/form-data', 'novalidate' => true],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{hint}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-input'],
                        'errorOptions' => ['class' => 'error-message'],
                    ],
                ]); ?>


                <div class="form-group">
                    <?= $form->field($model, 'username')->textInput([
                        'placeholder' => 'Логин для входа',
                        'required' => true,
                    ])->label('Логин') ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'email')->textInput([
                        'type' => 'email',
                        'placeholder' => 'example@email.com',
                        'required' => true,
                    ])->label('Email') ?>
                </div>

                <hr class="form-divider">

                <h3 class="section-title">Личная информация</h3>

                <div class="form-group">
                    <?= $form->field($model, 'name')->textInput([
                        'placeholder' => 'Ваше имя',
                        'required' => true,
                    ])->label('Имя') ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'surname')->textInput([
                        'placeholder' => 'Ваша фамилия',
                        'required' => true,
                    ])->label('Фамилия') ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'bio')->textarea([
                        'class' => 'form-input form-textarea',
                        'placeholder' => 'Кратко о себе...',
                        'rows' => 4,
                    ])->label('О себе') ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'website')->textInput([
                        'placeholder' => 'https://...',
                    ])->label('Сайт') ?>
                    <div class="form-hint">Укажите ссылку на ваш сайт или портфолио</div>
                </div>

                <hr class="form-divider">


                <h3 class="section-title">Аватар</h3>

                <div class="form-group">
                    <?php if ($model->avatar): ?>
                        <div class="avatar-preview">
                            <img src="<?= Yii::getAlias('@web/' . $model->avatar) ?>" alt="Аватар">
                        </div>
                    <?php else: ?>
                        <div class="avatar-preview">
                            <div class="avatar-placeholder"><?= mb_substr($model->name ?? $model->username ?? '?', 0, 1) ?></div>
                        </div>
                    <?php endif; ?>
                    <?= $form->field($model, 'avatarFile')->fileInput([
                        'class' => 'file-input',
                        'accept' => 'image/*',
                    ])->label('Выберите изображение') ?>
                    <div class="form-hint">PNG, JPG до 2 МБ</div>
                </div>

                <hr class="form-divider">

                <h3 class="section-title">Смена пароля</h3>

                <div class="form-group">
                    <label class="form-label" for="new_password">Новый пароль</label>
                    <div class="password-field">
                        <input type="password" name="User[new_password]" id="new_password" class="form-input"
                               placeholder="Минимум 8 символов" autocomplete="new-password">
                        <button type="button" class="password-toggle" data-target="new_password" aria-label="Показать пароль">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    <div class="form-hint">Оставьте пустым, если не хотите менять пароль</div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password_repeat">Повторите пароль</label>
                    <div class="password-field">
                        <input type="password" name="User[new_password_repeat]" id="new_password_repeat" class="form-input"
                               placeholder="Повторите новый пароль" autocomplete="new-password">
                        <button type="button" class="password-toggle" data-target="new_password_repeat" aria-label="Показать пароль">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Сохранить изменения</button>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) { entry.target.classList.add('active'); observer.unobserve(entry.target); }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });
            document.querySelectorAll('.rv').forEach(el => observer.observe(el));


            document.querySelectorAll('.password-toggle').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.dataset.target;
                    const input = document.getElementById(targetId);
                    if (!input) return;

                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    this.classList.toggle('active', isPassword);
                    input.focus();
                });
            });


            const pass1 = document.getElementById('new_password');
            const pass2 = document.getElementById('new_password_repeat');
            if (pass1 && pass2) {
                pass2.addEventListener('input', function() {
                    if (pass1.value && pass2.value && pass1.value !== pass2.value) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }
                });
            }

            const form = document.querySelector('.profile-form');
            form?.addEventListener('submit', function(e) {
                let hasError = false;


                if (pass1?.value && pass2?.value && pass1.value !== pass2.value) {
                    e.preventDefault();
                    pass2.classList.add('error');
                    const errorEl = pass2.closest('.form-group')?.querySelector('.error-message');
                    if (errorEl) {
                        errorEl.textContent = 'Пароли не совпадают';
                        errorEl.classList.add('visible');
                    }
                    hasError = true;
                }

                form.querySelectorAll('.form-input[required]').forEach(input => {
                    if (!input.value.trim()) {
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