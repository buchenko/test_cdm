<?php
/* @var $this MovieController */
/* @var $data Movie */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('original_title')); ?>:</b>
	<?php echo CHtml::encode($data->original_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('release_date')); ?>:</b>
	<?php echo CHtml::encode($data->release_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('runtime')); ?>:</b>
	<?php echo CHtml::encode($data->runtime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('overview')); ?>:</b>
	<?php echo CHtml::encode($data->overview); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('genres')); ?>:</b>
	<?php echo CHtml::encode($data->genres); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('poster_path')); ?>:</b>
	<br />

	*/ ?>
	<?php echo CHtml::image('/images/'.$model->poster_path); ?>

</div>