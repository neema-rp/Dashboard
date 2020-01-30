<?php
class MarketSegmentsTemplate extends AppModel {
	var $name = 'MarketSegmentsTemplate';

	
public $belongsTo = array (
        'Template' => array ('className' => 'Template', 'foreignKey'=>'template_id')
    );



}//end class
