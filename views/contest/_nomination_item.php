<?php
use yii\helpers\Html;

/** @var array $model */

$image = $model['image'] ?? 'default.jpg';
$imageUrl = Yii::getAlias('@web/images/' . $image);
?>

<div class="nomination-card">
    <div class="nomination-image">
        <img src="<?= Html::encode($imageUrl) ?>" alt="<?= Html::encode($model['title']) ?>" loading="lazy">
        <div class="nomination-overlay">
            <span class="overlay-text">Увеличить</span>
        </div>
    </div>
    <div class="nomination-content">
        <h3 class="nomination-title"><?= Html::encode($model['title']) ?></h3>
        <p class="nomination-desc"><?= Html::encode($model['description']) ?></p>
        <?= Html::a('Участвовать', ['submission/submission', 'id' => $model['id']], ['class' => 'nomination-btn']) ?>
    </div>
</div>