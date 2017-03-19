<?php

/* @var $this yii\web\View */

use yii\widgets\Pjax;
use yii\widgets\ListView;

$this->title = 'Список пользователей';
?>

<h1><?= $this->title ?></h1>

<?php Pjax::begin();

echo ListView::widget([
     'id' => 'users_list',
     'dataProvider' => $dataProvider,
     'layout' => "{items}\n<div class=\"row text-center\"><div class=\"col-lg-12\">{pager}</div></div>",
     'itemView' => '_user'
]);

Pjax::end(); ?>