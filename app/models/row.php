<?php
class Row extends AppModel {
	var $name = 'Row';
	var $displayField = 'name';
	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter row name',
			),
		'isUnique' => array(
				'rule' => array('checkDuplicate'),
				'message' => 'row name should be unique',
			)
		),
	);

	var $hasAndBelongsToMany = array('Sheet');



function checkDuplicate($rowName){
// echo "<pre>";
// print_r($columnName);
// exit;
	$row = $this->find('first',array('conditions'=>array('name'=>$rowName['name'], 'status !='=>2)));
	if(empty($row)){
		return true;
	}else{
		return false;
	}
}


}//end class
