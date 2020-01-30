<?php
class ActivitiesController extends AppController {

	var $name = 'Activities';
	var $components = array ('Sendemail', 'Session');
        
         function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('get_details');
        }

	
	public function admin_index()
	{
            
		$dwld  = isset($this->data['Activity']['report']);
                $between = ''; $field = ''; $condition = '1=1 ';
		if (!empty($this->data['Activity'])) {

                    $field = trim($this->data['Activity']['field']);
                    $value = trim($this->data['Activity']['value']);
                    
                    $field1 = trim($this->data['Activity']['field1']);
                    $value1 = trim($this->data['Activity']['value1']);
                    
                    if(!empty($this->data['Activity']['value']))
                    {
                            $condition .= ' AND Activity.client_id ="'.$value.'"';
                    } else {
                            $condition .= '';
                    }

                    if(!empty($field1) && !empty($value1))
                    {
                            $between  .= " And Activity.logged_in_time BETWEEN '{$field1} 00:00:00' AND '{$value1} 23:00:00' ";
                            $condition .= $between;
                    }

                }else{
                    $condition = '';
                }
                
                if (!empty($condition)) {
			$conditions = "where ". $condition;
		} else {
			$conditions = '';
		}
                
               // echo $conditions;
                
                $sql="SELECT
			Activity.id,
			Activity.user_id,
			Activity.client_id,
			Activity.logged_in_ip,
                        Activity.logged_in_time,
			COUNT(Activity.client_id) as total_login
			FROM `activities` AS Activity
			$conditions
			GROUP BY Activity.client_id order by Activity.logged_in_time DESC";
                
                //echo $sql; exit;
                
                $activitiesData = $this->Activity->query($sql);

                $this->Client = ClassRegistry::init('Client');
                $clients_list = $this->Client->find('list',array('conditions'=>array('Client.status'=>'1'),'fields'=>array('Client.id','Client.hotelname'),'recursive'=>'-1'));
                $this->set('clients_list',$clients_list);
                
		if (!empty($activitiesData)) {
			$csvString = '';
			$tempArray = array();
			foreach ($activitiesData as $key => $activities) {
				$query = 'SELECT SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND,logged_in_time,logout_time))) AS total_login_time, COUNT(Activity.client_id) AS total_login, logged_in_ip FROM activities as Activity WHERE Activity.client_id='. $activities['Activity']['client_id'] . $between .' GROUP BY Activity.client_id';
				$logintimeData = $this->Activity->query($query);//debug($logintimeData);
				if (empty($logintimeData)) {
					$tempArray[$key]['Activity']['total_login_time'] = 'N/A';
					$tempArray[$key]['Activity']['total_login']      = 0;
					//$tempArray[$key]['Activity']['logged_in_ip']     = 'N/A';
				} else {
					$tempArray[$key]['Activity'] = $logintimeData[0][0];
					//$tempArray[$key]['Activity']['logged_in_ip'] = $logintimeData[0]['Activity']['logged_in_ip'];
				}
                                $tempArray[$key]['Activity']['hotelname'] = $clients_list[$activities['Activity']['client_id']];
                                
                                
                                $query1 = 'SELECT logged_in_time, logged_in_ip FROM activities as Activity WHERE Activity.client_id='. $activities['Activity']['client_id'] . $between .' order by logged_in_time DESC LIMIT 1';
				$logintimeData1 = $this->Activity->query($query1);
                                $tempArray[$key]['Activity']['logged_in_time']      = $logintimeData1[0]['Activity']['logged_in_time'];
                                $tempArray[$key]['Activity']['logged_in_ip']      = $logintimeData1[0]['Activity']['logged_in_ip'];
				
			}

			$meargeData = Set::merge($activitiesData, $tempArray);//debug($meargeData);//exit;
                        
			//echo '<pre>'; print_r($meargeData);exit;
                        
			if ($dwld) {
				$csvString = '"#","Hotel Name","#Login","Spent Hours","Last Login","Tracked IP"'. "\n\n";
				foreach ($meargeData as $csvkey => $csvData) {
					$csvString .= '"'. ++$csvkey .'","'. $csvData['Activity']['hotelname'] .'","'. $csvData['Activity']['total_login'] .'","'. $csvData['Activity']['total_login_time'] .'",';
					$csvString .= '"'. $csvData['Activity']['logged_in_time'] .'","'. $csvData['Activity']['logged_in_ip'] .'"'. "\n";
					
                                        
                                        $client_id = $csvData['Activity']['client_id'];
                                        $field1 = (isset($field1)&&!empty($field1))?$field1:'';
                                        $value1 = (isset($value1)&&!empty($value1))?$value1:'';
                                        $user_details = $this->requestAction('/Activities/get_details/'.$client_id.'/'.$field1.'/'.$value1);
                                    
					if(!empty($user_details)){ 
						
						foreach($user_details as $key=>$user_detail) {
							$total_login_time = (empty($user_detail['total_login_time']))?'':$user_detail['total_login_time'];
							$csvString .= '"","'.$csvData['Activity']['hotelname'].' - '. $user_detail['user_name'] .'","'. $user_detail['total_login'] .'","'.$total_login_time.'",';
							$csvString .= '"'. $user_detail['last_login'] .'","'. $user_detail['logged_in_ip'] .'"'. "\n";
						}
					}
				}

				// Download the CSV
				header("Pragma: public");  
				header("Content-Type: application/octet-stream");
				header("Content-Disposition: attachment; filename=client_activity_". date('Y_m_d_H_i_s') .".csv");
				echo $csvString;
				exit;
			}

			$this->set('activities', $meargeData);
		} else {
			$this->set('activities', $activitiesData);
		}

		$this->set('selected', $field);
	}
        
	
   function get_details($client_id,$field1=null,$value1=null)
    {
       $this->autoRender = false;
        $this->layout = '';
	$usr_details = array();
        $usr_lists = array();
	
	$usr_lists = Classregistry::init('User')->find('all',array('conditions'=>array('User.client_id'=>$client_id,'User.status'<>'2'),'fields'=>array('User.username','User.last_login','User.id'),'recursive'=>'-1'));
	
        //echo '<pre>'; print_r($usr_lists); exit;
        
        $this->Client = ClassRegistry::init('Client');
        $clients_data = $this->Client->find('first',array('conditions'=>array('Client.id'=>$client_id),'fields'=>array('Client.id','Client.hotelname','Client.username'),'recursive'=>'-1'));
        
        if(!empty($usr_lists)){
		foreach($usr_lists as $key=>$usr_list){
			if(!empty($usr_list)){
                            
				$usr_details[$key]['user_name'] = $usr_list['User']['username'];
				
				$between = '';
				if(!empty($field1) && !empty($value1))
				{
					$between   .= " And Activity.logged_in_time BETWEEN '{$field1} 00:00:00' AND '{$value1} 23:59:59' ";
				}
				
				$activity_details = Classregistry::init('Activity')->query('SELECT SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND,logged_in_time,logout_time))) AS total_login_time, COUNT(Activity.client_id) AS total_login, logged_in_ip,Activity.logged_in_time FROM activities as Activity WHERE Activity.client_id='.$client_id.' ' . $between .'AND Activity.user_id='.$usr_list['User']['id'].' GROUP BY Activity.user_id  order by Activity.logged_in_time DESC');
                                
                                
                                $query1 = 'SELECT logged_in_time, logged_in_ip FROM activities as Activity WHERE Activity.client_id='.$client_id.' ' . $between .'AND Activity.user_id='.$usr_list['User']['id'].' order by logged_in_time DESC LIMIT 1';
				$logintimeData1 = $this->Activity->query($query1);
                                $usr_details[$key]['last_login']      = $logintimeData1[0]['Activity']['logged_in_time'];
                                $usr_details[$key]['logged_in_ip']      = $logintimeData1[0]['Activity']['logged_in_ip'];
                                
                                //$usr_details[$key]['last_login'] = @$activity_details[0]['Activity']['logged_in_time'];
                                $usr_details[$key]['total_login_time'] = isset($activity_details[0][0]['total_login_time'])?$activity_details[0][0]['total_login_time']:'N/A';
				$usr_details[$key]['total_login'] = isset($activity_details[0][0]['total_login'])?$activity_details[0][0]['total_login']:0;
				//$usr_details[$key]['logged_in_ip'] = isset($activity_details[0]['Activity']['logged_in_ip'])?$activity_details[0]['Activity']['logged_in_ip']:'N/A';
			}
			
		}
        }
        
        
        if(!empty($clients_data)){
                $key = $key + 1;
                $usr_details[$key]['user_name'] = $clients_data['Client']['username'].'(Client)';
                $between = '';
                if(!empty($field1) && !empty($value1))
                {
                        $between   .= " And Activity.logged_in_time BETWEEN '{$field1} 00:00:00' AND '{$value1} 23:59:59' ";
                }

                $activity_details = Classregistry::init('Activity')->query('SELECT SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND,logged_in_time,logout_time))) AS total_login_time, COUNT(Activity.client_id) AS total_login, logged_in_ip,Activity.logged_in_time FROM activities as Activity WHERE Activity.client_id='.$client_id.' ' . $between .'AND Activity.user_id=0 GROUP BY Activity.user_id  order by Activity.logged_in_time DESC');

                $query1 = 'SELECT logged_in_time, logged_in_ip FROM activities as Activity  WHERE Activity.client_id='.$client_id.' ' . $between .'AND Activity.user_id=0 order by logged_in_time DESC LIMIT 1';
                $logintimeData1 = $this->Activity->query($query1);
                $usr_details[$key]['last_login']      = $logintimeData1[0]['Activity']['logged_in_time'];
                $usr_details[$key]['logged_in_ip']      = $logintimeData1[0]['Activity']['logged_in_ip'];

                //$usr_details[$key]['last_login'] = @$activity_details[0]['Activity']['logged_in_time'];
                $usr_details[$key]['total_login_time'] = isset($activity_details[0][0]['total_login_time'])?$activity_details[0][0]['total_login_time']:'N/A';
                $usr_details[$key]['total_login'] = isset($activity_details[0][0]['total_login'])?$activity_details[0][0]['total_login']:0;
                //$usr_details[$key]['logged_in_ip'] = isset($activity_details[0]['Activity']['logged_in_ip'])?$activity_details[0]['Activity']['logged_in_ip']:'N/A';
        }

        //echo '<pre>'; print_r($usr_details); exit;
        
        return $usr_details;
    }
        

    function admin_performance_chart(){
        $this->Client = ClassRegistry::init('Client');
        $clients_list = $this->Client->find('list',array('conditions'=>array('Client.status'=>'1'),'fields'=>array('Client.id','Client.hotelname'),'recursive'=>'-1'));
        asort($clients_list);
        $this->set('clients_list',$clients_list);
    }
    
    function client_performance_chart(){
        $clientId = $this->Auth->user('id');
        $this->Client = ClassRegistry::init('Client');
        $clients_list = $this->Client->find('list', array('conditions' => array('OR'=>array('Client.parent_id' => $clientId,'Client.id' => $clientId), 'Client.status' => 1), 'fields'=>array('Client.id','Client.hotelname'), 'recursive' => '0'));
        $this->set('clients_list',$clients_list);
    }
    function staff_performance_chart(){
        $clientId = $this->Auth->user('client_id');
        $this->Client = ClassRegistry::init('Client');
        $clients_list = $this->Client->find('list',array('conditions'=>array('Client.id' => $clientId,'Client.status'=>'1'),'fields'=>array('Client.id','Client.hotelname'),'recursive'=>'-1'));
        $this->set('clients_list',$clients_list);
    }
    function admin_lookup_chart(){
        $this->Client = ClassRegistry::init('Client');
        $clients_list = $this->Client->find('list',array('conditions'=>array('Client.status'=>'1'),'fields'=>array('Client.id','Client.hotelname'),'recursive'=>'-1'));
        asort($clients_list);
        $this->set('clients_list',$clients_list);
    }
    function client_lookup_chart(){
        $clientId = $this->Auth->user('id');
        $this->Client = ClassRegistry::init('Client');
        $clients_list = $this->Client->find('list', array('conditions' => array('OR'=>array('Client.parent_id' => $clientId,'Client.id' => $clientId), 'Client.status' => 1), 'fields'=>array('Client.id','Client.hotelname'), 'recursive' => '0'));
        $this->set('clients_list',$clients_list);
    }
    function staff_lookup_chart(){
        $clientId = $this->Auth->user('client_id');
        $this->Client = ClassRegistry::init('Client');
        $clients_list = $this->Client->find('list',array('conditions'=>array('Client.id' => $clientId,'Client.status'=>'1'),'fields'=>array('Client.id','Client.hotelname'),'recursive'=>'-1'));
        $this->set('clients_list',$clients_list);
    }
    
     function client_weekly_report() {
                $clientId = $this->Auth->user('id');
                $this->Client = ClassRegistry::init('Client');
                
                $parent_data = $this->Client->find('first',array('conditions'=>array('Client.id'=>$clientId,'Client.status'=>1),'fields'=>'parent_id','recursive'=>'0'));
                if(!empty($parent_data)){
                       $parent_id = $parent_data['Client']['parent_id'];
                }else{
                        $parent_id = '';
                }
                
                if(!empty($parent_id)){
                    $child_data = $this->Client->find('all',
                        array('conditions'=>
                            array('OR'=>array('Client.parent_id'=>$clientId,'Client.id'=>array($clientId,$parent_id)),'Client.status'=>1)
                        ,'fields'=>'hotelname,id','recursive'=>'0'));
                    
                }else{
                    $child_data = $this->Client->find('all',
                        array('conditions'=>
                            array('OR'=>array('Client.parent_id'=>$clientId,'Client.id'=>$clientId),'Client.status'=>1)
                        ,'fields'=>'hotelname,id','recursive'=>'0'));
                }
                 $this->set('clientId',$clientId);
                $this->set('child_data',$child_data);
               
        }//end index()

        
         function staff_weekly_report() {
                $clientId = $this->Auth->user('client_id');
                $this->Client = ClassRegistry::init('Client');
                
                $parent_data = $this->Client->find('first',array('conditions'=>array('Client.id'=>$clientId,'Client.status'=>1),'fields'=>'parent_id','recursive'=>'0'));
                if(!empty($parent_data)){
                       $parent_id = $parent_data['Client']['parent_id'];
                }else{
                       $parent_id = '';
                }
                
                if(!empty($parent_id)){
                    $child_data = $this->Client->find('all',
                        array('conditions'=>
                            array('OR'=>array('Client.parent_id'=>$clientId,'Client.id'=>array($clientId,$parent_id)),'Client.status'=>1)
                        ,'fields'=>'hotelname,id','recursive'=>'0'));
                    
                }else{
                    $child_data = $this->Client->find('all',
                        array('conditions'=>
                            array('OR'=>array('Client.parent_id'=>$clientId,'Client.id'=>$clientId),'Client.status'=>1)
                        ,'fields'=>'hotelname,id','recursive'=>'0'));
                }
                 $this->set('clientId',$clientId);
                $this->set('child_data',$child_data);
               
        }//end index()
        
        function admin_weekly_report() {
                $this->Client = ClassRegistry::init('Client');
                $all_hotels = $this->Client->find('all',array('conditions'=>array('Client.status !=' => 2),'fields'=>'id,hotelname','order'=>'Client.hotelname ASC'));
                $this->set('all_hotels', $all_hotels);
               
        }//end index()

        

}// class end
