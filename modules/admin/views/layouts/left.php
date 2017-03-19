<?php
app\assets\AdminAsset::register($this);

/* @var $this \yii\web\View */
/* @var $content string */

?>
<aside class="main-sidebar">
    <section class="sidebar">
<?= dmstr\widgets\Menu::widget([
    'firstItemCssClass' => $this->context->module->id == 'structure' ? 'active' : '',
    'options' => ['class' => 'sidebar-menu'],
    'encodeLabels' => false,
    'items' => [
        ['label' => 'Пользователи', 'url' => ['/admin/user/index']],
        ['label' => 'Переводы', 'url' => ['/admin/transfers/index']]
    ]
]);	
?>
    </section>
</aside>