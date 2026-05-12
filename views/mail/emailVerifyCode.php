<?php
use yii\helpers\Html;

/** @var array|object $user */
/** @var string $code */

$userName = is_array($user) ? ($user['name'] ?? 'Пользователь') : ($user->name ?? 'Пользователь');
$userEmail = is_array($user) ? ($user['email'] ?? '') : ($user->email ?? '');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Код подтверждения</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: #f7f6f9;">

<div style="text-align: center; margin-bottom: 24px;">
    <img src="<?= Yii::getAlias('@web/image/1.png') ?>" alt="Logo" style="height: 40px;">
</div>

<h2 style="color: #111118; margin-bottom: 16px;">🔐 Ваш код подтверждения</h2>

<p style="color: #6b6b80; line-height: 1.6; margin-bottom: 24px;">
    Здравствуйте, <?= Html::encode($userName) ?>!<br>
    Для завершения регистрации введите этот код на сайте:
</p>

<div style="text-align: center; margin: 32px 0;">
    <div style="display: inline-block; padding: 16px 32px; background: #8b77b3; color: #fff;
                    font-size: 2rem; font-weight: bold; letter-spacing: 8px; border-radius: 12px; font-family: monospace;">
        <?= $code ?>
    </div>
</div>

<p style="color: #6b6b80; font-size: 0.9rem; text-align: center;">
    Код действителен <strong>15 минут</strong>.<br>
    Если вы не регистрировались, просто проигнорируйте это письмо.
</p>

<hr style="border: none; border-top: 1px solid #e5e3eb; margin: 32px 0;">

<p style="color: #8b8b9e; font-size: 0.85rem; text-align: center;">
    © <?= date('Y') ?> <?= Yii::$app->name ?>
</p>

</body>
</html>