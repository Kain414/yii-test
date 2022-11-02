<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Comment extends ActiveRecord {

    public function behaviors() {
        return [
            TimestampBehavior::class,
        ];
    }

    public function attributeLabels() {
        return [
            'comment' => 'Комментарий',
            'blog_id' => 'Пост',
            'user_id' => 'Пользователь',
            'like' => 'Лайк',
            'dislike' => 'Дизлайк',
        ];
    }

    public static function tableName() {
		return 'comments';
	}

    public function rules() {
        return [
        	[['comment', 'user_id', 'blog_id'], 'required'],
            [['comment'],'string'],
            [['user_id', 'blog_id', 'answer'],'integer'],
        ];
    }

    
    public function getUserModel() {

        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getAuthorName($id) {

        $model = static::findOne(['id' => $id]);
        return $model->userModel->firstName . ' ' . $model->userModel->lastName;
    }
}