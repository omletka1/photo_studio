<?php

namespace app\models;

use yii\db\ActiveRecord;

class JuryComment extends ActiveRecord
{
    public static function tableName()
    {
        return 'jury_comment';
    }

    public function rules()
    {
        return [
            [['submission_id', 'user_id', 'text'], 'required'],
            [['submission_id', 'user_id', 'parent_id'], 'integer'],
            ['text', 'string'],
            [['parent_id'], 'safe'],
        ];
    }
    public function getReplies()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id'])
            ->with('user')
            ->orderBy(['id' => SORT_ASC]);
    }
    public function getUser()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'user_id']);
    }
}