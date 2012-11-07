<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs=array(
	'Inventories'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Create Inventory', 'url'=>array('create')),
	array('label'=>'View Inventory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Inventory', 'url'=>array('admin')),
);
?>

<h1>Update Inventory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>