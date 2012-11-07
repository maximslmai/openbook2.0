<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs=array(
	'Inventories'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Inventory', 'url'=>array('admin')),
);
?>

<h1>Create Inventory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>