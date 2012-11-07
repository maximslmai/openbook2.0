<?php

class EntryController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Entry;
		
		if(isset($_POST['amount']) && isset($_POST['invoice']) && isset($_POST['name']))
		{
			$inventory_info = $_POST["name"];
			$inventory_info_arr = explode(":",$inventory_info);
			$inventory_id = intval($inventory_info_arr[0]);
			$inventory = Inventory::model()->findByPk($inventory_id);
			if($inventory->id){
				$inventory_id = $inventory->id; 
				$model->inventory = $inventory_id;
				$model->item = $inventory->name . " - " . $inventory->model;
				
				$model->price = intval($_POST['price']);
				$model->amount = intval($_POST['amount']);
				$model->invoice = intval($_POST['invoice']);
				
				if($model->save())
					$this->redirect(array('/invoice/view','id'=>$model->invoice));
			}else{
				throw new Exception("Invalid Inventory ID");
			}
		}

		$this->render('/invoice/view',array(
			'id'=>$_POST['invoice'],
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

		if(isset($_POST['Entry']))
		{
			$model->attributes=$_POST['Entry'];
			if($model->save())
				$this->redirect(array('/invoice/view','id'=>$model->Invoice));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$entry = $this->loadModel($id);
		$inventory = $entry->Inventory;

		$inventory->quantity = $inventory->quantity + $entry->quantity;
	
		if($entry->delete())
			$inventory->save();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Entry');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Entry('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Entry']))
			$model->attributes=$_GET['Entry'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Entry::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='entry-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
