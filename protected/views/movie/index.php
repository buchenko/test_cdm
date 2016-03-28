<?php
/* @var $this MovieController */
/* @var $model Movie */

$this->breadcrumbs = array(
    'Фильмы'
);
?>
<?php
$this->widget('CLinkPager', array(
    'pages' => $pages,
));
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'movie-grid',
    'dataProvider' => $model->search($pages->currentPage, $release),
    'enablePagination' => false,
    'columns' => array(
        'id',
        'title',
        'release_date',
        array(
            'class' => 'CButtonColumn',
            'viewButtonUrl' => 'Yii::app()->controller->createUrl("view",array("id"=>$data["id"]))',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data["id"]))',
            'deleteConfirmation'=> 'Удалить данную запись из локальной базы?',
            'updateButtonUrl' => 'Yii::app()->controller->createUrl("update",array("id"=>$data["id"]))'

        ),
    ),
)); ?>
