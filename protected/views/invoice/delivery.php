<?php
/* @var $this InvoiceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Invoices'=>array('admin'),
);

$this->menu=array(
	array('label'=>'Create Invoice', 'url'=>array('create')),
	array('label'=>'Performance Report', 'url'=>array('report')),
	array('label'=>'Manage Invoice', 'url'=>array('admin')),
	array('label'=>'Delivery Scheduler', 'url'=>array('delivery'))
);
?>

<h1>Invoices need to be deliveried on <?php echo $date;?></h1>
<strong>Or Select a date: </strong>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'delivery-form',
		'method'=>'post',
)); ?>
<?php 
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'name'=>'date',
		'value'=>"$date",
		// additional javascript options for the date picker plugin
		'options'=>array(
				'showAnim'=>'fold',
				'dateFormat' => 'yy-mm-dd',
		),
		'htmlOptions'=>array(
				'style'=>'height:20px;'
		),
));
?>
<?php echo CHtml::submitButton('Go'); ?>
<?php $this->endWidget(); ?>
 
 
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
		'columns'=>array(
			array(
				'type'=>'raw',
				'value'=>'CHtml::link(hash("crc32b",$data->id), array("invoice/view", "id"=>$data->id));',
				'name'=>'Reference#',
			),
			'date',
			'name',
			'phone1',
			'address',
			'Amount',				
				array(
					'class'=>'CButtonColumn',
				),
		)
)); ?>
