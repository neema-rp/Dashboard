<?php
class ContactsController extends AppController {
	var $name = 'Contacts';
	var $helpers = array ('Html' ,'Form', 'Javascript', 'Session');
	var $components = array ('Sendemail','Session');
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('index','content','feature','aboutus','newhome','build_calendar','ajax_calendar'));
    }
	function admin_index()
	{
		$selected_field = $field='firstname';

		if($this->data['Contact']['search']==true)
		{
			$field=trim($this->data['Contact']['field']);
			$value=trim($this->data['Contact']['value']);
			$this->paginate['conditions'] = array('Contact.'.$field .' LIKE'=>'%'.$value.'%', 'Contact.status !=' => 2);
		}
		else
		{
			$this->paginate['conditions'] = array('Contact.status !=' => 2, 'Contact.email !='=>'');
			
		}
		$this->Contact->recursive = 0;
		$this->paginate['order']= array('Contact.id' => 'Desc');
		$this->set('contactus', $this->paginate('Contact'));
        $search_options = array('firstname' => 'First Name', 'lastname' => 'Last Name', 'email' => 'Email');
        $this->set(compact('search_options', 'selected_field'));
	}
	
	function admin_view($id = null) 
	{
		if (empty($id))
		{
			$this->Session->setFlash(__('Invalid id', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('contactus', $this->Contact->read(null, $id));
	}
	
	function index()
	{

        $this->layout = 'contactus_layout';
		$userModel = ClassRegistry::init('Admin');
		$userModel->recursive = '-1';
		$userData = $userModel->find('first', array('conditions' => array('id' => '1')));
		$this->set('userData', $userData);
		if(!empty($this->data))
		{
			if($this->Contact->save($this->data))
			{
// 				$this->Sendemail->GetAscotEmailFrom = $userData['Admin']['email'];
// 				$subject = 'Contactus Enquairy Information';
// 				 $inputmessage=$this->data;
// 				 $toGroup = $userModel->Field('email', array('id' =>'1'));
// 				 $addcc = $toGroup;
// 				 $result  = $this->Sendemail->sendcontactus($toGroup,$addcc, $inputmessage);
// 				if($result)
// 				{
					$this->Session->setFlash(__('The message has been sent successfully. Thank You ! ', true));
					
// 				}else{
// 						$this->Session->setFlash(__('The message could not be send. Please, try again.', true));
// 					 }
				
				$this->redirect(array('controller' => 'contacts', 'action' => 'index'));
			}else
			{
				$this->Session->setFlash('The message has not been saved', true);
				$this->redirect(array('controller' => 'contacts', 'action' => 'index'));
			}
			
		}// if(!empty($this->data))
	}
	
	function admin_delete($id) {
		$this->_delete($id);
	}
	
	private function _delete($id = null, $status=null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Contact', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Contact->softDelete($id, $status)) {
			if($status=='active')
            {
                $this->Session->setFlash(__('Contact Deactivated', true));
            } else if($status=='deactivated')
            {
                 $this->Session->setFlash(__('Contact Activated', true));
            }else{
                    $this->Session->setFlash(__('Contact deleted !', true));
                 }
		}		
		$this->redirect(array('action' => 'index'));
	}



	function admin_add() 
	{
// 		if(isset($this->data)){ pr($this->data);}
		if(isset($this->data)){
		    $userModel = ClassRegistry::init('Contact');
		    if($userModel->save($this->data))
		    {
		      $this->Session->setFlash(__('Contact saved', true));
		      $this->redirect(array('action' => 'index'));
		    }
		    else
		    {
		      $this->Session->setFlash(__('Contact was not saved', true));
		    }
		}
		
	}

	public function content($id = null) 
	{
		$this->layout = 'contactus_layout';
		if (!$id)
		{
			$this->Session->setFlash(__('Invalid id', true));
			$this->redirect(array('action' => 'index'));
		}

		$contentModel = ClassRegistry::init('Content');
		$contentModel->recursive = '-1';
		$contentData = $contentModel->find('first', array('conditions' => array('id' =>$id)));
		$this->set('contents', $contentData);

// 		print_R($contentData);
// 		exit;
	}
        
        
        public function feature(){
            $this->layout = 'features';
        }
        public function aboutus(){
            $this->layout = 'about_us';
        }
        public function newhome(){
            $this->layout = 'new-home';
        }

function build_calendar($month,$year,$sheetId,$column_property=null,$client_id=null) {
    //echo $sheetId;
    $this->layout = false;
     $this->autoRender = false;
     

     // Create array containing abbreviations of days of week.
     $daysOfWeek = array('S','M','T','W','T','F','S');

     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

     // How many days does this month contain?
     $numberDays = date('t',$firstDayOfMonth);

     // Retrieve some information about the first day of the
     // month in question.
     $dateComponents = getdate($firstDayOfMonth);

     // What is the name of the month in question?
     $monthName = $dateComponents['month'];

     // What is the index value (0-6) of the first day of the
     // month in question.
     $dayOfWeek = $dateComponents['wday'];

     // Create the table tag opener and day headers
     
      $this->Sheet = ClassRegistry::init('Sheet');
      $sheet_data = $this->Sheet->getData($sheetId);
   // $sheet_data = $this->requestAction('/admin/sheets/data/'.$sheetId);
    
   // echo '<pre>'; print_r($sheet_data); exit;

     $calendar = "<table id='calendar_table' cellspacing='0'>";
     $calendar .= "<caption>$monthName $year</caption>";
     $calendar .= "<thead><tr>";

     // Create the calendar headers

     foreach($daysOfWeek as $day) {
          $calendar .= "<th>$day</th>";
     } 

     // Create the rest of the calendar

     // Initiate the day counter, starting with the 1st.

     $currentDay = 1;

     $calendar .= "</tr></thead><tbody><tr>";

     // The variable $dayOfWeek is used to
     // ensure that the calendar
     // display consists of exactly 7 columns.

     if ($dayOfWeek > 0) { 
          $calendar .= "<td class='' style='background:#ccc;' colspan='$dayOfWeek'>&nbsp;</td>"; 
     }
     
     $month = str_pad($month, 2, "0", STR_PAD_LEFT);
  
     while ($currentDay <= $numberDays) {

          // Seventh column (Saturday) reached. Start a new row.

          if ($dayOfWeek == 7) {

               $dayOfWeek = 0;
               $calendar .= "</tr><tr>";

          }
          
          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
          
          $date = "$year-$month-$currentDayRel";

          
          $match = '0';
          
          
          $date_details = date('d/m/y',strtotime($date));
          if(!empty($sheet_data)){
              
              if(!empty($column_property)){
              
              foreach($sheet_data as $sheet_details){
                  if (($sheet_details['Date']) == ($date_details)) {
                      
                      $low = '4';
                      $moderate = '6';
                      $busy = '9';
                      
                      
                      $column_check = $this->requestAction('/columns/check_column_name/'.$column_property.'/'.$client_id);
                      
                      $color = '';
                      if(!empty($column_check)){
                          if(str_replace(",", "", $sheet_details[$column_property]) <= $column_check['ColumnRange']['low_value']){
                              $color = '#F4FA58'; //yellow
                          }elseif(str_replace(",", "", $sheet_details[$column_property]) <= $column_check['ColumnRange']['moderate_value']){
                              $color = '#64FE2E'; //green
                          }elseif(str_replace(",", "", $sheet_details[$column_property]) <= $column_check['ColumnRange']['busy_value']){
                              $color = '#0040FF'; //blue
                          }else{
                              $color = '#FF0000'; //red
                          }
                      }
                      
                       $calendar .= "<td class='date_has_event day' style='background:$color;' rel='$date'>$currentDay<div class='events'>
                            <ul>";
                      
                        $calendar .= "<li><span><b>$column_property : </b>".$sheet_details[$column_property]."</span>";
                        break;
                  }
                  
              }
                  
              }else{
                  
                   $calendar .= "<td class='date_has_event day' rel='$date'>$currentDay<div class='events'>
                            <ul>";
                   
                      foreach($sheet_data as $sheet_val){
                          if ($sheet_val['Date'] == $date_details) {
                           foreach($sheet_val as $sheet_key=>$sheet_value){
                               if($sheet_key != 'id' && $sheet_key != 'sheetId' && $sheet_key != 'Date'){
                                    $calendar .= "<li>
                                                        <span><b>$sheet_key</b> : $sheet_value</span>
                                                </li>";
                               }
                            }  
                          }
                   }
              }
             
              
          }else{

               $calendar .= "<td class='date_has_event day' rel='$date'>$currentDay<div class='events'>
                            <ul>";
              
              $calendar .= "<li>
                                            <span class='title'>Sorry</span>
                                            <span class='desc'>No Data Found.</span>
                                    </li>";
          }
        
         $calendar .= "</ul></div></td>";
          // Increment counters 
          $currentDay++;
          $dayOfWeek++;
     }
     // Complete the row of the last week in month, if necessary

     if ($dayOfWeek != 7) { 
         $remainingDays = 7 - $dayOfWeek;
          $calendar .= "<td class='' style='background:#ccc;' colspan='$remainingDays'>&nbsp;</td>"; 
     }
     
     $calendar .= "</tr>";
     $calendar .= "<tbody></table>";
     return $calendar;

}


public function ajax_calendar($department_id=null,$column_property=null){
         $this->layout = false;
        
        $sheetIds = array();
        for($i='0';$i<='5';$i++){
            $year = date('Y');
            $month = date('m');
            $month = $month + $i;
            if($month > '12'){ $month = $month - '12'; $year = $year+'1'; }
            $this->Sheet = ClassRegistry::init('Sheet');
            $monthSheet = $this->Sheet->find('first', array('conditions' => array('Sheet.status' => 1, 'Sheet.year' =>$year,'Sheet.month' =>$month,'Sheet.department_id'=>$department_id), 'fields' => array('Sheet.id'),'recursive' => '0'));
            $sheetIds[$i] = $monthSheet['Sheet']['id'];
        }
        $this->Sheet = ClassRegistry::init('Sheet');
        $all_sheets = $this->Sheet->find('all', array('conditions' => array('Sheet.status' => 1, 'Sheet.id' =>$sheetIds), 'fields' => array('Sheet.id', 'Sheet.department_id', 'Sheet.year', 'Sheet.month','Sheet.name','User.id'), 'recursive' => '0','order'=>array('month'=>'ASC','year'=>'ASC')));
        
        $this->Department = ClassRegistry::init('Department');        
        $this->User = ClassRegistry::init('User');
        
        $clientFrmDetpId = $this->Department->find('first', array('fields' => array('client_id'), 'conditions' => array('Department.id' => $department_id)));
        $userdata = $this->User->find('first', array('fields' => array('Client.hotelname'), 'conditions' => array('User.client_id' => $clientFrmDetpId['Department']['client_id'], 'User.status !=' => 2),'recursive'=>'0'));

        $this->set('all_sheets',$all_sheets);
        $this->set('user_data',$userdata);
        $this->set('column_property',$column_property);
        $this->set('client_id',$clientFrmDetpId['Department']['client_id']);
        
    }

	
}//end class