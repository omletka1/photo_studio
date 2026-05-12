<?php

use yii\helpers\Html;

$this->title = 'Работы конкурса: ' . $konkurs->title;
?>
<h3>🏆 Победители</h3>

<ol>
    <?php foreach ($top3 as $workId => $s): ?>
        <li>
            Работа ID <?= $workId ?> —
            Средний балл: <?= $s['avg'] ?>,
            Рейтинг: <?= $s['final'] ?>
        </li>
    <?php endforeach; ?>
</ol>
<h2><?= Html::encode($this->title) ?></h2>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Голоса</th>
        <th>Средний балл</th>
        <th>Рейтинг</th>
    </tr>

    <?php foreach ($works as $work): ?>
        <tr>
            <td><?= $work->id ?></td>
            <td><?= $work->title ?></td>
            <td>
                <?= $work->voteCount ?>

                <?php if (!empty($groupedRatings[$work->id])): ?>
                    <div style="margin-top:5px; font-size:12px; background:#f9f9f9; padding:5px;">

                        <?php foreach ($groupedRatings[$work->id] as $userId => $userRatings): ?>

                            <div style="margin-bottom:5px; border-bottom:1px solid #ddd;">

                                <b>
                                    <?= $userRatings[0]->user->surname ?>
                                    <?= $userRatings[0]->user->name ?>
                                </b>

                                <ul style="margin:0; padding-left:15px;">
                                    <?php foreach ($userRatings as $r): ?>
                                        <li>
                                            <?= $r->nomination->title ?>:
                                            <b><?= $r->score ?></b>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                            </div>

                        <?php endforeach; ?>

                    </div>
                <?php endif; ?>

            </td>
            <td><?= $stats[$work->id]['avg'] ?? 0 ?></td>
            <td><b><?= $stats[$work->id]['final'] ?? 0 ?></b></td>

            <td>
                <?= Html::a('Редактировать оценки', [
                    'admin/rate',
                    'id' => $work->id
                ], ['class' => 'btn btn-warning btn-sm']) ?>

                <?= Html::a('Оценить', [
                    'admin/rate',
                    'id' => $work->id
                ], ['class' => 'btn btn-success btn-sm']) ?>

                <button class="btn btn-outline-primary btn-sm"
                        onclick="openCommentModal(<?= $work->id ?>)">
                    Комментарии
                </button>
            </td>
        </tr>

    <?php endforeach; ?>
</table>
<?php foreach ($works as $work): ?>

    <?php
    $comments = \app\models\JuryComment::find()
        ->where([
            'submission_id' => $work->id,
            'parent_id' => null
        ])
        ->with(['user', 'replies.user'])
        ->all();
    ?>

    <div class="modal fade" id="commentModal<?= $work->id ?>" tabindex="-1">

        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Комментарии</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="comment-list" id="comment-list-<?= $work->id ?>">

                    <?php foreach ($comments as $comment): ?>
                        <?php if ($comment->submission_id == $work->id): ?>
                            <?= $this->render('_comment', [
                                'comment' => $comment
                            ]) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>

                <form class="comment-form" data-submission="<?= $work->id ?>">

                    <input type="hidden" name="submission_id" value="<?= $work->id ?>">
                    <input type="hidden" name="parent_id" id="parent_id_<?= $work->id ?>">
                    <div id="replyBox_<?= $work->id ?>"
                         style="display:none; color:gray; margin-bottom:5px;">
                    </div>
                    <textarea name="text" required></textarea>

                    <button type="submit">Отправить</button>

                </form>

            </div>
        </div>
    </div>

<?php endforeach; ?>

