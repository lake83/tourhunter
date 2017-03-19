<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\SiteHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransfersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Переводы';
?>

<h1><?= $this->title ?></h1>

<?=  GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'export' => false,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'from_id',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'from_id',
                    $searchModel->getAllUsers(),
                    ['class' => 'form-control', 'prompt' => '- выбрать -']
                ),
                'value' => function ($model, $index, $widget) {
                    return $model->userFrom->username;}
            ],
            [
                'attribute' => 'to_id',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'to_id',
                    $searchModel->getAllUsers(),
                    ['class' => 'form-control', 'prompt' => '- выбрать -']
                ),
                'value' => function ($model, $index, $widget) {
                    return $model->userTo->username;}
            ],
            'sum',
            SiteHelper::created_at($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'options' => ['width' => '50px']
            ]
        ]
    ]);

?>