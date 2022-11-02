<?php

namespace app\controllers;

use app\functions\MyFunction;
use Yii;
use app\models\Blog;
use app\models\Like;
use yii\helpers\Url;
use yii\web\Response;
use app\models\Comment;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\search\BlogSearch;
use app\models\search\CommentSearch;

class BlogController extends Controller {
    
    public $layout = 'main';

    public function actionIndex () {

        return $this->render('index');
    }

    

    public function actionPosts() {

		$searchModel = new BlogSearch();
		$dataProvider = $searchModel->searchForPublic();

		return $this->render('posts', compact('dataProvider','searchModel'));
    }

    public function actionTestPjax() {
        $res = Yii::$app->request->post('string');
        if ($res == null) {
            return $this->render('test-pjax');
        }
        return $this->render('test-pjax',compact('res'));
    }

    public function actionPostsItem($id) {

        $model = Blog::findOne(['id' => $id]);

        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

		return $this->render('posts-item', compact('searchModel', 'dataProvider', 'model'));
    }

    public function actionPutComment($id) {

        $model = new Comment();
        $model->blog_id = $id;

        return $this->renderAjax('put-comment.php', compact('model'));

    }

    public function actionPutLike() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $like = new Like();
            $like->blog_id = abs($post['id']);
            $like->user_id = Yii::$app->user->id;
            $res = $like->setStatus($post['id']);
            if (!$res) {
                $like->save();
            }
        }        
    }

    public function actionSaveComment() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->post()) {
            $comm = new Comment();
            $comm->user_id = Yii::$app->user->id;
            if ($comm->load(Yii::$app->request->post()) && $comm->save()) {
                return ['success' => true];
            }
        }
    }

    public function actionPostAdd () {

        $post = Yii::$app->request->post();

        $model = new Blog();
        $model->status = 0;

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $model->upload();
            }
            $model->author = Yii::$app->user->id;
			if ($model->save()) {
                Yii::$app->session->setFlash('success', true);
                return $this->redirect('posts');
            } else {
                Yii::$app->session->setFlash('success', false);
            }
        }

        return $this->render('post-add', compact('model'));
    }

    public function actionMyPosts() {

        $searchModel = new BlogSearch();
        $searchModel->author = Yii::$app->user->id;
        $dataProvider = $searchModel->searchForMyPosts();

        return $this->render('my-posts', compact('dataProvider', 'searchModel'));
    }

    public function actionPostControl($id) {

        $model = Blog::findOne(['id' => $id]);
        
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        if (!is_null(Yii::$app->request->post('lets-delete-post'))) {
            if (file_exists('uploads/' . $model->image)) {
                unlink('uploads/' . $model->image);
            }
            $model->delete();
            return $this->redirect('my-posts');
        }

        return $this->render('post-control',compact('searchModel', 'dataProvider', 'model'));
    }

    public function actionPostEdit($id) {

        $post = Yii::$app->request->post();

        $model = Blog::findOne(['id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $model->upload();
            }
            $model->author = Yii::$app->user->id;
            if ($model->status == 1 or $model->status == 3) {
                $model->status = 4;
            }
            $model->save();
            Yii::$app->session->setFlash('success', 'true');
            if (Yii::$app->request->post('save-and-close') === null) {
                return $this->redirect(["post-edit", 'id' => $model->id]);
            } else {
                Yii::$app->session->removeAllFlashes();
                return $this->redirect(['post-control', 'id' => $model->id]);
            }
        }

        return $this->render('post-edit', compact('model'));
    }

    public function actionPostDelete() {

        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $model = Blog::findOne(['id' => $id]);

            if (file_exists('uploads/' . $model->image)) {
                unlink('uploads/' . $model->image);
            }
            $model->delete();
        }

    }
}