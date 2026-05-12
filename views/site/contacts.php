<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Обратная связь';
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
                            'sys-success': '#10b981',
                            'sys-success-bg': '#ecfdf5',
                            'sys-error': '#ef4444',
                            'sys-error-bg': '#fef2f2',
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

            .form-container {
                max-width: 640px;
                margin: 48px auto;
                padding: 0 20px;
            }
            .form-card {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                padding: 32px;
                transition: box-shadow 0.3s var(--ease), border-color 0.2s ease;
            }
            .form-card:hover {
                box-shadow: 0 12px 32px rgba(0,0,0,0.06);
                border-color: #d5d0e3;
            }
            .form-title {
                font-size: 1.75rem;
                font-weight: 700;
                color: #111118;
                margin: 0 0 8px 0;
                letter-spacing: -0.02em;
                text-align: center;
            }
            .form-subtitle {
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

            .form-group { margin-bottom: 24px; }
            .form-label {
                display: block;
                font-weight: 600;
                font-size: 0.9rem;
                color: #111118;
                margin-bottom: 8px;
            }
            .form-label-required::after {
                content: '*';
                color: #ef4444;
                margin-left: 4px;
                font-weight: 400;
            }

            .form-input, .form-textarea {
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
            .form-input:focus, .form-textarea:focus {
                outline: none;
                border-color: #8b77b3;
                box-shadow: 0 0 0 3px rgba(139, 119, 179, 0.15);
            }
            .form-textarea {
                min-height: 120px;
                resize: vertical;
                line-height: 1.6;
            }
            .form-textarea.rows-6 { min-height: 180px; }

            .form-hint {
                font-size: 0.85rem;
                color: #6b6b80;
                margin-top: 6px;
            }

            .success-card {
                background: #ecfdf5;
                border: 1px solid #a7f3d0;
                border-radius: 12px;
                padding: 20px 24px;
                text-align: center;
                margin-bottom: 24px;
                color: #065f46;
            }
            .success-card .back-link {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                margin-top: 12px;
                color: #8b77b3;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s ease;
            }
            .success-card .back-link:hover { color: #75639c; }

            .submit-btn {
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
            .submit-btn:hover {
                background: #222;
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            }
            .submit-btn:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }

            .help-block {
                color: #ef4444;
                font-size: 0.85rem;
                margin-top: 6px;
            }
            .has-error .form-input,
            .has-error .form-textarea {
                border-color: #ef4444;
            }
            .has-error .form-input:focus,
            .has-error .form-textarea:focus {
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
            }

            @media (max-width: 640px) {
                .form-card { padding: 24px 20px; }
                .form-title { font-size: 1.4rem; }
            }

            ::-webkit-scrollbar { width: 5px; }
            ::-webkit-scrollbar-track { background: #f7f6f9; }
            ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="form-container">
        <div class="form-card">
            <h1 class="form-title">Обратная связь</h1>
            <p class="form-subtitle">Заполните форму, и мы обязательно ответим</p>
            <hr class="form-divider">

            <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
                <div class="success-card">
                    <div style="font-weight: 600; margin-bottom: 4px;">Сообщение отправлено</div>
                    <div style="font-size: 0.95rem;">Спасибо! Мы ответим в ближайшее время.</div>
                    <?= Html::a('← Вернуться на главную', ['/'], ['class' => 'back-link']) ?>
                </div>
            <?php else: ?>
                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'contact-form'],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{hint}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                    ],
                ]); ?>

                <div class="form-group">
                    <?= $form->field($model, 'question')->textarea([
                        'class' => 'form-textarea',
                        'placeholder' => 'Кратко опишите тему обращения',
                        'rows' => 2,
                    ])->label('Тема вопроса <span class="form-label-required"></span>', ['encode' => false]) ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'description')->textarea([
                        'class' => 'form-textarea rows-6',
                        'placeholder' => 'Подробно опишите ситуацию или приложите шаги для воспроизведения',
                        'rows' => 6,
                    ])->label('Подробное описание <span class="form-label-required"></span>', ['encode' => false]) ?>
                    <div class="form-hint">Чем подробнее вы опишете проблему, тем быстрее мы сможем помочь</div>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'contacts')->textInput([
                        'class' => 'form-input',
                        'placeholder' => 'Email или Telegram для связи',
                    ])->label('Контакты для связи <span class="form-label-required"></span>', ['encode' => false]) ?>
                    <div class="form-hint">Укажите удобный способ связи для ответа</div>
                </div>

                <div class="form-group" style="margin-top: 32px;">
                    <?= Html::submitButton('Отправить обращение', ['class' => 'submit-btn']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            <?php endif; ?>
        </div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>