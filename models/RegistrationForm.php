<?php

namespace app\models;

use yii\base\Model;
use Yii;

class RegistrationForm extends Model {

    public $username;
    public $password;
    public $email;
    public $first_name;
    public $last_name;
    public $gender;
    public $birthday;

    public function rules () {
        return [
            [['username','password','email','first_name','last_name'],'required'],
            [['username','first_name','last_name'],'string'],
            ['email','email'],
            ['gender','integer'],
            ['birthday','date','format' => 'dd/mm/yyyy'],
            ['username','validateUsername'],
            ['email','validateEmail'],
        ];
    }

    public function validateUsername ($attribute) {
        $user = User::find()->where(['username' => $this->username])->exists();
        if ($user) {
            $this->addError($attribute, 'Данный логин занят.');
        }
    }

    public function validateEmail ($attribute) {
        $user = User::find()->where(['email' => $this->email])->exists();
        if ($user) {
            $this->addError($attribute, 'Данный email занят.');
        }
    }
    
    public function registration() {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        $user->access_token = Yii::$app->security->generateRandomString();
        $user->created_at = time();
        $user->update_at = time();
        $user->status = User::STATUS_USER;
        $user->last_visit = time();
        $user->birthday = Yii::$app->formatter->asDate($this->birthday, 'yyyy-MM-dd');
        $user->gender = $this->gender;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->legitimacy = 0;

        return $user->save();
    }
}