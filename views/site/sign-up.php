<?php
use app\models\User;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\bootstrap5\ActiveForm;

$this->title='Регистрация';
?>

<div class="card w-50 mx-auto">
    <div class="card-header w-100" style="text-align: center;"><h3>Заполните поля</h3></div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput() ?>
        <?= $form->field($model, 'email')->input('email') ?>
        <?= $form->field($model, 'password')->input('password') ?>
        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'first_name')->textInput() ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'last_name')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <?= $form->field($model, 'gender')->dropDownList(User::GENDERS) ?>
            </div>
            <div class="col-8">
                <?= $form->field($model, 'birthday')->widget(DatePicker::class, [
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
                <?= Html::submitButton('Зарегистрироваться', ['class' => "btn btn-success w-100 mt-4"]) ?>
            </div>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>