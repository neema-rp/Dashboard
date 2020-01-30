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

function toggle_year(year_id) {
      var ele = document.getElementById("year_div_"+year_id);
      var text = document.getElementById("YearToggle_"+year_id);
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
                <h1>Sheets <small><i class="icon-double-angle-right"></i> List</small></h1>
        </div>
	<?php echo $this->Form->create('Sheet', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index', $userId,$dept_id))); ?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
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
					<?php echo $this->Html->link('Clear', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'index', $userId, $dept_id), array('escape' => false,'class'=>'btn btn-info')); ?>
				</td>
				<td>&nbsp;</td>
				<td>
					<?php echo $this->Html->link('Add Sheet', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'add', $userId, $this->params['pass'][1]), array('escape' => false,'class'=>'btn btn-info')); ?>
                                </td>
                                <td>
					<?php echo $this->Html->link('Create Multiple Sheet', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'copysheet', $this->params['pass'][0], $last_sheet['Sheet']['id'],$dept_id), array('escape' => false,'class'=>'btn btn-info')); ?>
                                </td>
			</tr>
		</tbody>
	</table>
	<?php echo $this->Form->end(); ?>

    
        <table class="table table-striped table-bordered table-hover">
            <?php if(!empty($sheetYears)){ 
            foreach($sheetYears as $s_years){
            ?>
            <tr>
                <td>
                    <a id="YearToggle_<?php echo $s_years['Sheet']['year']; ?>" href="javascript:toggle_year('<?php echo $s_years['Sheet']['year']; ?>');" style="text-decoration: none; font-weight:bold;vertical-align: sub;"><i class="icon-plus"></i></a>
                    <?php echo $s_years['Sheet']['year']; ?>
                </td>
            </tr>
            
            <tr<?php echo $class;?>  style="display:none;" id="year_div_<?php echo $s_years['Sheet']['year'];?>">
            <td>
                
        <table class="table table-striped table-bordered table-hover">
        <?php if(!empty($msheets)){ ?>
        <tr>                        
                <th>Month</th>
                <th>Year</th>
        </tr>
        <?php
        $i = 0;
        $j = 0;
        foreach ($msheets as $msheet):
            
            if($msheet['Sheet']['year'] == $s_years['Sheet']['year']){
            
            
            $j++;
                $class = null;
                if ($i++ % 2 == 0) {
                        $class = ' class="altrow"';
                }
        ?>
        <tr<?php echo $class;?>>        
            <td>
            <a id="DeptText_<?php echo $msheet['Sheet']['month'].'_'.$msheet['Sheet']['year'];?>" href="javascript:toggle_dept('<?php echo $msheet['Sheet']['month'];?>','<?php echo $msheet['Sheet']['year'];?>');" style="text-decoration: none; font-weight:bold;vertical-align: sub;"><i class="icon-plus"></i></a>
            <?php echo date('F', mktime(0, 0, 0, $msheet['Sheet']['month'], 1, $msheet['Sheet']['year'])); ?>&nbsp;</td>
            <td><?php echo $msheet['Sheet']['year']; ?>&nbsp;</td>
        </tr>

        <tr<?php echo $class;?>  style="display:none;" id="department_div_<?php echo $msheet['Sheet']['month'].'_'.$msheet['Sheet']['year'];?>">
        <td colspan="2" style="background:none repeat scroll 0 0 #F5F5F5">
        <table cellpadding="0" cellspacing="0"  style="border:2px solid #000;" class="table table-striped table-bordered table-hover">
	<?php if(!empty($userSheets)){ ?>
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

	

	foreach ($userSheets as $sheet):
                if(($msheet['Sheet']['month'] == $sheet['Sheet']['month']) && ($msheet['Sheet']['year'] == $sheet['Sheet']['year'])){
		$j++;
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $j; ?>&nbsp;</td>
		<td>
			<?php echo $sheet['Sheet']['name'];
                        //echo $this->Html->link($sheet['Sheet']['name'], array('controller' => 'sheets', 'action' => 'view', $sheet['Sheet']['id'],$dept_id)); ?>                        
		</td>
		<td><?php echo $department; ?>&nbsp;</td>
		<td><?php echo $sheet['User']['username']; ?>&nbsp;</td>
		<td><?php echo date('F', mktime(0, 0, 0, $sheet['Sheet']['month'], 1, $sheet['Sheet']['year'])); ?>&nbsp;</td>
		<td><?php echo $sheet['Sheet']['year']; ?>&nbsp;</td>
		<td class="actions">
                    
                  	<?php 
			  if($sheet['Sheet']['formula_status'] == "yes"){
			      if($sheet['Sheet']['status'] == 0){
				  echo $this->Html->link(__('<i class="icon-qrcode bigger-130"></i>', true), array('prefix' => 'admin', 'admin' => true, 'action' => 'webform', $sheet['Sheet']['id']),array('onclick'=>'alert("Webform is locked by Hotel.")','title' => 'Sheets', 'escape' => false,'class'=>'blue')); 
			      }
			      else
			      {
				  echo $this->Html->link(__('<i class="icon-qrcode bigger-130"></i>', true), array('prefix' => 'admin', 'admin' => true, 'action' => 'webform', $sheet['Sheet']['id']),array('title' => 'Sheets', 'escape' => false,'class'=>'blue')); 
			      }

				echo " | ".$this->Html->link(__('<i class="icon-download-alt bigger-130"></i>', true), array('prefix' => 'admin', 'admin' => true, 'action' => 'pdfwebforms', $sheet['Sheet']['id']),array('target'=>'_blank','title' => 'Archive', 'escape' => false,'class'=>'green')); 
				
			  }
			  else
			  {
			    ?>
			  <a href="javascript:void(0)" onclick='alert("Please create formula for this Webform  first.")'>Webform</a>
			  <?php
			  }
			  ?>
			| <?php echo $this->Html->link(__('Formulas', true), array('prefix' => 'admin', 'admin' => true, 'controller'=>'formulas', 'action' => 'index', $sheet['Sheet']['id'],$dept_id)); ?>
			| <?php echo $this->Html->link(__('<i class="icon-zoom-in bigger-130"></i>', true), array('action' => 'view', $sheet['Sheet']['id'],$dept_id), array('title' => 'View', 'escape' => false,'class'=>'blue')); ?>
			| <?php echo $this->Html->link(__('<i class="icon-pencil bigger-130"></i>', true), array('action' => 'edit', $sheet['Sheet']['id'],$dept_id), array('title' => 'Edit', 'escape' => false,'class'=>'green')); ?>
			| <?php echo $this->Html->link(__('<i class="icon-copy bigger-130"></i>', true), array('action' => 'copy', $this->params['pass'][0], $sheet['Sheet']['id'],$dept_id), array('title' => 'Copy', 'escape' => false,'class'=>'blue')); ?>
                        | <?php echo $this->Html->link(__('Copy Col.', true), array('action' => 'copy_column', $sheet['Sheet']['id'])); ?>
			| <?php echo $this->Html->link(__('<i class="icon-trash bigger-130"></i>', true), array('action' => 'delete', $sheet['Sheet']['id'],$dept_id), array('title' => 'Delete', 'escape' => false,'class'=>'red'), 'Are you sure you want to delete this sheet?'); ?>
<?php
		if($sheet['Sheet']['status'] == 0)
		{
		  $lockaction = 'unlock';
		  $lockname = '<i class="icon-unlock bigger-130"></i>';
		 
		}
		else
		{
		  $lockaction = 'lock';
		  $lockname = '<i class="icon-lock bigger-130"></i>';

		}
		?>

<?php 
			      echo " | ".$this->Html->link(
					$lockname,
					array('controller' => 'sheets', 'action' => $lockaction, $sheet['Sheet']['id']),
					array('title' => $lockaction, 'escape' => false,'class'=>'red'),
					"Are you sure you wish to $lockaction this sheet?"
			      ); 
			?>

		</td>
	</tr>
<?php } endforeach; }else{ ?>
	<tr><td colsapn="7" style="text-align:center;">No sheet is Available</td></tr>
 <?php     } ?>
	</table>
	 </td>
        </tr>
    
<?php } //end if for year match
endforeach;
} else { ?>
        <tr><td colsapn="7" style="text-align:center;">No sheet is Available</td></tr>
<?php } ?>
</table>
                </td>
            </tr>
            <?php }
            } ?>
        </table>

</div>