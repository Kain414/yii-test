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
</style>

<?php

use app\models\Like;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\ActiveForm;

$comments = $dataProvider->models;

?>
<div class="w-75 mx-auto">
    <div class="card mb-4 mt-4">
        <div class="card-header"><h4><?=$model->title?></h4></div>
        <div class="card-body">
            <div style="text-align: center;"><?=Html::img('/uploads/' . $model->image, ['style' => 'width: 50%; object-fit: cover;', 'class' => 'rounded']) ?></div>
            </br>
            <?=$model->body ?>
            <?php 
            if (!Yii::$app->user->isGuest) {
                Pjax::begin([
                    'id' => 'pjax-' . $model->id,
                ]) ?> 
                <div class="d-flex">
                    <?php
                    $like = Like::findOne(['blog_id' => $model->id, 'user_id' => Yii::$app->user->id]);
                    if ($like != null) {
                        if ($like->status == 1) { ?>
                            <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart1.png',['style' => 'width: 24px']) 
                            ,['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'data-id' => $model->id])?></div>
                    
                            <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart4.png',['class' => 'like-off', 'style' => 'width: 24px']) 
                            . Html::img('/images/heart2.png',['class' => 'like-on', 'style' => 'width: 24px']),['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'data-id' => ($model->id * -1)])?></div>
                        <?php } else { ?>
                            <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart3.png',['class' => 'like-off', 'style' => 'width: 24px']) 
                            . Html::img('/images/heart1.png',['class' => 'like-on', 'style' => 'width: 24px']),['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'data-id' => $model->id])?></div>
                        
                            <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart2.png',['style' => 'width: 24px']) 
                            ,['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'data-id' => ($model->id * -1)])?></div>
                        <?php }
                    } else { ?>
                        <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart3.png',['class' => 'like-off', 'style' => 'width: 24px']) 
                        . Html::img('/images/heart1.png',['class' => 'like-on', 'style' => 'width: 24px']),['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'data-id' => $model->id])?></div>
                    
                        <div class="image-hover" style="margin-right: 5px;"><?=Html::button(Html::img('/images/heart4.png',['class' => 'like-off', 'style' => 'width: 24px']) 
                        . Html::img('/images/heart2.png',['class' => 'like-on', 'style' => 'width: 24px']),['class' => 'btn btn-outline-secondary w-100 h-100 like-set', 'data-id' => ($model->id * -1)])?></div>
                    <?php } ?>
                </div>
                <?php Pjax::end();
            } ?>
        </div>
    </div>
    
    <div class="header-comment-text"></div>
    <?php 
    if (!Yii::$app->user->isGuest) {
    Pjax::begin([
        'enablePushState' => false,
        'id' => 'pjax-textarea'
    ]); ?>
        <h5><?= Html::a('Оставить комментарий...',Url::to(['put-comment', 'id' => $model->id]),['class' => 'link-primary put-comment-text', 'style' => 'text-decoration: none']); ?></h5>
    <?php Pjax::end(); 
    } else {?>
        <div class="d-flex">
            <h6><?= Html::a('Войдите','/site/login',['class' => 'link-primary', 'style' => 'text-decoration: none']); ?></h6>
            <h6><?="&nbsp;или&nbsp;"?></h6>
            <h6><?= Html::a('зарегистрируйтесь, чтобы оставлять комментарии...','/site/sign-up',['class' => 'link-primary', 'style' => 'text-decoration: none']); ?></h6>
        </div>
    <?php } ?>

    <div class="card mb-4 mt-4">
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
                            <div class="card-header pt-0 pb-0 ps-3" style="font-size: 14px; color: DarkCyan; position: relative;">
                                <?php $author_name = $comment->userModel->first_name . " " . $comment->userModel->last_name; ?>
                                <strong><?= $author_name ?></strong>
                                <?= Html::button('ответить',['class' => 'btn btn-primary pt-0 pb-0 ps-1 pe-1 answer-btn', 'style' => 'font-size: 11px; position: absolute; right: 5px;', 'data-id' => $comment->id, 'data-name' => $author_name]) ?>
                            </div>
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



<?php

$js = <<< JS

var go;
var answer_com = 0;
var answer_name = '';
console.log(answer_com) 

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

$(document).on('click','.answer-btn', function () {
    go = $(this);
    if ($('.put-comment-text').length != 0) {
        $('.put-comment-text').trigger('click');
        $('#pjax-textarea').on("pjax:end", function ()  {
            debugger
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".header-comment-text").offset().top
            }, 200);
            answer_com = go.data('id');
            answer_name = go.data('name');
            $('#comment-area').val(answer_name + ', ');
            $('#comment-answer').val(answer_com);
        });
    } else {
        $([document.documentElement, document.body]).animate({
            scrollTop: $(".header-comment-text").offset().top
        }, 200);
        answer_com = go.data('id');
        answer_name = go.data('name');
        $('#comment-area').val(answer_name + ', ');
        $('#comment-answer').val(answer_com);
    }
});

JS;

$this->registerJs($js);

?>