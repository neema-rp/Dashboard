<?php
class Template extends AppModel {
	var $name = 'Template';
	//var $displayField = 'name';
        var $hasMany = array(
//		'TemplateFormula'=>array(
//				'order'=>array('TemplateFormula.column_order ASC')
//			),
            'ResultColumnTemplate'=>array(
				'order'=>array('ResultColumnTemplate.id ASC')
			),
            'ColumnsTemplate'=>array(
				'order'=>array('ColumnsTemplate.id ASC')
			),
//            'RowsTemplate'=>array(
//				'order'=>array('RowsTemplate.id ASC')
//			),
            'MarketSegmentsTemplate'=>array(
				'order'=>array('MarketSegmentsTemplate.id ASC')
			)
	);
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
                'joinTable' => 'columns_templates',
                'foreignKey' => 'template_id',
                'associationForeignKey' => 'column_id',
                'unique' => false
            ),
//             'Row'=>
//            array(
//                'className' => 'Row',
//                'joinTable' => 'template_formulas',
//                'foreignKey' => 'template_id',
//                'associationForeignKey' => 'row_id',
//                'unique' => false
//            )
            );

}//end class
