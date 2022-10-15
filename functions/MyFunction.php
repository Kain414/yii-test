<?php

namespace app\functions;

use yii\web\Request;
use app\models\Log;
use Yii;


class MyFunction {
	public static function logAdd ($status, $user, $model = null) {
        $log = new Log();
        $req = new Request();
        $log->time = Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s'));
		$log->status = $status;
		switch ($status) {
			case 1:
		        $log->message = 'Успешная авторизация пользователя: &lt;' . $user->getName() . '&gt;';
		        $log->user_id = Yii::$app->user->id;
				break;
			case 3:
				if (! $user === null) {
		        	$log->message = 'Неуспешный вход под пользователем: &lt;' . $user->getName() . '&gt;';
		        	$log->user_id = $user->getId();
		        } else {
		        	$log->message = 'Неуспешный вход под несуществующим пользователем: &lt;' . $model->username . '&gt;';
		        	$log->user_id = 0;
		        }
				break;
			default:
				break;
		}
        $log->agent = $req->getUserAgent();
		$log->save();
	}
}