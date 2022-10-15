<?php
use yii\grid\GridView;
use yii\grid\SerialColumn;
?>
<div style="width: 70%;">
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
	    ['class' => SerialColumn::class],
	    'id',
	    [
		    'attribute' => 'time',
		    'format' => 'datetime',
		    'headerOptions' => [
		        'style' => ['width' => '200	px'],
		    ]
	    ],
	    'status',
	    'message',
	],

]);
?>
</div>