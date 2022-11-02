<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.2.0/css/all.css">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
    ]);

    $items = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $items = array_merge($items,[['label' => 'Login', 'url' => ['/site/login']]]);
        $items = array_merge($items,[['label' => 'Sign up', 'url' => ['/site/sign-up']]]);
    } else {
        $logout =
           '<li class="nav-item">'
            . Html::beginForm(['/site/logout'])
            . Html::submitButton(
                'Выйти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'nav-link btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
            
        $items = array_merge($items, [$logout]);
        if (Yii::$app->user->identity->status == 1) {
            $items = array_merge($items,[['label' => 'Админка', 'url' => ['/admin/admin']]]);
        }
        if (Yii::$app->user->identity->status == 3) {
            $items = array_merge($items,[['label' => 'Модерация', 'url' => ['/moderator/index']]]);
        }
        
        // $cabinet =
        //     '<li class="nav-item" style="float:right">'
        //     . Html::a(Html::button('Кабинет', ['class' => 'btn btn-secondary']), '/site/cabinet')
        //     . '</li>';
        // $items = array_merge($items, [$cabinet]);
    }
    // var_dump($items);
    // die;
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $items,
    ]);
    
    ?>
    <?php 
    // var_dump(Yii::$app->user->identity);
    // die;
    if (!Yii::$app->user->isGuest) {
    ?>
    <div class="text-right" style="display: block; flex: 1;">
    <li class="nav-item d-flex" style="float:right;"> 
    <?= Html::a(Html::button('Кабинет', ['class' => 'btn btn-secondary me-1']), '/site/cabinet') ?>
    <?php if (file_exists('uploads/' . Yii::$app->user->identity->avatar) && !is_dir('uploads/' . Yii::$app->user->identity->avatar)) { ?>
    <div>
        <?= Html::img('/uploads/' . Yii::$app->user->identity->avatar,['class' => 'rounded-circle shadow', 'style' => 'width: 38px; height: 38px; object-fit: cover;']) ?>
    </div>
    <?php } else { ?>
    <div>
    <?= Html::img('/uploads/no_avatar.png',['class' => 'rounded-circle shadow', 'style' => 'width: 38px; height: 38px; object-fit: cover;']) ?>
    </div>
    <?php } ?>
    </li>
    </div>
    <?php } ?>

    <?php NavBar::end();?>

</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <div class="rounded ps-3 pt-3 pb-1 mt-2 mb-2 shadow">
            <h6><?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?></h6>
            </div>
        <?php endif ?>
        <!-- <?= Alert::widget() ?> -->
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; My Company <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
