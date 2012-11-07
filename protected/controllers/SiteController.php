<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionAbout(){
		$this->render("pages/about");
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		
		$week= intval(date('W'));
		$year = intval(date('Y'));
		$month = intval(date('m'));
		$day = intval(date('d'));
		$date = date('Y-m-d');
		
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
		
		$rawData = Yii::app()->db->createCommand()
		->select(array('count(*) as num'))
		->from('invoice')
		->where('YEAR(date) =:year AND WEEK(date)=:week', array(':year'=>$year, ':week'=>$week))
		->queryRow();
		
		$open_invoices = $rawData['num'];
		unset($rawData);
		
		
		$rawData = Yii::app()->db->createCommand()
		->select('SUM(entry.amount) as total')
		->from(array('invoice','entry'))
		->where('invoice.id = entry.invoice and WEEK(invoice.date)=:week and YEAR(invoice.date)=:year', array(':week'=>$week, ':year'=>$year))
		->queryRow();
		
		$week_total = $rawData['total'];
		unset($rawData);
		
		
		$rawData = Yii::app()->db->createCommand()
		->select('SUM(entry.amount) as total')
		->from(array('invoice','entry'))
		->where('invoice.id = entry.invoice and invoice.date=:date', array(':date'=>$date))
		->queryRow();
		
		$day_total = $rawData['total'];
		unset($rawData);
		unset($date);
		
		$rawData = Yii::app()->db->createCommand()
		->select('SUM(entry.amount) as total')
		->from(array('invoice','entry'))
		->where('invoice.id = entry.invoice and YEAR(invoice.date)=:year and MONTH(invoice.date)=:month', array(':year'=>$year, ':month'=>$month))
		->queryRow();
		
		$month_total = $rawData['total'];
		unset($rawData);
		
		
		$rawData = Yii::app()->db->createCommand()
		->select('MONTH(invoice.date) as month, SUM(entry.amount) as revenue')
		->from(array('invoice','entry'))
		->where('invoice.id = entry.invoice and YEAR(invoice.date)=:year', array(':year'=>$year))
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
		
		
		$recent_invoices = new CActiveDataProvider('Invoice', array(
			    'criteria'=>array(
			        'order'=>'date DESC',
			    	'limit'=>'10',
			    ),
			));
		
		$recent_deliveries = new CActiveDataProvider('Invoice', array(
				'criteria'=>array(
						'order'=>'delivery_date DESC',
						'limit'=>'10',
				),
		));
		
		$this->render('index', array('monthly_net_revenue'=>$monthly_net_revenue, 
				'monthly_revenue'=>$monthly_revenue, 
				'month_total'=>$month_total,
				'week_total'=>$week_total,
				'day_total'=>$day_total,
				'open_invoices'=>$open_invoices,
				'year'=>$year,
				'years'=>$years,
				'recent_invoices'=>$recent_invoices,
				'recent_deliveries'=>$recent_deliveries
				)
			);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}