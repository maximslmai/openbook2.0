<h1>Company Name</h1>

<p>
Company Address:<br/>
Phone:  <br/>
Hours:  <br/>
</p>
<hr/>
<h2>Invoice Number: <?php echo hash("md5",$model->id); ?></h2>
<h4>Invoice Date: <?php echo $model->date; ?></h4>
<h4>Client Name: <?php echo $model->name; ?></h4>
<h4>Client Address: <?php echo $model->address; ?></h4>
<h4>Phone: <?php echo $model->phone1; ?></h4>
<table border="1" width="100%">
<tr>
<th>DESCRIPTION</th>
<th>QTY</th>
<th>PRICE</th>
<th>AMOUNT</th>
</tr>
<?php 
foreach($model->Entries as $entry){
?>
	<tr>
		<td><?php echo $entry->item; ?></td>
		<td align="right"><?php echo $entry->quantity; ?></td>
		<td align="right"><?php echo $entry->price; ?></td>
		<td align="right"><?php echo $entry->amount; ?></td>
	</tr>
<?php
}
?>
<tr><td></td><td></td><td></td><td></td></tr>
<tr>
	<td></td><td></td>
	<td><strong>Subtotal</strong></td>
	<td align="right"><?php echo $model->Amount; ?></td>
</tr>
<tr>
	<td></td><td></td>
	<td><strong>Shipping & Handling</strong></td>
	<td align="right"><?php echo $model->delivery_cost; ?></td>
</tr>
<tr>
	<td></td><td></td>
	<td><strong>Deposit</strong></td>
	<td align="right"><?php echo $model->deposit; ?></td>
</tr>
<tr>
	<td></td><td></td>
	<td><strong>Included Tax(13%)</strong></td>
	<td align="right"><?php echo round(($model->Amount)/1.13*0.13,2) ?></td>
</tr>
<tr>
	<td></td><td></td>
	<td><strong>Total</strong></td>
	<td align="right"><?php echo $model->Amount + $model->delivery_cost; ?></td>
</tr>
<tr>
	<td></td><td></td>
	<td><strong>Balance</strong></td>
	<td align="right"><?php echo $model->Amount + $model->delivery_cost - $model->deposit; ?></td>
</tr>
</table>
<hr/>
Customer Copy - THANK YOU <br/>
printed on <?php echo date("F j, Y, g:i a"); ?>
