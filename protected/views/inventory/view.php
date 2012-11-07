<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs=array(
	'Inventories'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create Inventory', 'url'=>array('create')),
	array('label'=>'Update Inventory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Inventory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Inventory', 'url'=>array('admin')),
);
?>

<h1>View Inventory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
	'attributes'=>array(
		'id',
		'name',
		'model',
		'quantity',
		'in',
		'date',
	),
)); ?>
