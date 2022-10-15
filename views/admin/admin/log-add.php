<?php

use yii\bootstrap5\Breadcrumbs;
use yii\widgets\ActiveForm;	
use yii\helpers\Html;

$this->title = 'Добавление логов';

$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Админка', 'url' => ['/admin/admin']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Журнал авторизаций', 'url' => ['/admin/admin/log-search']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => "$this->title"];

?>

<?php $form = ActiveForm::begin([
    'method' => 'post',
	]); ?>
<div style="padding: 10px; border: 2px solid;" class="rounded">
<table class="table table-striped table-dark" style="width: 100%;" name="">
<h3 style="text-align: center; text-shadow: 1px 1px 2px black">Новая запись</h3>
	<tr>
		<th><?= $form->field($model, 'id')?></th>
		<th><?= $form->field($model, 'status')->label('Статус')?></th>
		<th><?= $form->field($model, 'message')->label('Сообщение')?></th>
		<th><?= $form->field($model, 'user_id')->label('ID пользователя')?></th>
		<th class="form-group" style="vertical-align: bottom;">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    	</th>
	</tr>
</table>
</div>
</br>
<?php ActiveForm::end();

$result = Yii::$app->session->hasFlash('success');
if ($result) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
	  <?="Запись успешно создана"?>
	  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php
}