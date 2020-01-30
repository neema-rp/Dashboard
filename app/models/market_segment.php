<?php
class MarketSegment extends AppModel {
	var $name = 'MarketSegment';
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

	//var $hasAndBelongsToMany = array('Template');
        
        
    public $hasAndBelongsToMany = array(
        'Template' =>
            array(
                'className' => 'Template',
                'joinTable' => 'template_formulas',
//                'foreignKey' => 'market_segment_id',
//                'associationForeignKey' => 'template_id',
                'unique' => false
            )
        );


function checkDuplicate($columnName){
	$column = $this->find('first',array('conditions'=>array('name'=>$columnName['name'], 'status !='=>2)));
	if(empty($column)){
		return true;
	}else{
		return false;
	}
}


}//end class
