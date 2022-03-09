<?php

/**
 * @var yii\web\View $this
 * @var \restapp\modules\zendesk\forms\AdminUiForm $model
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<html>
    <head>
        <title>Admin Ui</title>
    </head>
    <body>
        <div>
            <?php $form = ActiveForm::begin([
                'method' => 'post',
                'action' => $model->return_url,
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

                <?= Html::label('Имя аккаунта') ?><br>
                <?= Html::textInput('name', $model->name) ?><br>
                <?= Html::label('Метаданные') ?><br>
                <?= Html::textInput('metadata', $model->metadata) ?><br>
                <?= Html::label('Состояние') ?><br>
                <?= Html::textInput('state', $model->state) ?><br>
                <?= Html::label('ReturnUrl') ?><br>
                <?= Html::textInput('ReturnUrl', $model->return_url) ?><br>

                <?= Html::hiddenInput('instance_push_id', $model->instance_push_id, ['id' => 'instance_push_id']) ?>
                <?= Html::hiddenInput('zendesk_access_token', $model->zendesk_access_token, ['id' => 'zendesk_access_token']) ?>

            <?= Html::submitButton() ?>
            <?php ActiveForm::end(); ?>
        </div>
    </body>
</html>

