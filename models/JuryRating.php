<?php

namespace app\models;

use yii\db\ActiveRecord;

class JuryRating extends ActiveRecord
{
    public static function tableName()
    {
        return 'jury_rating';
    }

    public function rules()
    {
        return [
            [['submission_id', 'nomination_id', 'user_id', 'score'], 'required'],
            [['submission_id', 'nomination_id', 'user_id', 'score'], 'integer'],
            [['comment'], 'string'],
            [['comment'], 'string', 'max' => 500],
            [['comment'], 'safe'],  // ← Обязательно! Без этого поле игнорируется при load()
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'submission_id' => 'Работа',
            'nomination_id' => 'Номинация',
            'user_id' => 'Жюри',
            'score' => 'Балл',
            'comment' => 'Комментарий',  // ← Метка для формы
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getNomination()
    {
        return $this->hasOne(Nomination::class, ['id' => 'nomination_id']);
    }
}