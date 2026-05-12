<?php

namespace app\models;
use yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "submission".
 *
 * @property int $id
 * @property int $user_id
 * @property int $konkurs_id
 * @property string $title
 * @property string $description
 * @property string $img
 *
 * @property Konkurs $konkurs
 * @property Result[] $results
 * @property User $user
 */
class Submission extends \yii\db\ActiveRecord
{


    public $imageFile;
    public $vote_count;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'submission';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'konkurs_id', 'title', 'description'], 'required'],
            [['user_id', 'konkurs_id'], 'integer'],
            [['title', 'description'], 'string', 'max' => 255],
            [['image1', 'image2', 'image3', 'image4', 'image5'], 'string', 'max' => 255],
            [['imageFile'], 'safe'],
            [['imageFile'], 'file', 'extensions' => 'jpg, png, jpeg', 'maxFiles' => 5],

            // 🔥 Уникальность: один пользователь — одна работа на конкурс
            [['user_id', 'konkurs_id'], 'unique',
                'targetClass' => Submission::class,
                'targetAttribute' => ['user_id', 'konkurs_id'],
                'message' => 'Вы уже подавали работу в этот конкурс.'
            ],

            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['konkurs_id'], 'exist', 'skipOnError' => true, 'targetClass' => Konkurs::class, 'targetAttribute' => ['konkurs_id' => 'id']],
        ];
    }


    public function init()
    {
        parent::init();
        $this->image1 = '';
        $this->image2 = '';
        $this->image3 = '';
        $this->image4 = '';
        $this->image5 = '';
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'konkurs_id' => 'Konkurs ID',
            'title' => 'Title',
            'description' => 'Description',
            'imageFile' => 'imageFile',
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
     * Gets query for [[Results]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResults()
    {
        return $this->hasMany(Result::class, ['submission_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    public function upload()
    {
        if (empty($this->imageFile)) {
            $this->addError('imageFile', 'Не выбраны файлы для загрузки');
            return false;
        }

        $userId = Yii::$app->user->id;

        // 📁 папка: uploads/works/user_13/
        $uploadPath = Yii::getAlias("@webroot/uploads/works/user_{$userId}/");

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $successCount = 0;

        foreach ($this->imageFile as $key => $file) {

            if ($key >= 5) break;

            $fileName = uniqid('img_') . '.' . $file->extension;

            // ❗ ФИЗИЧЕСКИЙ ПУТЬ (куда сохраняем)
            $fullPath = $uploadPath . $fileName;

            if ($file->saveAs($fullPath)) {

                // ❗ ПУТЬ ДЛЯ БАЗЫ (URL)
                $relativePath = "uploads/works/user_{$userId}/" . $fileName;

                $this->{'image' . ($key + 1)} = $relativePath;

                $successCount++;
            }
        }

        if ($successCount === 0) {
            $this->addError('imageFile', 'Не удалось сохранить ни одного файла');
            return false;
        }

        return $this->save(false);
    }

    public function getImages()
    {
        return $this->hasMany(SubmissionImage::class, ['submission_id' => 'id']);
    }

    public function getStatusText()
    {
        $map = [
            0 => 'Не оценено',
            1 => 'Участник',
            2 => '2 место',
            3 => '1 место',
        ];

        return $map[$this->status] ?? 'Неизвестно';
    }
    public function getVotes()
    {
        return $this->hasMany(Vote::class, ['submission_id' => 'id']);
    }

    public function getVoteCount()
    {
        return $this->getVotes()->count();
    }

    /**
     * Связь с номинацией
     */
    public function getNomination()
    {
        return $this->hasOne(\app\models\Nomination::class, ['id' => 'nomination_id']);
    }
    public function getJuryRatings()
    {
        return $this->hasMany(JuryRating::class, ['submission_id' => 'id'])
            ->with('nomination');
    }
}
