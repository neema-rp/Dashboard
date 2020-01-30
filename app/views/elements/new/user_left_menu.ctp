<?php
$controller = $this->params['controller'];
$action     = $this->params['action'];
?>

<div class="sidebar" id="sidebar">
        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                        <button class="btn btn-small btn-success" onClick="window.location.href='/staff/sheets';">
                               <i class="icon-signal"></i>
                        </button>
                        <button class="btn btn-small btn-info" onClick="window.location.href='/users/edit';">
                               <i class="icon-pencil"></i>
                        </button>
                        <button class="btn btn-small btn-warning" onClick="window.location.href='/staff/sheets/list';">
                                <i class="icon-group"></i>
                        </button>
                        <button class="btn btn-small btn-danger" onClick="window.location.href='/staff/sheets';">
                                <i class="icon-cogs"></i>
                        </button>
                </div>

                <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                        <span class="btn btn-success"></span>
                        <span class="btn btn-info"></span>
                        <span class="btn btn-warning"></span>
                        <span class="btn btn-danger"></span>
                </div>
        </div><!--#sidebar-shortcuts-->


        <ul class="nav nav-list">
                <?php 
                $clientId = $this->Session->read('Auth.User.client_id');
                
                if($this->Session->read('Auth.User.id') == '338'){ ?>
                    <li class="staffusersusers"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Daily Flash </span>', true), array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'flash'),array('escape'=>false)); ?></li>
                <?php }else{ ?>
                       <li class="staffsheets"><?php echo $this->Html->link(__('<i class="icon-dashboard"></i><span class="menu-text"> Dashboard </span>', true), array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'index'),array('escape'=>false));?></li>
                       <li class="staffsheetslist staffdepartmentslist"><?php echo $this->Html->link(__('<i class="icon-desktop"></i><span class="menu-text">List Department </span>', true), array('prefix' => 'staff', 'staff' => true, 'controller' => 'departments', 'action' => 'list'),array('escape'=>false));?></li>
<!--                       <li class="staffusersflash"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Daily Flash </span>', true), array('prefix' => 'staff', 'staff' => true, 'controller' => 'users', 'action' => 'flash'),array('escape'=>false)); ?></li>-->
                       <li class="staffgpspacks"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> GPS Pack </span>', true), array('prefix' => 'staff', 'staff' => true, 'controller' => 'GpsPacks', 'action' => 'index',$clientId),array('escape'=>false)); ?></li>
                       
                       <li class="activities">
                            <a href="#" class="dropdown-toggle">
                                    <i class="icon-edit"></i>
                                    <span class="menu-text"> Reports </span>
                                    <b class="arrow icon-angle-down"></b>
                            </a>
                            <ul class="submenu">
                                 <li class="staffactivitiesweekly_report"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Weekly Report </span>', true), array('prefix' => 'staff', 'staff' => true, 'controller' => 'Activities', 'action' => 'weekly_report'),array('escape'=>false)); ?></li>
                                 <li class="staffactivitiesperformance_chart"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Yield Performance Chart </span>', true), array('prefix' => 'staff', 'staff' => true, 'controller' => 'Activities', 'action' => 'performance_chart'),array('escape'=>false)); ?></li>
                                 <li class="staffactivitieslookup_chart"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Trends Chart </span>', true), array('prefix' => 'staff', 'controller' => 'Activities','action'=>'lookup_chart', 'staff' => true),array('escape'=>false)); ?></li>
                           </ul>
                    </li>
                       
                       
                       <li><a href="http://www.mypricingwizard.net/login.php?hotel_id=<?php echo $clientId; ?>" target="_blank"><i class="icon-arrow-right"></i><span class="menu-text"> Pricing Wizard </span></a></li>
                <?php } ?>

