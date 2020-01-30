<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotels <small><i class="icon-double-angle-right"></i>View Details</small></h1>
        </div>
    
    <div id="user-profile-1" class="user-profile row-fluid">
    <div class="span12">
            <div class="profile-user-info profile-user-info-striped">
                    <div class="profile-info-row">
                            <div class="profile-info-name"> <?php __('Id'); ?> </div>
                            <div class="profile-info-value"><?php echo $client['Client']['id']; ?></div>
                    </div>
            </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"><?php __('Username'); ?></div>
                        <div class="profile-info-value"><?php echo $client['Client']['username']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Firstname'); ?> </div>
                        <div class="profile-info-value"><?php echo $client['Client']['firstname']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Lastname'); ?> </div>
                        <div class="profile-info-value"><?php echo $client['Client']['lastname']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Email'); ?> </div>
                        <div class="profile-info-value"><?php echo $client['Client']['email']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Phone'); ?> </div>
                        <div class="profile-info-value"><?php echo $client['Client']['phone']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
            <div class="profile-info-row">
                    <div class="profile-info-name"> <?php __('Logo'); ?> </div>
                    <div class="profile-info-value">
                <?php echo $client['Client']['logo'] ? $this->Html->image('/files/clientlogos'. DS . $client['Client']['logo'] , array('width'=>200 , 'height'=>120)) : $this->Html->image('/img/pna.png', array('width'=>200 , 'height'=>120)); ?></div>
            </div>
        </div>

    <div class="space-20"></div>
    
    <h4 class="blue smaller">Related Users</h4>
    
	<?php if (!empty($client['User'])):?>
	<table class="table table-striped table-bordered table-hover">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Client Id'); ?></th>
		<th><?php __('Username'); ?></th>
		<th><?php __('Firstname'); ?></th>
		<th><?php __('Lastname'); ?></th>
		<th><?php __('Email'); ?></th>
		<th><?php __('Phone'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($client['User'] as $user):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $user['id'];?></td>
			<td><?php echo $user['client_id'];?></td>
			<td><?php echo $user['username'];?></td>
			<td><?php echo $user['firstname'];?></td>
			<td><?php echo $user['lastname'];?></td>
			<td><?php echo $user['email'];?></td>
			<td><?php echo $user['phone'];?></td>
			<td><?php echo $user['created'];?></td>
			<td><?php echo $user['modified'];?></td>
			<td class="actions">
                            <?php echo $this->Html->link('<i class="icon-zoom-in bigger-130"></i>', array('controller' => 'users','action' => 'view', $user['id']), array('title' => 'View', 'escape' => false,'class'=>'blue')); ?>
                            <?php echo $this->Html->link('<i class="icon-pencil bigger-130"></i>', array('controller' => 'users','action' => 'edit', $user['id']), array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
                            <?php echo $this->Html->link('<i class="icon-trash bigger-130"></i>', array('controller' => 'users','action' => 'delete', $user['id']), array('title' => 'Delete', 'escape' => false,'class'=>'red'), sprintf(__('Are you sure you want to delete # %s?', true), $user['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
</div>
</div>