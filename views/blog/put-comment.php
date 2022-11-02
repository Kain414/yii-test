<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;

?>

<div class="card mb-4 mt-4">
    <div class="card-header"><h4>Оставить комментарий</h4></div>
    <div class="card-body">
        <?php $form = ActiveForm::begin(['method' => 'post', 'enableClientValidation' => false]); ?>
                <?= $form->field($model, 'comment')->textarea([
                    'rows' => 6,
                    'id' => 'comment-area',
                ]) ?>
                <?= $form->field($model, 'blog_id')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'answer')->hiddenInput([
                    'id' => 'comment-answer',
                ])->label(false) ?>
                <?= Html::submitButton('Написать', ['class' => 'btn btn-primary', 'id' => 'write-comment']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php

$js = <<< JS

$(document).on('click','#write-comment', function (event) {
    event.preventDefault();
    $.ajax({
        url: '/blog/save-comment',
        type: 'POST',
        data: $('form').serialize(),
        success: function (res) {
            $.pjax.reload({container:'#update-comments'});
            $('#comment-area').val(null)
        },
        error: function () {
        }
    });
});
JS;

$this->registerJs($js);

?>