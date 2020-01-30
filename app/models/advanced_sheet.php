<?php
class AdvancedSheet extends AppModel {
	var $name = 'AdvancedSheet';
        var $displayField = 'name';
	var $belongsTo = array('Template','User');
        var $hasMany = array(
		'AdvanceData' =>array(
				'className' => 'AdvanceData',
				'foreignKey' => 'advanced_sheet_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => array('AdvanceData.date ASC', 'AdvanceData.column_id ASC','AdvanceData.market_segment_id ASC'),
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)	
	);
        //var $hasAndBelongsToMany = array('Column' => array('order' => array('Column.id ASC')),'Row'=> array('order'=>array('Row.id ASC')));
         var $hasAndBelongsToMany = array(
//           'MarketSegment'=>
//            array(
//                'className' => 'MarketSegment',
//                'joinTable' => 'template_formulas',
//                'foreignKey' => 'template_id',
//                'associationForeignKey' => 'market_segment_id',
//                'unique' => false
//            ),
            'Column'=>
            array(
                'className' => 'Column',
                'joinTable' => 'advance_datas',
                'foreignKey' => 'advanced_sheet_id',
                'associationForeignKey' => 'column_id',
                'unique' => false
            ),
             'Row'=>
            array(
                'className' => 'Row',
                'joinTable' => 'advance_datas',
                'foreignKey' => 'advanced_sheet_id',
                'associationForeignKey' => 'row_id',
                'unique' => false
            )
            );
}//end class
