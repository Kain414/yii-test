<style>
    .form-label {
            font-weight: 600;
    }
</style>    

<?php

use app\models\User;
use Faker\Core\Color;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = "Изменение пользователя ";

$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Админка', 'url' => ['/admin/admin']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Пользователи', 'url' => ['/admin/admin/users']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link} <b style=\"color: green;\">$model->username </b></li>\n", 'label' => "$this->title"];


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

<?= $form->field($model, 'username')->textInput() ?>
<?= $form->field($model, 'email')->input('email') ?>
<?= $form->field($model, 'status')->dropDownList(User::STATUSES) ?>
<div class="row">
    <div class="col-8">
        <?= Html::submitButton('Применить', ['class' => "btn btn-success"]) ?>
        <?= Html::submitButton('Применить и закрыть', ['class' => "btn btn-primary", 'name' => 'save-and-close']) ?>
    </div>
</div>

<?php ActiveForm::end() ?>