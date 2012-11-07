<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs=array(
	'Inventories'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Inventory', 'url'=>array('index')),
	array('label'=>'Create Inventory', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('inventory-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Inventories</h1>

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
	'id'=>'inventory-grid',
	'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array('name'=>'id', 'header'=>'Reference#'),
		array('name'=>'name', 'header'=>'Name'),
		array('name'=>'model', 'header'=>'Model#'),
		array('name'=>'quantity', 'header'=>'Quantity'),
		array('name'=>'in', 'header'=>'Buy-in Price'),
		array('name'=>'date', 'header'=>'Date'),
		array(
			'class'=>'CButtonColumn',
			'header'=>'Operations',
		),
	),
)); ?>
