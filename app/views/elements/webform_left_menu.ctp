<?php ?>
<style>
#chart-menu{
  float:left;
  width: 185px;
  font-size: 12px;
/*   padding-left: 12px; */
}
.charts{
  background-color: #1084DC;
  padding: 3px;
  border-radius: 5px;
  text-decoration: none;
  color: #fff;
  float: left;
  margin: 5px;
}
#sheetinfo{
    background-color: #CDDEF3;
    border-radius: 5px 5px 5px 5px;
    box-shadow: 3px 4px 4px #9C9C9C;
    font-size: 13px;
    padding: 10px;
    /*position: absolute;*/
    /*top: 336px;
    width: 238px;*/
}
#sheetinfo li{
  margin-bottom: 5px;
}

.admin_left_pannel {
    background: none repeat scroll 0 0 #fff;
    float: left;
    padding: 3px;
    position: absolute;
    width: 15%;
    z-index: 1001;
}
#webform-grid{
   width: 100%;
   border-left:1px solid #fff;
}
.x-panel{
 margin:auto;   
}
</style>

<?php ?>

<div class="admin_left_pannel" id="left_menu" style="display:none;">
    <div class="actions">
	    <h3><?php __('Actions'); ?></h3>
	    <ul>
		    <li><?php echo $this->Html->link(__('Dashboard', true), array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'index')); ?></li>
                    <li><?php echo $this->Html->link(__('View All Hotels and Dept.', true), array('prefix' => 'admin', 'controller' => 'clients','action'=>'get_user_list', 'admin' => true)); ?></li>
		    <li><?php echo $this->Html->link(__('List Hotels', true), array('prefix' => 'admin', 'controller' => 'clients','action'=>'index', 'admin' => true)); ?></li>
		    <li><?php echo $this->Html->link(__('List Users', true), array('prefix' => 'admin', 'controller' => 'users', 'admin' => true)); ?></li>
		    <li><?php echo $this->Html->link(__('List Columns', true), array('prefix' => 'admin', 'controller' => 'columns', 'admin' => true)); ?></li>
		    <li><?php echo $this->Html->link(__('List Rows', true), array('prefix' => 'admin', 'controller' => 'rows', 'admin' => true)); ?></li>
		    <li><?php echo $this->Html->link(__('Manage Contents', true), array('prefix' => 'admin', 'controller' => 'contents','action'=>'index', 'admin' => true)); ?></li>
		    <li><?php echo $this->Html->link(__('Contact Enquiry', true), array('prefix' => 'admin', 'controller' => 'contacts','action'=>'index', 'admin' => true)); ?></li>
		    <li><?php echo $this->Html->link(__('Edit Profile', true), array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'edit', $this->Session->read('Auth.Admin.id'))); ?></li>
		    <li><?php echo $this->Html->link(__('Logout', true), array('prefix' => 'admin', 'admin' => false, 'controller' => 'admins', 'action' => 'logout')); ?></li>
	    </ul>
    </div>

    <?php if($this->layout == "ext"){ ?>
    <?php 
	$dept_obj = ClassRegistry::init('Department');
	$dept_data = $dept_obj->field('name',array('id'=>$sheet['Sheet']['department_id']));
    ?>
    <div id="sheetinfo">
	    <li>Sheet Name : <?=$sheet['Sheet']['name'] ;?></li>
	    <li>Department Name : <?=$dept_data ;?></li>
	    <li>Username : <?=$sheet['User']['username'] ;?></li>
            

	    <div id="chart-menu">
		<?php
		echo $this->Html->link('Pie-Chart','javascript:void(0)', array('class'=>'charts','onClick'=>"window.open('/admin/sheets/viewchart/$sheetId/pie','WebForms-PieChart','menubar=1,resizable=1,width=750,height=550,algin=center,scrollbars=yes');"));
		echo $this->Html->link('Line-Chart','javascript:void(0)', array('class'=>'charts','onClick'=>"window.open('/admin/sheets/viewchart/$sheetId/line','WebForms-PieChart','menubar=1,resizable=1,width=750,height=550,algin=center,scrollbars=yes');"));
		echo $this->Html->link('Download Report as CSV',"export_csv/$sheetId/csv", array('class'=>'charts'));
		echo $this->Html->link('Download Report as PDF',"export_pdf/$sheetId/pdf", array('class'=>'charts'));
                
		if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_excel'] == 1)){
                    echo $this->Html->link('Starlite Import', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_excel' ,$sheetId), array('class' => 'charts'));
		}
               
                if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_protel'] == 1)){                
                   echo $this->Html->link('Protel Import', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_protel' ,$sheetId), array('class' => 'charts'));
		}
                
                if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_protel_grunerbaum'] == 1)){                
                   echo $this->Html->link('Protel Import (Grunerbaum)', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_protel_test' ,$sheetId), array('class' => 'charts'));
		}
                
                if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_excel_draycott'] == 1)){                
                   echo $this->Html->link('Protel Import (Draycott)', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_excel_draycott' ,$sheetId), array('class' => 'charts'));
		}

                if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_excel_barbados'] == 1)){                
                   echo $this->Html->link('Import RDP (Barbados)', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_excel_barbados' ,$sheetId), array('class' => 'charts'));
		}
                
		if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_txt'] == 1)){                    
                    
                    echo $this->Html->link('Opera Import', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_txt' ,$sheetId), array('class' => 'charts'));
                }
                
                if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_opera_txt_grand'] == 1)){                    
                    
                    echo $this->Html->link('Opera excl Comps', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_operaexcel' ,$sheetId), array('class' => 'charts'));
                }
                
                if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_opera_txt_habtoor'] == 1)){                    
                    echo $this->Html->link('Opera Habtoor', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_txt_habtoor' ,$sheetId), array('class' => 'charts'));
                }
                
		?>
	    </div>
	    <div class="clear"></div>
    </div>
</div>
<?php } ?>