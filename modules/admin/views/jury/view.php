<?php
use yii\helpers\Html;

/** @var $work app\models\Submission */
/** @var $images string[] */
/** @var $voteCount int */
/** @var $juryRatings app\models\JuryRating[] */
/** @var $avgScore float */
/** @var $comments app\models\JuryComment[] */

$this->title = 'Работа: ' . $work->title;
?>

<!-- Убираем лишние обёртки — .main-container уже в main.php -->
<div class="jury-work-view">

    <!-- Header -->
    <header class="page-header">
        <div class="header-left">
            <?= Html::a('
                <svg class="back-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                Назад
            ', ['/admin/jury/submissions', 'konkurs_id' => $work->konkurs_id], ['class' => 'btn-back', 'encode' => false]) ?>
            <h1 class="page-title"><?= Html::encode($work->title) ?></h1>
        </div>
        <div class="header-actions">
            <?php
            $hasRated = \app\models\JuryRating::find()
                ->where(['submission_id' => $work->id, 'user_id' => Yii::$app->user->id])
                ->exists();
            ?>
            <?php if (!$hasRated): ?>
                <?= Html::a('
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    Оценить
                ', ['rate', 'submission_id' => $work->id], ['class' => 'btn-rate', 'encode' => false]) ?>
            <?php else: ?>
                <span class="btn-rated">
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                    Оценено
                </span>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Grid -->
    <div class="content-grid">

        <!-- Left Column: Images + Description -->
        <div class="content-main">

            <!-- Images -->
            <section class="card">
                <h3 class="card-title">Изображения</h3>
                <?php if (!empty($images)): ?>
                    <div class="image-grid">
                        <?php foreach ($images as $img): ?>
                            <a href="<?= Html::encode($img) ?>" data-lightbox="work-<?= $work->id ?>" class="image-item">
                                <img src="<?= Html::encode($img) ?>" alt="" loading="lazy">
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-images">Изображения не загружены</div>
                <?php endif; ?>
            </section>

            <!-- Description -->
            <section class="card">
                <h3 class="card-title">Описание</h3>
                <p class="description-text"><?= Html::encode($work->description ?: '—') ?></p>
            </section>
        </div>

        <!-- Right Column: Meta Info -->
        <aside class="content-sidebar">

            <!-- Author -->
            <div class="card">
                <h3 class="card-title">Автор</h3>
                <div class="author-card">
                    <div class="author-avatar"><?= mb_substr($work->user?->name ?? '?', 0, 1) ?></div>
                    <div class="author-info">
                        <div class="author-name"><?= Html::encode($work->user?->surname . ' ' . $work->user?->name ?? '—') ?></div>
                        <div class="author-username">@<?= Html::encode($work->user?->username ?? '—') ?></div>
                    </div>
                </div>
            </div>

            <!-- Contest -->
            <div class="card">
                <h3 class="card-title">Конкурс</h3>
                <div class="contest-info">
                    <div class="contest-title"><?= Html::encode($work->konkurs?->title ?? '—') ?></div>
                    <?php if ($work->nomination): ?>
                        <div class="contest-nomination">Номинация: <?= Html::encode($work->nomination->title) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Stats -->
            <div class="card">
                <h3 class="card-title">Статистика</h3>
                <div class="stats-list">
                    <div class="stat-row">
                        <span class="stat-label">Голоса участников:</span>
                        <span class="stat-value"><?= $voteCount ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Оценок жюри:</span>
                        <span class="stat-value"><?= count($juryRatings) ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Средний балл:</span>
                        <?php
                        $scoreClass = $avgScore >= 4 ? 'score-high' : ($avgScore >= 2.5 ? 'score-mid' : 'score-low');
                        ?>
                        <span class="stat-value score-badge <?= $scoreClass ?>"><?= $avgScore ?: '—' ?>/5</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <!-- Jury Ratings Table -->
    <?php if (!empty($juryRatings)): ?>
        <section class="card mt-8">
            <h3 class="card-title">Оценки жюри</h3>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Жюри</th>
                        <th>Номинация</th>
                        <th class="text-center">Балл</th>
                        <th>Комментарий</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($juryRatings as $r): ?>
                        <tr>
                            <td class="font-medium"><?= Html::encode($r->user?->surname . ' ' . $r->user?->name ?? '—') ?></td>
                            <td class="text-muted"><?= Html::encode($r->nomination?->title ?? '—') ?></td>
                            <td class="text-center">
                                <?php $sc = $r->score >= 4 ? 'score-high' : ($r->score >= 2.5 ? 'score-mid' : 'score-low'); ?>
                                <span class="score-badge <?= $sc ?>"><?= $r->score ?>/5</span>
                            </td>
                            <td class="text-muted"><?= Html::encode($r->comment ?: '—') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>

    <!-- Comments Section -->
    <section class="card mt-8">
        <div class="card-header">
            <h3 class="card-title">Комментарии жюри</h3>
            <button type="button" onclick="openCommentModal(<?= $work->id ?>)" class="btn-secondary">
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Добавить комментарий
            </button>
        </div>

        <div id="comment-list-<?= $work->id ?>" class="comment-list">
            <?php if (empty($comments)): ?>
                <p class="comment-empty">Комментариев пока нет</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <?= $this->render('@app/modules/admin/views/jury/_comment', [
                        'comment' => $comment,
                        'submissionId' => $work->id
                    ]) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Comment Modal -->
    <div id="commentModal<?= $work->id ?>" class="modal" style="display:none;">
        <div class="modal-backdrop" onclick="closeCommentModal(<?= $work->id ?>)"></div>
        <div class="modal-card">
            <div class="modal-header">
                <h4 class="modal-title">Новый комментарий</h4>
                <button type="button" class="modal-close" onclick="closeCommentModal(<?= $work->id ?>)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <form class="comment-form" data-submission="<?= $work->id ?>">
                    <input type="hidden" name="submission_id" value="<?= $work->id ?>">
                    <input type="hidden" name="parent_id" id="parent_id_<?= $work->id ?>">

                    <div id="replyBox_<?= $work->id ?>" class="reply-preview" style="display:none;"></div>

                    <div class="form-group">
                        <textarea name="text" class="form-textarea" rows="4" placeholder="Введите комментарий..." required></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Отправить</button>
                        <button type="button" class="btn-secondary" onclick="clearParent(<?= $work->id ?>)" id="cancelReply_<?= $work->id ?>" style="display:none;">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    :root { --ease: cubic-bezier(0.16, 1, 0.3, 1); }

    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .header-left { display: flex; align-items: center; gap: 12px; }
    .page-title { font-size: 1.5rem; font-weight: 700; color: #111118; margin: 0; letter-spacing: -0.02em; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        color: #6b6b80; text-decoration: none; font-weight: 500;
        transition: color 0.2s ease;
    }
    .btn-back:hover { color: #8b77b3; }
    .back-icon { width: 16px; height: 16px; }

    .header-actions { display: flex; gap: 8px; }

    .btn-rate, .btn-rated, .btn-secondary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px; border-radius: 10px;
        font-weight: 600; font-size: 0.9rem;
        text-decoration: none; transition: all 0.25s var(--ease);
        cursor: pointer; border: none;
    }
    .btn-rate { background: #10b981; color: #fff; }
    .btn-rate:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .btn-rated { background: #f3f4f6; color: #6b7280; cursor: default; }
    .btn-secondary { background: #fff; color: #111118; border: 1.5px solid #e5e3eb; }
    .btn-secondary:hover { border-color: #8b77b3; background: #f0eaf5; color: #8b77b3; }
    .btn-icon { width: 16px; height: 16px; }

    .content-grid {
        display: grid; grid-template-columns: 1fr; gap: 24px;
    }
    @media (min-width: 1024px) { .content-grid { grid-template-columns: 2fr 1fr; } }

    .content-main { display: flex; flex-direction: column; gap: 24px; }
    .content-sidebar { display: flex; flex-direction: column; gap: 16px; }

    .card {
        background: #fff; border: 1px solid #e5e3eb; border-radius: 16px;
        padding: 20px; transition: box-shadow 0.3s var(--ease), border-color 0.2s ease;
    }
    .card:hover { box-shadow: 0 12px 32px rgba(0,0,0,0.06); border-color: #d5d0e3; }
    .card-title {
        font-size: 1.1rem; font-weight: 600; color: #111118;
        margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #f0eef5;
    }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f0eef5;
    }
    .card-header .card-title { margin: 0; padding: 0; border: none; }

    .image-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;
    }
    @media (min-width: 640px) { .image-grid { grid-template-columns: repeat(3, 1fr); } }
    .image-item {
        position: relative; border-radius: 10px; overflow: hidden;
        aspect-ratio: 1; background: #f0eaf5; display: block; cursor: zoom-in;
    }
    .image-item img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.4s var(--ease); display: block;
    }
    .image-item:hover img { transform: scale(1.04); }
    .empty-images {
        text-align: center; padding: 32px; color: #6b6b80;
        background: #fafaf8; border-radius: 10px; border: 1px dashed #e5e3eb;
    }

    .description-text {
        color: #111118; line-height: 1.7; white-space: pre-wrap; margin: 0;
    }

    .author-card { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .author-avatar {
        width: 48px; height: 48px; border-radius: 50%;
        background: #f0eaf5; color: #8b77b3;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; font-weight: 600; flex-shrink: 0;
    }
    .author-info { min-width: 0; }
    .author-name { font-weight: 600; color: #111118; font-size: 0.95rem; }
    .author-username { font-size: 0.85rem; color: #6b6b80; }

    .contest-info { margin-bottom: 12px; }
    .contest-title { font-weight: 600; color: #111118; margin-bottom: 4px; }
    .contest-nomination { font-size: 0.9rem; color: #6b6b80; }

    .stats-list { display: flex; flex-direction: column; gap: 12px; }
    .stat-row { display: flex; align-items: center; justify-content: space-between; }
    .stat-label { color: #6b6b80; font-size: 0.9rem; }
    .stat-value { font-weight: 600; color: #111118; font-size: 0.9rem; }

    .score-badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; border-radius: 999px; font-size: 0.8rem; font-weight: 600;
    }
    .score-high { background: #ecfdf5; color: #059669; }
    .score-mid { background: #fef3c7; color: #92400e; }
    .score-low { background: #fef2f2; color: #b91c1c; }

    .table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .data-table { width: 100%; border-collapse: collapse; min-width: 520px; }
    .data-table th {
        padding: 12px 14px; text-align: left; font-size: 0.72rem; font-weight: 600;
        color: #6b6b80; text-transform: uppercase; letter-spacing: 0.04em;
        background: #fafaf8; border-bottom: 1px solid #e5e3eb; white-space: nowrap;
    }
    .data-table td {
        padding: 12px 14px; border-bottom: 1px solid #f0eef5;
        font-size: 0.9rem; color: #111118; vertical-align: middle;
    }
    .data-table tbody tr:hover { background: #f9f8fc; }
    .text-muted { color: #6b6b80; }
    .font-medium { font-weight: 500; }

    .comment-list { display: flex; flex-direction: column; gap: 12px; max-height: 400px; overflow-y: auto; }
    .comment-empty { text-align: center; color: #6b6b80; padding: 24px; }

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
        max-width: 560px; width: 100%; max-height: 90vh; overflow: hidden;
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

    .reply-preview {
        background: #f5f3f9; border: 1px solid #e5e3eb; border-radius: 10px;
        padding: 12px 14px; font-size: 0.9rem; color: #111118; margin-bottom: 12px;
    }
    .form-group { margin-bottom: 16px; }
    .form-textarea {
        width: 100%; padding: 12px 14px; border-radius: 10px;
        border: 1.5px solid #e5e3eb; background: #fff; color: #111118;
        font-size: 0.95rem; font-family: inherit; resize: vertical;
        transition: border-color 0.2s ease, box-shadow 0.2s ease; min-height: 100px;
    }
    .form-textarea:focus {
        outline: none; border-color: #8b77b3;
        box-shadow: 0 0 0 3px rgba(139, 119, 179, 0.15);
    }
    .form-actions { display: flex; gap: 10px; }

    .btn-primary {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 0.9rem;
        text-decoration: none; transition: all 0.25s var(--ease); cursor: pointer; border: none;
        background: #111118; color: #fff;
    }
    .btn-primary:hover { background: #222; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }

    .mt-8 { margin-top: 32px; }

    @media (max-width: 640px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .header-actions { width: 100%; justify-content: flex-end; }
        .content-grid { grid-template-columns: 1fr; }
        .card { padding: 16px; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-secondary { width: 100%; }
    }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: #f7f6f9; }
    ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
</style>

<script>
    // Lightbox init
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lightbox !== 'undefined') {
            lightbox.option({ resizeDuration: 200, wrapAround: true, fadeDuration: 200, showImageNumberLabel: false });
        }
    });

    // Modal functions
    function openCommentModal(id) {
        const modal = document.getElementById('commentModal' + id);
        if (modal) { modal.style.display = 'flex'; setTimeout(() => modal.classList.add('show'), 10); document.body.style.overflow = 'hidden'; }
    }
    function closeCommentModal(id) {
        const modal = document.getElementById('commentModal' + id);
        if (modal) { modal.classList.remove('show'); setTimeout(() => { modal.style.display = 'none'; }, 250); document.body.style.overflow = ''; }
    }

    // CSRF token helper
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_csrf"]')?.value || '';
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

    // Delete comment
    function deleteComment(id, btn) {
        if (!confirm('Удалить этот комментарий?')) return;
        const deleteUrl = btn?.dataset?.deleteUrl || "<?= \yii\helpers\Url::to(['/admin/jury/delete-comment']) ?>";
        const csrfToken = getCsrfToken();
        if (!csrfToken) { alert('Ошибка безопасности'); return; }

        fetch(deleteUrl + '?id=' + encodeURIComponent(id), {
            method: 'POST', headers: { 'X-CSRF-Token': csrfToken, 'Accept': 'application/json' }
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const el = document.querySelector(`[data-id="${id}"]`);
                    if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 200); }
                } else alert('Ошибка: ' + (data.error || 'Не удалось удалить'));
            })
            .catch(() => alert('Не удалось удалить комментарий'));
    }

    // Send comment via AJAX
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submissionId = this.dataset.submission;
                const submitBtn = this.querySelector('button[type="submit"]');
                const textarea = this.querySelector('textarea[name="text"]');
                if (submitBtn) submitBtn.disabled = true;
                if (textarea) textarea.disabled = true;

                fetch("<?= \yii\helpers\Url::to(['/admin/jury/add-comment']) ?>", {
                    method: 'POST', headers: { 'X-CSRF-Token': getCsrfToken() }, body: formData
                })
                    .then(r => r.json())
                    .then(data => {
                        if (submitBtn) submitBtn.disabled = false;
                        if (textarea) textarea.disabled = false;
                        if (!data.success) {
                            alert('Ошибка: ' + (data.errors ? Object.values(data.errors).flat().join('\n') : data.error || 'Неизвестная'));
                            return;
                        }
                        // Insert new comment
                        const temp = document.createElement('div');
                        temp.innerHTML = data.html;
                        const newComment = temp.firstElementChild;
                        if (data.parent_id) {
                            const parent = document.querySelector(`[data-id="${data.parent_id}"] .replies`);
                            if (parent) { parent.appendChild(newComment); newComment.style.display = 'block'; }
                        } else {
                            const list = document.getElementById('comment-list-' + submissionId);
                            if (list) {
                                const emptyMsg = list.querySelector('.comment-empty');
                                if (emptyMsg) emptyMsg.remove();
                                list.prepend(newComment);
                            }
                        }
                        // Reset form
                        this.reset(); clearParent(submissionId);
                        // Highlight new comment
                        if (newComment) {
                            newComment.scrollIntoView({behavior: 'smooth', block: 'nearest'});
                            newComment.style.background = '#f0eaf5';
                            setTimeout(() => newComment.style.background = '', 1500);
                        }
                        // Close modal if not a reply
                        if (!data.parent_id) closeCommentModal(submissionId);
                    })
                    .catch(err => {
                        console.error('Send error:', err);
                        if (submitBtn) submitBtn.disabled = false;
                        if (textarea) textarea.disabled = false;
                        alert('Не удалось отправить комментарий');
                    });
            });
        });

        // Close modal on backdrop click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this || e.target.classList.contains('modal-backdrop')) {
                    closeCommentModal(this.id.replace('commentModal', ''));
                }
            });
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    closeCommentModal(modal.id.replace('commentModal', ''));
                });
            }
        });
    });
</script>