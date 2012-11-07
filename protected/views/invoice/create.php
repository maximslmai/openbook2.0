<?php
/* @var $this InvoiceController */
/* @var $model Invoice */

$this->breadcrumbs=array(
	'Invoices'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Invoice', 'url'=>array('admin')),
);
?>


<h1>Create Invoice</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'entries'=>$entries)); ?>

<?php 
$this->renderPartial("_maps");
?>