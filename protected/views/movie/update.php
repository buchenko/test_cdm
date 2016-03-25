<?php
/* @var $this MovieController */
/* @var $model Movie */

$this->breadcrumbs=array(
	'Фильмы'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Изменить',
);
?>

<h1>Изменить информацию о фильме в локальной базе #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>