<?php
class ResultColumnTemplate extends AppModel {
	var $name = 'ResultColumnTemplate';

	
public $belongsTo = array (
        'Template' => array ('className' => 'Template', 'foreignKey'=>'template_id'),
        'Column' => array ('className' => 'Column', 'foreignKey'=>'column_id')
    );



}//end class
