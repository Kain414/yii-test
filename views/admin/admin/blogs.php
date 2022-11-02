<style>
.blog-body {
	  white-space: nowrap;
	  overflow: hidden;
	  text-overflow: ellipsis;
	  width: 250px;
	}
</style>

<?php

use app\models\Blog;
use PhpParser\Node\Stmt\Switch_;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\grid\ActionColumn;


$this->title = 'Посты';

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
            <th><?= $form->field($searchModel, 'id') ?></th>

            <th><?= $form->field($searchModel, 'title') ?></th>

            <th><?= $form->field($searchModel, 'body') ?></th>

            <th><?= $form->field($searchModel, 'author') ?></th>

            <th><?= $form->field($searchModel, 'open')->dropDownList(Blog::OPENS,['prompt' => 'Все']) 
            ?></th>

            <th style=""><div class="form-group" style="margin-left: 20px; margin-top: 15px;">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            </div></th>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>
</div>
</div>
<div class="card-body" style="overflow-x: auto; width: 100%;">
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'name' => 'delete_ids[]',
            'checkboxOptions' => function ($model) {
                return ['form'=>'delete-blog-form','value' => $model->id];
            }
        ],
        'id:integer',
        [
            'attribute' => 'title',
            'format' => 'html',
            'value' => function (Blog $model) {
                return Html::tag('div', $model->title, ['class' => '', 'style' => 'width: 230px;']);
            }
        ],
        [
            'attribute' => 'body',
            'format' => 'html',
            'value' => function (Blog $model) {
                return Html::tag('div', $model->body, ['class' => 'blog-body']);
            }
        ],
        [
            'attribute' => 'image',
            'format' => 'html',
            'value' => function ($model) {
                return Html::img('/uploads/' . $model->image, ['style' => 'width: 128px; height: 128px; object-fit: cover;', 'class' => 'img-thumbnail rounded']);
            },
        ],
        [
            'attribute' => 'author',
            'format' => 'html',
            'value' => function (Blog $model) {
                return Html::tag('div', $model->authorModel->username, ['class' => '', 'style' => 'width: 66px;']);
            }
        ],
        // 'created_at:datetime',
        // 'updated_at:datetime',
        'likes:integer',
        'dislikes:integer',
        'views:integer',
        [
            'attribute' => 'open',
            'value' => function ($model) {
                return Blog::OPENS[$model->open] ?? 'Неизвестный';
            },
        ],
        //'status:integer',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'update') {
                        $url = Url::to(['blogs-update', 'id' => $model->id]);
                        return $url;
                }

                if ($action === 'delete') {
                    $url = Url::to(['blogs-delete', 'id' => $model->id]);
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
            <?= Html::beginForm('blogs','POST',['id' => 'delete-blog-form']) ?>
                <?= Html::submitButton('Удалить', ['class' => 'btn btn-danger']);?>
            <?= Html::endForm() ?>
        </div>
        <div class="col-3">
            <?= Html::a(Html::button('Добавить пост', ['class' => 'btn btn-success']), 'blogs-add'); ?>
        </div>
</div>