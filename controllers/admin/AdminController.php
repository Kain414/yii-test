<?php

namespace app\controllers\admin;

use yii\web\Controller;
use app\models\search\LogSearch;
use app\models\entry\LogEntry;
use app\models\Log;
use Yii;
use yii\filters\AccessControl;

class AdminController extends Controller {

	public $layout = 'administrative';

	 public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                        	return Yii::$app->user->identity->status == 1;
                        }
                    ],
                ],
            ],
        ];
    }

	public function actionIndex () {

        // if (Yii::$app->user->isGuest || Yii::$app->user->identity->status !== 1) {
        //     return $this->goHome();
        // }

		return $this->render('index');
		// return "Action index";
	}

	public function actionUsers () {	

        // if (Yii::$app->user->isGuest || Yii::$app->user->identity->status !== 1) {
        //     return $this->goHome();
        // }	

		return $this->render('users');
	}

	public function actionLogs () {

        // if (Yii::$app->user->isGuest || Yii::$app->user->identity->status !== 1) {
        //     return $this->goHome();
        // }

		$searchModel = new LogSearch;

		$dataProvider = $searchModel->search();
		return $this->render('logs', compact('dataProvider'));
	}

	public function actionLogSearch () {
		
        // if (Yii::$app->user->isGuest || Yii::$app->user->identity->status !== 1) {
        //     return $this->goHome();
        // }


		// var_dump(Yii::$app->request->post());
		// die;

		$model = new LogEntry();

		if ($model->load(Yii::$app->request->post()) && $model->validate()) { 
            // return $this->render('log-test', ['model' => $model]);
            Yii::$app->session->setFlash('success', 'true');
            // return $this->refresh();
        }


        if (Yii::$app->request->post('delete')) {
        	$arr = 	Yii::$app->request->post('delete');
        	$row_num = Log::deleteAll(['id' => $arr]);
        	if ($row_num != 0) {
            	Yii::$app->session->setFlash('delete', $row_num);
        	}
            return $this->refresh();
        }

		return $this->render('log-search', compact('model'));
	}

	public function actionLogAdd () {
		
        // if (Yii::$app->user->isGuest || Yii::$app->user->identity->status !== 1) {
        //     return $this->goHome();
        // }


		$model2 = new Log;
		if ($model2->load(Yii::$app->request->post()) && $model2->validate()) {
			$model2->time = Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s'));
			$model2->save();
            Yii::$app->session->setFlash('success', 'true');
            return $this->refresh();
        } else {
			return $this->render('log-add', ['model' => $model2]);
		}
	}
}
