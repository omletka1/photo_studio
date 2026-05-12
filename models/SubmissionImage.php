<?php

namespace app\models;

use yii\db\ActiveRecord;

class SubmissionImage extends ActiveRecord
{
    public static function tableName()
    {
        return 'submission_image';
    }

    public function rules()
    {
        return [
            [['submission_id', 'path'], 'required'],
            [['submission_id'], 'integer'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    public function getSubmission()
    {
        return $this->hasOne(Submission::class, ['id' => 'submission_id']);
    }
    public function getImages()
    {
        return $this->hasMany(SubmissionImage::class, ['submission_id' => 'id']);
    }

}

