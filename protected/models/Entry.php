<?php

/**
 * This is the model class for table "entry".
 *
 * The followings are the available columns in table 'entry':
 * @property integer $id
 * @property integer $invoice
 * @property string $item
 * @property integer $quantity
 * @property integer $price
 * @property integer $amount
 * @property integer $inventory
 */
class Entry extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Entry the static model class
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
		return 'entry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoice, item, amount, inventory', 'required'),
			array('invoice, quantity, price, amount, inventory', 'numerical', 'integerOnly'=>true),
			array('item', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, invoice, item, quantity, price, amount, inventory', 'safe', 'on'=>'search'),
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
			'Invoice'=>array(self::BELONGS_TO, 'Invoice','invoice'),
			'Inventory'=>array(self::BELONGS_TO, 'Inventory', 'inventory'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'invoice' => 'Invoice',
			'item' => 'Item',
			'quantity' => 'Quantity',
			'price' => 'Price',
			'amount' => 'Amount',
			'inventory'=> 'Internal Inventory Number',
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
		$criteria->compare('invoice',$this->invoice);
		$criteria->compare('item',$this->item,true);
		$criteria->compare('quantity',$this->quantity);
		$criteria->compare('price',$this->price);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('inventory', $this->inventory);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}