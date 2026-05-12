<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Vote extends ActiveRecord
{
    public static function tableName()
    {
        return 'vote';
    }

    public function rules()
    {
        return [
            [['user_id', 'submission_id'], 'required'],
            [['user_id', 'submission_id'], 'integer'],
            [['user_id', 'submission_id'], 'unique', 'targetAttribute' => ['user_id', 'submission_id']],
            [['created_at'], 'safe'],
        ];
    }

}
