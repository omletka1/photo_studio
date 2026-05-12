<?php
use yii\helpers\Html;

/** @var array $model */
/** @var string $baseImageUrl */

$firstImage = null;
for ($i = 1; $i <= 5; $i++) {
    $imageField = 'image' . $i;
    if (!empty($model[$imageField])) {
        $firstImage = $baseImageUrl . ltrim($model[$imageField], 'uploads/');
        break;
    }
}

$isVoted = \app\models\Vote::find()
    ->where(['user_id' => Yii::$app->user->id, 'submission_id' => $model['id']])
    ->exists();
$isOwnWork = !Yii::$app->user->isGuest && isset($model['user_id']) && $model['user_id'] == Yii::$app->user->id;
?>

<div class="submission-card" id="work-<?= $model['id'] ?>">

    <a href="<?= \yii\helpers\Url::to(['view', 'id' => $model['id']]) ?>" class="block">
        <div class="image-container">
            <?php if ($firstImage): ?>
                <img src="<?= Html::encode($firstImage) ?>"
                     alt="<?= Html::encode($model['title']) ?>"
                     class="main-image"
                     loading="lazy">
            <?php else: ?>
                <div class="image-placeholder">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            <?php endif; ?>
            <div class="image-overlay">
                <span class="overlay-text">Посмотреть подробнее →</span>
            </div>
        </div>
    </a>

    <div class="submission-header">
        <div class="submission-title"><?= Html::encode($model['konkurs_title']) ?></div>
        <div class="submission-meta">
            <span class="meta-item">
                <svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                <?= Html::a(
                    Html::encode($model['user_name'] . ' ' . $model['user_surname']),
                    ['/account/view-profile', 'id' => $model['user_id']],
                    ['class' => 'meta-link']
                ) ?>
                <?php if ($isOwnWork): ?>
                    <span class="own-badge">вы</span>
                <?php endif; ?>
            </span>
            <span class="meta-item">
                <svg class="meta-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <?= Html::encode($model['title']) ?>
            </span>
        </div>
    </div>

    <div class="vote-section">
        <div class="vote-count">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <span id="vote-count-<?= $model['id'] ?>"><?= (int)($model['vote_count'] ?? 0) ?></span>
        </div>
        <?php if (!Yii::$app->user->isGuest && !$isOwnWork): ?>
            <button class="vote-btn" data-id="<?= $model['id'] ?>" data-voted="<?= $isVoted ? 'true' : 'false' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="vote-icon">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="vote-text"><?= $isVoted ? 'Проголосовано' : 'Голосовать' ?></span>
            </button>
        <?php elseif ($isOwnWork): ?>
            <span class="vote-disabled">ваша работа</span>
        <?php else: ?>
            <span class="vote-disabled">
                <?= Html::a('войдите', ['/site/login'], ['class' => 'vote-link']) ?>, чтобы голосовать
            </span>
        <?php endif; ?>
    </div>


</div>
