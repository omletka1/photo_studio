<?php
use yii\helpers\Html;

/** @var $profileUser app\models\User */
/** @var $isOwnProfile bool */
/** @var $history array */

$this->title = 'Профиль: ' . $profileUser->surname . ' ' . $profileUser->name;
?>

<!-- Убираем лишние обёртки — .main-container уже в main.php -->
<div class="public-profile">

    <header class="page-header">
        <h1 class="page-title"><?= Html::encode($profileUser->surname . ' ' . $profileUser->name) ?></h1>
        <?= Html::a('
            <svg class="back-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
            Назад
        ', ['dashboard'], ['class' => 'btn-back', 'encode' => false]) ?>
    </header>

    <!-- Профиль -->
    <section class="profile-section">
        <div class="profile-card">
            <div class="profile-grid">
                <!-- Аватар -->
                <div class="profile-avatar">
                    <?php if (!empty($profileUser->avatar)): ?>
                        <a href="<?= Yii::getAlias('@web/' . $profileUser->avatar) ?>"
                           data-lightbox="avatar-<?= $profileUser->id ?>"
                           data-title="Аватар: <?= Html::encode($profileUser->surname . ' ' . $profileUser->name) ?>"
                           class="avatar-link">
                            <img src="<?= Yii::getAlias('@web/' . $profileUser->avatar) ?>" alt="Аватар" class="avatar-img">
                            <span class="avatar-overlay">
                                <svg class="avatar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                                </svg>
                            </span>
                        </a>
                    <?php else: ?>
                        <div class="avatar-placeholder"><?= mb_substr($profileUser->name ?? '?', 0, 1) ?></div>
                    <?php endif; ?>
                </div>

                <!-- Информация -->
                <div class="profile-details">
                    <h2 class="profile-name"><?= Html::encode($profileUser->surname . ' ' . $profileUser->name) ?></h2>

                    <div class="profile-meta">
                        <div class="meta-row">
                            <svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="m22 6-10 7L2 6"/>
                            </svg>
                            <?= Html::encode($profileUser->email) ?>
                        </div>
                        <div class="meta-row">
                            <svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            <span class="meta-muted">@<?= Html::encode($profileUser->username) ?></span>
                        </div>
                    </div>

                    <?php if (!empty($profileUser->bio)): ?>
                        <p class="profile-bio"><?= Html::encode($profileUser->bio) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($profileUser->instagram) || !empty($profileUser->website)): ?>
                        <div class="social-links">
                            <?php if (!empty($profileUser->instagram)): ?>
                                <a href="<?= Html::encode($profileUser->instagram) ?>" target="_blank" class="social-link instagram">
                                    <svg class="social-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                                    </svg>
                                    Instagram
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($profileUser->website)): ?>
                                <a href="<?= Html::encode($profileUser->website) ?>" target="_blank" class="social-link website">
                                    <svg class="social-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                                    </svg>
                                    Сайт
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($isOwnProfile): ?>
                        <?= Html::a('
                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Редактировать профиль
                        ', ['profile'], ['class' => 'btn-edit', 'encode' => false]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- История работ -->
    <section class="history-section">
        <h3 class="section-title">Работы и результаты</h3>

        <?php if (empty($history)): ?>
            <div class="empty-state">У пользователя пока нет работ на конкурсах</div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Конкурс</th>
                        <th class="text-center">Голоса</th>
                        <th class="text-center">Балл</th>
                        <th class="text-center">Рейтинг</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($history as $item): ?>
                        <tr>
                            <td class="font-medium"><?= Html::encode($item['title']) ?></td>
                            <td><?= Html::encode($item['konkurs_title']) ?></td>
                            <td class="text-center font-semibold"><?= $item['votes'] ?></td>
                            <td class="text-center">
                                <?php if ($item['avg_score'] > 0): ?>
                                    <?php $sc = $item['avg_score'] >= 4 ? 'score-high' : ($item['avg_score'] >= 2.5 ? 'score-mid' : 'score-low'); ?>
                                    <span class="score-badge <?= $sc ?>"><?= number_format($item['avg_score'], 1) ?>/5</span>
                                <?php else: ?>
                                    <span class="score-badge score-empty">не оценено</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center font-bold"><?= $item['rating'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>

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

    .profile-section { margin-bottom: 32px; }
    .profile-card {
        background: #fff; border: 1px solid #e5e3eb; border-radius: 16px;
        padding: 20px;
    }
    .profile-grid {
        display: flex; flex-direction: column; align-items: center; gap: 16px;
        width: 100%;
    }
    @media (min-width: 768px) {
        .profile-grid { flex-direction: row; align-items: flex-start; text-align: left; }
    }

    .profile-avatar { flex-shrink: 0; }
    .avatar-link {
        position: relative; display: block;
        width: 80px; height: 80px; border-radius: 50%;
        overflow: hidden; border: 3px solid #f0eaf5; background: #f5f3f9;
    }
    @media (min-width: 768px) { .avatar-link { width: 96px; height: 96px; } }
    .avatar-img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .avatar-overlay {
        position: absolute; inset: 0; background: rgba(17,17,24,0.45);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.2s ease;
    }
    .avatar-link:hover .avatar-overlay { opacity: 1; }
    .avatar-icon { width: 20px; height: 20px; color: #fff; }
    .avatar-placeholder {
        width: 80px; height: 80px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: #f0eaf5; color: #8b77b3; font-size: 1.8rem; font-weight: 600;
    }
    @media (min-width: 768px) { .avatar-placeholder { width: 96px; height: 96px; } }

    .profile-details {
        flex: 1; min-width: 0; width: 100%; text-align: center;
    }
    @media (min-width: 768px) { .profile-details { text-align: left; } }

    .profile-name {
        font-size: 1.25rem; font-weight: 700; color: #111118;
        margin: 0 0 8px 0; letter-spacing: -0.01em; word-break: break-word;
    }
    @media (min-width: 768px) { .profile-name { font-size: 1.5rem; } }

    .profile-meta {
        display: flex; flex-direction: column; gap: 6px;
        margin-bottom: 12px; font-size: 0.88rem; color: #6b6b80;
        align-items: center;
    }
    @media (min-width: 768px) { .profile-meta { align-items: flex-start; } }
    .meta-row { display: flex; align-items: center; gap: 6px; }
    .meta-icon { width: 14px; height: 14px; color: #8b77b3; flex-shrink: 0; }
    .meta-muted { color: #6b6b80; }

    .profile-bio {
        font-style: italic; color: #6b6b80; font-size: 0.9rem;
        background: #fafaf8; padding: 12px 16px; border-radius: 10px;
        margin: 12px 0; border: 1px solid #e5e3eb; word-break: break-word;
    }
    .profile-bio::before { content: '\201C'; }
    .profile-bio::after { content: '\201D'; }

    .social-links { display: flex; flex-wrap: wrap; gap: 8px; margin: 12px 0; justify-content: center; }
    @media (min-width: 768px) { .social-links { justify-content: flex-start; } }
    .social-link {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 12px; border-radius: 999px; font-size: 0.82rem; font-weight: 500;
        text-decoration: none; transition: all 0.2s var(--ease);
        border: 1px solid #e5e3eb; color: #111118; background: #fff;
    }
    .social-icon { width: 14px; height: 14px; }
    .social-link:hover { border-color: #8b77b3; background: #f0eaf5; color: #8b77b3; }

    .btn-edit {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 18px; background: #111118; color: #fff;
        font-weight: 600; font-size: 0.85rem; border-radius: 10px;
        text-decoration: none; transition: all 0.25s var(--ease); margin-top: 4px;
    }
    .btn-edit:hover { background: #222; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
    .btn-icon { width: 14px; height: 14px; }

    .section-title {
        font-size: 1.15rem; font-weight: 700; color: #111118;
        margin: 0 0 16px 0; letter-spacing: -0.01em;
    }

    .table-wrapper {
        background: #fff; border: 1px solid #e5e3eb; border-radius: 16px;
        overflow: hidden; overflow-x: auto; -webkit-overflow-scrolling: touch;
    }
    .data-table { width: 100%; border-collapse: collapse; min-width: 460px; }
    .data-table th {
        padding: 12px 14px; text-align: left; font-size: 0.72rem; font-weight: 600;
        color: #6b6b80; text-transform: uppercase; letter-spacing: 0.04em;
        background: #fafaf8; border-bottom: 1px solid #e5e3eb; white-space: nowrap;
    }
    .data-table td {
        padding: 12px 14px; border-bottom: 1px solid #f0eef5;
        font-size: 0.88rem; color: #111118; white-space: nowrap;
    }
    .data-table tbody tr:hover { background: #f9f8fc; }

    .score-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 3px 8px; border-radius: 999px; font-size: 0.72rem; font-weight: 600;
    }
    .score-high { background: #ecfdf5; color: #059669; }
    .score-mid { background: #fef3c7; color: #92400e; }
    .score-low { background: #fef2f2; color: #b91c1c; }
    .score-empty { background: #f3f4f6; color: #6b7280; font-style: italic; }

    .empty-state {
        text-align: center; padding: 40px 20px; background: #fff;
        border: 1px dashed #e5e3eb; border-radius: 16px; color: #6b6b80; font-size: 0.95rem;
    }

    @media (max-width: 480px) {
        .profile-card { padding: 16px; }
        .data-table th, .data-table td { padding: 10px; font-size: 0.82rem; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lightbox !== 'undefined') {
            lightbox.option({ resizeDuration: 200, wrapAround: true, fadeDuration: 200, showImageNumberLabel: false });
        }
    });
</script>