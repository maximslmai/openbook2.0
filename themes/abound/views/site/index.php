<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
$baseUrl = Yii::app()->theme->baseUrl; 
?>

<div class="row-fluid">
  <div class="span3 ">
	<div class="stat-block">
	  <ul>
		<li class="stat-graph inlinebar" id="weekly-visit">8,4,6,5,9,10</li>
		<li class="stat-count"><span>$<?php echo $week_total;?></span><span>Weekly Sales</span></li>
		<li class="stat-percent"><span class="text-success stat-percent">20%</span></li>
	  </ul>
	</div>
  </div>
  <div class="span3 ">
	<div class="stat-block">
	  <ul>
		<li class="stat-graph inlinebar" id="new-visits">2,4,9,1,5,7,6</li>
		<li class="stat-count"><span>$<?php echo $month_total;?></span><span>Monthly Sales</span></li>
		<li class="stat-percent"><span class="text-error stat-percent">-15%</span></li>
	  </ul>
	</div>
  </div>
  <div class="span3 ">
	<div class="stat-block">
	  <ul>
		<li class="stat-graph inlinebar" id="unique-visits">200,300,500,200,300,500,1000</li>
		<li class="stat-count"><span><?php echo $open_invoices;?></span><span>Open Invoices</span></li>
		<li class="stat-percent"><span class="text-success stat-percent">10%</span></li>
	  </ul>
	</div>
  </div>
  <div class="span3 ">
	<div class="stat-block">
	  <ul>
		<li class="stat-graph inlinebar" id="">1000,3000,6000,8000,3000,8000,10000</li>
		<li class="stat-count"><span>$25,000</span><span>Overdue</span></li>
		<li class="stat-percent"><span><span class="text-success stat-percent">20%</span></li>
	  </ul>
	</div>
  </div>
</div>

<div class="row-fluid">

    
	<div class="span6">
	  <?php $this->widget('zii.widgets.grid.CGridView', array(
			/*'type'=>'striped bordered condensed',*/
			'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
			'dataProvider'=>$recent_invoices,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'id', 'header'=>'Reference#'),
				array('name'=>'name', 'header'=>'Client Name'),
				array('name'=>'phone1', 'header'=>'Phone'),
				array('name'=>'Amount', 'header'=>'Total Amount'),
			),
		)); ?>
	</div><!--/span-->
	<div class="span3">
		 <?php $this->widget('zii.widgets.grid.CGridView', array(
			/*'type'=>'striped bordered condensed',*/
			'htmlOptions'=>array('class'=>'table table-striped table-bordered table-condensed'),
			'dataProvider'=>$recent_deliveries,
			'template'=>"{items}",
			'columns'=>array(
				array('name'=>'id', 'header'=>'Reference#'),
				array('name'=>'address', 'header'=>'Address'),
				array('name'=>'date', 'header'=>'Invoice Date'),
				array('name'=>'delivery_date', 'header'=>'Delivery Date'),
			),
		)); ?>
        	
	</div>
	
	<div class="span3">
		<div class="summary">
          <ul>
          	<li>
          		<span class="summary-icon">
                	<img src="<?php echo $baseUrl ;?>/img/credit.png" width="36" height="36" alt="Monthly Income">
                </span>
                <span class="summary-number"><?php echo 123; ?></span>
                <span class="summary-title"> Monthly Income</span>
            </li>
            <li>
            	<span class="summary-icon">
                	<img src="<?php echo $baseUrl ;?>/img/page_white_edit.png" width="36" height="36" alt="Open Invoices">
                </span>
                <span class="summary-number">125</span>
                <span class="summary-title"> Open Invoices</span>
            </li>
            <li>
            	<span class="summary-icon">
                	<img src="<?php echo $baseUrl ;?>/img/page_white_excel.png" width="36" height="36" alt="Open Quotes">
                </span>
                <span class="summary-number">53</span>
                <span class="summary-title"> Open Quotes</span>
            </li>
            <li>
            	<span class="summary-icon">
                	<img src="<?php echo $baseUrl ;?>/img/group.png" width="36" height="36" alt="Active Members">
                </span>
                <span class="summary-number">654,321</span>
                <span class="summary-title"> Active Members</span>
            </li>
            <li>
            	<span class="summary-icon">
                	<img src="<?php echo $baseUrl ;?>/img/folder_page.png" width="36" height="36" alt="Recent Conversions">
                </span>
                <span class="summary-number">630</span>
                <span class="summary-title"> Recent Conversions</span></li>
        
          </ul>
        </div>

	</div>
</div>

<div class="row-fluid">
	<div class="span6">

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
						array('name' => 'Total Revenue', 'data' => array_values($monthly_revenue)),
						array('name' => 'Total Net Revenue', 'data' =>array_values($monthly_net_revenue)),
				)
		)
));
?>
	</div><!--/span-->
    <div class="span6">
    	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'<span class="icon-th-list"></span> Inventory Sales Distribution Chart',
			'titleCssClass'=>''
		));
		?>
        
        <div class="pieStats" style="height: 230px;width:100%;margin-top:15px; margin-bottom:15px;"></div>
        
        <?php $this->endWidget(); ?>
    </div>
</div><!--/row-->