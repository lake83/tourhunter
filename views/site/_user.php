<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \app\models\User */
?>

<div class="col-md-12">
    <p>Пользователь: <strong><?= Html::encode($model->username) ?></strong></p>
    <p>Баланс: <?= $model->balance ?></p>
</div>