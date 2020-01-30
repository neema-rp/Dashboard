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
                <h1>Hotels <small><i class="icon-double-angle-right"></i> List</small></h1>
        </div>
    
	<?php echo $this->Form->create('Client'); ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<?php if(!empty($search)){
						$search_val = $search;
				      }else{
						$search_val = 'search';
				      } ?>
				<td>
                                    <span class="input-icon">
                                            <?php echo $this->Form->text('value', array('id'=>'search','value'=>$search_val,'onclick'=>'javascript:make_blank();'));?>
                                            <i class="icon-search nav-search-icon"></i>
                                    </span>
					search by Hotelname,username,firstname,lastname.
                                        &nbsp;&nbsp;
                                        <?php echo $this->Form->submit('Search', array('id' => 'button', 'name' => 'button', 'class' => 'btn btn-danger', 'div' => false)); ?>
				</td>
				<td>
				  	<?php echo $this->Html->link('Clear', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'index'), array('escape' => false,'class'=>'btn btn-info')); ?>
				</td>
				<td>&nbsp;</td>
				<td>
				    <div style="float:left;">
					<?php echo $this->Html->link('Add Hotel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'clients', 'action' => 'add'), array('escape' => false,'class'=>'btn btn-info')); ?>
				    </div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php echo $this->Form->end(); ?>

<div>
	<table class="table table-striped table-bordered table-hover">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('hotelname');?></th>
			<th><?php echo $this->Paginator->sort('parent_id');?></th>
			<th><?php echo $this->Paginator->sort('username');?></th>
			<th><?php echo $this->Paginator->sort('firstname');?></th>
			<th><?php echo $this->Paginator->sort('lastname');?></th>
<!-- 			<th><?php echo $this->Paginator->sort('email');?></th> -->
<!-- 			<th><?php echo $this->Paginator->sort('phone');?></th> -->
			<th class="actions"><?php __('Actions');?></th>
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
                        <a title='Pricing Wizard' target="_blank" href="http://www.mypricingwizard.net/login.php?hotel_id=<?php echo $client['Client']['id']; ?>" class="purple">
                        <i class="icon-tasks bigger-130"></i>
                        </a>
			<?php //echo $this->Html->link(__('Assign', true), array('controller'=>'clients', 'action' => 'assign', $client['Client']['id'])); ?>
			<?php //echo $this->Html->link(__('Chain', true), array('controller'=>'clients', 'action' => 'chain', $client['Client']['id'])); ?>
                        <?php echo $this->Html->link('<i class="icon-upload bigger-130"></i>', array('controller'=>'clients', 'action' => 'land', $client['Client']['id']), array('title' => 'More Options', 'escape' => false,'class'=>'orange')); ?>
			<?php //echo $this->Html->link(__('Dept.', true), array('controller'=>'departments', 'action' => 'index', $client['Client']['id'])); ?>
			<?php echo $this->Html->link('<i class="icon-zoom-in bigger-130"></i>', array('action' => 'view', $client['Client']['id']), array('title' => 'View', 'escape' => false,'class'=>'blue')); ?>
			<?php echo $this->Html->link('<i class="icon-pencil bigger-130"></i>', array('action' => 'edit', $client['Client']['id']), array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
			<?php echo $this->Html->link('<i class="icon-trash bigger-130"></i>', array('action' => 'delete', $client['Client']['id']), array('title' => 'Delete', 'escape' => false,'class'=>'red'), sprintf(__('Are you sure you want to delete # %s?', true), $client['Client']['id'])); ?>
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
	?>
        </p>
	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?> | <?php echo $this->Paginator->numbers();?> | <?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>