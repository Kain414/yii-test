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

    .add-btn {
        width: 250px;
        height: 50px;
        font-size: 18px;
        border-radius: 0 !important;
    }

    /* .growl-close{float:right;font-size:1.5rem;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;opacity:.5;margin-top:-3px}.growl-close:hover{color:#000;text-decoration:none}.growl-close:not(:disabled):not(.disabled):focus,.growl-close:not(:disabled):not(.disabled):hover{opacity:.75}button.growl-close{padding:0;background-color:transparent;border:0}a.growl-close.disabled{pointer-events:none}.alert-growl{border-radius:10px;background-color:#191919;background-color:rgba(25,25,25,.9);border-width:2px;border-color:#fff;border-color:rgba(255,255,255,.9);color:#fff;box-shadow:0 0 10px #191919;box-shadow:0 0 10px rgba(25,25,25,.8)}.alert-growl button.growl-close{color:#fff;opacity:1}.alert-growl img{float:left;padding:0 5px 0 0}.alert-growl .alert-link{color:#fff} */
</style>

<?php

//use Yii;
use app\models\Like;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\growl\Growl;
use kartik\growl\GrowlAsset;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;


$posts = $dataProvider->models;

?>

<?php

if (Yii::$app->session->getFlash('success') != null) {
    $result = Yii::$app->session->getFlash('success');
    if ($result) {
        echo Growl::widget([
            'type' => Growl::TYPE_SUCCESS,
            'title' => 'Добавление поста',
            'icon' => 'fas fa-check-circle',
            'body' => 'Пост успешно создан. Дождитесь решения модерации перед его публикацией.',
            'showSeparator' => true,
            'delay' => 0,
            'pluginOptions' => [
                'showProgressbar' => true,
                'placement' => [
                    'from' => 'top',
                    'align' => 'right',
                ]
            ]
        ]);
        Yii::$app->session->removeAllFlashes();
    } else {
        echo Growl::widget([
            'type' => Growl::TYPE_WARNING,
            'title' => 'Добавление поста',
            'icon' => 'fas fa-check-circle',
            'body' => 'Что-то при добавлении поста произошло не так. Попробуйте ещё раз. Убедитесь, что во все поля добавляются соответствующие данные.',
            'showSeparator' => true,
            'delay' => 0,
            'pluginOptions' => [
                'showProgressbar' => true,
                'placement' => [
                    'from' => 'top',
                    'align' => 'right',
                ]
            ]
        ]);
        Yii::$app->session->removeAllFlashes();
    }
}
?>

<?php if (Yii::$app->user->isGuest) {?>
<div class="d-flex justify-content-center">
<?php } else { ?>
<div class="d-flex justify-content-start">
    <div class="me-4 mt-4 d-flex-column">
        <div><?= Html::a(Html::button('Добавить запись', ['class' => 'btn btn-outline-success btn-sm add-btn mb-2']), 'post-add') ?></div>
        <div><?= Html::a(Html::button('Мои публикации', ['class' => 'btn btn-outline-success btn-sm add-btn mb-2']), 'my-posts') ?></div>
    </div>
<?php } ?>
    <div class="w-50 ms-4">
        <?php foreach ($posts as $post) { ?>
            <div class="card mb-4 mt-4 shadow" style="border-radius: 0 !important;">
                <div class="card-header"><h4><?=$post->title?></h4></div>
                <div class="card-body">
                    <div style="text-align: center;"><?=Html::img('/uploads/' . $post->image, ['style' => 'width: 50%; object-fit: cover;', 'class' => 'rounded']) ?></div>
                    </br>
                    <?php 
                    $sub_body = substr($post->body,0,120);
                    ?>
                    <!-- <?=$sub_body ?> -->
                    <!-- <?=$post->body ?> -->
                    <?= mb_substr(strip_tags($post->body, ['ul', 'li', 'p', 'a', 'h3', 'br', 'strong']), 0, 300) . ' ...' ?>
                    <b><?= Html::a('Читать полностью',Url::to(['posts-item', 'id' => $post->id]),['class' => 'link-primary', 'style' => 'text-decoration: none']) ?></b>
                    <?php 
                    if (!Yii::$app->user->isGuest) {
                        Pjax::begin([
                            'id' => 'pjax-' . $post->id,
                        ]) ?>    
                            <div class="d-flex">
                                <?php
                                $like = Like::findOne(['blog_id' => $post->id, 'user_id' => Yii::$app->user->id]);
                                if ($like != null) {
                                    if ($like->status == 1) { ?>
                                        <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart1.png',['style' => 'width: 24px']) 
                                        ,['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'id' => 'like' . $post->id, 'data-id' => $post->id])?></div>
                                
                                        <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart4.png',['class' => 'like-off', 'style' => 'width: 24px']) 
                                        . Html::img('/images/heart2.png',['class' => 'like-on', 'style' => 'width: 24px']),['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'id' => 'dislike' . $post->id * -1, 'data-id' => ($post->id * -1)])?></div>
                                    <?php } else { ?>
                                    <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart3.png',['class' => 'like-off', 'style' => 'width: 24px']) 
                                    . Html::img('/images/heart1.png',['class' => 'like-on', 'style' => 'width: 24px']),['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'id' => 'like' . $post->id, 'data-id' => $post->id])?></div>
                                
                                    <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart2.png',['style' => 'width: 24px']) 
                                    ,['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'id' => 'dislike' . $post->id * -1, 'data-id' => ($post->id * -1)])?></div>
                                    <?php }
                                } else { ?>
                                    <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart3.png',['class' => 'like-off', 'style' => 'width: 24px']) 
                                    . Html::img('/images/heart1.png',['class' => 'like-on', 'style' => 'width: 24px']),['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'id' => 'like' . $post->id, 'data-id' => $post->id])?></div>
                                
                                    <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart4.png',['class' => 'like-off', 'style' => 'width: 24px']) 
                                    . Html::img('/images/heart2.png',['class' => 'like-on', 'style' => 'width: 24px']),['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'id' => 'dislike' . $post->id * -1, 'data-id' => ($post->id * -1)])?></div>
                                <?php } ?>
                            </div>
                        <?php Pjax::end();
                    } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php

$js = <<< JS

    $(document).on('click','.like-set', function () {
        var un = Math.abs($(this).data('id'));
        $.ajax({
            url: '/blog/put-like',
            type: 'POST',
            data: {
                id: $(this).data('id'),
            },
            success: function (res) {
                $.pjax.reload({container:'#pjax-'+un});
            },
            error: function () {
            }
        });
    });
JS;

$this->registerJs($js);

?>

<?= LinkPager::widget([
'pagination' => $dataProvider->pagination,
]);
?>