<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property string $question
 * @property string|null $description
 * @property string $contacts
 * @property string $created_at
 */
class ContactRequest extends ActiveRecord
{
    public static function tableName()
    {
        return 'contact_request';
    }

    public function rules()
    {
        return [
            [['question', 'contacts'], 'required'],
            [['question', 'description'], 'string'],
            [['contacts'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'question' => 'Вопрос',
            'description' => 'Подробное описание',
            'contacts' => 'Контактная информация',
        ];
    }
    public function getUser()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'user_id']);
    }
}
