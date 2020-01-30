<script type="text/javascript">
function make_blank()
{
 document.getElementById('search').value ="";
}

function showtext()
{
 document.getElementById('search').value ="search";
}
</script>
<div class="clients index">
	<fieldset>
	<legend><?php echo $parent_name; ?>&nbsp;<?php //__('Hotels');?></legend>
	<?php //echo $this->Form->create('Client'); ?>
	<form accept-charset="utf-8" action="/admin/clients/chain/<?php echo $id; ?>" method="post" id="ClientAdminChainForm">
	<table width="100%" cellspacing="0" cellpadding="0" border="0" id="searchBox">
		<tbody>
			<tr>
				<td class="userIndex">&nbsp;</td>
				<td class="userIndex searchboxcol">
					<!--input type="hidden" id="ClientSearch" value="1" name="data[Client][search]"-->
					<?php //echo $this->Form->text('value', array('id'=>'search','class' => 'tb11','value'=>'search','onclick'=>'javascript:make_blank();','onblur'=>'javascript:showtext();','style'=>'color:grey'));?>
					<!--search by username,firstname,lastname.-->
				</td>
				<td class="userIndex buttonscol">&nbsp;
				      <div style="  padding-right: 10px;    position: relative;    top: -17px;    width: 87px;">
					<?php //echo $this->Form->submit('Search', array('id' => 'button', 'name' => 'button', 'class' => 'btnred', 'div' => false)); ?>&nbsp;
				      </div>
				</td>
				<td class="userIndex buttonscol">
				  <div style="float:left;width:120px;">
					<?php //echo $this->Html->link('Clear', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'chain',$id), array('escape' => false, 'style' => 'text-decoration:none;font-size:18px;','class'=>'addbutton')); ?>
				  </div>
				</td>

				<td class="userIndex buttonscol"><div style="float:left;width:120px;">
					<?php echo $this->Html->link('Dashboard', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'index'), array('escape' => false, 'style' => 'text-decoration:none;font-size:16px;','class'=>'addbutton')); ?>
				  </div>
				</td>

				<td class="userIndex buttonscol">
				    <div style="float:left;width:120px;">
					<?php echo $this->Html->link('Add Hotel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'add'), array('escape' => false, 'style' => 'text-decoration:none;font-size:18px;width:80px;','class'=>'addbutton')); ?>
				    </div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php echo $this->Form->end(); ?>

<div style="overflow:auto;width:978px;">
	<table cellpadding="0" cellspacing="0" style="border:1px solid #DDDDDD;width:978px;">
	<tr>
			<th style="width: 24px"><?php echo $this->Paginator->sort('id');?></th>
			<th style="width: 97px"><?php echo $this->Paginator->sort('hotelname');?></th>
			<th style="width: 54px"><?php echo $this->Paginator->sort('parent_id');?></th>
			<th style="width: 85px"><?php echo $this->Paginator->sort('username');?></th>
			<th style="width: 79px"><?php echo $this->Paginator->sort('firstname');?></th>
			<th style="width: 88px"><?php echo $this->Paginator->sort('lastname');?></th>
<!-- 			<th style="width: 263px"><?php echo $this->Paginator->sort('email');?></th> -->
<!-- 			<th><?php echo $this->Paginator->sort('phone');?></th> -->
			<th style="width: 221px" class="actions"><?php __('Actions');?></th>
	</tr>
	<?php

	$i = 0;
	$j = 1;
	foreach ($clients as $client):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $j ; $j++; //$client['Client']['id']; ?>&nbsp;</td>
		<td><?php echo $client['Client']['hotelname']; ?>&nbsp;</td>
		<td><?php
			  if(empty($client['Client']['parent_name'])){
			      echo 'Self';
			  }
			  else
			  {
			      echo $client['Client']['parent_name']; 
			  }
		?>&nbsp;</td>
		<td><?php echo $client['Client']['username']; ?>&nbsp;</td>
		<td><?php echo $client['Client']['firstname']; ?>&nbsp;</td>
		<td><?php echo $client['Client']['lastname']; ?>&nbsp;</td>
<!-- 		<td><?php echo $client['Client']['email']; ?>&nbsp;</td> -->
<!-- 		<td><?php echo $client['Client']['phone']; ?>&nbsp;</td> -->
		<td class="actions">
			<?php echo $this->Html->link(__('Dept.', true), array('controller'=>'departments', 'action' => 'index', $client['Client']['id'])); ?>
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $client['Client']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $client['Client']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $client['Client']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $client['Client']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
	</fieldset>
</div>

<?php echo $this->element('admin_left_menu'); ?>
