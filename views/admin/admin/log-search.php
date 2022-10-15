<?php

use app\models\Log;
use yii\widgets\ActiveForm;	
use yii\helpers\Html;
use yii\helpers\BaseHtml;
use yii\bootstrap5\Breadcrumbs;


$this->title = 'Журнал авторизаций';

$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Админка', 'url' => ['/admin/admin']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => "$this->title"];

?>

<?php
$data = Log::find();

if ($model->id != "") {
	$data->where("id=$model->id");
}
if ($model->status != "") {
	$data->andwhere("status=$model->status");
}
if ($model->user_id != "") {
	$data->andwhere("user_id=$model->user_id");
}
if ($model->message != "") {
	$data->andwhere(['like', 'message', $model->message]);
}

$done = $data->all();
// $data->andWhere('status=4');
$result = Yii::$app->session->hasFlash('success');
if ($result) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
	  <?="Совпадений надйено:  " . count($done)?>
	  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php
}

$result = Yii::$app->session->getFlash('delete');
if ($result !== null) { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
	  <?="Строк удалено:  " . $result?>
	  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php
}

?>

<style type="text/css">
	.user-agent {
	  white-space: nowrap;
	  overflow: hidden;
	  text-overflow: ellipsis;
	  width: 250px;
	}

	[data-tooltip] {
		position: relative;
	}
	[data-tooltip]::after {
		content: attr(data-tooltip); /* Выводим текст */
		z-index: 50; 
		position: absolute; /* Абсолютное позиционирование */
		width: 300px; /* Ширина подсказки */
		left: -50%; top: 50px; /* Положение подсказки */
		background: #3989c9; /* Синий цвет фона */
		color: #fff; /* Цвет текста */
		padding: 0.5em; /* Поля вокруг текста */
		box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); /* Параметры тени */
		pointer-events: none; /* Подсказка */
		opacity: 0; /* Подсказка невидима */
		transition: 1s; /* Время появления подсказки */
	} 
	[data-tooltip]:hover::after {
		opacity: 1; /* Показываем подсказку */
		top: 2em; /* Положение подсказки */
	}
	/* width */
	::-webkit-scrollbar {
	  width: 10px;
	}

	/* Track */
	::-webkit-scrollbar-track {
	  background: #f1f1f1;
	}

	/* Handle */
	::-webkit-scrollbar-thumb {
	  background: #888;
	}

	/* Handle on hover */
	::-webkit-scrollbar-thumb:hover {
	  background: #555;
	}

	.table_list {
		display: block;
		height: 500px;
		overflow-y: scroll;
		margin-bottom: 20px;
	}

	.bg-secondary {
		background-color: $gray-200;
	}
</style>
<?php $form = ActiveForm::begin([
    'method' => 'post',
	]); ?>
<div class="p-3 mb-2 rounded" style="border: 2px solid; background-color: #333;">
<div style="padding: 10px; border: 2px solid; background-color: white;" class="rounded">
<table class="table" style="width: 100%;" name="">
<h3 style="text-align: center; text-shadow: 1px 1px 2px black">Поиск</h3>
	<tr>
		<th><?= $form->field($model, 'id')?></th>
		<th><?= $form->field($model, 'status')->label('Статус')?></th>
		<th><?= $form->field($model, 'message')->label('Сообщение')?></th>
		<th><?= $form->field($model, 'user_id')->label('ID пользователя')?></th>
		<th class="form-group" style="vertical-align: bottom;">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
    	</th>
	</tr>
</table>
</div>
</br>
<?php ActiveForm::end(); ?>
<div style="padding: 10px; border: 2px solid; background-color: white;" class="rounded">
<div class="table_list">
<table class="table">
<tr id="tr_top" class="" style="background-color: white; position: -webkit-sticky; position: -moz-sticky; position: -ms-sticky; position: -o-sticky; position: sticky; top: 0px; z-index: 200"><th><?= Html::img('/images/trash-can_24x24.png');?></th><th>ID</th><th>Время</th><th>Статус</th><th>Сообщение</th><th>Пользователь</th><th>Агенты</th></tr>
<?= Html::beginForm('log-search', 'post');
foreach ($done as $row) {
	// var_dump($row);
?>
	<?php
	switch ($row->status) {
		case 4:
			?> <tr class="table-danger"> <?php
			break;
		case 3:
			?> <tr class="table-warning"> <?php
			break;
		case 2:
			?> <tr class="table-secondary"> <?php
			break;
		case 1:
			?> <tr class="table-info"> <?php
			break;
		default:
			?> <tr class="table-light"> <?php
			break;
	}
	?>
	<!-- <th><input name="delete[]" value=<?= $row->id ?> type="checkbox"></th> -->
	<th style="text-align:center;">
		<div class="form-check">
			<input class="form-check-input" name="delete[]" type="checkbox" value=<?= $row->id ?> id="flexCheckDefault">
			<!-- <label class="form-check-label" for="flexCheckDefault">Default checkbox</label> -->
		</div>
	</th>
	<!-- <th><?=Html::checkbox("delete[]", false, ['value' => $row->id]);?></th> -->
	<th><?= $row->id; ?></th>
	<th style="width: 225px;"><?= Yii::$app->formatter->format($row->time, 'datetime'); ?></th>
	<th><?= $row->status; ?></th>
	<th><?= $row->message; ?></th>
	<th><?= $row->user_id; ?></th>
	<th data-tooltip="<?= $row->agent; ?>"><div class="user-agent"><?= $row->agent; ?></div></th>
</tr><?php
}
?>
</table>
</div>
<?= Html::submitButton('Удалить', ['class' => 'btn btn-primary']) ?>
<?= Html::endForm() ?>
<button onclick="location.href = 'log-add';" class="btn btn-success" style="margin-left: 10px;">Добавить новую запись</button>
</div>
</div>
<?php

// if (empty($done)) {
// 	echo 'Данных нет';
// } else {
// 	var_dump($done);
// }
