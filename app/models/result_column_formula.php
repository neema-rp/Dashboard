<?php
class ResultColumnFormula extends AppModel {
	var $name = 'ResultColumnFormula';

	
public $belongsTo = array (
        'Template' => array ('className' => 'Template', 'foreignKey'=>'template_id'),
        'Column' => array ('className' => 'Column', 'foreignKey'=>'column_id')
    );



}//end class
