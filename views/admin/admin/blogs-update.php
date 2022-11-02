<style>
    .form-label {
            font-weight: 600;
    }
</style>    

<?php

use app\models\Blog;
use Faker\Core\Color;
use yii\helpers\Html;
use kartik\editors\Summernote;
use yii\bootstrap5\ActiveForm;

$this->title = "Изменение поста ";

$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Админка', 'url' => ['/admin/admin']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Посты', 'url' => ['/admin/admin/blogs']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => "$this->title"];


if (Yii::$app->session->getFlash('save') != null) {
    $result = Yii::$app->session->hasFlash('save');
    if ($result) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?="Запись сохранена"?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
    } else { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?="Запись не сохранена"?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
    }
}

?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'title')->textInput() ?>
<?= $form->field($model, 'body')->widget(Summernote::class, [
    'useKrajeePresets' => true,
    // other widget settings
]); ?>
<?= $form->field($model, 'file')->fileInput() ?>
<?= $form->field($model, 'open')->dropDownList(Blog::OPENS) ?>
<div class="row">
    <div class="col-8">
        <?= Html::submitButton('Применить', ['class' => "btn btn-success"]) ?>
        <?= Html::submitButton('Применить и закрыть', ['class' => "btn btn-primary", 'name' => 'save-and-close']) ?>
    </div>
</div>

<?php ActiveForm::end() ?>