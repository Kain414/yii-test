<style>
    .image-hover .like-on {
        display: none;
    }
    .image-hover:hover .like-off {
        display: none;
    }
    .image-hover:hover .like-on {
        display: block;
    }    

    .btn-control {
        width: 250px;
        height: 50px;
        font-size: 18px;
        border-radius: 0 !important;
    }

    .card {
        border-radius: 0 !important;
    }
</style>

<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;


$this->title = "Просмотр поста";
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Модерация', 'url' => ['/moderator/index']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Посты на публикацию', 'url' => ['/moderator/moderator-posts']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => "$this->title"];

?>
<div class="d-flex justify-content-center">
    <div class="w-50">
        <div class="card shadow">
            <div class="card-header"><h4><?=$model->title?></h4></div>
            <div class="card-body">
                <?php if (!is_dir('uploads/' . $model->image) && file_exists('uploads/' . $model->image)) {?>
                    <div style="text-align: center;"><?=Html::img('/uploads/' . $model->image, ['style' => 'width: 50%; object-fit: cover;', 'class' => 'rounded']) ?></div>
                    </br>
                <?php } ?>
                <?=$model->body ?>
            </div>
        </div>
        <?php $form = ActiveForm::begin(['method' => 'post']); ?>
        <div class="card shadow mt-2 mb-2">
            <div class="card-header" style="text-align: center;"><h5>Комментарий модератора</h5></div>
            <div class="card-body">
                    <?= $form->field($model, 'info')->textarea(['rows' => 4])->label(false); ?>
            </div>
        </div>
        <div class="card shadow ">
            <div class="card-header" style="text-align: center;"><h5>Решение</h5></div>
            <div class="card-body d-flex justify-content-between">
                    <?= Html::submitButton('Разршить к публикации',['class' => 'btn btn-success', 'name' => 'post-allow', 'value' => 1])?>
                    <?= Html::submitButton('На редактирование',['class' => 'btn btn-warning', 'name' => 'post-allow', 'value' => 3])?>
                    <?= Html::submitButton('Запретить к публикации',['class' => 'btn btn-danger', 'name' => 'post-allow', 'value' => 2])?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>