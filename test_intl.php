<?php
// Простой тест расширения intl
header('Content-Type: text/html; charset=UTF-8');

echo '<h3>Проверка PHP-расширений:</h3>';

// 1. Проверяем intl
if (extension_loaded('intl')) {
    echo '✅ <strong>intl</strong> — включено (локализация дат будет работать)<br>';
    echo '🌍 Доступные локали: ' . implode(', ', array_slice(Locale::getAvailableLocales(), 0, 10)) . '...<br>';
} else {
    echo '❌ <strong>intl</strong> — НЕ включено (даты могут быть на английском)<br>';
    echo '🔧 Решение: включи <code>extension=intl</code> в настройках PHP OpenServer<br>';
}

echo '<hr>';

// 2. Проверяем, видит ли Yii конфиг
if (file_exists(__DIR__ . '/../config/web.php')) {
    echo '✅ <strong>config/web.php</strong> — найден<br>';

    // Пробуем загрузить и проверить formatter
    $params = require __DIR__ . '/../config/params.php';
    $db = require __DIR__ . '/../config/db.php';
    $config = require __DIR__ . '/../config/web.php';

    if (isset($config['components']['formatter'])) {
        echo '✅ <strong>formatter</strong> — настроен в конфиге<br>';
        echo '🌐 Locale: ' . ($config['components']['formatter']['locale'] ?? 'не указан') . '<br>';
    } else {
        echo '❌ <strong>formatter</strong> — НЕ найден в конфиге<br>';
    }
} else {
    echo '❌ <strong>config/web.php</strong> — не найден<br>';
}

echo '<hr>';
echo '<small>Время сервера: ' . date('Y-m-d H:i:s') . '</small>';
?>