<?php

/**
 * This is the model class for table "invoice".
 *
 * The followings are the available columns in table 'invoice':
 * @property integer $id
 * @property string $date
 * @property string $address
 * @property string $phone1
 * @property string $phone2
 * @property integer $deposit
 * @property string $name
 * @property string $delivery_date
 * @property integer $delivery_cost
 */
class Invoice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Invoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date', 'required'),
			array('deposit, delivery_cost', 'numerical', 'integerOnly'=>true),
			array('address', 'length', 'max'=>256),
			array('phone1, phone2, name', 'length', 'max'=>20),
			array('delivery_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, date, address, phone1, phone2, deposit, name, delivery_date, delivery_cost', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
					'Entries'=>array(self::HAS_MANY, 'Entry', 'invoice'),
					'Amount' => array(self::STAT, 'Entry', 'invoice', 'select' =>"SUM(amount)",),
				);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Date',
			'address' => 'Address',
			'phone1' => 'Phone1',
			'phone2' => 'Phone2',
			'deposit' => 'Deposit',
			'name' => 'Name',
			'delivery_date' => 'Delivery Date',
			'delivery_cost' => 'Delivery Cost',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone1',$this->phone1,true);
		$criteria->compare('phone2',$this->phone2,true);
		$criteria->compare('deposit',$this->deposit);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('delivery_date',$this->delivery_date,true);
		$criteria->compare('delivery_cost',$this->delivery_cost);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}