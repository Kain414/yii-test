<style>
    .card .btn {
        border-radius: 0 !important;
    }
</style>

<?php

use yii\bootstrap5\Html;



$this->title = "Модерация";
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => "$this->title"];
?>

<div class="d-flex justify-content-center">
    <div class="card shadow w-75">
        <div class="card-body d-flex flex-column align-items-center">
            <div class="w-75 mb-3" style="height: 50px;"><?= Html::a(Html::button('Посты на модерацию',['class' => 'btn btn-outline-primary shadow-sm w-100 h-100']),'/moderator/moderator-posts') ?></div>
            <div class="w-75 mb-3" style="height: 50px;"><?= Html::a(Html::button('Обращения',['class' => 'btn btn-outline-primary shadow-sm w-100 h-100'])) ?></div>
            <div class="w-75 mb-3" style="height: 50px;"><?= Html::a(Html::button('Уведомления',['class' => 'btn btn-outline-primary shadow-sm w-100 h-100'])) ?></div>
        </div>
    </div>
</div>