<?php
namespace app\models;

use yii\db\ActiveRecord;

class Nomination extends ActiveRecord
{
    public static function tableName()
    {
        return 'nominations';
    }

    public function rules()
    {
        return [
            // 🔥 ВСЕ поля обязательны
            [['title', 'description', 'image'], 'required', 'message' => 'Поле "{attribute}" обязательно для заполнения'],
            [['title', 'description', 'image'], 'string', 'max' => 255],
        ];
    }

    public function getKonkursy()
    {
        return $this->hasMany(Konkurs::class, ['id' => 'konkurs_id'])
            ->viaTable('konkurs_nominations', ['nomination_id' => 'id']);
    }
    public function getKonkursNominations()
    {
        return $this->hasMany(KonkursNominations::class, ['nomination_id' => 'id']);
    }
}