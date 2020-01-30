<?php
class TeeTime extends AppModel {
	var $name = 'TeeTime';
        
        function getMonthlyData($month,$year,$user_id){
            $returnData = $this->find('all',array('conditions'=>array('TeeTime.month' => $month,'TeeTime.year' => $year,'TeeTime.user_id' => $user_id)));
            $array = array();
            if(!empty($returnData)){
                foreach($returnData as $data){
                    $array[$data['TeeTime']['time_lapse']][$data['TeeTime']['date']]['booked'] = $data['TeeTime']['booked_value'];
                    $array[$data['TeeTime']['time_lapse']][$data['TeeTime']['date']]['actual_value'] = $data['TeeTime']['actual_value'];
                    $array[$data['TeeTime']['time_lapse']][$data['TeeTime']['date']]['time_lapse'] = $data['TeeTime']['time_lapse'];
                    $array[$data['TeeTime']['time_lapse']][$data['TeeTime']['date']]['tee_time'] = $data['TeeTime']['tee_time'];
                    $array[$data['TeeTime']['time_lapse']][$data['TeeTime']['date']]['booked_value'] = $data['TeeTime']['booked_value'];
                    $array[$data['TeeTime']['time_lapse']][$data['TeeTime']['date']]['id'] = $data['TeeTime']['id'];
                }
            }
            return $array;
        }
        
        
}//end class
