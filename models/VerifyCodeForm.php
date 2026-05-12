<?php
namespace app\models;

use yii\base\Model;

class VerifyCodeForm extends Model
{
    public $email;
    public $code;

    public function rules()
    {
        return [
            [['email', 'code'], 'required'],
            ['email', 'email'],
            ['code', 'match', 'pattern' => '/^\d{6}$/', 'message' => 'Код должен содержать ровно 6 цифр'],
            ['code', 'string', 'length' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'code' => 'Код подтверждения',
        ];
    }

    /**
     * Проверка кода и активация пользователя
     */
    public function verify(): bool
    {
        $user = User::find()->where([
            'email' => $this->email,
            'status' => User::STATUS_WAIT,
            'verification_code' => $this->code,
        ])->one();

        if (!$user) {
            return false;
        }

        // Проверка срока действия
        if ($user->verification_code_expire < time()) {
            return false;
        }

        return $user->verifyCode($this->code);
    }
}