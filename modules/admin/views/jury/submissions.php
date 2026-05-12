<?php
use yii\helpers\Html;
use app\models\JuryRating;
use app\models\JuryComment;

/** @var $submissions app\models\Submission[] */
/** @var $avgScores array */
/** @var $konkurs_id int */

$this->title = 'Работы конкурса';
?>

<!-- Убираем лишние обёртки — .main-container уже в main.php -->
<div class="jury-submissions">

    <header class="page-header">
        <h1 class="page-title">Работы конкурса</h1>
        <?= Html::a('
            <svg class="back-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
            Назад
        ', ['index'], ['class' => 'btn-back', 'encode' => false]) ?>
    </header>

    <?php if (empty($submissions)): ?>
        <div class="empty-state rv active">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p>Нет работ для оценки</p>
        </div>
    <?php else: ?>
        <div class="submissions-list">
            <?php foreach ($submissions as $s): ?>
                <?php
                $comments = JuryComment::find()
                    ->where(['submission_id' => $s->id, 'parent_id' => null])
                    ->with(['user', 'replies.user'])
                    ->orderBy(['created_at' => SORT_ASC])
                    ->all();
                $hasRated = JuryRating::find()->where(['submission_id' => $s->id, 'user_id' => Yii::$app->user->id])->exists();
                $avgScore = round($avgScores[$s->id] ?? 0, 2);
                $scoreClass = $avgScore >= 4 ? 'score-high' : ($avgScore >= 2.5 ? 'score-mid' : 'score-low');
                ?>

                <article class="submission-card rv">
                    <header class="submission-header">
                        <div class="submission-title-wrap">
                            <?= Html::a(Html::encode($s->title), ['view', 'id' => $s->id], ['class' => 'submission-title']) ?>
                            <span class="submission-author">
                                <svg class="author-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                                <?= Html::encode($s->user?->surname . ' ' . $s->user?->name ?? '—') ?>
                            </span>
                        </div>
                        <span class="score-badge <?= $scoreClass ?>"><?= $avgScore ?>/5</span>
                    </header>

                    <div class="submission-body">
                        <?php if ($s->description): ?>
                            <p class="submission-desc"><?= Html::encode($s->description) ?></p>
                        <?php endif; ?>

                        <?php if ($s->image1): ?>
                            <div class="submission-image">
                                <img src="<?= Yii::getAlias('@web/' . $s->image1) ?>" alt="<?= Html::encode($s->title) ?>" loading="lazy">
                            </div>
                        <?php endif; ?>

                        <div class="submission-actions">
                            <?php if (!$hasRated): ?>
                                <?= Html::a('
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    Оценить
                                ', ['rate', 'submission_id' => $s->id], ['class' => 'btn-rate', 'encode' => false]) ?>
                            <?php else: ?>
                                <span class="btn-rated">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                                    Оценено
                                </span>
                            <?php endif; ?>

                            <?= Html::a('
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Подробно
                            ', ['view', 'id' => $s->id], ['class' => 'btn-view', 'encode' => false]) ?>


                        </div>
                    </div>

                    <!-- Custom Modal -->

                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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

    .empty-state {
        text-align: center; padding: 48px 24px; background: #fff;
        border: 1px dashed #e5e3eb; border-radius: 16px;
        color: #6b6b80; font-size: 0.95rem;
    }
    .empty-icon { width: 48px; height: 48px; color: #d5d0e3; margin: 0 auto 12px; display: block; }

    .submissions-list { display: flex; flex-direction: column; gap: 20px; }

    .submission-card {
        background: #fff; border: 1px solid #e5e3eb; border-radius: 16px;
        overflow: hidden; transition: box-shadow 0.3s var(--ease), border-color 0.2s ease;
        opacity: 0; transform: translateY(16px);
    }
    .submission-card.active { opacity: 1; transform: translateY(0); }
    .submission-card:hover { box-shadow: 0 12px 32px rgba(0,0,0,0.06); border-color: #d5d0e3; }

    .submission-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; border-bottom: 1px solid #f0eef5; gap: 12px; flex-wrap: wrap;
    }
    .submission-title-wrap { display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1; }
    .submission-title {
        font-size: 1.1rem; font-weight: 600; color: #111118; text-decoration: none;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        transition: color 0.2s ease;
    }
    .submission-title:hover { color: #8b77b3; }
    .submission-author {
        display: flex; align-items: center; gap: 6px; font-size: 0.9rem; color: #6b6b80;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .author-icon { width: 14px; height: 14px; color: #8b77b3; flex-shrink: 0; }

    .score-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 6px 12px; border-radius: 999px; font-size: 0.85rem; font-weight: 600;
        white-space: nowrap;
    }
    .score-high { background: #ecfdf5; color: #059669; }
    .score-mid { background: #fef3c7; color: #92400e; }
    .score-low { background: #fef2f2; color: #b91c1c; }

    .submission-body { padding: 20px; }
    .submission-desc {
        color: #111118; line-height: 1.6; margin: 0 0 16px 0; font-size: 0.95rem;
    }
    .submission-image {
        border-radius: 12px; overflow: hidden; margin-bottom: 16px;
        background: #f0eaf5; aspect-ratio: 16/9;
    }
    .submission-image img {
        width: 100%; height: 100%; object-fit: cover; display: block;
    }

    .submission-actions {
        display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px;
    }
    .btn-rate, .btn-rated, .btn-view, .btn-comments {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 500;
        text-decoration: none; transition: all 0.25s var(--ease); cursor: pointer; border: none;
    }
    .btn-rate { background: #10b981; color: #fff; }
    .btn-rate:hover { background: #059669; transform: translateY(-1px); }
    .btn-rated { background: #f3f4f6; color: #6b7280; cursor: default; }
    .btn-view { background: #fff; color: #111118; border: 1.5px solid #e5e3eb; }
    .btn-view:hover { border-color: #8b77b3; background: #f0eaf5; color: #8b77b3; }
    .btn-comments { background: #fff; color: #111118; border: 1.5px solid #e5e3eb; }
    .btn-comments:hover { border-color: #8b77b3; background: #f0eaf5; color: #8b77b3; }
    .btn-icon { width: 14px; height: 14px; }

    /* ===== MODAL ===== */
    .modal {
        position: fixed; inset: 0; z-index: 1000; display: none;
        align-items: center; justify-content: center; padding: 20px;
    }
    .modal.show { display: flex; }
    .modal-backdrop {
        position: absolute; inset: 0; background: rgba(0,0,0,0.5); z-index: 1;
    }
    .modal-card {
        position: relative; background: #fff; border-radius: 16px;
        max-width: 640px; width: 100%; max-height: 90vh; overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2); animation: modalIn 0.25s var(--ease);
        z-index: 2;
    }
    @keyframes modalIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; border-bottom: 1px solid #e5e3eb;
    }
    .modal-title { font-weight: 600; color: #111118; font-size: 1.1rem; margin: 0; }
    .modal-close {
        width: 32px; height: 32px; border-radius: 8px; display: flex;
        align-items: center; justify-content: center; color: #6b6b80;
        background: transparent; border: none; cursor: pointer;
        transition: all 0.2s ease;
    }
    .modal-close:hover { background: #f0eaf5; color: #8b77b3; }
    .modal-close svg { width: 18px; height: 18px; }

    .modal-body { padding: 20px; }

    .comment-list {
        display: flex; flex-direction: column; gap: 12px;
        max-height: 320px; overflow-y: auto; padding-right: 4px; margin-bottom: 16px;
    }
    .comment-empty { text-align: center; color: #6b6b80; padding: 24px; }

    .reply-preview {
        background: #f5f3f9; border: 1px solid #e5e3eb; border-radius: 10px;
        padding: 12px 14px; font-size: 0.9rem; color: #111118; margin-bottom: 12px;
    }

    .form-group { margin-bottom: 16px; }
    .form-textarea {
        width: 100%; padding: 12px 14px; border-radius: 10px;
        border: 1.5px solid #e5e3eb; background: #fff; color: #111118;
        font-size: 0.95rem; font-family: inherit; resize: vertical;
        transition: border-color 0.2s ease, box-shadow 0.2s ease; min-height: 80px;
    }
    .form-textarea:focus {
        outline: none; border-color: #8b77b3;
        box-shadow: 0 0 0 3px rgba(139, 119, 179, 0.15);
    }
    .form-actions { display: flex; gap: 10px; }

    .btn-primary, .btn-secondary {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.9rem;
        text-decoration: none; transition: all 0.25s var(--ease); cursor: pointer; border: none;
    }
    .btn-primary { background: #111118; color: #fff; }
    .btn-primary:hover { background: #222; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .btn-secondary { background: #fff; color: #111118; border: 1.5px solid #e5e3eb; }
    .btn-secondary:hover { border-color: #8b77b3; background: #f0eaf5; color: #8b77b3; }

    .mt-4 { margin-top: 16px; }

    .rv { opacity: 0; transform: translateY(16px); transition: opacity 0.7s var(--ease), transform 0.7s var(--ease); }
    .rv.active { opacity: 1; transform: translateY(0); }

    @media (max-width: 640px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .submission-header { flex-direction: column; align-items: flex-start; }
        .submission-actions { width: 100%; }
        .btn-rate, .btn-rated, .btn-view, .btn-comments { flex: 1; justify-content: center; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-secondary { width: 100%; }
    }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: #f7f6f9; }
    ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
</style>

<script>
    // CSRF token helper
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content
            || document.querySelector('input[name="_csrf"]')?.value || '';
    }

    // Modal functions
    function openCommentModal(id) {
        const modal = document.getElementById('commentModal' + id);
        if (modal) { modal.style.display = 'flex'; setTimeout(() => modal.classList.add('show'), 10); document.body.style.overflow = 'hidden'; }
    }
    function closeCommentModal(id) {
        const modal = document.getElementById('commentModal' + id);
        if (modal) { modal.classList.remove('show'); setTimeout(() => { modal.style.display = 'none'; }, 250); document.body.style.overflow = ''; }
    }

    // Set parent for reply
    function setParent(submissionId, commentId) {
        const input = document.getElementById('parent_id_' + submissionId);
        const replyBox = document.getElementById('replyBox_' + submissionId);
        const cancelBtn = document.getElementById('cancelReply_' + submissionId);
        if (!input || !replyBox) return;

        input.value = commentId;
        const commentEl = document.querySelector(`[data-id="${commentId}"]`);
        if (commentEl) {
            const author = commentEl.querySelector('b')?.innerText?.trim() || 'Автор';
            const text = commentEl.querySelector('p')?.innerText?.trim() || '';
            replyBox.style.display = 'block';
            replyBox.innerHTML = `<strong>Ответ:</strong> ${author} — <em>${text}</em>`;
            if (cancelBtn) cancelBtn.style.display = 'inline-flex';
        }
        openCommentModal(submissionId);
    }

    function clearParent(submissionId) {
        const input = document.getElementById('parent_id_' + submissionId);
        const replyBox = document.getElementById('replyBox_' + submissionId);
        const cancelBtn = document.getElementById('cancelReply_' + submissionId);
        if (input) input.value = '';
        if (replyBox) { replyBox.style.display = 'none'; replyBox.innerHTML = ''; }
        if (cancelBtn) cancelBtn.style.display = 'none';
    }

    // Show more replies
    function showMoreReplies(button) {
        const container = button.closest('.replies');
        if (!container) return;
        const hidden = Array.from(container.querySelectorAll('.reply-item[style*="display:none"]'));
        hidden.slice(0, 3).forEach(el => el.style.display = 'block');
        if (hidden.length <= 3) button.remove();
        else button.innerText = 'Показать ещё ' + (hidden.length - 3);
    }


    // Send comment via AJAX
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) { entry.target.classList.add('active'); observer.unobserve(entry.target); } });
        }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });
        document.querySelectorAll('.rv').forEach(el => observer.observe(el));




        // Close modal on backdrop click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this || e.target.classList.contains('modal-backdrop')) {
                    closeCommentModal(this.id.replace('commentModal', ''));
                }
            });
        });

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    closeCommentModal(modal.id.replace('commentModal', ''));
                });
            }
        });
    });
</script>