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
                <h1>Guests <small><i class="icon-double-angle-right"></i> <?php if($access_survey == '1'){
                echo 'Completed Survey';
            } ?></small></h1>
        </div>
            
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
               	<td>
                    <?php if($access_survey == '0'){
                        echo $this->Html->link('View Guest completed Survey', array('prefix' => 'admin', 'admin' => true, 'controller' => 'SurveyUsers', 'action' => 'index',$client_id,'1'), array('escape' => false,'class'=>'btn btn-info'));
                    }else{
                        echo $this->Html->link('View Guest', array('prefix' => 'admin', 'admin' => true, 'controller' => 'SurveyUsers', 'action' => 'index',$client_id,'0'), array('escape' => false,'class'=>'btn btn-info'));
                    }?>

                    <a href="/SurveyUsers/view_survey/<?php echo $client_id; ?>" class="btn btn-info" target="_blank">View Survey Sample</a>  
                    &nbsp;&nbsp;&nbsp;<?php
                    echo $this->Html->link('Survey Questions', array('prefix' => 'admin', 'admin' => true, 'controller' => 'SurveyQuestions', 'action' => 'index',$client_id), array('escape' => false,'class'=>'btn btn-info'));
                    ?>
                    &nbsp;&nbsp;&nbsp;<?php
                    echo $this->Html->link('Report', array('action' => 'reports',$client_id), array('escape' => false,'class'=>'btn btn-info'));
                    ?>  
                </td>
               
            </tr>
        </table>
    
	<?php echo $this->Form->create('SurveyUser', array('url'=>array('controller'=>'SurveyUsers', 'action'=>'admin_index',$client_id,$access_survey)));?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
				<?php
                                     if(!empty($search)){
						$search_val = $search;
				      }else{
						$search_val = 'search';
				      } 
                                  ?>

                                <td>
                                    <span class="input-icon">
                                            <?php echo $this->Form->text('value', array('id'=>'search','value'=>$search_val,'onclick'=>'javascript:make_blank();'));?>
                                            <i class="icon-search nav-search-icon"></i>
                                    </span>
					search by name, email
                                        &nbsp;&nbsp;
                                        <?php echo $this->Form->submit('Search', array('id' => 'button', 'name' => 'button', 'class' => 'btn btn-danger', 'div' => false)); ?>
				</td>
                                
                                <td>
				  	<?php echo $this->Html->link('Clear', array('prefix' => 'admin', 'admin' => true, 'controller' => 'SurveyUsers', 'action' => 'index',$client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
				</td>
				<td>&nbsp;</td>
                                <td>
				    <div style="float:left;">
					<?php echo $this->Html->link('Add Guest', array('prefix' => 'admin', 'admin' => true, 'controller' => 'SurveyUsers', 'action' => 'add',$client_id), array('escape' => false,'class'=>'btn btn-info')); ?>
				    </div>
				</td>
			</tr>
		</tbody>
	</table>
	<?php echo $this->Form->end(); ?>

        <div >
	<table class="table table-striped table-bordered table-hover">
	<tr>
            <th><?php echo $this->Paginator->sort('id');?></th>
            <th><?php echo $this->Paginator->sort('name');?></th>
            <th><?php echo $this->Paginator->sort('email');?></th>
            <th><?php echo $this->Paginator->sort('survey_sent_on');?></th>
            <th><?php echo $this->Paginator->sort('survey_completed_on');?></th>
            <?php if($access_survey == '1'){ ?>
                <th style="width: 221px" class="actions"><?php __('Score');?></th>
            <?php } ?>
            <th><?php __('Actions');?></th>
	</tr>
	<?php

	$i = 0;
	$j = 1;
	foreach ($users as $user):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $j ; $j++; ?>&nbsp;</td>
		<td><?php echo $user['SurveyUser']['name']; ?>&nbsp;</td>
		<td><?php echo $user['SurveyUser']['email']; ?>&nbsp;</td>
                <td><?php echo $user['SurveyUser']['survey_sent_on']; ?>&nbsp;</td>
		<td><?php echo $user['SurveyUser']['survey_completed_on']; ?>&nbsp;</td>
                <?php if($access_survey == '1'){ ?>
                    <td><?php echo $this->requestAction('/SurveyUsers/getUserScores/'.$user['SurveyUser']['id']); ?></td>
                <?php } ?>
                <td class="actions">
                    <?php if($access_survey == '0'){
                        if($user['SurveyUser']['survey_sent'] == '0'){
                            //echo $this->Html->link(__('<i class="icon-upload bigger-130"></i>', true), array('action' => 'sent', $user['SurveyUser']['id']), array('title' => 'Sent Survey', 'escape' => false,'class'=>'orange'));
                        }
                        echo $this->Html->link(__('<i class="icon-upload bigger-130"></i>', true), array('action' => 'sent', $user['SurveyUser']['id']), array('title' => 'Sent Survey', 'escape' => false,'class'=>'orange'));
                        echo $this->Html->link(__('<i class="icon-pencil bigger-130"></i>', true), array('action' => 'add', $client_id,$user['SurveyUser']['id']), array('title' => 'Edit', 'escape' => false,'class'=>'green'));
                    }else{ ?>
                    <a href="/SurveyUsers/completed_survey/<?php echo $user['SurveyUser']['id']; ?>" target="_blank" class="blue" title="View"><i class="icon-zoom-in bigger-130"></i></a>
                   <?php } ?>
                    <?php echo $this->Html->link(__('<i class="icon-trash bigger-130"></i>', true), array('action' => 'delete', $user['SurveyUser']['id']), array('title' => 'Delete', 'escape' => false,'class'=>'red'), sprintf(__('Are you sure you want to delete # %s?', true), $user['SurveyUser']['id'])); ?>
		</td>
	</tr>
        <?php endforeach; ?>
	</table>

            <?php if($access_survey == '0'){ ?>
                <div style="float:left;margin:10px;">
                    <?php echo $this->Html->link(__('Sent Survey To All Guests', true), array('action' => 'sentall', $client_id),  array("class"=>"btn btn-info")); ?>
                 </div>
            <?php } ?>
        
            
        </div>
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