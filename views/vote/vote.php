<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $submission app\models\Submission */

$this->title = 'Голосование за работу: ' . $submission->title;
?>

<div class="submission-vote">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="submission-info">
        <h3>Описание работы</h3>
        <p><?= Html::encode($submission->description) ?></p>
    </div>

    <div class="vote-controls">
        <button class="vote-button" data-id="<?= $submission->id ?>" data-voted="false">
            <i class="fa fa-heart-o"></i> Проголосовать
        </button>
        <div id="vote-count-<?= $submission->id ?>">Голосов: <?= $submission->votes_count ?></div>
    </div>
</div>
