<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transfers */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = 'Создание перевода';
?>

<h1><?= $this->title ?></h1>

<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'to_id')->textInput() ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <div class="box-footer">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>