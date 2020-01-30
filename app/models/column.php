<?php
class Column extends AppModel {
	var $name = 'Column';
	var $displayField = 'name';
	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter column name',
			),
			'isUnique' => array(
				'rule' => array('checkDuplicate'),
				'message' => 'column name should be unique',
			)
		),
	);

	var $hasAndBelongsToMany = array('Sheet');


function checkDuplicate($columnName){
// echo "<pre>";
// print_r($columnName);
// exit;
	$column = $this->find('first',array('conditions'=>array('name'=>$columnName['name'], 'status !='=>2)));
	if(empty($column)){
		return true;
	}else{
		return false;
	}
}


}//end class
