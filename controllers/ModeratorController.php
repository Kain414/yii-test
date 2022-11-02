<?php

namespace app\controllers;

use Yii;
use app\models\Blog;
use yii\web\Controller;
use app\models\search\BlogSearch;
use app\models\search\CommentSearch;

class ModeratorController extends Controller {
    
    public function actionIndex() {

        return $this->render('index');
    }

    public function actionModeratorPosts() {
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->searchForModerator();

        return $this->render('moderator-posts', compact('dataProvider', 'searchModel'));
    }

    public function actionModeratorPostControl($id) {
        
        $model = Blog::findOne(['id' => $id]);


        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post())) {
                $all_good = true;
                switch (Yii::$app->request->post('post-allow')) {
                    case 1: $model->status = 1; break;
                    case 2: $model->status = 2; break;
                    case 3: $model->status = 3; break;
                    default: $all_good = false;
                }
                if ($all_good) {
                    if ($model->save()) {
                        return $this->redirect('/moderator/moderator-posts');
                    }                    
                }
            }
        }

        return $this->render('moderator-post-control',compact('model'));
    }
}