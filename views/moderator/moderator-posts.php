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
    white-space: nowrap; /* Запрещаем перенос строк */
    overflow: hidden; /* Обрезаем все, что не помещается в область */
    background: #fc0; /* Цвет фона */
    padding: 5px; /* Поля вокруг текста */
    text-overflow: ellipsis; /* Добавляем многоточие */
}
</style>
<?php

$posts = $dataProvider->models;

$this->title = "Посты на публикацию";
?> 

<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;

use yii\bootstrap5\LinkPager;

$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => 'Модерация', 'url' => ['/moderator/index']];
$this->params['breadcrumbs'][] = ['template' => "<li>&nbsp/ {link}</li>\n", 'label' => "$this->title"];
?>

<div class="d-flex">
    <div class="card w-100" style="border-radius: 0 !important;">
            <div class="card-header" style="text-align: center;"><h4>Публикации</h4></div>
            <div class="card-body">
                <?php Pjax::begin([
                    'id' => 'pjax-123',
                    'options' => [
                        'class' => 'd-flex flex-wrap justify-content-between p-2',
                    ],
                ]) ?>
                <?php foreach($posts as $post) {?>
                        <div class="d-flex mb-3 shadow post-card" style="height: 200px; width: 48%;">
                            <?php if (file_exists('uploads/' . $post->image) && !is_dir('uploads/' . $post->image)) { ?>
                            <div>
                                <?= Html::img('/uploads/' . $post->image,['style' => 'height: 200px; width: 200px; object-fit: cover; border: 1px solid; border-color: lightgray;']) ?>
                            </div>
                            <?php } else { ?>
                            <div>
                                <?= Html::img('/uploads/no_image.png',['style' => 'height: 200px; width: 200px; object-fit: cover; border: 1px solid; border-color: lightgray;']) ?>
                            </div>
                            <?php } ?>
                            <div class="card mb-3" style="border-radius: 0 !important; height: 100%; width: 100%;">
                                <div class="card-header"><?= $post->title ?></div>
                                <div class="card-body d-flex p-0">
                                    <div class="card" style="border-radius: 0 !important; width: 100%; height: 157px;">
                                        <div class="card-body">
                                            <?= mb_substr(strip_tags($post->body, ['ul', 'li', 'p', 'a', 'h3', 'br', 'strong']), 0, 385) . ' ...'?>
                                        </div>
                                    </div>
                                    <div class="d-flex-column" style="width: auto;">
                                        <?= Html::a(Html::button('Просмотр', ['class' => 'btn btn-outline-success btn-sm post-btn ms-2 me-2 mt-2 mb-2']), Url::to(['moderator-post-control', 'id' => $post->id])) ?>
                                        <div class="ms-2 me-2">
                                            <div style="font-size: 10px;">Создано</div>
                                            <div style="font-size: 10px;"><?= date('d/m/Y H:m:s',$post->created_at) ?></div>
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