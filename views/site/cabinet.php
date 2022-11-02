<?php

use app\models\User;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\bootstrap5\ActiveForm;


?>
<style>
.datepicker .datepicker-dropdown {
    z-index: 1500 important;
}
</style>

<div class="card w-75 mx-auto" style="margin-top: 50px;">
    <div class="card-header" style=""><h3>Настройки пользователя <?= $user->username?></h3></div>
    <div class="card-body">
    <?php
    if (!empty($user->avatar)) { ?>
        <div>
        <?= Html::img('/uploads/' . $user->avatar, ['style' => 'width: 256px; height: 256px; object-fit: cover;', 'class' => 'img-thumbnail rounded']); ?>
        </div>
    <?php
    }
    ?>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field($user, 'file')->fileInput() ?>
    <?= $form->field($user, 'email')->input('email') ?>
    <div class="row">
        <div class="col-6">
            <?= $form->field($user, 'first_name')->textInput() ?>
        </div>
        <div class="col-6">
            <?= $form->field($user, 'last_name')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <?= $form->field($user, 'gender')->dropDownList(User::GENDERS) ?>
        </div>
        <div class="col-8">
            <?= $form->field($user, 'birthday')->widget(DatePicker::class, [
                    'options' => ['placeholder' => 'Enter birth date ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ])?>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?= Html::submitButton('Сохранить', ['class' => "btn btn-success w-100 mt-4"]) ?>
        </div>
    </div>

    <?php ActiveForm::end() ?>
    </div>
</div>