<?php
class ColumnsTemplate extends AppModel {
	var $name = 'ColumnsTemplate';

	
public $belongsTo = array (
        'Template' => array ('className' => 'Template', 'foreignKey'=>'template_id')
    );



}//end class
