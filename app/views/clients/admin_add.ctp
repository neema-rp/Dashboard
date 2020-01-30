<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#ClientAdd").validationEngine();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i> Add Hotel</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Add New Hotel</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
            <?php echo $this->Form->create('Client', array('type' => 'file', 'id' => 'ClientAdd','class'=>'form-horizontal'));?>
	
            	<?php
                if(isset($allhotels)){
                $parent_hotels = array();
                  foreach($allhotels as $hotel)
                  {
                    $parent_hotels[$hotel['Client']['id']] = $hotel['Client']['hotelname'];
                  }
                }
		echo $this->Form->input('parent_id', array('type'=>'select','options'=>$parent_hotels,'empty'=>'-- Select Parent Hotel --','class'=>'span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('hotelname',array('id'=>'hotelname','class'=>'validate[required] span6','placeholder'=>'Hotelname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                echo $this->Form->input('hotel_profile',array('id'=>'hotel_profile','type'=>'textarea','class'=>'span6','placeholder'=>'Hotel Profile','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('username',array('id'=>'username','class'=>'validate[required,length[0,20]] span6','placeholder'=>'Username','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('password',array('id'=>'password','class'=>'validate[required] span6','placeholder'=>'Password','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('firstname',array('id'=>'firstname','class'=>'validate[required,length[0,20]] span6','placeholder'=>'Firstname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('lastname',array('id'=>'lastname','class'=>'span6','placeholder'=>'Lastname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('email',array('id'=>'email','class'=>'validate[required,custom[email]] span6','placeholder'=>'Email','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('phone',array('id'=>'phone','class'=>'validate[required,length[0,10]]] span6','placeholder'=>'Phone','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                echo $this->Form->input('number_of_rooms',array('id'=>'number_of_rooms','class'=>'span6','placeholder'=>'Number Of Rooms','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		echo $this->Form->input('restaurant_open_hours',array('id'=>'restaurant_open_hours','class'=>'span6','placeholder'=>'Restaurant Open Hours','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                echo $this->Form->input('chairs_in_restaurant',array('id'=>'chairs_in_restaurant','class'=>'span6','placeholder'=>'Chairs In Restaurant','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                echo $this->Form->input('clientlogo', array('type' => 'file','escape' => false,'class'=>'span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
	
                echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
                echo "&nbsp;&nbsp;";
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'index'), array('class' => 'btn btn-success', 'escape' => false));
                echo $this->Form->end();
                ?>
                    
            </div></div></div></div>
</div>