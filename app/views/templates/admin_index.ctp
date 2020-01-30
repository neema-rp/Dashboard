<?php ?>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Templates <small><i class="icon-double-angle-right"></i> List</small></h1>
        </div>
    
	<?php echo $this->Form->create('Template', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'Templates', 'action' => 'index'))); ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" id="searchBox">
		<tbody>
			<tr>
				<?php $search_val = 'Search by Template name'; ?>
                                <td>
                                    <span class="input-icon">
                                          <?php echo $this->Form->text('value', array('id'=>'search','class' => 'tb11','value'=>$search_val,'style'=>'color:grey'));?>
                                            <i class="icon-search nav-search-icon"></i>
                                    </span>
					<?php echo $this->Form->submit('Search', array('id' => 'button', 'name' => 'button', 'class' => 'btn btn-danger', 'div' => false)); ?>&nbsp;
				</td>
                                <td>
					<?php echo $this->Html->link('Clear', array('prefix' => 'admin', 'admin' => true, 'controller' => 'Templates', 'action' => 'index'), array('escape' => false,'class'=>'btn btn-info')); ?>
				</td>
				<td>&nbsp;</td>
				<td>
					<?php echo $this->Html->link('Add Template', array('prefix' => 'admin', 'admin' => true, 'controller' => 'Templates', 'action' => 'add'), array('escape' => false,'class'=>'btn btn-info')); ?>
                                </td>
			</tr>
		</tbody>
	</table>
	<?php echo $this->Form->end(); ?>

	<table class="table table-striped table-bordered table-hover">
	<?php if(!empty($userTemplates)){ ?>
	<tr>
			<th><?php echo 'Id';?></th>
			<th><?php echo 'Template';?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	$j = 0;
	foreach ($userTemplates as $Template):
		$j++;
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $j; ?>&nbsp;</td>
		<td>
			<?php //echo $this->Html->link($Template['Template']['name'], array('controller' => 'Templates', 'action' => 'view', $Template['Template']['id'])); 
                        echo $Template['Template']['name'];
                        ?>
		</td>
		<td class="actions">
			<?php //echo $this->Html->link(__('Formulas', true), array('prefix' => 'admin', 'admin' => true, 'controller'=>'templates', 'action' => 'formula', $Template['Template']['id'])); ?>
			<?php //echo $this->Html->link(__('Preview', true), array('action' => 'view', $Template['Template']['id'])); ?>
    
                        <?php echo $this->Html->link('<i class="icon-edit bigger-130"></i>', array('action' => 'formula',$Template['Template']['id']),array('title' => 'Formulas', 'escape' => false,'class'=>'blue')); ?>
                        <?php echo $this->Html->link('<i class="icon-pencil bigger-130"></i>', array('action' => 'edit',$Template['Template']['id']),array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
			<?php echo $this->Html->link('<i class="icon-trash bigger-130"></i>', array('action' => 'delete',$Template['Template']['id']), array('title' => 'Delete', 'escape' => false,'class'=>'red'), sprintf(__('Are you sure you want to delete # %s?', true), $Template['Template']['id'])); ?>

		</td>
	</tr>
<?php  endforeach; }else{ ?>
	<tr><td colsapn="7" style="text-align:center;">No Template is Available</td></tr>
 <?php     } ?>
	</table>
</div>