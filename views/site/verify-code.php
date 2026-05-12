<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\VerifyCodeForm $model */

$this->title = 'Подтверждение регистрации';
?>

<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <div class="text-center mb-8">
                <div class="text-5xl mb-4">🔐</div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Введите код из письма</h1>
                <p class="text-gray-500 text-sm">
                    Мы отправили 6-значный код на<br>
                    <strong class="text-gray-900"><?= Html::encode($model->email) ?></strong>
                </p>
            </div>

            <!-- Флеш-сообщения -->
            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm border border-red-200">
                    <?= Yii::$app->session->getFlash('error') ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-lg text-sm border border-green-200">
                    <?= Yii::$app->session->getFlash('success') ?>
                </div>
            <?php endif; ?>

            <?php $form = ActiveForm::begin([
                'id' => 'verify-code-form',
                'options' => ['class' => 'space-y-6']
            ]); ?>
            <?= $form->field($model, 'email')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'code')->textInput([
                'maxlength' => 6,
                'placeholder' => '000000',
                'class' => 'w-full text-center text-3xl font-mono tracking-[0.5em] py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8b77b3] focus:border-transparent outline-none transition',
                'inputmode' => 'numeric',
                'pattern' => '[0-9]*',
                'autofocus' => true,
            ])->label(false) ?>

            <button type="submit" class="w-full py-3 px-4 bg-[#8b77b3] hover:bg-[#75639c] text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg">
                ✅ Подтвердить
            </button>
            <?php ActiveForm::end(); ?>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Не получили код?
                    <?= Html::a('Отправить повторно', ['/site/resend-code', 'email' => $model->email], [
                        'class' => 'text-[#8b77b3] hover:underline font-medium'
                    ]) ?>
                </p>
                <p class="text-xs text-gray-400 mt-2">Код действителен 15 минут</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.querySelector('input[name="VerifyCodeForm[code]"]');
        if (input) {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
            });
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                this.value = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').slice(0, 6);
            });
        }
    });
</script>