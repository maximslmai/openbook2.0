<?php
/* @var $this InvoiceController */
/* @var $model Invoice */

$this->breadcrumbs=array(
	'Invoices'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create Invoice', 'url'=>array('create')),
	array('label'=>'Update Invoice', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Invoice', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Invoice', 'url'=>array('admin')),
	array('label'=>'View printable invoice', 'url'=>array('print', 'id'=>$model->id)),
);
?>

<h1>View Invoice #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
	'attributes'=>array(
		'id',
		'date',
		'address',
		'phone1',
		'phone2',
		'deposit',
		'name',
		'delivery_date',
		'delivery_cost',
		'Amount'
	),
)); ?>

<div id="map_canvas"></div>

<?php 
$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>new CArrayDataProvider($model->Entries),
		'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
		'columns'=>array(
			array('name'=>'item', 'header'=>'Item Description'),
			array('name'=>'price', 'header'=>'Price'),
			array('name'=>'quantity', 'header'=>'Quantity'),
			array('name'=>'amount','header'=> 'Amount'),
			array(            // display a column with "view", "update" and "delete" buttons
				'class'=>'CButtonColumn',
				'header'=>'Operations',
				'viewButtonUrl'=>'Yii::app()->createUrl("/entry/view", array("id"=>$data["id"]))',
				'updateButtonUrl'=>'Yii::app()->createUrl("/entry/update", array("id"=>$data["id"]))',
				'deleteButtonUrl'=>'Yii::app()->createUrl("/entry/delete", array("id"=>$data["id"]))',
			),
		),
));
?>

<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
		'id'=>'new-entry-dialog',
		// additional javascript options for the dialog plugin
		'options'=>array(
				'title'=>'Add a new item to invoice#' . $model->id,
				'autoOpen'=>false,
				'width'=> '400px',
				'height' => '250',
		),
));

?>
		<?php $form = $this->beginWidget('CActiveForm', array(
		    'id'=>'new-entry-form',
		    'enableAjaxValidation'=>true,
		    'enableClientValidation'=>true,
		)); ?>
				
		<?php
		echo CHtml::hiddenField("invoice", $model->id);
		echo CHtml::hiddenField("inventory", -1);
		echo "Inventory Name: ";
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'name',
				'source'=>$this->createUrl('inventory/autocomplete'),
				// additional javascript options for the autocomplete plugin
				'options'=>array(
						'minLength'=>'2',
				),
		));
		echo "<br/>";
		echo "Price: " .  CHtml::textField("price", $entry->price, array("size"=>5));
		echo "<br/>";
		echo "Quantity: " .  CHtml::textField("quantity", $entry->quantity, array("size"=>5));
		echo "<br/>";
		echo "Amount: " .  CHtml::textField("amount", $entry->amount, array("size"=>5));
		echo "<br/>";
		?>
		<?php echo CHtml::button('Submit', array('submit' => array('entry/create'))); ?>
<?php $this->endWidget(); ?>

<?php

$this->endWidget('zii.widgets.jui.CJuiDialog');

// the link that may open the dialog
echo CHtml::button('Add a new item',  array(
		'onclick'=>'$("#new-entry-dialog").dialog("open"); return false;',
));


?>