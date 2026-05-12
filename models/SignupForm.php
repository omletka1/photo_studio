<?php

namespace app\models;

use Yii;
use yii\base\Model;


class SignupForm extends Model
{



    public $username;
    public $password;
    public $email;
    public $access_token;
    public $surname;
    public $name;

    public function rules()
    {
        return [
            // 🔥 Обязательные поля
            [['username', 'password', 'name', 'surname', 'email'], 'required'],

            // 🔥 Email
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Этот email уже занят'],
            ['email', 'string', 'max' => 255],

            // 🔥 Логин: минимум 4 символа, только латиница, цифры, подчёркивание
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Этот логин уже занят'],
            ['username', 'string', 'min' => 4, 'max' => 20],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => 'Логин может содержать только латинские буквы, цифры и подчёркивание'],

            // 🔥 Пароль: минимум 8 символов
            ['password', 'string', 'min' => 8],

            // 🔥 Имя и Фамилия: только кириллица, пробелы, дефис; максимум 70 символов
            [['name', 'surname'], 'string', 'max' => 70],
            [['name', 'surname'], 'match',
                'pattern' => '/^[а-яА-ЯёЁ\-\s]+$/u',
                'message' => '{attribute} может содержать только русские буквы, пробелы и дефис'
            ],
            // 🔥 Безопасность (массовое присваивание)
            [['username', 'password', 'email', 'name', 'surname'], 'safe'],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->email = $this->email;
        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        $user->access_token = Yii::$app->security->generateRandomString();
        $user->status = User::STATUS_WAIT;

        if (!$user->save()) {
            return null;
        }

        // 🔥 Отправляем код
        $this->sendVerificationEmail($user);

        return $user;
    }
    private function sendVerificationEmail(User $user)
    {
        $code = $user->generateVerificationCode();

        if (!$user->save(false)) {
            Yii::error("Failed to save verification code: " . print_r($user->errors, true), __METHOD__);
            return false;
        }

        return Yii::$app->mailer->compose('emailVerifyCode', [
            'user' => $user,
            'code' => $code,
        ])
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo($user->email)
            ->setSubject('Код подтверждения регистрации')
            ->send();
    }
    /**
     * Отправка письма с кодом подтверждения
     */


    public function attributeLabels(){
        return [
            'username'=>'Логин',
            'password'=>'Пароль',
            'name'=>'Имя',
            'email'=>'емайл',
            'surname'=>'Фамилия',
        ];
    }

}