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
                <h1>Hotel <small><i class="icon-double-angle-right"></i> Departments</small></h1>
        </div>
    
	<?php echo $this->Form->create('Department'); ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" id="searchBox">
		<tbody>
			<tr>
                                <?php if(!empty($search)){
                                            $search_val = $search;
                                  }else{
                                            $search_val = 'search';
                                  } ?>
                                <td>
                                    <span class="input-icon">
                                           <?php echo $this->Form->text('value', array('id'=>'search','class' => 'tb11','value'=>$search_val,'style'=>'color:grey'));?>
                                            <i class="icon-search nav-search-icon"></i>
                                    </span>
                                        &nbsp;&nbsp;
					<?php echo $this->Form->submit('Search', array('id' => 'button', 'name' => 'button', 'class' => 'btn btn-danger', 'div' => false)); ?>
				</td>
				<td>
					<?php echo $this->Html->link('Clear', array('prefix' => 'client', 'client' => true, 'departments' => 'users', 'action' => 'list'), array('escape' => false, 'style' => 'text-decoration:none;font-size:18px;','class'=>'btn btn-info')); ?>
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
	$j = 0;
        $k = 0;
	foreach ($departments as $department):
            $k++;
        ?>
	<tr>
		<td><?php echo $k; ?>&nbsp;</td>
		<td><?php echo $department['Department']['name']." - ".$department['Client']['hotelname']; ?>&nbsp;</td>
		<td class="actions">
		
                   <?php
                   echo $this->Html->link(__('Segment Fcst', true), array('prefix' => 'client', 'client' => true, 'controller' => 'advancedSheets', 'action' => 'index', $departments[$j]['Department']['id']));
                    ?>
                    
                    | <?php echo $this->Html->link(__('<i class="icon-qrcode bigger-130"></i>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'index', $departments[$j]['Department']['id']),array('title' => 'Sheets', 'escape' => false,'class'=>'blue')); ?>
                    |	<?php echo $this->Html->link(__('<i class="icon-user bigger-130"></i>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'assign', $departments[$j]['Department']['id']), array('title' => 'Assign Users', 'escape' => false,'class'=>'blue')); ?>	
                    |   <?php echo $this->Html->link(__('<i class="icon-calendar bigger-130"></i>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'calendar', $departments[$j]['Department']['id']),array('title' => 'Calendar', 'escape' => false,'class'=>'green')); 
                        $j++; ?>	
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
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?> | 
		<?php echo $this->Paginator->numbers();?> |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>