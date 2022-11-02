<?php

namespace app\controllers\admin;

use Yii;
use app\models\Log;
use app\models\Blog;
use app\models\User;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\entry\LogEntry;
use yii\filters\AccessControl;
use app\models\search\LogSearch;
use app\models\search\BlogSearch;
use app\models\search\UserSearch;

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

        // var_dump(Yii::$app->request->post());
        // die;

        if (Yii::$app->request->post('delete_ids')) {
            //var_dump(Yii::$app->request->post('delete_ids'));
            $arr = 	Yii::$app->request->post('delete_ids');
        	User::deleteAll(['id' => $arr]);
            return $this->refresh();
        }
        
		$searchModel = new UserSearch();

		$dataProvider = $searchModel->search(Yii::$app->request->get());
		return $this->render('users', compact('dataProvider','searchModel'));
	}

    public function actionUsersUpdate ($id) {
        
        $model = User::findOne(['id' => $id]);

        $post = Yii::$app->request->post();

        if ($post != null) {
            if ($model->load($post) && $model->save()) {
            	Yii::$app->session->setFlash('save', true);
                if (Yii::$app->request->post('save-and-close') === null) {
                    return $this->redirect(["users-update", 'id' => $id]);
                } else {
                    Yii::$app->session->removeAllFlashes();
                    return $this->redirect(["users", 'id' => $id]);
                }
            } else {
            	Yii::$app->session->setFlash('save', false);
            }
        }

        if ($model) {
		    return $this->render('users-update', compact('model'));
        } else {
            return $this->redirect('users');
        }
    }

    public function actionUsersAdd () {

        $post = Yii::$app->request->post();

        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$model->password = Yii::$app->security->generatePasswordHash($post['User']['password']);
            $model->access_token = Yii::$app->getSecurity()->generateRandomString();
            $model->created_at = Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s'));
            $model->update_at = Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s'));
			$model->save();
            Yii::$app->session->setFlash('success', 'true');
            if (Yii::$app->request->post('save-and-close') === null) {
                return $this->redirect(["users-update", 'id' => $model->id]);
            } else {
                Yii::$app->session->removeAllFlashes();
                return $this->redirect(["users"]);
            }
        }

        return $this->render('users-add', compact('model'));
    }

    public function actionUsersDelete ($id) {

        User::deleteAll(['id' => $id]   );

        return $this->redirect('users');
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

    public function actionBlogs () {

        if (Yii::$app->request->post('delete_ids')) {
            //var_dump(Yii::$app->request->post('delete_ids'));
            $arr = 	Yii::$app->request->post('delete_ids');
        	Blog::deleteAll(['id' => $arr]);
            return $this->refresh();
        }
        
		$searchModel = new BlogSearch();

		$dataProvider = $searchModel->search(Yii::$app->request->get());
		return $this->render('blogs', compact('dataProvider','searchModel'));
    }

    public function actionBlogsAdd () {

        $post = Yii::$app->request->post();

        $model = new Blog();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $model->upload();
            }
            $model->author = Yii::$app->user->id;
            $model->status = 1;
			$model->save();
            Yii::$app->session->setFlash('success', 'true');
            if (Yii::$app->request->post('save-and-close') === null) {
                return $this->redirect(["blogs-update", 'id' => $model->id]);
            } else {
                Yii::$app->session->removeAllFlashes();
                return $this->redirect(["blogs"]);
            }
        }

        return $this->render('blogs-add', compact('model'));
    }

    public function actionBlogsUpdate ($id) {
        
        $model = Blog::findOne(['id' => $id]);

        $post = Yii::$app->request->post();

        if ($post != null) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $model->upload();
            }
            if ($model->load($post) && $model->save()) {
            	Yii::$app->session->setFlash('save', true);
                if (Yii::$app->request->post('save-and-close') === null) {
                    return $this->redirect(["blogs-update", 'id' => $id]);
                } else {
                    Yii::$app->session->removeAllFlashes();
                    return $this->redirect(["blogs", 'id' => $id]);
                }
            } else {
            	Yii::$app->session->setFlash('save', false);
            }
        }

        if ($model) {
		    return $this->render('blogs-update', compact('model'));
        } else {
            return $this->redirect('blogs');
        }
    }

}
