<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organizers".
 *
 * @property int $id
 * @property string $name
 * @property string $role
 * @property string|null $description
 * @property string|null $image
 * @property string|null $social_facebook
 * @property string|null $social_instagram
 * @property string|null $social_website
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Organizers extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organizers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'image', 'social_facebook', 'social_instagram', 'social_website'], 'default', 'value' => null],
            [['name', 'role'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'role'], 'string', 'max' => 100],
            [['image', 'social_facebook', 'social_instagram', 'social_website'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'role' => 'Role',
            'description' => 'Description',
            'image' => 'Image',
            'social_facebook' => 'Social Facebook',
            'social_instagram' => 'Social Instagram',
            'social_website' => 'Social Website',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