<script>
    // =====================
    // ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
    // =====================

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content
            || document.querySelector('input[name="_csrf"]')?.value
            || '';
    }

    function openCommentModal(id) {
        const el = document.getElementById('commentModal' + id);
        if (el) {
            new bootstrap.Modal(el).show();
        }
    }

    // =====================
    // SET PARENT (ОТВЕТ)
    // =====================
    function setParent(submissionId, commentId) {
        document.getElementById('parent_id_' + submissionId).value = commentId;

        const commentEl = document.querySelector(`[data-id="${commentId}"]`);
        if (!commentEl) return;

        const author = commentEl.querySelector('b')?.innerText || '';
        const text = commentEl.querySelector('p')?.innerText || '';

        const box = document.getElementById('replyBox_' + submissionId);
        if (box) {
            box.style.display = 'block';
            box.innerHTML = `
                <div style="background:#f0f0f0; padding:8px; border-radius:4px; margin-bottom:8px;">
                    Ответ на <b>${author}</b>: <em>${text}</em>
                    <button type="button" onclick="clearParent(${submissionId})"
                            style="float:right; background:none; border:none; color:#999; cursor:pointer;">✖</button>
                </div>
            `;
        }
    }

    // =====================
    // CLEAR PARENT
    // =====================
    function clearParent(submissionId) {
        const input = document.getElementById('parent_id_' + submissionId);
        const box = document.getElementById('replyBox_' + submissionId);

        if (input) input.value = '';
        if (box) {
            box.innerHTML = '';
            box.style.display = 'none';
        }
    }

    // =====================
    // SHOW MORE REPLIES
    // =====================
    function showMoreReplies(button) {
        const container = button.closest('.replies');
        const hidden = Array.from(
            container.querySelectorAll('.reply-item[style*="display:none"]')
        );

        hidden.slice(0, 3).forEach(el => el.style.display = 'block');

        if (hidden.length <= 3) {
            button.remove();
        } else {
            button.innerText = 'Показать ещё ' + (hidden.length - 3);
        }
    }

    // =====================
    // DELETE COMMENT 🔥 ИСПРАВЛЕНО
    // =====================
    function deleteComment(id, btn) {
        if (!confirm('Удалить этот комментарий?')) return;

        // Получаем URL из data-атрибута кнопки
        const deleteUrl = btn?.dataset?.deleteUrl
            || "<?= \yii\helpers\Url::to(['/admin/admin/delete-comment']) ?>";

        const csrfToken = getCsrfToken();

        if (!csrfToken) {
            console.error('CSRF token not found');
            alert('Ошибка безопасности. Обновите страницу.');
            return;
        }

        fetch(deleteUrl + '?id=' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': csrfToken,
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Плавное удаление элемента
                    const el = document.querySelector(`[data-id="${id}"]`);
                    if (el) {
                        el.style.transition = 'opacity 0.3s, transform 0.3s';
                        el.style.opacity = '0';
                        el.style.transform = 'translateX(20px)';
                        setTimeout(() => el.remove(), 300);
                    }
                } else {
                    alert('Ошибка: ' + (data.error || 'Не удалось удалить комментарий'));
                    console.error('Delete error:', data);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Не удалось удалить комментарий. Проверьте консоль разработчика (F12).');
            });
    }

    // =====================
    // SEND COMMENT
    // =====================
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submissionId = this.dataset.submission;
            const submitBtn = this.querySelector('button[type="submit"]');

            // Блокируем кнопку на время отправки
            if (submitBtn) submitBtn.disabled = true;

// 🔥 ВАЖНО: правильный маршрут для модуля admin
            fetch('<?= \yii\helpers\Url::to(['/admin/admin/add-comment']) ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': getCsrfToken()
                },
                body: formData
            })
                .then(r => r.json())
                .then(data => {
                    if (submitBtn) submitBtn.disabled = false;

                    if (!data.success) {
                        if (data.errors) {
                            alert('Ошибка: ' + JSON.stringify(data.errors));
                        }
                        return;
                    }

                    const temp = document.createElement('div');
                    temp.innerHTML = data.html;
                    const newComment = temp.firstElementChild;

                    if (data.parent_id) {
                        // Ответ на комментарий
                        const parent = document.querySelector(
                            `[data-id="${data.parent_id}"] .replies`
                        );
                        if (parent) {
                            parent.appendChild(newComment);
                        }
                    } else {
                        // Новый корневой комментарий
                        const list = document.getElementById('comment-list-' + submissionId);
                        if (list) {
                            list.prepend(newComment);
                        }
                    }

                    // Очистка формы
                    this.reset();
                    clearParent(submissionId);
                })
                .catch(err => {
                    console.error('Send comment error:', err);
                    if (submitBtn) submitBtn.disabled = false;
                    alert('Не удалось отправить комментарий');
                });
        });
    });
</script>