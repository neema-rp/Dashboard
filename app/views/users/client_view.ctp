<?php ?>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>User<small><i class="icon-double-angle-right"></i>View Details</small></h1>
        </div>
    
    <div id="user-profile-1" class="user-profile row-fluid">
    <div class="span12">
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Client'); ?> </div>
                        <div class="profile-info-value">&nbsp;<?php //echo $this->Html->link($user['Client']['username'], array('controller' => 'clients', 'action' => 'view', $user['Client']['id'])); 
                        echo $user['Client']['hotelname'];
                        ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">&nbsp;
                        <div class="profile-info-name"> <?php __('Department'); ?> </div>
                        <div class="profile-info-value">
                            <?php 
			      $count  = count($user['DepartmentsUser']);
			      $check = 0;
			      foreach($user['DepartmentsUser'] as $dept)
			      {
				if($check == ($count-1)){
				    echo $dept['department_name'];
				}
				else
				{
				    echo $dept['department_name'] . ' , ';
				}
				$check++;				
			      } ?>
                        </div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Username'); ?> </div>
                        <div class="profile-info-value">&nbsp;<?php echo $user['User']['username']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Firstname'); ?> </div>
                        <div class="profile-info-value">&nbsp;<?php echo $user['User']['firstname']; ?></div>
                </div>
        </div>
        
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Lastname'); ?> </div>
                        <div class="profile-info-value">&nbsp;<?php echo $user['User']['lastname']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Email'); ?> </div>
                        <div class="profile-info-value">&nbsp;<?php echo $user['User']['email']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Phone'); ?> </div>
                        <div class="profile-info-value">&nbsp;<?php echo $user['User']['phone']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Created'); ?> </div>
                        <div class="profile-info-value">&nbsp;<?php echo $user['User']['created']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Modified'); ?> </div>
                        <div class="profile-info-value">&nbsp;<?php echo $user['User']['modified']; ?></div>
                </div>
        </div>
</div>
</div>
</div>