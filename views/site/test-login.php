<?php

use yii\helpers\Html;
use yii\grid\GridView;



?>

<?=Html::beginForm(['site/index'],'post');?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
    	[
    		'class' => DataColumn::class,
    		'attribute' => 'username',
    		'format' => 'text',
    		'label' => 'Имя пользователя',
    	],
    	[
    		'class' => DataColumn::class,
    		'attribute' => 'password',
    		'format' => 'text',
    		'label' => 'Пароль',
    	],
    	[
    		'class' => CheckboxColumn::class,
    		'attribute' => 'rememberMe',
    		'label' => 'Запомнить меня',
    	]
    ],
]) ?>

<?=Html::endForm();?>