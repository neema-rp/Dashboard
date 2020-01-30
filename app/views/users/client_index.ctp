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
					<?php echo $this->Html->link('Clear', array('prefix' => 'client', 'client' => true, 'controller' => 'users', 'action' => 'index'), array('escape' => false, 'style' => 'text-decoration:none;font-size:18px;','class'=>'btn btn-info')); ?>
				</td>
                                <td>&nbsp;</td>
				<td>
                                    <div style="float:left;">
					<?php echo $this->Html->link('Add User', array('prefix' => 'client', 'client' => true, 'controller' => 'users', 'action' => 'add'), array('escape' => false,'class'=>'btn btn-info')); ?>
                                        </div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php echo $this->Form->end(); ?>


	<table class="table table-striped table-bordered table-hover">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('Department', 'department_name');?></th>
			<th><?php echo $this->Paginator->sort('username');?></th>
			<th><?php echo $this->Paginator->sort('firstname');?></th>
			<th><?php echo $this->Paginator->sort('lastname');?></th>
			<?php /* <th><?php echo $this->Paginator->sort('email');?></th> */ ?>
			<th><?php echo $this->Paginator->sort('phone');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	$j = 0;
	foreach ($users as $user):
		$j++;
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $j; ?>&nbsp;</td>
		<td><?php 

if(!empty($user['Department'])){
  foreach($user['Department'] as $dept_name){
      
	echo $dept_name.' - '.$child_data[$user['User']['client_id']].'<br/>';
      
  }
}
else
{
  echo "NA";
}

?>&nbsp;</td>
		<td><?php echo $user['User']['username']; ?>&nbsp;</td>
		<td><?php echo $user['User']['firstname']; ?>&nbsp;</td>
		<td><?php echo $user['User']['lastname']; ?>&nbsp;</td>
		<?php /* <td><?php echo $user['User']['email']; ?>&nbsp;</td> */ ?>
		<td><?php echo $user['User']['phone']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('<i class="icon-zoom-in bigger-130"></i>', true), array('action' => 'view', $user['User']['id']), array('title' => 'View', 'escape' => false,'class'=>'blue')); ?>
			<?php echo $this->Html->link(__('<i class="icon-pencil bigger-130"></i>', true), array('action' => 'edit', $user['User']['id']), array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
			<?php echo $this->Html->link(__('<i class="icon-trash bigger-130"></i>', true), array('action' => 'delete', $user['User']['id']), array('title' => 'Delete', 'escape' => false,'class'=>'red'), sprintf(__('Are you sure you want to delete?', true), $user['User']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
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


