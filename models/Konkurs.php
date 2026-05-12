<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "konkurs".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 *
 * @property Result[] $results
 * @property Submission[] $submissions
 */
class Konkurs extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'konkurs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'start_date', 'end_date', 'status'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['title', 'description', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Results]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResults()
    {
        return $this->hasMany(Result::class, ['konkurs_id' => 'id']);
    }
    public function getNominations()
    {
        return $this->hasMany(Nomination::class, ['id' => 'nomination_id'])
            ->viaTable('konkurs_nominations', ['konkurs_id' => 'id']);
    }
    /**
     * Gets query for [[Submissions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubmissions()
    {
        return $this->hasMany(Submission::class, ['konkurs_id' => 'id']);
    }

}
