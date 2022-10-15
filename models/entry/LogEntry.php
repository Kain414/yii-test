<?php

namespace app\models\entry;

use yii\base\Model;

class LogEntry extends Model
{
    public $id;
    public $status;
    public $message;
    public $user_id;

    public function rules()
    {
        return [
            [['id', 'status', 'user_id'], 'integer'],
            ['message', 'safe'],
        ];
    }
}