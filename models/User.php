<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class User extends ActiveRecord  implements \yii\web\IdentityInterface
{
    public $authKey;
    public $file = 123;

    //--------------------------------------------
    public const STATUS_ADMIN = 1;
    public const STATUS_USER = 2;
    public const STATUS_MODERATOR = 3;
    public const STATUSES = [
        self::STATUS_ADMIN => 'Администартор',
        self::STATUS_USER => 'Пользователь',
        self::STATUS_MODERATOR => 'Модератор',
    ];
    public const STATUSES_NUM = [1,2,3];

    //---------------------------------------------
    public const GENDER_OTHER = 0;
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;
    public const GENDERS = [
        self::GENDER_OTHER => 'Другое',
        self::GENDER_MALE => 'Мужчина',
        self::GENDER_FEMALE => 'Женщина',
    ];
    public const GENDERS_NUM = [0,1,2];

    public function attributeLabels() {
        return [
            'username' => 'Имя',
            'email' => 'Email',
            'status' => 'Статус',
            'password' => 'Пароль',
            'file' => 'Аватарка',
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email', 'status', 'password','first_name','last_name'], 'required'],
            [['username', 'email'], 'unique'],
            [['username', 'password','first_name','last_name'], 'string'],
            ['email', 'email'],
            [['status', 'gender'], 'integer'],
            ['birthday','date','format' => 'dd.mm.yyyy'],
            ['status', 'in', 'range' => self::STATUSES_NUM, 'message' => 'Вы инвалид'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp'],
            ['avatar', 'safe'],
        ];
    }

    public static function tableName() {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function upload()
    {
        if ($this->file instanceof UploadedFile) {
            $filename = md5(time()) . '.' . $this->file->extension;
            $this->file->saveAs('uploads/' . $filename, false);
            if (!is_dir('uploads/' . $this->avatar) && file_exists('uploads/' . $this->avatar)) {
                unlink('uploads/' . $this->avatar);
            }
            $this->avatar = $filename;
            return true;
        } else {
            return false;
        }
    }
}
