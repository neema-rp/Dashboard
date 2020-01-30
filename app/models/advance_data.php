<?php
class AdvanceData extends AppModel {
	var $name = 'AdvanceData';
	var $belongsTo = array(
             'AdvancedSheet' => array ('className' => 'AdvancedSheet', 'foreignKey'=>'advanced_sheet_id')
        );
}//end class
