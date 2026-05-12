<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "result".
 *
 * @property int $id
 * @property int $konkurs_id
 * @property int $submission_id
 * @property int $ocenka
 * @property string $comment
 *
 * @property Konkurs $konkurs
 * @property Submission $submission
 */
class Result extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['konkurs_id', 'submission_id', 'ocenka', 'comment'], 'required'],
            [['konkurs_id', 'submission_id', 'ocenka'], 'integer'],
            [['comment'], 'string'],
            [['konkurs_id'], 'exist', 'skipOnError' => true, 'targetClass' => Konkurs::class, 'targetAttribute' => ['konkurs_id' => 'id']],
            [['submission_id'], 'exist', 'skipOnError' => true, 'targetClass' => Submission::class, 'targetAttribute' => ['submission_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'konkurs_id' => 'Konkurs ID',
            'submission_id' => 'Submission ID',
            'ocenka' => 'Ocenka',
            'comment' => 'Comment',
        ];
    }

    /**
     * Gets query for [[Konkurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKonkurs()
    {
        return $this->hasOne(Konkurs::class, ['id' => 'konkurs_id']);
    }

    /**
     * Gets query for [[Submission]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubmission()
    {
        return $this->hasOne(Submission::class, ['id' => 'submission_id']);
    }

}
