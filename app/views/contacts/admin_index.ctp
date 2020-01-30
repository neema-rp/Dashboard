<?php 
$controller = $this->params['controller'];
?>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Contacts <small><i class="icon-double-angle-right"></i> List</small></h1>
        </div>
    
	<table class="table table-striped table-bordered table-hover">
        <tr>
            <th>#</th>
            <th><?php echo $this->Paginator->sort('First Name', 'name');?></th>
            <th><?php echo $this->Paginator->sort('Email', 'email');?></th>
            <th><?php __('View');?></th>
            <th><?php __('Delete');?></th>
        </tr>
	<?php
	      if(!empty($contactus))	
	      {
		  $i = 0;
		  foreach ($contactus as $contact): $class = null; if ($i++ % 2 == 0) { $class = ' class="altrow"'; }?>
		<tr<?php echo $class;?>>
		      <td><?php echo $i; ?>&nbsp;</td>
		      <td><?php echo $contact['Contact']['name']; ?>&nbsp;</td>
		      
		      <td><?php echo $contact['Contact']['email']; ?>&nbsp;</td>
		      
		      <?php
                    $statusCheck = ($contact['Contact']['status'] == 0)?'active':'deactivated';
                    $statusCheckImg = ($contact['Contact']['status'] == 0)?'active-check32.png':'active-error32.png';

                    $linkAction_status   = array('controller' => $controller, 'action' => 'delete',   $contact['Contact']['id'],$statusCheck);
                    $linkAction_view     = array('controller' => $controller, 'action' => 'view',     $contact['Contact']['id']);
                     $linkAction_delete   = array('controller' => $controller, 'action' => 'delete',   $contact['Contact']['id']);
		  ?>
		<!--td style="text-align:center;"><?php echo $this->Html->link("<img src='/img/{$statusCheckImg}' alt='{$statusCheck}' height='23'>", $linkAction_status,   array( 'escape' => false, 'title' => $statusCheck)) ; ?></td-->
		<td style="text-align:center;"><?php echo $this->Html->link('<i class="icon-zoom-in bigger-130"></i>', $linkAction_view,   array( 'escape' => false, 'title' => 'View','class'=>'blue')) ; ?></td>
		<td style="text-align:center;"><?php echo $this->Html->link('<i class="icon-trash bigger-130"></i>' , $linkAction_delete, array( 'escape' => false, 'title' => 'Delete','class'=>'red'), sprintf(__('Are you sure you want to delete # %s?', true), $contact['Contact']['id'])); ?></td>
	</tr>
		<?php endforeach;
			} else {?>

			<tr>
				<th colspan="8" width="100%"  style="text-align:center;">::Records Not found::</th>
			</tr>	
			<?php }?>
	</table>
        <!-- pagination code start-->
			<?php // echo $this->element('admins/adminpagination');?>
		<!-- pagination code end-->
</div>
