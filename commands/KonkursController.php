<?php
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

class KonkursController extends Controller
{
    /**
     * Автоматически закрывает конкурсы, у которых end_date < сегодня
     * Использование: php yii konkurs/close-expired
     */
    public function actionCloseExpired()
    {
        $count = \Yii::$app->db->createCommand()
            ->update(
                'konkurs',
                ['status' => 'закрыт'],
                'status = "открыт" AND end_date < CURDATE()'
            )
            ->execute();

        if ($count > 0) {
            $this->stdout("✅ Автоматически закрыто конкурсов: $count\n", \yii\helpers\Console::FG_GREEN);
        } else {
            $this->stdout("ℹ️ Просроченных конкурсов не найдено\n", \yii\helpers\Console::FG_YELLOW);
        }

        return ExitCode::OK;
    }
}