<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class User extends ActiveRecord  implements \yii\web\IdentityInterface
{
    public $authKey;

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
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email', 'status', 'password'], 'required'],
            [['username', 'email'], 'unique'],
            [['username', 'password'], 'string'],
            ['email', 'email'],
            ['status', 'integer'],
            ['status', 'in', 'range' => self::STATUSES_NUM, 'message' => 'Вы инвалид'],
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
}
