<?php

class InvoiceController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view',),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','print','report', 'delivery'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete', 'generate'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$entry = new Entry;
		$entry->invoice = $id;
		$this->render('view',array(
			'model'=>$this->loadModel($id),
				'entry'=>$entry,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Invoice;
		$model->date = date("Y-m-d");
		$entries = array();
		for($i=0;$i<10;$i++){ $entries[] = new Entry; }
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Invoice']))
		{
			$model->attributes=$_POST['Invoice'];
			$entries = array();
			if(isset($_POST['entryCount'])){
				$count = intval($_POST['entryCount']);
				for($i=0;$i<$count;$i++){
					$entry = new Entry;
					$inventory_info = $_POST["Entry$i"."_name"];
					$inventory_info_arr = explode(":",$inventory_info);
					$inventory_id = intval($inventory_info_arr[0]);
					$inventory = Inventory::model()->findByPk($inventory_id);
					$entry->attributes=$_POST["Entry$i"];
					$entry->inventory = $inventory_id;
					//$entry->item = $inventory->name . " - " . $inventory->model;
					if($entry->amount != "" && $inventory_id != -1)
						$entries[] = $entry;
				}
			}
			if(count($entries) > 0){
				if ($model->save()){
					foreach($entries as $entry){
						$entry->invoice = $model->id;
						$entry->save();
						$inventory = Inventory::model()->findByPk($entry->inventory);

						$inventory->quantity = $inventory->quantity - $entry->quantity;
						$inventory->save();
					}
					// also need to update the inventory table to reflect the 
					// changes we made in our invoice instance.
					
					
					
					// if everything went well, we will return the page to the user.
					$this->redirect(array('view','id'=>$model->id));
				}
			}else{
				for($i=0;$i<10;$i++){
					$entries[] = new Entry;
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'entries'=>$entries
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$count = (isset($_POST['entryCount'])) ?  intval($_POST['entryCount']) : 0;
		
		if(isset($_POST['Invoice']))
		{
			$model->attributes=$_POST['Invoice'];
			for($i=0;$i<$count;$i++){
				//$item = new Item;
				//$item->attributes = $_POST["Item$i"];
				//$pk = $item->id;
				$_item = Entry::model()->findByPk(intval($_POST["Entry$i"."_id"]));
				$_item->attributes = $_POST["Entry$i"];
				$_item->save();
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
			'entries'=>$model->Entries
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}



	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Invoice('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Invoice']))
			$model->attributes=$_GET['Invoice'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionGenerate(){
		$names = file(getcwd() . "/protected/tests/names.txt");
		$streets = file(getcwd() . "/protected/tests/streets.txt");
		
		
		$name_arr = array();
		$street_arr = array();
		
		
		foreach($names as $line){
			$name = explode(" ", $line);
			$name_arr[] = trim($name[0]);
		}
		foreach($streets as $line){
			
			$street = explode(" ", $line);
			if(count($street) == 3){
				$name1 = trim($street[0]);
				$name2 = trim($street[1]);
				$name3 = trim($street[2]);
				$street_arr[] = $name1 . " " . $name2 . " " . $name3 . ", Toronto";
			}
		}
		
		$limit = 1000000;
		for($i=0;$i<$limit;$i++){
			set_time_limit(0); // resetting the MySQL timeout
			echo "Creating an invoice #$i <br/>";
			$invoice = new Invoice;
			$invoice->name =  $name_arr[rand(0, count($name_arr)-1)];
			$invoice->address = rand(1,5000) . " " . $street_arr[rand(0,count($street_arr)-1)];
			
			$y = 2012; //rand(2006,2012);
			if($y<2012){
				$m = rand(1,12);
			}else{
				$m = rand(1,11);
			}
			$m = 11;
			$d = rand(1,2);
			$invoice->date = date($y.'-'.$m.'-'.$d);
			$invoice->delivery_date = date($y.'-'.$m.'-'.$d);;
			$invoice->phone1 = "647" . rand(1,9) . rand(1,9) .rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9);
			$invoice->delivery_date = date($y.'-'.$m.'-'.$d);
			$invoice->delivery_cost = rand(10,50);
			if($invoice->save()){
				$number_of_items = rand(3,9);
				for($j=0;$j<$number_of_items;$j++){
					$entry = new Entry;
					$entry->invoice = $invoice->id;
					$entry->inventory = rand(3,2709);
					$inventory = Inventory::model()->findByPk($entry->inventory); 
					if($inventory){
						$entry->item = $inventory->name . " #" . $inventory->model;
						$entry->quantity = rand(1,$inventory->quantity);
						$entry->price = round($inventory->in * 1.3);
						$entry->amount = round($entry->price * 0.9 * $entry->quantity);
						
						if($entry->save()){
							echo "--Entry Created! <br/>";
							$inventory->quantity = $inventory->quantity - $entry->quantity;
							if($inventory->save()){
								echo "---Inventory Updated! <br/>";
							}else{
								$entry->delete();
								echo "***Inventory Error! Failed to update <br/>";
							}
						}else{
							echo "***Entry Error!  <br/>";
							print_r($entry->getErrors());
							echo "<br/>";
						}
					}else{
						echo "*Inventory Error! (invalid randomed inventory id) <br/>"; 
					}
				}
				
				if($invoice->Amount > 0){
					echo "Invoice Created! id=>".$invoice->id . "<br/>";
				}else{
					echo "Invoice has no entries! Deleting id=>".$invoice->id. "<br/>";
					$invoice->delete();
				}
			}else{
				echo "Invoice Error! <br/>";
				print_r($invoice->getError());
				echo "<br/>";
			}
			echo "<hr/>";
		}
		
	}

	public function actionDelivery(){
		
		if(isset($_POST['date'])){
			$date = $_POST['date'];
		}else{
			$date = date("Y-m-d");
		}
		
		$dataProvider=new CActiveDataProvider('Invoice', array(
		    'criteria'=>array(
		        'condition'=>"delivery_date='$date'",
		        'order'=>'id DESC',
		    ),
		    'pagination'=>array(
		        'pageSize'=>20,
		    ),
		));
		
		$this->render("delivery", array('dataProvider'=>$dataProvider, 'date'=>$date));
	}
	
	public function actionReport(){
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$start = $time;
		
		$rawData=Yii::app()->db->createCommand()
		->select('DISTINCT YEAR(date) AS year')
		->from('invoice')
		->order('YEAR(date) DESC')
		->queryAll();

		$years = array();
		$max = 0;
		foreach($rawData as $y){
			$years[$y['year']] = $y['year'];
			if($y['year']>=$max){
				$max = $y['year'];
			}
		}
		unset($rawData);
		
		$year = $max;
		if(isset($_GET['year'])){
			$year = $_GET['year'];
		}
		
		
		$rawData = Yii::app()->db->createCommand()
		->select('MONTH(invoice.date) as month, SUM(entry.amount) as revenue')
		->from(array('invoice','entry'))
		->where('invoice.id = entry.invoice and YEAR(invoice.date)=:year', array(':year'=>intval($year)))
		->group('month(invoice.date)')
		->order('month(invoice.date)')
		->queryAll();
		
		$monthly_revenue = array();
		foreach($rawData as $m){
			$monthly_revenue[intval($m['month'])] = intval($m['revenue']);
		}
		
		
		unset($rawData);

		
		$rawData = Yii::app()->db->createCommand()
		->select('MONTH(`invoice`.`date`) as month, SUM(`entry`.`amount` - `inventory`.`in` * `entry`.`quantity`) as revenue')
		->from(array('invoice','entry','inventory'))
		->where('invoice.id = entry.invoice and entry.inventory = inventory.id and  YEAR(invoice.date)=:year', array(':year'=>intval($year)))
		->group('month(invoice.date)')
		->order('month(invoice.date)')
		->queryAll();
		
		$monthly_net_revenue = array();
		foreach($rawData as $m){
			$monthly_net_revenue[intval($m['month'])] = intval($m['revenue']);
		}
		unset($rawData);
		
		/*
		 * filling up empty slots with 0
		 */
		for($i=1;$i<=12;$i++){
			if(!array_key_exists($i, $monthly_revenue)){
				$monthly_revenue[$i] = 0;
			}
			
			if(!array_key_exists($i, $monthly_net_revenue)){
				$monthly_net_revenue[$i] = 0;
			}
		}
		
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $start), 2);
		$this->render('report', array(
				'dataProvider'=>array_values($monthly_revenue), 
				'dataProvider2'=>array_values($monthly_net_revenue), 
				'year'=>$year, 
				'loadingTime'=>$total_time, 
				'years'=>$years)
				);
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Invoice::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='invoice-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	public function actionPrint($id){
		$this->renderPartial('print',array(
				'model'=>$this->loadModel($id),
		));
	}
}
