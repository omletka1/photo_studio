<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model app\models\Konkurs */
/** @var $existingNominations app\models\Nomination[] */
/** @var $nominationRows int */

$this->title = $model->isNewRecord ? 'Создать конкурс' : 'Редактировать: ' . $model->title;
?>

<!-- Убираем лишние обёртки — .main-container уже в main.php -->
<div class="konkurs-form">

    <header class="page-header">
        <h1 class="page-title"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('
            <svg class="back-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
            Назад
        ', ['index'], ['class' => 'btn-back', 'encode' => false]) ?>
    </header>

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-stack', 'enctype' => 'multipart/form-data', 'novalidate' => true],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{hint}\n{error}",
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-input'],
            'errorOptions' => ['class' => 'error-message'],
        ],
    ]); ?>

    <!-- Basic Info -->
    <section class="form-section rv">
        <h3 class="section-title">
            <svg class="section-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Информация о конкурсе
        </h3>

        <div class="form-grid">
            <div class="form-group full">
                <?= $form->field($model, 'title')->textInput([
                    'placeholder' => 'Например: «Весна в объективе»',
                    'required' => true,
                ])->label('Название') ?>
            </div>

            <div class="form-group full">
                <?= $form->field($model, 'description')->textarea([
                    'class' => 'form-input form-textarea',
                    'placeholder' => 'Описание условий и тематики...',
                    'rows' => 3,
                ])->label('Описание') ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'start_date')->input('date', [
                    'value' => $model->start_date ? date('Y-m-d', strtotime($model->start_date)) : '',
                    'required' => true,
                ])->label('Дата начала') ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'end_date')->input('date', [
                    'value' => $model->end_date ? date('Y-m-d', strtotime($model->end_date)) : '',
                    'required' => true,
                ])->label('Дата окончания') ?>
            </div>

            <div class="form-group full">
                <?= $form->field($model, 'status')->dropDownList([
                    'открыт' => 'Открыт',
                    'закрыт' => 'Закрыт',
                ], ['class' => 'form-input'])->label('Статус') ?>
            </div>
        </div>
    </section>

    <!-- Nominations -->
    <section class="form-section rv">
        <div class="section-header">
            <h3 class="section-title">
                <svg class="section-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M7 7h10M7 12h10M7 17h6M4 4h16v16H4z"/>
                </svg>
                Номинации конкурса
            </h3>
            <button type="button" id="add-nomination" class="btn-add">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Добавить
            </button>
        </div>

        <p class="form-hint">Создайте номинации для этого конкурса. Каждая номинация может иметь изображение-баннер.</p>

        <!-- Existing nominations -->
        <?php if (!empty($existingNominations)): ?>
            <div class="existing-list">
                <p class="existing-title">Уже назначенные номинации:</p>
                <?php foreach ($existingNominations as $nom): ?>
                    <div class="existing-item">
                        <?php if ($nom->image): ?>
                            <img src="<?= Yii::getAlias('@web/images/' . $nom->image) ?>" alt="" class="existing-img">
                        <?php else: ?>
                            <div class="existing-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <div class="existing-info">
                            <div class="existing-name"><?= Html::encode($nom->title) ?></div>
                            <?php if ($nom->description): ?>
                                <div class="existing-desc"><?= Html::encode($nom->description) ?></div>
                            <?php endif; ?>
                        </div>
                        <span class="existing-id">#<?= $nom->id ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- New nominations container -->
        <div id="nominations-container" class="nominations-container">
            <?php for ($i = 0; $i < ($nominationRows ?? 3); $i++): ?>
                <?= $this->render('_nomination-row', [
                    'index' => $i,
                    'oldTitles' => $oldTitles ?? [],
                    'oldDescs' => $oldDescs ?? [],
                ]) ?>
            <?php endfor; ?>
        </div>

        <!-- Progress -->
        <div class="progress-section">
            <div class="progress-header">
                <span>Заполнено: <strong id="nomination-count">0</strong> из 5</span>
                <span id="nomination-status" class="progress-status low">
                    <svg class="status-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    Нужно минимум 3
                </span>
            </div>
            <div class="progress-bar">
                <div id="nomination-progress" class="progress-fill low" style="width: 0%"></div>
            </div>
        </div>
    </section>

    <!-- Actions -->
    <div class="form-actions rv">
        <?= Html::submitButton('
            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Сохранить конкурс
        ', ['class' => 'btn-primary', 'encode' => false]) ?>
        <?= Html::a('
            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            Отмена
        ', ['index'], ['class' => 'btn-secondary', 'encode' => false]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<!-- Template for new nomination row -->
<template id="nomination-template">
    <div class="nomination-row">
        <div class="nomination-header">
            <div class="nomination-title">Номинация <span class="row-index">#</span></div>
            <button type="button" class="btn-remove">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                Удалить
            </button>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <input type="text" name="NominationTitle[]" placeholder="Название номинации *" class="form-input" required>
            </div>
            <div class="form-group">
                <input type="text" name="NominationDesc[]" placeholder="Краткое описание" class="form-input">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Изображение-баннер</label>
            <input type="file" name="NominationImage[]" accept="image/*" class="file-input">
            <p class="form-hint">PNG, JPG до 2 МБ</p>
        </div>
    </div>
</template>

<style>
    :root { --ease: cubic-bezier(0.16, 1, 0.3, 1); }

    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-title {
        font-size: 1.5rem; font-weight: 700; color: #111118;
        margin: 0; letter-spacing: -0.02em;
    }
    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        color: #6b6b80; text-decoration: none; font-weight: 500;
        transition: color 0.2s ease;
    }
    .btn-back:hover { color: #8b77b3; }
    .back-icon { width: 16px; height: 16px; }

    .form-stack { display: flex; flex-direction: column; gap: 24px; }

    .form-section {
        background: #fff; border: 1px solid #e5e3eb; border-radius: 16px;
        padding: 24px; transition: box-shadow 0.3s var(--ease), border-color 0.2s ease;
    }
    .form-section:hover { box-shadow: 0 12px 32px rgba(0,0,0,0.06); border-color: #d5d0e3; }

    .section-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px; flex-wrap: wrap; gap: 12px;
    }
    .section-title {
        display: flex; align-items: center; gap: 10px;
        font-size: 1.1rem; font-weight: 700; color: #111118;
        margin: 0;
    }
    .section-icon { width: 20px; height: 20px; color: #8b77b3; }

    .btn-add {
        display: inline-flex; align-items: center; gap: 6px;
        background: transparent; color: #8b77b3; border: none;
        font-weight: 600; font-size: 0.9rem; cursor: pointer;
        padding: 8px 12px; border-radius: 8px;
        transition: background 0.2s ease;
    }
    .btn-add:hover { background: #f0eaf5; }
    .btn-icon { width: 16px; height: 16px; }

    .form-grid {
        display: grid; grid-template-columns: 1fr; gap: 16px;
    }
    @media (min-width: 640px) { .form-grid { grid-template-columns: repeat(2, 1fr); } }
    .form-group.full { grid-column: 1 / -1; }

    .form-group { margin-bottom: 0; }
    .form-label {
        display: block; font-weight: 600; font-size: 0.9rem;
        color: #111118; margin-bottom: 8px;
    }
    .form-label .required { color: #ef4444; margin-left: 4px; font-weight: 400; }

    .form-input {
        width: 100%; padding: 12px 14px; font-size: 0.95rem;
        font-family: inherit; color: #111118; background: #fff;
        border: 1.5px solid #e5e3eb; border-radius: 10px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-input:focus {
        outline: none; border-color: #8b77b3;
        box-shadow: 0 0 0 3px rgba(139, 119, 179, 0.15);
    }
    .form-input::placeholder { color: #8b8b9e; }
    .form-input.error { border-color: #ef4444; }
    .form-input.error:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15); }
    .form-textarea { min-height: 100px; resize: vertical; line-height: 1.6; }

    .form-hint { font-size: 0.8rem; color: #6b6b80; margin-top: 6px; }
    .error-message { color: #ef4444; font-size: 0.85rem; margin-top: 6px; display: none; }
    .error-message.visible { display: block; }

    .existing-list {
        margin: 20px 0; padding: 16px 0;
        border-top: 1px solid #e5e3eb; border-bottom: 1px solid #e5e3eb;
    }
    .existing-title {
        font-size: 0.9rem; font-weight: 600; color: #111118;
        margin: 0 0 12px 0;
    }
    .existing-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px; background: #fafaf8; border-radius: 10px;
        margin-bottom: 8px; transition: background 0.2s ease;
    }
    .existing-item:hover { background: #f0eaf5; }
    .existing-img {
        width: 48px; height: 48px; border-radius: 8px;
        object-fit: cover; background: #f0eaf5; flex-shrink: 0;
    }
    .existing-placeholder {
        width: 48px; height: 48px; border-radius: 8px;
        background: #f0eaf5; color: #8b77b3; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .existing-placeholder svg { width: 24px; height: 24px; }
    .existing-info { flex: 1; min-width: 0; }
    .existing-name {
        font-weight: 500; color: #111118; font-size: 0.9rem;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .existing-desc {
        font-size: 0.8rem; color: #6b6b80;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .existing-id {
        font-size: 0.75rem; color: #8b8b9e; font-family: ui-monospace, monospace;
    }

    .nominations-container { display: flex; flex-direction: column; gap: 16px; }

    .nomination-row {
        background: #fafaf8; border: 1px solid #e5e3eb; border-radius: 12px;
        padding: 20px; transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .nomination-row.has-error {
        border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    }
    .nomination-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px; flex-wrap: wrap; gap: 8px;
    }
    .nomination-title { font-weight: 600; color: #111118; font-size: 0.95rem; }
    .nomination-title span { color: #6b6b80; font-weight: 400; }

    .btn-remove {
        display: inline-flex; align-items: center; gap: 4px;
        background: transparent; color: #6b6b80; border: none;
        font-size: 0.85rem; cursor: pointer; transition: color 0.2s ease;
    }
    .btn-remove:hover { color: #ef4444; }

    .file-input {
        display: block; width: 100%; padding: 10px 14px;
        background: #fff; border: 1.5px dashed #e5e3eb; border-radius: 10px;
        font-size: 0.9rem; color: #6b6b80; cursor: pointer;
        transition: border-color 0.2s ease, background 0.2s ease;
    }
    .file-input:hover { border-color: #8b77b3; background: #f0eaf5; }
    .file-input::file-selector-button {
        margin-right: 12px; padding: 8px 14px; background: #f0eaf5;
        color: #8b77b3; border: none; border-radius: 8px;
        font-weight: 500; font-size: 0.85rem; cursor: pointer;
        transition: background 0.2s ease;
    }
    .file-input::file-selector-button:hover { background: #e8e0f2; }

    .progress-section {
        margin-top: 24px; padding-top: 20px; border-top: 1px solid #e5e3eb;
    }
    .progress-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 12px; font-size: 0.9rem;
    }
    .progress-status {
        display: inline-flex; align-items: center; gap: 6px; font-size: 0.85rem;
    }
    .progress-status.low { color: #ef4444; }
    .progress-status.mid { color: #f59e0b; }
    .progress-status.high { color: #10b981; }
    .progress-status.full { color: #8b77b3; }
    .status-icon { width: 14px; height: 14px; }

    .progress-bar {
        width: 100%; height: 6px; background: #e5e3eb;
        border-radius: 999px; overflow: hidden;
    }
    .progress-fill {
        height: 100%; border-radius: inherit;
        transition: width 0.3s var(--ease), background 0.3s ease;
    }
    .progress-fill.low { background: #ef4444; }
    .progress-fill.mid { background: #f59e0b; }
    .progress-fill.high { background: #10b981; }
    .progress-fill.full { background: #8b77b3; }

    .form-actions {
        display: flex; gap: 12px; margin-top: 8px; flex-wrap: wrap;
    }
    .btn-primary, .btn-secondary {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 0.95rem;
        text-decoration: none; transition: all 0.25s var(--ease); cursor: pointer; border: none;
    }
    .btn-primary { background: #111118; color: #fff; }
    .btn-primary:hover { background: #222; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .btn-secondary { background: #fff; color: #111118; border: 1.5px solid #e5e3eb; }
    .btn-secondary:hover { border-color: #8b77b3; background: #f0eaf5; color: #8b77b3; }

    .rv { opacity: 0; transform: translateY(16px); transition: opacity 0.7s var(--ease), transform 0.7s var(--ease); }
    .rv.active { opacity: 1; transform: translateY(0); }
    .stagger-1 { transition-delay: 0.08s; }
    .stagger-2 { transition-delay: 0.16s; }

    @media (max-width: 640px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .form-section { padding: 20px; }
        .form-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-secondary { width: 100%; }
        .section-header { flex-direction: column; align-items: flex-start; }
    }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: #f7f6f9; }
    ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) { entry.target.classList.add('active'); observer.unobserve(entry.target); }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });
        document.querySelectorAll('.rv').forEach(el => observer.observe(el));

        // Nomination logic
        const container = document.getElementById('nominations-container');
        const template = document.getElementById('nomination-template');
        const addBtn = document.getElementById('add-nomination');
        const countEl = document.getElementById('nomination-count');
        const progressEl = document.getElementById('nomination-progress');
        const statusEl = document.getElementById('nomination-status');
        let rowIndex = <?= ($nominationRows ?? 3) ?>;

        function updateProgress() {
            const titles = container.querySelectorAll('input[name="NominationTitle[]"]');
            const filled = Array.from(titles).filter(input => input.value.trim() !== '').length;

            countEl.textContent = filled;
            const pct = Math.min((filled / 5) * 100, 100);
            progressEl.style.width = pct + '%';

            // Update status and colors
            progressEl.className = 'progress-fill';
            statusEl.className = 'progress-status';
            if (filled < 3) {
                progressEl.classList.add('low');
                statusEl.classList.add('low');
                statusEl.innerHTML = '<svg class="status-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>Нужно ещё ' + (3 - filled);
            } else if (filled < 5) {
                progressEl.classList.add('mid');
                statusEl.classList.add('mid');
                statusEl.innerHTML = '<svg class="status-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>Достаточно';
            } else {
                progressEl.classList.add('full');
                statusEl.classList.add('full');
                statusEl.innerHTML = '<svg class="status-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>Максимум достигнуто';
            }
        }

        // Add new row
        addBtn?.addEventListener('click', function() {
            if (container.querySelectorAll('.nomination-row').length >= 5) {
                alert('Максимум 5 номинаций');
                return;
            }
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('.nomination-row');
            row.querySelector('.row-index').textContent = '#' + (rowIndex + 1);
            container.appendChild(row);
            rowIndex++;
            // Animate in
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            setTimeout(() => {
                row.style.transition = 'opacity 0.2s, transform 0.2s';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 10);
            updateProgress();
        });

        // Remove row (delegation)
        container?.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove')) {
                const row = e.target.closest('.nomination-row');
                row.style.opacity = '0';
                row.style.transform = 'translateX(20px)';
                setTimeout(() => { row.remove(); updateProgress(); }, 200);
            }
        });

        // Update on input
        container?.addEventListener('input', function(e) {
            if (e.target.name === 'NominationTitle[]') { updateProgress(); }
        });

        // Form validation
        document.querySelector('.form-stack')?.addEventListener('submit', function(e) {
            const rows = container.querySelectorAll('.nomination-row');
            let validCount = 0;
            let hasError = false;

            rows.forEach(row => {
                const title = row.querySelector('input[name="NominationTitle[]"]').value.trim();
                const desc = row.querySelector('input[name="NominationDesc[]"]').value.trim();
                const image = row.querySelector('input[name="NominationImage[]"]').files.length > 0;

                if (title !== '') {
                    if (desc === '' || !image) {
                        hasError = true;
                        row.classList.add('has-error');
                    } else {
                        validCount++;
                        row.classList.remove('has-error');
                    }
                }
            });

            if (validCount < 3 || validCount > 5 || hasError) {
                e.preventDefault();
                if (validCount < 3) {
                    alert('Минимум 3 заполненные номинации (сейчас: ' + validCount + ')');
                } else if (validCount > 5) {
                    alert('Максимум 5 номинаций (сейчас: ' + validCount + ')');
                } else {
                    alert('Для каждой номинации обязательно: Название, Описание и Изображение');
                }
                container.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        // Init
        updateProgress();
    });
</script>