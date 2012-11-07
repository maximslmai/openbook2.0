<script type="text/javascript">
$(function(){
    // bind change event to select
    $('#year').bind('change', function () {
        var url = $(this).val(); // get selected value
        if (url) { // require a URL
            window.location = 'index.php?r=invoice/report&year='+url; // redirect
        }
        return false;
    });
  });
</script>
<?php
/* @var $this InvoiceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Invoices'=>array('admin'),
	'Invoice Financial Report',
);

$this->menu=array(
	array('label'=>'Create Invoice', 'url'=>array('create')),
	array('label'=>'Manage Invoice', 'url'=>array('admin')),

);
?>
<strong>Showing report for year: </strong> 
<?php 
echo CHtml::dropDownList('year', array_search($year, $years), $years, array('empty' => '(Select a year)',));
?>

<?php
$this->Widget('ext.highcharts.HighchartsWidget', array(
		'options'=>array(
				'title' => array('text' => "Monthly Total Revenue of $year"),
				'xAxis' => array(
						'categories' => array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')
				),
				'yAxis' => array(
						'title' => array('text' => 'Monthly Report')
				),
				'series' => array(
						array('name' => 'Total Revenue', 'data' => $dataProvider),
						array('name' => 'Total Net Revenue', 'data' =>$dataProvider2),
				)
		)
));
?>
<p>
<strong>Total Revenue of <?php echo $year;?>: </strong> $<?php echo array_sum($dataProvider); ?> <br/>
<strong>Total Net Revenue of <?php echo $year;?>: </strong> $<?php echo array_sum($dataProvider2); ?> <br/>
</p>
<hr/>
<div>page loading time: <?php echo $loadingTime; ?> seconds</div>