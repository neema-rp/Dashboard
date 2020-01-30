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
                <h1><?php __('Survey Questions '); ?> <small><i class="icon-double-angle-right"></i>List</small></h1>
        </div>
	<?php echo $this->Form->create('SurveyQuestion', array('url'=>array('controller'=>'SurveyQuestions', 'action'=>'admin_index',$client_id)));?>

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
					search by question
                                        &nbsp;&nbsp;
                                        <?php echo $this->Form->submit('Search', array('id' => 'button', 'name' => 'button', 'class' => 'btn btn-danger', 'div' => false)); ?>
				</td>
				<td>
				  	<?php echo $this->Html->link('Clear', array('prefix' => 'admin', 'admin' => true, 'controller' => 'SurveyQuestions', 'action' => 'index',$client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
				</td>
				<td>&nbsp;</td>
				<td>
				    <div style="float:left;">
                                        <?php echo $this->Html->link('Guests', array('prefix' => 'admin', 'admin' => true, 'controller' => 'SurveyUsers', 'action' => 'index',$client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
					<?php echo $this->Html->link('Add Question', array('prefix' => 'admin', 'admin' => true, 'controller' => 'SurveyQuestions', 'action' => 'add',$client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
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
			<th><?php echo $this->Paginator->sort('question');?></th>
			<th><?php echo $this->Paginator->sort('type');?></th>
                        <th style="width: 54px">Action</th>
	</tr>
        
	<?php
	$i = 0;
	$j = 1;
	foreach ($questions as $que):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $j ; $j++; ?>&nbsp;</td>
		<td><?php echo $que['SurveyQuestion']['title']; ?>&nbsp;</td>
		<td><?php echo $que['SurveyQuestion']['type']; ?>&nbsp;</td>
		<td class="actions">
                    <?php echo $this->Html->link('<i class="icon-pencil bigger-130"></i>', array('action' => 'add',$client_id,$que['SurveyQuestion']['id']), array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
                    <?php echo $this->Html->link('<i class="icon-trash bigger-130"></i>', array('action' => 'delete',$que['SurveyQuestion']['id']), array('title' => 'Delete', 'escape' => false,'class'=>'red'), sprintf(__('Are you sure you want to delete # %s?', true),$que['SurveyQuestion']['id'])); ?>
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
</div>