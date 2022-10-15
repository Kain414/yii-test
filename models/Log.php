<?php

namespace app\models;

use yii\db\ActiveRecord;

class Log extends ActiveRecord {

	public static function tableName() {
		return 'access_logs';
	}

    public function rules()
    {
        return [
        	[['status', 'message', 'user_id'], 'required'],
            [['id', 'status', 'user_id'], 'integer'],
            ['message', 'safe'],
        ];
    }
}