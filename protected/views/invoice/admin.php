<?php
/* @var $this InvoiceController */
/* @var $model Invoice */

$this->breadcrumbs=array(
	'Invoices'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Manage Invoice', 'url'=>array('admin')),
	array('label'=>'Create Invoice', 'url'=>array('create')),
	array('label'=>'Performance Report', 'url'=>array('report')),
	array('label'=>'Delivery Scheduler', 'url'=>array('delivery'))
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('invoice-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Invoices</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'invoice-grid',
	'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array('name'=>'id', 'header'=>'Reference#'),
		array('name'=>'date', 'header'=>'Date'),
		array('name'=>'address', 'header'=>'Address'),
		array('name'=>'phone1', 'header'=>'Phone'),
		array('name'=>'deposit', 'header'=>'Deposit'),
		array('name'=>'name', 'header'=>'Client Name'),
		array('name'=>'delivery_date', 'header'=>'Delivery Date'),
	 	array('name'=>'delivery_cost', 'header'=>'Shipping & Handling'),
		array(
			'class'=>'CButtonColumn',
			'header'=>'Operations'
		),
	),
)); ?>
