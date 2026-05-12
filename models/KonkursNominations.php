<?php

namespace app\models;

use yii\db\ActiveRecord;

class KonkursNominations extends ActiveRecord
{
    public static function tableName()
    {
        return 'konkurs_nominations';
    }

    public function rules()
    {
        return [
            [['konkurs_id', 'nomination_id'], 'required'],
            [['konkurs_id', 'nomination_id'], 'integer'],
        ];
    }

    public function getNomination()
    {
        return $this->hasOne(Nomination::class, ['id' => 'nomination_id']);
    }

    public function getKonkurs()
    {
        return $this->hasOne(Konkurs::class, ['id' => 'konkurs_id']);
    }
}