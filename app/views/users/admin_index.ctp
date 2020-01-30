<?php ?><script type="text/javascript">
function make_blank()
{
 document.getElementById('search').value ="";
}

function showtext()
{
 document.getElementById('search').value ="search by username";
}
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Users <small><i class="icon-double-angle-right"></i> List</small></h1>
        </div>
	<?php echo $this->Form->create('User'); ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" id="searchBox">
		<tbody>
			<tr>
				<?php if(!empty($search)){
						$search_val = $search;
				      }else{
						$search_val = '';
				      } ?>
                                <td>
                                    <span class="input-icon">
                                           <?php echo $this->Form->text('value', array('id'=>'search','class' => 'tb11','value'=>$search_val,'style'=>'color:grey'));?>
                                            <i class="icon-search nav-search-icon"></i>
                                    </span>
					search by username,firstname,lastname
                                        &nbsp;&nbsp;
					<?php echo $this->Form->submit('Search', array('id' => 'button', 'name' => 'button', 'class' => 'btn btn-danger', 'div' => false)); ?>
				</td>
				<td>
					<?php echo $this->Html->link('Clear', array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'index'), array('escape' => false, 'style' => 'text-decoration:none;font-size:18px;','class'=>'btn btn-info')); ?>
				</td>
                                <td>&nbsp;</td>
				<td>
                                    <div style="float:left;">
					<?php echo $this->Html->link('Add User', array('prefix' => 'admin', 'admin' => true, 'controller' => 'users', 'action' => 'add'), array('escape' => false,'class'=>'btn btn-info')); ?>
                                        </div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php echo $this->Form->end(); ?>

	<table class="table table-striped table-bordered table-hover">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('client_id');?></th>
			<th><?php echo $this->Paginator->sort('Department', 'department_name');?></th>
			<th><?php echo $this->Paginator->sort('username');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	$i = 0;
	$num = 0;
	foreach ($users as $user):
		$num++;
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $num; ?></td>
		<td>
			<?php echo $user['Client']['hotelname']; ?>
		</td>
		<td><?php echo $user['DepartmentsUser']['department_name']; ?></td>
		<td><?php echo $user['User']['username']; ?></td>
		<td class="actions">
			<?php echo $this->Html->link('<i class="icon-zoom-in bigger-130"></i>', array('action' => 'view', $user['User']['id'],$user['DepartmentsUser']['department_id']), array('title' => 'View', 'escape' => false,'class'=>'blue')); ?>
			<?php echo $this->Html->link('<i class="icon-pencil bigger-130"></i>', array('action' => 'edit', $user['User']['id'],$user['DepartmentsUser']['department_id']), array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
                        <?php echo $this->Html->link(__('Adv. Sheets', true), array('controller' => 'advancedSheets', 'action' => 'index', $user['User']['id'],$user['DepartmentsUser']['department_id'])); ?>
			<?php echo $this->Html->link('<i class="icon-qrcode bigger-130"></i>', array('controller' => 'sheets', 'action' => 'index', $user['User']['id'],$user['DepartmentsUser']['department_id']), array('title' => 'Sheets', 'escape' => false,'class'=>'blue')); ?>
			<?php echo $this->Html->link('<i class="icon-trash bigger-130"></i>', array('action' => 'delete', $user['User']['id'],$user['DepartmentsUser']['department_id']),  array('title' => 'Delete', 'escape' => false,'class'=>'red'), sprintf(__('Are you sure you want to delete # %s?', true), $user['User']['id'])); ?>
                        <a href="/admin/sheets/calendar/<?php echo $user['DepartmentsUser']['department_id']; ?>" target="_blank" title="Calendar" class="green"><i class="icon-calendar bigger-130"></i></a>
		</td>
	</tr>
<?php 
// }
endforeach;
?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>
	</p>

	<div class="paging" >
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>

