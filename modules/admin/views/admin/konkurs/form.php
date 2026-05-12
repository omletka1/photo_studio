<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerCssFile('@web/css/konform.css');
$this->title = $model->isNewRecord ? 'СОЗДАНИЕ КОНКУРСА' : 'РЕДАКТИРОВАНИЕ КОНКУРСА';
?>


<div class="contest-form-container">
    <div class="contest-form-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="contest-form-divider"></div>
    </div>

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{hint}\n{error}",
            'options' => ['class' => 'contest-form-group'],
            'inputOptions' => ['class' => 'contest-form-control'],
        ],
    ]); ?>

    <?= $form->field($model, 'title', ['labelOptions' => ['class' => '']])->label('Название конкурса')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->label('Описание конкурса')->textarea(['rows' => 6]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'start_date')->label('Дата начала')->input('date') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'end_date')->label('Дата окончания')->input('date') ?>
        </div>
    </div>

    <?= $form->field($model, 'status')->label('Статус')->dropDownList([
        'открыт' => 'Открыт',
        'закрыт' => 'Закрыт',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('СОХРАНИТЬ', ['class' => 'contest-submit-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
