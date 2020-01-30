<?php
class RowsTemplate extends AppModel {
	var $name = 'RowsTemplate';
	public $belongsTo = array (
        'Template' => array ('className' => 'Template', 'foreignKey'=>'template_id')
    );




}//end class
