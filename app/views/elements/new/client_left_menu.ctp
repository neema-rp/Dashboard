<?php
$controller = $this->params['controller'];
$action     = $this->params['action'];
$clientId = $this->Session->read('Auth.Client.id');
?>

<div class="sidebar" id="sidebar">
        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                        <button class="btn btn-small btn-success" onClick="window.location.href='/clients/';">
                               <i class="icon-signal"></i>
                        </button>
                        <button class="btn btn-small btn-info" onClick="window.location.href='/clients/edit';">
                               <i class="icon-pencil"></i>
                        </button>
                        <button class="btn btn-small btn-warning" onClick="window.location.href='/client/users/';">
                                <i class="icon-group"></i>
                        </button>
                        <button class="btn btn-small btn-danger" onClick="window.location.href='/clients/';">
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
                   <li class="clients"><?php echo $this->Html->link(__('<i class="icon-dashboard"></i><span class="menu-text"> Dashboard </span>', true), array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'index'),array('escape'=>false));?></li>
                    <li class="clientusers"><?php echo $this->Html->link(__('<i class="icon-list"></i><span class="menu-text">Users</span>', true), array('prefix' => 'client', 'client' => true ,'controller' => 'users', 'action' => 'index'),array('escape'=>false)); ?> </li>
                    <li class="clientdepartments"><?php echo $this->Html->link(__('<i class="icon-desktop"></i><span class="menu-text">List Department</span>', true), array('prefix' => 'client', 'client' => true ,'controller' => 'departments', 'action' => 'list'),array('escape'=>false)); ?></li>
                    <li class="clientschain_list"><?php echo $this->Html->link(__('<i class="icon-text-width"></i><span class="menu-text">List Chain Hotels</span>', true), array('client' => false ,'controller' => 'clients', 'action' => 'chain_list'),array('escape'=>false)); ?></li>
<!--                    <li class="clientclientsflash"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Daily Flash </span>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'clients', 'action' => 'flash'),array('escape'=>false)); ?></li>-->
                    <li class="clientgpspacks"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> GPS Pack </span>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'GpsPacks', 'action' => 'index',$clientId),array('escape'=>false)); ?></li>
                   <!-- <li class="clientsurveyUsers"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Guest Survey </span>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'SurveyUsers', 'action' => 'index',$clientId),array('escape'=>false)); ?></li>-->
                    
                    <li class="surveyUsers">
                            <a href="#" class="dropdown-toggle">
                                    <i class="icon-edit"></i>
                                    <span class="menu-text"> Guest Survey </span>
                                    <b class="arrow icon-angle-down"></b>
                            </a>
                            <ul class="submenu">
                                  <li class="clientsurveyusersindex<?php echo $clientId ?>"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Guest List </span>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'SurveyUsers', 'action' => 'index',$clientId),array('escape'=>false)); ?></li>                    
                                  <li class="clientsurveyquestionsindex<?php echo $clientId ?>"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Survey Questions </span>', true), array('prefix' => 'client', 'controller' => 'SurveyQuestions','action'=>'index',$clientId, 'client' => true),array('escape'=>false)); ?></li>
                                   <li class="clientsurveyusersreports<?php echo $clientId ?>"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Survey Report </span>', true), array('prefix' => 'client', 'controller' => 'SurveyUsers','action'=>'reports',$clientId, 'client' => true),array('escape'=>false)); ?></li>
                           </ul>
                    </li>
                    
                    <li class="activities">
                            <a href="#" class="dropdown-toggle">
                                    <i class="icon-tasks"></i>
                                    <span class="menu-text"> Reports </span>
                                    <b class="arrow icon-angle-down"></b>
                            </a>
                            <ul class="submenu">
                                  <li class="clientactivitiesweekly_report"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Weekly Report </span>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'Activities', 'action' => 'weekly_report'),array('escape'=>false)); ?></li>
                                  <li class="clientactivitiesperformance_chart"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Yield Performance Chart </span>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'Activities', 'action' => 'performance_chart'),array('escape'=>false)); ?></li>
                                  <li class="clientactivitieslookup_chart"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> Trends Chart </span>', true), array('prefix' => 'client', 'controller' => 'Activities','action'=>'lookup_chart', 'client' => true),array('escape'=>false)); ?></li>
                           </ul>
                    </li>
                    
                    <li><a href="http://www.mypricingwizard.net/login.php?hotel_id=<?php echo $clientId; ?>" target="_blank"><i class="icon-arrow-right"></i><span class="menu-text"> Pricing Wizard </span></a></li>
                    
