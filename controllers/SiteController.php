<?php

namespace app\controllers;

use Yii;
use app\models\Blog;
use app\models\Like;
use app\models\User;
use yii\web\Response;
use app\models\Comment;
use yii\web\Controller;
use app\models\LoginForm;
use yii\web\UploadedFile;
use app\models\ContactForm;
use yii\filters\VerbFilter;
use app\functions\MyFunction;
use yii\filters\AccessControl;
use app\models\RegistrationForm;
use app\models\search\BlogSearch;
use app\models\search\CommentSearch;
use app\models\search\UserSearch;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect('/blog/posts');
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {

        // $this->layout = 'main';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = $model->getUser();
            MyFunction::logAdd(1, $user);
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignUp () {

        $model = new RegistrationForm();

        if ($model->load(Yii::$app->request->post()) && $model->registration()) {
            return $this->redirect('login');
        }

        return $this->render('sign-up', compact('model'));
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSay($message = 'Привет')
    {
        return $this->render('say', ['message' => $message]);
    }

    public function actionCabinet() {

        $user = User::findOne(Yii::$app->user->id);
        $user->birthday = Yii::$app->formatter->asDate($user->birthday, 'dd.MM.yyyy');
        if ($user->load(Yii::$app->request->post())) {
            $user->file = UploadedFile::getInstance($user, 'file');
            if ($user->validate()) {
                $user->upload();
                $user->birthday = Yii::$app->formatter->asDate($user->birthday, 'yyyy-MM-dd');
                $user->save(false);
                return $this->refresh();
            }
        }
        
        return $this->render('/site/cabinet',compact('user'));
    }
}
