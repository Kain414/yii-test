<style>
    .image-hover .like-on {
        display: none;
    }
    .image-hover:hover .like-off {
        display: none;
    }
    .image-hover:hover .like-on {
        display: block;
    }    

    .btn-control {
        width: 250px;
        height: 50px;
        font-size: 18px;
        border-radius: 0 !important;
    }

    .card {
        border-radius: 0 !important;
    }
</style>

<?php

use app\models\Blog;
use app\models\Like;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\ActiveForm;

$comments = $dataProvider->models;


$this->title = "Управление постом";
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Посты', 'url' => ['/blog/posts']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Мои публикации', 'url' => ['/blog/my-posts']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => "$this->title"];

?>
<div class="d-flex">
    <div class="card mt-4 me-4 shadow" style="height: 100%; width: 285px;">
        <div class="card-header">Панель управления</div>
        <div class="card-body">
            <div class="d-flex-column">
                <div><i class="fa-regular fa-face-smile me-2 mb-1" title="Лайки"></i><?= $model->likes ?></div>
                <div><i class="fa-regular fa-face-frown me-2 mb-1" title="Дизайки"></i><?= $model->dislikes ?></div>
                <div><i class="fa-sharp fa-solid fa-eye me-2 mb-1" title="Просмотры"></i><?= $model->views ?></div>
                <?php switch ($model->status) {
                case 0: echo "<div class='card text-white bg-info mt-3' style='border-radius: 0 !important; height: 48px;'>"; break;
                case 1: echo "<div class='card text-white bg-success mt-3' style='border-radius: 0 !important; height: 48px;'>"; break;
                case 2: echo "<div class='card text-white bg-danger mt-3' style='border-radius: 0 !important; height: 48px;'>"; break;
                case 3: echo "<div class='card text-white bg-warning mt-3' style='border-radius: 0 !important; height: 48px;'>"; break;
                default: echo "<div class='card mt-3' style='border-radius: 0 !important; height: 48px;'>";
                } ?>
                    <div class="card-body p-1" style="font-size: 12px;">
                        <?= Blog::STATUSES[$model->status]; ?>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-header">Комментарий модераторов</div>
                    <div class="card-body"  style="height: 225px; overflow-y: scroll;">
                        <?= $model->info ?>
                    </div>
                </div>
                <?= Html::a(Html::button('Редактировать',['class' => 'btn btn-outline-primary btn-control mt-2']),Url::to(['post-edit', 'id' => $model->id])) ?>
                <?= Html::submitButton('Удалить',['class' => 'btn btn-outline-danger btn-control mt-2', 'id' => 'delete-post-btn']) ?>
            </div>
        </div>
    </div>
    <div class="w-50">
        <div class="card mb-4 mt-4 shadow">
            <div class="card-header"><h4><?=$model->title?></h4></div>
            <div class="card-body">
                <div style="text-align: center;"><?=Html::img('/uploads/' . $model->image, ['style' => 'width: 50%; object-fit: cover;', 'class' => 'rounded']) ?></div>
                </br>
                <?=$model->body ?>
            </div>
        </div>

        <div class="card mb-4 mt-4 shadow">
            <div class="card-header"><h4>Комментарии</h4></div>
                <div class="card-body p-2">
                <?php Pjax::begin([
                    'id' => 'update-comments',
                ]) ?>
                <?php 
                    foreach ($comments as $comment) { ?>
                    <div class="ps-2 pe-2 m-2 rounded bg-dark">
                        <div class="d-flex">
                            <div class="me-2 pt-2">
                                <?php $path_to_img = '/uploads/' . $comment->userModel->avatar ?>
                                <?= Html::img($path_to_img, ['style' => 'width: 80px; height: 80px; object-fit: cover;', 'class' => 'rounded shadow'])?>
                            </div>
                            <div class="card shadow mb-2 mt-2 w-100">
                                <div class="card-header pt-0 pb-0 ps-3" style="font-size: 14px; color: DarkCyan;"><strong><?= $comment->userModel->first_name . " " . $comment->userModel->last_name; ?></strong></div>
                                <div class="card-body">
                                    <?= $comment->comment ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div>
                    <?= LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
                    ]); ?>
                </div>
                <?php Pjax::end(); ?>
                </div>
        </div>
    </div>
</div>

<?php 
Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modal',
    'size' => 'modal-sm',
]);
echo "<div id='modelHeader' style='text-align:center'><h3>Удаление поста</h3></br></div><div id='modalContent'><div style='text-align:center'>"
    . "<h6>Вы точно хотите удалить данный пост? Его удаление перманентное и восстановлению подлежать не будет.</h6></br>"
    . "<div class='d-flex justify-content-center'>"
    . Html::beginForm('','post')
    . Html::submitbutton('Да, удалить пост',['class' => 'btn btn-outline-danger ms-2', 'name' => 'lets-delete-post'])  
    . Html::endForm()
    . "</div></div></div>";

Modal::end(); 

$js1 = <<< JS
$(function() {
    $(document).on('click', '#delete-post-btn', function() {
         if ($('#modal').hasClass('in')) {
             $('#modal').find('#modalContent')
                 .load($(this).attr('value'));
             document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
         } else {
             $('#modal').modal('show')
                 .find('#modalContent')
                 .load($(this).attr('value'));
             document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        }
    });
});
JS;

$this->registerJs($js1);

?>

