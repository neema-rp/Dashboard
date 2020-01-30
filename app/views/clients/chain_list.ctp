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

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotel <small><i class="icon-double-angle-right"></i> Chain</small></h1>
        </div>
   
        <table class="table table-striped table-bordered table-hover">
	<tr>
			<th style="width: 24px"><?php echo $this->Paginator->sort('id');?></th>
			<th style="width: 97px"><?php echo $this->Paginator->sort('hotelname');?></th>
			<th style="width: 54px"><?php echo $this->Paginator->sort('parent_id');?></th>
			<th style="width: 85px"><?php echo $this->Paginator->sort('username');?></th>
			<th style="width: 79px"><?php echo $this->Paginator->sort('firstname');?></th>
			<th style="width: 88px"><?php echo $this->Paginator->sort('lastname');?></th>
			<th  style="width: 221px;" class="actions"><?php __('Actions');?></th>
	</tr>
	<?php

	$i = 0;
	$j = 1;
	foreach ($clients as $client):
	?>
	<tr>
		<td><?php echo $j ; $j++; ?>&nbsp;</td>
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
		<td class="actions">
			<?php echo $this->Html->link(__('Dept.', true), array('controller'=>'departments', 'action' => 'list', $client['Client']['id'],'prefix' => 'client', 'client' => true)); ?>
                        <?php echo $this->Html->link('<i class="icon-pencil bigger-130"></i>', array('action' => 'edit', $client['Client']['id']), array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
                        <?php echo $this->Html->link('<i class="icon-group bigger-130"></i>', array('controller' => 'users', 'action' => 'index',$client['Client']['id'],'prefix' => 'client', 'client' => true), array('title' => 'Users', 'escape' => false,'class'=>'green')); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
        
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>
        </p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>