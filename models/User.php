<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public $avatarFile;
    public $new_password;
    public $new_password_repeat;
    const STATUS_WAIT = 0;
    const STATUS_ACTIVE = 1;
    const VERIFICATION_CODE_LENGTH = 6;
    const VERIFICATION_CODE_EXPIRE = 900; // 15 минут в секундах
    public static function tableName()
    {
        return 'user';
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
     //   return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
     //   return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function rules()
    {
        return [
            // Файлы
            [['avatarFile'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2],

            // Обязательные поля
            [['username', 'email', 'name', 'surname'], 'required'],

            // Строковые поля с ограничением длины
            [['username', 'email', 'name', 'surname'], 'string', 'max' => 255],
            [['bio'], 'string', 'max' => 500], // опционально: лимит для био
            [['website'], 'string', 'max' => 255],
            [['verification_code', 'verification_code_expire'], 'safe'],
            // Валидация email и URL
            ['email', 'email'],
            ['website', 'url', 'defaultScheme' => 'http', 'skipOnEmpty' => true, 'message' => 'Введите корректную ссылку (например, https://...)'],

            // 🔥 ВАЖНО: разрешаем массовое присваивание для этих полей
            [['bio', 'website', 'name', 'surname'], 'safe'],

            // Виртуальные поля для формы (пароль)
            [['new_password', 'new_password_repeat'], 'safe'],
            ['new_password', 'string', 'min' => 8],
            ['new_password_repeat', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли не совпадают'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'new_password' => 'Новый пароль',
            'new_password_repeat' => 'Повторите новый пароль',
        ];
    }
    public function uploadAvatar()
    {
        if ($this->avatarFile) {

            $folder = Yii::getAlias('@webroot/uploads/avatars');

            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $fileName = $this->id . '.' . $this->avatarFile->extension;

            $fullPath = $folder . '/' . $fileName;

            if ($this->avatarFile->saveAs($fullPath)) {
                $this->avatar = 'uploads/avatars/' . $fileName;
                return true;
            }
        }

        return false;
    }
    public function generateVerificationCode(): string
    {
        $this->verification_code = str_pad(random_int(0, 999999), self::VERIFICATION_CODE_LENGTH, '0', STR_PAD_LEFT);
        $this->verification_code_expire = time() + self::VERIFICATION_CODE_EXPIRE;
        return $this->verification_code;
    }

    // 🔥 Проверка кода и активация пользователя
    public function verifyCode(string $code): bool
    {
        if ($this->status !== self::STATUS_WAIT) return false;
        if ($this->verification_code !== $code) return false;
        if ($this->verification_code_expire < time()) return false; // Истёк

        $this->status = self::STATUS_ACTIVE;
        $this->verification_code = null;
        $this->verification_code_expire = null;
        return $this->save(false);
    }

    // 🔥 Сброс кода (для повторной отправки)
    public function resetVerificationCode()
    {
        $this->verification_code = null;
        $this->verification_code_expire = null;
    }
}
