<style>
    .form-label {
            font-weight: 600;
    }
</style>    

<?php

use app\models\Blog;
use yii\helpers\Html;
use kartik\editors\Summernote;
use yii\bootstrap5\ActiveForm;

$this->title = "Добавление поста";

$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Посты', 'url' => ['/blog/posts']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => "$this->title"];

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
    </div>
</div>

<?php ActiveForm::end() ?>