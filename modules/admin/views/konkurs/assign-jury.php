<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $konkurs app\models\Konkurs */
/** @var $users app\models\User[] */
/** @var $selected array */

$this->title = 'Назначить жюри: ' . $konkurs->title;
?>

<!-- Убираем лишние обёртки — .main-container уже в main.php -->
<div class="jury-assign">

    <header class="page-header">
        <div>
            <h1 class="page-title">Жюри конкурса</h1>
            <p class="page-subtitle"><?= Html::encode($konkurs->title) ?></p>
        </div>
        <?= Html::a('
            <svg class="back-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
            Назад
        ', ['index'], ['class' => 'btn-back', 'encode' => false]) ?>
    </header>

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-stack', 'novalidate' => true],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-input'],
            'errorOptions' => ['class' => 'error-message'],
        ],
    ]); ?>

    <section class="form-section rv">
        <h3 class="section-title">
            <svg class="section-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Выберите жюри
        </h3>

        <p class="form-hint">Отметьте пользователей, которые будут оценивать работы в этом конкурсе</p>

        <div class="user-list">
            <?php foreach ($users as $user): ?>
                <label class="user-item">
                    <input type="checkbox" name="jury[]" value="<?= $user->id ?>"
                        <?= in_array($user->id, $selected) ? 'checked' : '' ?>
                           class="user-checkbox">
                    <div class="user-info">
                        <div class="user-name"><?= Html::encode($user->surname . ' ' . $user->name) ?></div>
                        <div class="user-username">@<?= Html::encode($user->username) ?></div>
                    </div>
                    <span class="user-check">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                    </span>
                </label>
            <?php endforeach; ?>

            <?php if (empty($users)): ?>
                <div class="empty-users">
                    <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    <p>Пользователи с ролью «жюри» не найдены</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <div class="form-actions rv">
        <?= Html::submitButton('
            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Сохранить
        ', ['class' => 'btn-primary', 'encode' => false]) ?>
        <?= Html::a('
            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            Отмена
        ', ['index'], ['class' => 'btn-secondary', 'encode' => false]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
    :root { --ease: cubic-bezier(0.16, 1, 0.3, 1); }

    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .page-title {
        font-size: 1.5rem; font-weight: 700; color: #111118;
        margin: 0 0 4px 0; letter-spacing: -0.02em;
    }
    .page-subtitle { color: #6b6b80; font-size: 0.95rem; margin: 0; }

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

    .section-title {
        display: flex; align-items: center; gap: 10px;
        font-size: 1.1rem; font-weight: 700; color: #111118;
        margin: 0 0 16px 0;
    }
    .section-icon { width: 20px; height: 20px; color: #8b77b3; }

    .form-hint { font-size: 0.9rem; color: #6b6b80; margin-bottom: 20px; }

    .user-list {
        display: flex; flex-direction: column; gap: 8px;
        max-height: 400px; overflow-y: auto; padding-right: 4px;
    }

    .user-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px; border-radius: 10px;
        background: #fafaf8; border: 1.5px solid transparent;
        cursor: pointer; transition: all 0.2s var(--ease);
        position: relative;
    }
    .user-item:hover {
        background: #f0eaf5; border-color: #e5e3eb;
    }
    .user-item:has(input:checked) {
        background: #f0eaf5; border-color: #8b77b3;
    }

    .user-checkbox {
        position: absolute; inset: 0; opacity: 0; cursor: pointer;
        width: 100%; height: 100%; margin: 0;
    }

    .user-info { flex: 1; min-width: 0; }
    .user-name {
        font-weight: 600; color: #111118; font-size: 0.95rem;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .user-username {
        font-size: 0.85rem; color: #6b6b80;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .user-check {
        width: 24px; height: 24px; border-radius: 6px;
        background: #fff; border: 1.5px solid #e5e3eb;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: all 0.2s var(--ease);
    }
    .user-check svg {
        width: 14px; height: 14px; color: transparent;
        transition: color 0.2s ease;
    }
    .user-item:has(input:checked) .user-check {
        background: #8b77b3; border-color: #8b77b3;
    }
    .user-item:has(input:checked) .user-check svg {
        color: #fff;
    }

    .empty-users {
        text-align: center; padding: 32px 20px; color: #6b6b80;
    }
    .empty-icon {
        width: 48px; height: 48px; color: #d5d0e3;
        margin: 0 auto 12px; display: block;
    }

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
    .btn-icon { width: 16px; height: 16px; }

    .rv { opacity: 0; transform: translateY(16px); transition: opacity 0.7s var(--ease), transform 0.7s var(--ease); }
    .rv.active { opacity: 1; transform: translateY(0); }

    @media (max-width: 640px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .form-section { padding: 20px; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-secondary { width: 100%; }
    }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: #f7f6f9; }
    ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) { entry.target.classList.add('active'); observer.unobserve(entry.target); }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });
        document.querySelectorAll('.rv').forEach(el => observer.observe(el));
    });
</script>