<!--                    <li class="clientsedit"><?php echo $this->Html->link(__('<i class="icon-edit"></i><span class="menu-text"> Edit Profile </span>', true), array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'edit'),array('escape'=>false)); ?></li>-->
                    
                    
                    <li class="activities">
                            <a href="#" class="dropdown-toggle">
                                    <i class="icon-tasks"></i>
                                    <span class="menu-text"> Settings </span>
                                    <b class="arrow icon-angle-down"></b>
                            </a>
                            <ul class="submenu">
                                  <li class="clientcolumnsrange"><?php echo $this->Html->link(__('<i class="icon-calendar"></i><span class="menu-text"> Calendar Heat Map Range </span>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'columns', 'action' => 'range'),array('escape'=>false)); ?></li>                                 
                                  <li class="clientmarketsegmentslist"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> MarketSegments </span>', true), array('prefix' => 'client', 'client' => true, 'controller' => 'MarketSegments', 'action' => 'list'),array('escape'=>false)); ?></li>
                                  <li class="clientgpspacksindex"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text"> GpsPacks Settings </span>', true), array('prefix' => 'client', 'controller' => 'GpsPacks','action'=>'index',$clientId, 'client' => true),array('escape'=>false)); ?></li>
                           </ul>
                    </li>
                    
                    
<!--                    <li>
                        <?php echo $this->Html->link(__('<i class="icon-certificate"></i><span class="menu-text"> Logout </span>', true), array('prefix' => 'client', 'client' => false, 'controller' => 'clients', 'action' => 'logout'),array('escape'=>false)); ?>
                    </li>-->
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
    <div id="sheetinfo" style="text-align:center;">
            <li>Sheet Name : <?=$sheet['Sheet']['name'] ;?></li>
            <li>Department Name : <?=$dept_data ;?></li>
            <li>Username : <?=$sheet['User']['username'] ;?></li>

            <div id="chart-menu">
               <?php
                echo $this->Html->link('Pie-Chart','javascript:void(0)', array('class'=>'btn btn-warning btn-mini','onClick'=>"window.open('/client/sheets/viewchart/$sheetId/pie','WebForms-PieChart','menubar=1,resizable=1,width=750,height=550,algin=center,scrollbars=yes');",'style'=>'margin-bottom:4px;'));
                echo '&nbsp;&nbsp;';
                echo $this->Html->link('Line-Chart','javascript:void(0)', array('class'=>'btn btn-warning btn-mini','onClick'=>"window.open('/client/sheets/viewchart/$sheetId/line','WebForms-PieChart','menubar=1,resizable=1,width=750,height=550,algin=center,scrollbars=yes');",'style'=>'margin-bottom:4px;'));
               
                echo $this->Html->link('Download Report as CSV',"export_csv/$sheetId/csv", array('class'=>'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                
                echo $this->Html->link('Download Report as PDF',"export_pdf/$sheetId/pdf", array('class'=>'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['pms_csv_import'] == 1)){
                    echo $this->Html->link('Import PMS CSV', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_csv' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                                
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['import_excel'] == 1)){
                    echo $this->Html->link('Starlite Import', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_excel' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
		}
                    
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['import_txt'] == 1)){
                    echo $this->Html->link('Opera Import', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_txt' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['import_protel'] == 1)){
                    echo $this->Html->link('Protel Import', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_protel' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
		}
                
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['import_protel_grunerbaum'] == 1)){                
                    echo $this->Html->link('Protel Import', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_protel_gb' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
		}
                
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['import_opera_txt_grand'] == 1)){
                    echo $this->Html->link('Opera import incl comps', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_operaexcel' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['import_excel_barbados'] == 1)){                
                    echo $this->Html->link('Import RDP (Barbados)', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_excel_barbados' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
		}

                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['4c_import'] == 1)){                    
                    echo $this->Html->link('Import CSV', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_4ccsv' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['4c_cie_import'] == 1)){                    
                    echo $this->Html->link('Import CSV', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_cie' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['lucknam_import'] == 1)){                    
                    echo $this->Html->link('Import CSV', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_luckname' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['import_simola'] == 1)){                    
                    echo $this->Html->link('Import CSV', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_simola' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                if(($this->params['prefix'] == 'client') && ($sheet['Sheet']['import_raithwaite'] == 1)){                    
                    echo $this->Html->link('Import CSV', array('prefix' => 'client', 'client' => true, 'controller' => 'sheets', 'action' => 'import_raithwaite' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                ?>
            </div>
            <div class="clear"></div>
    </div>
<?php } ?>

<?php if(($this->params['controller'] == 'advancedSheets' || $this->params['controller'] == 'AdvancedSheets') && ($this->params['action'] == 'client_webform')){ ?>
    <div id="sheetinfo" style="text-align:center;">
	    <li>Sheet Name : <?=$sheet_name; ?></li>
	    <li>Department Name : <?=$dept_name; ?></li>
	    <li>Username : <?=$username; ?></li>
	    <div id="chart-menu">
		<?php
                echo $this->Html->link('Download Report as CSV',"/admin/AdvancedSheets/export_csv/$sheetId", array('class'=>'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
		echo $this->Html->link('Download Report as PDF',"/admin/AdvancedSheets/export_pdf/$sheetId", array('class'=>'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                if(($this->params['prefix'] == 'client') && ($data['AdvancedSheet']['import_grunerbaum'] == 1)){
                    echo $this->Html->link('Import Grunerbaum Excel', array('prefix' => 'client', 'client' => true, 'controller' => 'AdvancedSheets', 'action' => 'import_grunerbaum' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                if(($this->params['prefix'] == 'admin') && ($data['AdvancedSheet']['import_simola'] == 1)){
                    //echo $this->Html->link('Import CSV', array('prefix' => 'admin', 'admin' => true, 'controller' => 'AdvancedSheets', 'action' => 'import_simola' ,$sheetId), array('class' => 'charts'));
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