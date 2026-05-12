<?php

namespace app\models;

use yii\db\ActiveRecord;

class KonkursJury extends ActiveRecord
{
    public static function tableName()
    {
        return 'konkurs_jury';
    }

    public function rules()
    {
        return [
            [['konkurs_id', 'user_id'], 'required'],
            [['konkurs_id', 'user_id'], 'integer'],
        ];
    }
}