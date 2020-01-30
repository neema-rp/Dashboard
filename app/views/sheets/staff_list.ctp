<?php ?><script type="text/javascript">
function make_blank()
{
 document.getElementById('search').value ="";
}

function showtext()
{
 document.getElementById('search').value ="search";
}

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
<?php echo $this->Session->flash(); ?>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Sheets <small><i class="icon-double-angle-right"></i> List</small></h1>
        </div>

	<?php echo $this->Form->create('Sheet', array('url' => array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'list'))); ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" id="searchBox">
		<tbody>
			<tr>
                                <td>
                                    <span class="input-icon">
                                          <?php echo $this->Form->text('value', array('id'=>'search','class' => 'tb11','value'=>$search_val,'style'=>'color:grey', 'placeholder'=>'Search by Sheet name'));?>
                                            <i class="icon-search nav-search-icon"></i>
                                    </span>
					<?php echo $this->Form->submit('Search', array('id' => 'button', 'name' => 'button', 'class' => 'btn btn-danger', 'div' => false)); ?>&nbsp;
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
                    <a id="YearToggle_<?php echo $s_years['Sheet']['year']; ?>" href="javascript:toggle_year('<?php echo $s_years['Sheet']['year']; ?>');" style="text-decoration: none; font-weight:bold;vertical-align: sub;">
                        <i class="icon-plus"></i></a>
                    <?php echo $s_years['Sheet']['year']; ?>
                </td>
            </tr>
            
            <tr<?php echo $class;?>  style="display:none;" id="year_div_<?php echo $s_years['Sheet']['year'];?>">
            <td>
        
        <?php if(!empty($msheets)){ ?>
        <table  class="table table-striped table-bordered table-hover">
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
			<?php echo $sheet['Sheet']['name']; ?>&nbsp;
		</td>
		<td><?php echo $sheet['Sheet']['department_name']; ?>&nbsp;</td>
		<td><?php echo $sheet['User']['username']; ?>&nbsp;</td>
		<td><?php echo date('F', mktime(0, 0, 0, $sheet['Sheet']['month'], 1, $sheet['Sheet']['year'])); ?>&nbsp;</td>
		<td><?php echo $sheet['Sheet']['year']; ?>&nbsp;</td>
		<td class="actions">
		<?php if($sheet['Sheet']['status'] == 0){ ?>
<!--                            <a href="javascript:void(0)" onclick='alert("Webform is locked. Please contact Hotel.")' >Webform</a>-->
                                <?php echo $this->Html->link(__('<i class="icon-qrcode bigger-130"></i>', true), '#',array('onclick'=>'alert("This sheet is locked.")','title' => 'Sheets', 'escape' => false,'class'=>'blue')); ?>
                        <?php } else {
                                
                            echo $this->Html->link(__('Copy Column', true), array('prefix' => 'staff', 'staff' => true, 'action' => 'copy_column', $sheet['Sheet']['id']));
                            echo " | ";
                            echo $this->Html->link(__('<i class="icon-qrcode bigger-130"></i>', true), array('prefix' => 'staff', 'staff' => true, 'action' => 'webform', $sheet['Sheet']['id']),array('title' => 'Sheets', 'escape' => false,'class'=>'blue'));
                            echo " | ";
                            echo $this->Html->link(__('<i class="icon-download-alt bigger-130"></i>', true), array('prefix' => 'staff', 'staff' => true, 'action' => 'pdfwebforms', $sheet['Sheet']['id']),array('target'=>'_blank','title' => 'Archive', 'escape' => false,'class'=>'green')); 
                           // echo " | ";
                           // echo $this->Html->link(__('Calendar', true), array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'calendar', $sheet['Sheet']['department_id']));
                        }
                        ?>
		</td>
	</tr>
	<?php } endforeach; } else { ?>
        <tr><td colsapn="7" style="text-align:center;">No sheet is Available</td></tr>
        <?php } ?>
	</table>
	
         </td>
        </tr>

<?php  } //end if for year match
endforeach; } else { ?>
        <tr><td colsapn="7" style="text-align:center;">No sheet is Available</td></tr>
<?php } ?>
</table>        
                 </td>
            </tr>
            <?php }
            } ?>
        </table>
</div>