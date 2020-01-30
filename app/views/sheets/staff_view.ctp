<?php
//debug($sheet);
?>
<style>.profile-info-name{ width:200px; }
.profile-info-value{ margin-left: 200px; }</style>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotels <small><i class="icon-double-angle-right"></i>Department Sheet</small></h1>
        </div>
    
    <div id="user-profile-1" class="user-profile row-fluid">
    <div class="span12">
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Id'); ?> </div>
                        <div class="profile-info-value"><?php echo $sheet['Sheet']['id']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Department Sheet'); ?> </div>
                        <div class="profile-info-value"><?php echo $sheet['Sheet']['name']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Department Name'); ?> </div>
                        <div class="profile-info-value"><?php echo $sheet['User']['department_name'];; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Department User'); ?> </div>
                        <div class="profile-info-value"><?php echo $sheet['User']['firstname'] ." ". $sheet['User']['lastname']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Department Sheet'); ?> </div>
                        <div class="profile-info-value"><?php echo $sheet['User']['email']; ?></div>
                </div>
        </div>
        <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                        <div class="profile-info-name"> <?php __('Month'); ?> </div>
                        <div class="profile-info-value"><?php echo date('F Y', mktime(0, 0, 0, $sheet['Sheet']['month'], 1, $sheet['Sheet']['year'])); ?></div>
                </div>
        </div>
		
<div class="space-20"></div>

    <h4 class="blue smaller">Columns</h4>
    
	<?php if (!empty($sheet['Column'])):?>
	<table class="table table-striped table-bordered table-hover">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Column Name'); ?></th>
		<th><?php __('Status'); ?></th>
		<th><?php __('Locked'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($sheet['Column'] as $column):
			
		?>
		<tr>
			<td><?php echo $column['id'];?></td>
			<td><?php echo $column['name'];?></td>
			<td><?php echo $column['status']? 'Active' : 'Inactive';?></td>
			<td><?php echo $column['ColumnsSheet']['locked'];?></td>
			
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

    
<h4 class="blue smaller">Rows</h4>
	<?php if (!empty($sheet['Row'])):?>
	<table class="table table-striped table-bordered table-hover">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Row Name'); ?></th>
		<th><?php __('Status'); ?></th>
		<th><?php __('Locked'); ?></th>
	</tr>
	<?php
		foreach ($sheet['Row'] as $row):
		?>
		<tr>
			<td><?php echo $row['id'];?></td>
			<td><?php echo $row['name'];?></td>
			<td><?php echo $row['status']? 'Active' : 'Inactive';?></td>
			<td><?php echo $row['RowsSheet']['locked'];?></td>
			
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
	
</div></div></div>