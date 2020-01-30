<?php ?>
<script>
function toggle_dept(month_id,year_id) {
      var ele = document.getElementById("department_div_"+month_id+"_"+year_id);
      var text = document.getElementById("DeptText_"+month_id+"_"+year_id);
      if(ele.style.display == "") {
      ele.style.display = "none";
      text.innerHTML = '<i class="icon-plus"></i>';
      } else {
      ele.style.display = "";
      text.innerHTML = '<i class="icon-minus"></i>';
      }
}
</script>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Department Segmentation Sheets <small><i class="icon-double-angle-right"></i> List</small></h1>
        </div>

	<?php echo $this->Form->create('AdvancedSheet', array('url' => array('prefix' => 'staff', 'staff' => true, 'controller' => 'AdvancedSheets', 'action' => 'index', $userId,$dept_id))); ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" style="display:none;">
		<tbody>
			<tr>
                                <td>
                                    <span class="input-icon">
                                          <?php echo $this->Form->text('value', array('id'=>'search','class' => 'tb11','value'=>$search_val,'style'=>'color:grey', 'placeholder'=>'Search by Sheet name'));?>
                                            <i class="icon-search nav-search-icon"></i>
                                    </span>
					<?php echo $this->Form->submit('Search', array('id' => 'button', 'name' => 'button', 'class' => 'btn btn-danger', 'div' => false)); ?>&nbsp;
				</td>

				<td class="AdvancedSheetIndex buttonscol">
					<?php echo $this->Html->link('Clear', array('prefix' => 'staff', 'staff' => true, 'controller' => 'AdvancedSheets', 'action' => 'index', $userId, $dept_id), array('escape' => false,'class'=>'btn btn-info')); ?>
				</td>
				<td>&nbsp;</td>
				<td>
					<?php //echo $this->Html->link('Add Segmentation Sheet', array('prefix' => 'staff', 'staff' => true, 'controller' => 'Templates', 'action' => 'create_advance', $userId, $this->params['pass'][1]), array('escape' => false,'class'=>'btn btn-info')); ?>
                                </td>
                                <td>
					<?php //echo $this->Html->link('Create Multiple Segmentation', array('prefix' => 'staff', 'staff' => true, 'controller' => 'Templates', 'action' => 'copyAdvancedSheet', $this->params['pass'][0], $last_AdvancedSheet['AdvancedSheet']['id'],$dept_id), array('escape' => false,'class'=>'btn btn-info')); ?>
                                </td>
			</tr>
		</tbody>
	</table>
	<?php echo $this->Form->end(); ?>


        <table class="table table-striped table-bordered table-hover">
        <?php if(!empty($mAdvancedSheets)){ ?>
        <tr>                        
                <th>Month</th>
                <th>Year</th>
        </tr>
        <?php
        $i = 0;
        $j = 0;
        foreach ($mAdvancedSheets as $mAdvancedSheet):
$j++;
                $class = null;
                if ($i++ % 2 == 0) {
                        $class = ' class="altrow"';
                }
        ?>

        <tr<?php echo $class;?>>        
        <td>
        <a id="DeptText_<?php echo $mAdvancedSheet['AdvancedSheet']['month'].'_'.$mAdvancedSheet['AdvancedSheet']['year'];?>" href="javascript:toggle_dept('<?php echo $mAdvancedSheet['AdvancedSheet']['month'];?>','<?php echo $mAdvancedSheet['AdvancedSheet']['year'];?>');" style="text-decoration: none; font-weight:bold;vertical-align: sub;"><i class="icon-plus"></i></a>

        <?php echo date('F', mktime(0, 0, 0, $mAdvancedSheet['AdvancedSheet']['month'], 1, $mAdvancedSheet['AdvancedSheet']['year'])); ?>&nbsp;</td>
        <td><?php echo $mAdvancedSheet['AdvancedSheet']['year']; ?>&nbsp;</td>
        </tr>

        <tr<?php echo $class;?>  style="display:none;" id="department_div_<?php echo $mAdvancedSheet['AdvancedSheet']['month'].'_'.$mAdvancedSheet['AdvancedSheet']['year'];?>">
        <td colspan="2" style="background:none repeat scroll 0 0 #F5F5F5">
        <table cellpadding="0" cellspacing="0"  style="border:2px solid #000;" class="table table-striped table-bordered table-hover">

	<?php if(!empty($userAdvancedSheets)){ ?>

	<tr>
			<th><?php echo 'Id';?></th>
			<th><?php echo 'Sheet';?></th>
			<th><?php echo 'Department';?></th>
			<th><?php echo 'Username';?></th>
			<th><?php echo 'Month';?></th>
			<th><?php echo 'Year';?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php

	$i = 0;
	$j = 0;

	foreach ($userAdvancedSheets as $AdvancedSheet):
                if(($mAdvancedSheet['AdvancedSheet']['month'] == $AdvancedSheet['AdvancedSheet']['month']) && ($mAdvancedSheet['AdvancedSheet']['year'] == $AdvancedSheet['AdvancedSheet']['year'])){
		$j++;
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $j; ?>&nbsp;</td>
		<td>
			<?php echo $AdvancedSheet['AdvancedSheet']['name'];
                        //echo $this->Html->link($AdvancedSheet['AdvancedSheet']['name'], array('controller' => 'AdvancedSheets', 'action' => 'view', $AdvancedSheet['AdvancedSheet']['id'],$dept_id)); ?>                        
		</td>
		<td><?php echo $department; ?>&nbsp;</td>
		<td><?php echo $AdvancedSheet['User']['username']; ?>&nbsp;</td>
		<td><?php echo date('F', mktime(0, 0, 0, $AdvancedSheet['AdvancedSheet']['month'], 1, $AdvancedSheet['AdvancedSheet']['year'])); ?>&nbsp;</td>
		<td><?php echo $AdvancedSheet['AdvancedSheet']['year']; ?>&nbsp;</td>
		<td class="actions">
			<?php 
			  echo $this->Html->link(__('<i class="icon-qrcode bigger-130"></i>', true), array('prefix' => 'staff', 'staff' => true, 'action' => 'webform', $AdvancedSheet['AdvancedSheet']['id']), array('title' => 'Sheets', 'escape' => false,'class'=>'blue'));                           
                	?>
		</td>
	</tr>
<?php } endforeach; }else{ ?>
	<tr><td colsapn="7" style="text-align:center;">No Sheet is Available</td></tr>
 <?php     } ?>
	</table>
	<!--<p>-->
	<?php
	//echo $this->Paginator->counter(array(	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)));
	?>	<!--</p>-->

	<!--<div class="paging">
		<?php //echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php //echo $this->Paginator->numbers();?>
 |
		<?php //echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>-->
         </td>
        </tr>

<?php endforeach;} else { ?>
        <tr><td colsapn="7" style="text-align:center;">No Sheet is Available</td></tr>
<?php } ?>
</table>

</div>

