<?php
/* @var $this MovieController */
/* @var $model Movie */

$this->breadcrumbs=array(
	'Фильмы'=>array('index'),
	$model->title,
);
//
?>

<h1>Прсмотр информации о фильме #<?php echo $model->id; ?> [<?php echo CHtml::link('изменить', Yii::app()->controller->createUrl("update",array("id"=>$model["id"]))); ?>] </h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
//		'id',
		'title',
		'original_title',
		'release_date',
		'runtime',
		'overview',
		'genres',
		'poster_path'=> array(
			'name'=>'Постер',
			'type'=>'raw',
			'value'=> CHtml::image('/images/'.$model->poster_path),
		),
	),
)); ?>
