<?php
/* @var $this InvoiceController */
/* @var $model Invoice */
/* @var $form CActiveForm */
?>



<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invoice-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php 
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'invoice_date',
				'value'=>date("m/d/Y"),
				// additional javascript options for the date picker plugin
				'options'=>array(
						'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
						'style'=>'height:20px;'
				),
		));
		?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone1'); ?>
		<?php echo $form->textField($model,'phone1',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phone1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone2'); ?>
		<?php echo $form->textField($model,'phone2',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phone2'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>
	<div id="map_canvas"></div>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deposit'); ?>
		<?php echo $form->textField($model,'deposit'); ?>
		<?php echo $form->error($model,'deposit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'delivery_date'); ?>
		<?php 
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'invoice_delivery_date',
				'value'=>"00-00-0000",
				// additional javascript options for the date picker plugin
				'options'=>array(
						'showAnim'=>'fold',
				),
				'htmlOptions'=>array(
						'style'=>'height:20px;'
				),
		));
		?>
		<?php echo $form->error($model,'delivery_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'delivery_cost'); ?>
		<?php echo $form->textField($model,'delivery_cost'); ?>
		<?php echo $form->error($model,'delivery_cost'); ?>
	</div>
	
	
	<hr/>
	<?php 
	
	for($i=0;$i<count($entries); $i++){
	?>
		<div class="row">
		
		<?php
		$entry = $entries[$i];
		echo CHtml::hiddenField("Entry".$i."_id", $entry->id);
		echo CHtml::hiddenField("Entry".$i."[inventory]", -1);
		echo "Inventory Name: ";
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'Entry'.$i."_name",
				'source'=>$this->createUrl('inventory/autocomplete'),
				// additional javascript options for the autocomplete plugin
				'options'=>array(
						'minLength'=>'2',
				),
		));
		echo "Price: " .  CHtml::textField("Entry".$i."[price]", $entry->price, array("size"=>5));
		echo "Quantity: " .  CHtml::textField("Entry".$i."[quantity]", $entry->quantity, array("size"=>5));
		echo "Amount: " .  CHtml::textField("Entry".$i."[amount]", $entry->amount, array("size"=>5));
		?>
		
		</div>
		<?php
	}
	?>
	
	<?php 
	echo CHtml::hiddenField("entryCount",count($entries));
	?>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->