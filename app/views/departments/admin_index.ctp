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
<?php ?>
<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __('Departments '); ?> <small><i class="icon-double-angle-right"></i>List</small></h1>
        </div>
    
	<?php echo $this->Form->create('Department',array('url' => array('controller' => 'departments', 'action' => 'index', $this->params['pass'][0]))); ?>
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
					<?php echo $this->Form->submit('Search', array('id' => 'button', 'name' => 'button', 'class' => 'btn btn-danger', 'div' => false)); ?>
				</td>
				<td>
				  	<?php echo $this->Html->link('Clear', array('prefix' => 'admin', 'admin' => true, 'controller' => 'departments', 'action' => 'index',$client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
				</td>
				<td>&nbsp;</td>
				<td>
				    <div style="float:left;">
					<?php echo $this->Html->link('Add Department', array('prefix' => 'admin', 'admin' => true, 'controller' => 'departments', 'action' => 'add',$client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
				    </div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php echo $this->Form->end(); ?>

	<table class="table table-striped table-bordered table-hover">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
        		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	$j = 0;
	foreach ($departments as $department):
	$j++;
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $j; ?>&nbsp;</td>
		<td><?php echo $department['Department']['name']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link('<i class="icon-zoom-in bigger-130"></i>', array('action' => 'view', $department['Department']['id'], $client_id), array('title' => 'View', 'escape' => false,'class'=>'blue')); ?>
			<?php echo $this->Html->link('<i class="icon-pencil bigger-130"></i>', array('action' => 'edit',$department['Department']['id'], $client_id), array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
			<?php echo $this->Html->link('<i class="icon-trash bigger-130"></i>', array('action' => 'delete', $department['Department']['id'], $client_id), array('title' => 'Delete', 'escape' => false,'class'=>'red'), sprintf(__('Are you sure you want to delete # %s?', true), $department['Department']['id'])); ?>
			<?php echo $this->Html->link('<i class="icon-group bigger-130"></i>', array('controller'=>'sheets','action' => 'assign',$this->params['pass'][0], $department['Department']['id']), array('title' => 'Assign User', 'escape' => false,'class'=>'green')); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
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
</div>