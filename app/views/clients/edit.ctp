<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
    $("#ClientProfileEdit").validationEngine();
    $("#ClientResetPass").validationEngine();
});
</script>


<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotel <small><i class="icon-double-angle-right"></i> Edit Profile</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Edit Profile</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

                        <?php echo $this->Form->create('Client', array('type' => 'file', 'id' => 'ClientProfileEdit','class'=>'form-horizontal'));
                        
                        echo $this->Form->input('username',array('class'=>'validate[required,length[0,20]] span6','placeholder'=>'Username','label'=>true,'label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('firstname',array('class'=>'validate[required,length[0,20]] span6','placeholder'=>'Firstname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));//echo $client['Client']['firstname'];
			echo $this->Form->input('lastname',array('class'=>'validate[required] text-input span6','placeholder'=>'Lastname','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));//echo $client['Client']['lastname'];
                        echo $this->Form->input('email',array('class'=>'validate[required,custom[email]] span6','placeholder'=>'Email','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));//echo $client['Client']['email'];
			echo $this->Form->input('phone',array('class'=>'validate[required] span6','placeholder'=>'Phone','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));//echo $client['Client']['phone'];
                        
                        echo $this->Form->input('number_of_rooms',array('id'=>'number_of_rooms','class'=>'span6','placeholder'=>'Number Of Rooms','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('restaurant_open_hours',array('id'=>'restaurant_open_hours','class'=>'span6','placeholder'=>'Restaurant Open Hours','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        echo $this->Form->input('chairs_in_restaurant',array('id'=>'chairs_in_restaurant','class'=>'span6','placeholder'=>'Chairs In Restaurant','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                
                        echo $this->Form->input('clientlogo', array('type' => 'file','placeholder'=>'Logo','class'=>'span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
                        ?>
			
                    <div class="control-group">
                        <label class="control-label"><?php __('Existing Logo'); ?></label>
                        <span>
                                <?php echo $this->data['Client']['logo'] ? $this->Html->image('/files/clientlogos'. DS . $this->data['Client']['logo'] , array('width'=>200 , 'height'=>120)) : $this->Html->image('/img/pna.png', array('width'=>200 , 'height'=>120)); ?>
                                &nbsp;
                         </span>
                    </div>
            <?php
            echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
            echo "&nbsp;&nbsp;";
            echo $this->Html->link('Cancel', array('prefix' => 'clients', 'clients' => true,'action' => 'profile'), array('class' => 'btn btn-success', 'escape' => false));
            echo $this->Form->end();
            ?>
        </div></div></div></div>
</div>

<div class="widget-box">
    <div class="widget-header widget-header-blue widget-header-flat">
            <h4 class="lighter">Change password</h4>
        </div>
<div class="widget-body">
     <div class="widget-main">
        <div class="row-fluid">
	<?php echo $this->Form->create('Client', array('id' => 'ClientResetPass','class'=>'form-horizontal'));?>
		<?php
			echo $this->Form->input('id');
			echo $this->Form->hidden('username');
			echo $this->Form->input('password', array('value' => '','id'=>'password','class'=>'validate[required] text-input span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
			echo $this->Form->input('confirm_password', array('type' => 'password', 'value' => '','id'=>'confirm_password','class'=>'validate[required,equals[password] span6','label'=>array('class'=>'control-label'),'div'=>array('class'=>'control-group')));
		?>
            <?php
            echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
            echo "&nbsp;&nbsp;";
            echo $this->Html->link('Cancel', array('prefix' => 'clients', 'clients' => true,'action' => 'profile'), array('class' => 'btn btn-success', 'escape' => false));
            echo $this->Form->end();
            ?>
</div></div></div></div>