<!--                    <li class="usersedit"><?php echo $this->Html->link(__('<i class="icon-edit"></i><span class="menu-text"> Edit Profile </span>', true), array('prefix' => 'staff', 'staff' => false,'controller' => 'users', 'action' => 'edit'),array('escape'=>false)); ?></li>
                    <li><?php echo $this->Html->link(__('<i class="icon-certificate"></i><span class="menu-text"> Logout </span>', true), array('prefix' => 'staff', 'staff' => false, 'controller' => 'users', 'action' => 'logout'),array('escape'=>false)); ?></li>-->
                    <li>
                        <a href="mailto:support@revenue-performance.com">
                                <i class="icon-info-sign"></i><span class="menu-text"> Help </span>
                        </a>
                    </li>

    <?php if($this->layout == "ext"){ ?>
    <?php 
        $dept_obj = ClassRegistry::init('Department');
        $dept_data = $dept_obj->field('name',array('id'=>$sheet['Sheet']['department_id']));
    ?>
    <div id="sheetinfo" style="text-align: center;">
            <li>Sheet Name : <?=$sheet['Sheet']['name'] ;?></li>
            <li>Department Name : <?=$dept_data ;?></li>
            <li>Username : <?=$sheet['User']['username'] ;?></li>

            <div id="chart-menu">
                 <?php
                echo $this->Html->link('Pie-Chart','#', array('class'=>'btn btn-warning btn-mini','onClick'=>"javascript:window.open('/staff/sheets/viewchart/$sheetId/pie','p1','menubar=1,resizable=1,width=750,height=550,algin=center,scrollbars=yes');return false;",'style'=>'margin-bottom:4px;'));
                echo '&nbsp;&nbsp;';
                echo $this->Html->link('Line-Chart','#', array('class'=>'btn btn-warning btn-mini','onClick'=>"javascript:window.open('/staff/sheets/viewchart/$sheetId/line','p2','menubar=1,resizable=1,width=750,height=550,algin=center,scrollbars=yes');return false;",'style'=>'margin-bottom:4px;'));
                echo $this->Html->link('Download Report as CSV',"export_csv/$sheetId/csv", array('class'=>'btn btn-warning btn-mini'));
                echo $this->Html->link('Download Report as PDF',"export_pdf/$sheetId/csv", array('class'=>'btn btn-warning btn-mini'));
                
                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['pms_csv_import'] == 1)){
                    echo $this->Html->link('Import PMS CSV', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_csv' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
               }
               
                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['import_excel'] == 1)){
                    echo $this->Html->link('Starlite Import', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_excel' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;')); 
		}
                
                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['import_txt'] == 1)){
                    echo $this->Html->link('Opera Import', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_txt' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                
                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['import_protel'] == 1)){                
                     echo $this->Html->link('Protel Import', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_protel' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
		}
                
                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['import_protel_grunerbaum'] == 1)){                
                    echo $this->Html->link('Protel Import', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_protel_gb' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
		}
                
              if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['import_opera_txt_grand'] == 1)){
                  echo $this->Html->link('Opera import incl comps', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_operaexcel' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                
                
                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['import_excel_barbados'] == 1)){                
                    echo $this->Html->link('Import RDP (Barbados)', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_excel_barbados' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
		}

                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['4c_import'] == 1)){                    
                    echo $this->Html->link('Import CSV', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_4ccsv' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['4c_cie_import'] == 1)){                    
                    echo $this->Html->link('Import CSV', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_cie' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }

                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['lucknam_import'] == 1)){                    
                    echo $this->Html->link('Import CSV', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_luckname' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['import_simola'] == 1)){                    
                    echo $this->Html->link('Import CSV', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_simola' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                if(($this->params['prefix'] == 'staff') && ($sheet['Sheet']['import_raithwaite'] == 1)){                    
                    echo $this->Html->link('Import CSV', array('prefix' => 'staff', 'staff' => true, 'controller' => 'sheets', 'action' => 'import_raithwaite' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                ?>
            </div>
             <div class="clear"></div>
    </div>
<?php } ?>

                    
<?php if(($this->params['controller'] == 'advancedSheets' || $this->params['controller'] == 'AdvancedSheets') && ($this->params['action'] == 'staff_webform')){ ?>
    <div id="sheetinfo" style="text-align:center;">
	    <li>Sheet Name : <?=$sheet_name; ?></li>
	    <li>Department Name : <?=$dept_name; ?></li>
	    <li>Username : <?=$username; ?></li>
	    <div id="chart-menu">
		<?php
                echo $this->Html->link('Download Report as CSV',"/admin/AdvancedSheets/export_csv/$sheetId", array('class'=>'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
		echo $this->Html->link('Download Report as PDF',"/admin/AdvancedSheets/export_pdf/$sheetId", array('class'=>'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                if(($this->params['prefix'] == 'staff') && ($data['AdvancedSheet']['import_grunerbaum'] == 1)){
                    //echo $this->Html->link('Import Grunerbaum Excel', array('prefix' => 'client', 'client' => true, 'controller' => 'AdvancedSheets', 'action' => 'import_grunerbaum' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                if(($this->params['prefix'] == 'staff') && ($data['AdvancedSheet']['import_italy'] == 1)){
                    //echo $this->Html->link('Import Protel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'AdvancedSheets', 'action' => 'import_protel' ,$sheetId), array('class' => 'charts'));
                }
                ?>
	    </div>
	    <div class="clear"></div>
    </div>
<?php } ?>
                    
 </ul>
        
        <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="icon-double-angle-left"></i>
        </div>
        
         <div class="logo" style="float:bottom;text-align:center;vertical-align: bottom;padding-bottom: 2px;bottom: 0px;position: relative;">
             <a href="http://www.revenue-performance.com/" target="_blank">
                 <img src="http://beta.myrevenuedashboard.net/img/RP-logo.png" alt="Revenue Performance" />
             </a>
         </div>
        
        
</div>


<script>
$(document).ready(function() {  
    
    var path=window.location.pathname;
    var basepath1 = path.split('/').join('');
    var basepath = basepath1.toLowerCase();
    //alert(basepath);
    $("."+basepath).addClass("active");
    //$("#"+basepath).closest("span").addClass("open");
    $("."+basepath).parents("li:first").addClass('active');
    $("."+basepath).parents("li:first").addClass('open');
});
</script>