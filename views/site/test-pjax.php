<?php

use yii\widgets\Pjax;
use yii\bootstrap5\Html;
?>






<?php Pjax::begin(); ?>
    <?= Html::beginForm(['/site/test-pjax'], 'post', ['data-pjax' => '']); ?>
        <?= Html::input('text', 'string', Yii::$app->request->post('string')) ?>
        <?= Html::submitButton('Вычислить MD5', ['class' => 'btn btn-lg btn-primary']) ?>
    <?= Html::endForm() ?>      
    <?php if (isset($res)) { ?>
    <h3><?= date('H:m:s') . " " . $res; ?></h3>
    <?php } else { ?>
        <h3><?= date('H:m:s'); ?></h3>
    <?php } ?>
<?php Pjax::end(); ?>