<style>
.blog-body {
	  white-space: nowrap;
	  overflow: hidden;
	  text-overflow: ellipsis;
	  width: 250px;
	}

.post-btn {
    border-radius: 0 !important;
}


.post-title {
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
	height: 48px;
   }
</style>
<?php

$posts = $dataProvider->models;

$this->title = "Мои публикации";
?> 

<?php

use app\models\Blog;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;

use yii\bootstrap5\LinkPager;
use function PHPUnit\Framework\fileExists;

$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Посты', 'url' => ['/blog/posts']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => "$this->title"];
?>

<div class="d-flex">
    <div class="card w-100" style="border-radius: 0 !important;">
            <div class="card-header" style="text-align: center;"><h4>Мои публикации</h4></div>
            <div class="card-body">
            <?php Pjax::begin(['id' => 'pjax-123']) ?>
            <?php foreach($posts as $post) {?>
                    <div class="d-flex mb-3 shadow post-card" style="height: 160px;">
                        <?php if (file_exists('uploads/' . $post->image) && !is_dir('uploads/' . $post->image)) { ?>
                        <div>
                            <?= Html::img('/uploads/' . $post->image,['style' => 'height: 100%; width: 100%; object-fit: cover; border: 1px solid; border-color: lightgray;']) ?>
                        </div>
                        <?php } else { ?>
                        <div>
                            <?= Html::img('/uploads/no_image.png',['style' => 'height: 100%; width: 100%; object-fit: cover; border: 1px solid; border-color: lightgray;']) ?>
                        </div>
                        <?php } ?>
                        <div class="card mb-3" style="border-radius: 0 !important; height: 100%; width: 100%;">
                            <div class="card-body d-flex">
                                <div class="d-flex-column">
                                    <div class="post-title" style="width: 200px;">
                                        <strong><?=$post->title ?></strong>
                                    </div>
                                    <div>
                                    <i class="fa-regular fa-face-smile me-2"></i><?= $post->likes ?>
                                    </div>
                                    <div>
                                    <i class="fa-regular fa-face-frown me-2"></i><?= $post->dislikes ?>
                                    </div>
                                    <div>
                                        <i class="fa-sharp fa-solid fa-eye me-2"></i><?= $post->views ?>
                                    </div>
                                </div>
                                <div class="card ms-3 me-3" style="border-radius: 0 !important; width: 100%; height: 126px;">
                                    <div class="card-body">
                                        <?= mb_substr(strip_tags($post->body, ['ul', 'li', 'p', 'a', 'h3', 'br', 'strong']), 0, 375) . ' ...'?>
                                    </div>
                                </div>
                                <div class="d-flex-column" style="width: 200px;">
                                    <?= Html::a(Html::button('Управление', ['class' => 'btn btn-outline-success btn-sm post-btn mb-2']), Url::to(['post-control', 'id' => $post->id])) ?>
                                    <?= Html::button('Удалить', ['class' => 'btn btn-outline-danger btn-sm post-btn mb-2 delete-post-btn', 'data-id' => $post->id]) ?>
                                    <?php switch ($post->status) {
                                    case 0: echo "<div class='card text-white bg-info' style='border-radius: 0 !important; height: 48px;'>"; break;
                                    case 1: echo "<div class='card text-white bg-success' style='border-radius: 0 !important; height: 48px;'>"; break;
                                    case 2: echo "<div class='card text-white bg-danger' style='border-radius: 0 !important; height: 48px;'>"; break;
                                    case 3: echo "<div class='card text-white bg-warning' style='border-radius: 0 !important; height: 48px;'>"; break;
                                    default: echo "<div class='card' style='border-radius: 0 !important; height: 48px;'>";
                                    } ?>
                                        <div class="card-body p-1" style="font-size: 12px;">
                                            <?= Blog::STATUSES[$post->status]; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php } ?>
            <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            ]); ?>
            <?php Pjax::end() ?>
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
    . Html::button('Да, удалить пост',['class' => 'btn btn-outline-danger ms-2', 'id' => 'lets-delete-post', 'data-id'])
    . "</div></div></div>";

Modal::end(); 

$js1 = <<< JS
$(function() {
    $(document).on('click', '.delete-post-btn', function() {
        console.log($(this).data('id'))
        $('#lets-delete-post').data('id', $(this).data('id'))
        $('#modal').modal('show');
    });
        
    $(document).on('click','#lets-delete-post', function() {
        $.ajax({
            url: '/blog/post-delete',
            type: 'POST',
            data: {
                id: $(this).data('id'),
            },
            success: function (res) {
                $.pjax.reload({container:'#pjax-123'});
                $('#modal').modal('hide')
            },
            error: function () {
            }
        });
    });
});
JS;

$this->registerJs($js1);

?>