<?php

use app\models\User;
use PhpParser\Node\Stmt\Switch_;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\grid\ActionColumn;


$this->title = 'Пользователи';

$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Админка', 'url' => ['/admin/admin']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => "$this->title"];

?>

<div class="card mt-2 mb-2">
<div class="card-header">
<div class="post-search">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>
    <table style="width: 100%;">
        <tr>
            <th><?= $form->field($searchModel, 'id')->label('ID') ?></th>

            <th><?= $form->field($searchModel, 'username')->label('Имя пользователя') ?></th>

            <th><?= $form->field($searchModel, 'email')->label('Email') ?></th>

            <th><?= $form->field($searchModel, 'status')->dropDownList(User::STATUSES,['prompt' => 'Все']) 
            ?></th>

            <th style=""><div class="form-group" style="margin-left: 20px; margin-top: 15px;">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            </div></th>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>
</div>
</div>
<div class="card-body">
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'name' => 'delete_ids[]',
            'checkboxOptions' => function ($model) {
                return ['form'=>'delete-user-form','value' => $model->id];
            }
        ],
        'id:integer',
        'username:text',
        'email:email',
        'created_at:datetime',
        'update_at:datetime',
        [
            'attribute' => 'status',
            'value' => function ($model) {
                return User::STATUSES[$model->status] ?? 'Неизвестный';
            },
        ],
        //'status:integer',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'update') {
                        $url = Url::to(['users-update', 'id' => $model->id]);
                        return $url;
                }

                if ($action === 'delete') {
                    $url = Url::to(['users-delete', 'id' => $model->id]);
                        return $url;
                }
            }
        ],
    ],
]);

?>
    </div>
</div>

<div class="row">
        <div class="col-1">
            <?= Html::beginForm('users','POST',['id' => 'delete-user-form']) ?>
                <?= Html::submitButton('Удалить', ['class' => 'btn btn-danger']);?>
            <?= Html::endForm() ?>
        </div>
        <div class="col-3">
            <?= Html::a(Html::button('Добавить пользователя', ['class' => 'btn btn-success']), 'users-add'); ?>
        </div>
</div>