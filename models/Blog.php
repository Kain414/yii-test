<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;

class Blog extends ActiveRecord {

    public $file;

    //--------------------------------------------
    public const OPEN_TRUE = 0;
    public const OPEN_FALSE = 1;
    public const OPENS = [
        self::OPEN_TRUE => 'Открытый пост',
        self::OPEN_FALSE => 'Закрытый пост',
    ];
    public const STATUSES_NUM = [0,1];

    //--------------------------------------------
    public const STATUS_MODERATION = 0;
    public const STATUS_CONFIRMED = 1;
    public const STATUS_DISALLOW = 2;
    public const STATUS_REWRITE = 3;
    public const STATUS_EDITED = 4;
    public const STATUSES = [
        self::STATUS_MODERATION => 'Пост на модерации',
        self::STATUS_CONFIRMED => 'Пост одобрен',
        self::STATUS_DISALLOW => 'Пост не допускается к публикации',
        self::STATUS_REWRITE => 'Пост следует отредактировать',
        self::STATUS_EDITED => 'Пост повторно отредактирован',
    ];
    public const MOD_STATUSES_NUM = [0,1,2,3,4];

    public function behaviors() {
        return [
            TimestampBehavior::class,
        ];
    }

    public function attributeLabels() {
        return [
            'title' => 'Заголовок',
            'body' => 'Содержание',
            'image' => 'Картинка',
            'author' => 'Автор',
            'likes' => 'Лайки',
            'dislikes' => 'Дизлайки',
            'views' => 'Просмотры',
            'open' => 'Открытость комментариев',
            'status' => 'Статус поста',
            'info' => 'Комментарий'
        ];
    }

    public static function tableName() {
		return 'blogs';
	}

    public function rules() {
        return [
        	[['title', 'body', 'open', 'status'], 'required'],
            ['open', 'boolean'],
            [['title', 'body', 'info'],'string'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp'],
            ['image', 'safe'],
        ];
    }

    public function upload()
    {
        if ($this->file instanceof UploadedFile) {
            $filename = md5(time()) . '.' . $this->file->extension;
            $this->file->saveAs('uploads/' . $filename, false);
            if (!is_dir('uploads/' . $this->image) && file_exists('uploads/' . $this->image)) {
                unlink('uploads/' . $this->image);
            }
            $this->image = $filename;
            return true;
        } else {
            return false;
        }
    }

    public function getAuthorModel() {

        return $this->hasOne(User::class, ['id' => 'author']);
    }

}