<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\SiteHelper;

$this->title = 'Личный кабинет ' . Yii::$app->user->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
    
<p><?= Html::a('Сделать перевод', ['transfer'], ['class' => 'btn btn-success']) ?></p>

<?=  GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'pjax' => false,
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
            SiteHelper::created_at($searchModel)
        ]
    ]);
?>