<?php
use yii\helpers\Html;

/** @var $comment app\models\JuryComment */
/** @var $submissionId int */
/** @var $isVisible bool|null */
?>

<?php
// 🔥 Если isVisible не передан — показываем комментарий (для совместимости)
$isHidden = !empty($isVisible) ? '' : 'hidden';
$indentClass = $comment->parent_id ? 'ml-8 border-l-2 border-gray-200' : '';
?>

<div class="comment comment-item p-4 bg-gray-50 rounded-lg <?= $isHidden ?> <?= $indentClass ?>"
     data-id="<?= $comment->id ?>">

    <div class="flex items-start justify-between gap-3">
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
                <span class="font-medium text-gray-900">
                    <?= Html::encode($comment->user?->surname . ' ' . $comment->user?->name ?? 'Пользователь') ?>
                </span>
                <time class="text-xs text-gray-400"
                      title="<?= $comment->created_at ? Yii::$app->formatter->asDatetime($comment->created_at) : '' ?>"
                      datetime="<?= $comment->created_at ? date('c', strtotime($comment->created_at)) : date('c') ?>">
                    <?= $comment->created_at ? Yii::$app->formatter->asRelativeTime($comment->created_at) : 'только что' ?>
                </time>
            </div>
            <p class="text-gray-700 text-sm"><?= Html::encode($comment->text) ?></p>
        </div>

        <!-- Кнопки действий -->
        <div class="flex items-center gap-1">
            <?php if (!$comment->parent_id): ?>
                <button type="button"
                        onclick="setParent(<?= $submissionId ?>, <?= $comment->id ?>)"
                        class="p-1.5 text-gray-400 hover:text-orange-600 transition rounded"
                        title="Ответить">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </button>
            <?php endif; ?>

            <?php
            $identity = Yii::$app->user->identity;
            $isAuthor = !Yii::$app->user->isGuest && Yii::$app->user->id == $comment->user_id;
            $isAdmin = $identity && ($identity->role ?? 0) == 1;
            if ($isAuthor || $isAdmin):
                ?>
                <button type="button"
                        onclick="deleteComment(<?= $comment->id ?>, this)"
                        data-delete-url="<?= \yii\helpers\Url::to(['/admin/jury/delete-comment']) ?>"
                        class="p-1.5 text-gray-400 hover:text-red-600 transition rounded"
                        title="Удалить">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Ответы -->
    <?php if (!empty($comment->replies)): ?>
        <div class="replies mt-3 space-y-2">
            <?php foreach ($comment->replies as $i => $reply): ?>
                <div class="reply-item <?= $i >= 3 ? 'hidden' : '' ?>">
                    <?= $this->render('@app/modules/admin/views/jury/_comment', [
                        'comment' => $reply,
                        'submissionId' => $submissionId,
                        'isVisible' => true
                    ]) ?>
                </div>
            <?php endforeach; ?>
            <?php if (count($comment->replies) > 3): ?>
                <button type="button" onclick="showMoreReplies(this)" class="text-xs text-orange-600 hover:underline">
                    Показать ещё <?= count($comment->replies) - 3 ?>
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>