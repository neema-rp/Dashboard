<?php ?>
<div class="sidebar" id="sidebar">
        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                        <button class="btn btn-small btn-success" onClick="window.location.href='/admins/';">
                               <i class="icon-signal"></i>
                        </button>
                        <button class="btn btn-small btn-info" onClick="window.location.href='/admins/edit';">
                               <i class="icon-pencil"></i>
                        </button>
                        <button class="btn btn-small btn-warning" onClick="window.location.href='/admin/clients/';">
                                <i class="icon-group"></i>
                        </button>
                        <button class="btn btn-small btn-danger" onClick="window.location.href='/admins/';">
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
                <li class="admins">
                        <a href="/admins/">
                                <i class="icon-dashboard"></i>
                                <span class="menu-text"> Dashboard </span>
                        </a>
                </li>
                <li class="adminclientsget_user_list">
                        <a href="/admin/clients/get_user_list">
                                <i class="icon-text-width"></i>
                                <span class="menu-text">Hotels Flow</span>
                        </a>
                </li>
                <li class="adminclients">
                        <a href="/admin/clients/">
                                <i class="icon-text-width"></i>
                                <span class="menu-text">Hotels</span>
                        </a>
                </li>
                <li class="adminusers">
                        <a href="/admin/users/">
                                <i class="icon-list"></i><span class="menu-text">Users</span>
                        </a>
                </li>
                <li class="adminsubadmins">
                        <a href="/admin/subadmins/">
                                <i class="icon-list-alt"></i>
                                <span class="menu-text"> Sub-Admins </span>
                        </a>
                </li>
                <li >
                        <a href="#" class="dropdown-toggle">
                                <i class="icon-edit"></i><span class="menu-text"> Settings </span>

                                <b class="arrow icon-angle-down"></b>
                        </a>
                        <ul class="submenu">
                                <li class="admincolumns">
                                        <a href="/admin/columns">
                                                <i class="icon-double-angle-right"></i>
                                                Columns
                                        </a>
                                </li>
                                <li class="adminrows">
                                        <a href="/admin/rows">
                                                <i class="icon-double-angle-right"></i>
                                                Rows
                                        </a>
                                </li>
                                <li class="adminsarchive">
                                        <a href="/admins/archive">
                                                <i class="icon-double-angle-right"></i>
                                                Archive Rooms Webform
                                        </a>
                                </li>
                                <li class="adminshotel_package">
                                        <a href="/admins/hotel_package">
                                                <i class="icon-double-angle-right"></i>
                                                Advanced Package
                                        </a>
                                </li>
                        </ul>
                </li>
                
                <li>
                        <a href="#" class="dropdown-toggle">
                                <i class="icon-edit"></i>
                                <span class="menu-text"> Segmentation </span>

                                <b class="arrow icon-angle-down"></b>
                        </a>
                        <ul class="submenu">
                                <li class="adminTemplates">
                                        <a href="/admin/Templates/">
                                                <i class="icon-double-angle-right"></i>
                                                Template
                                        </a>
                                </li>
                                <li class="adminmarketSegments">
                                        <a href="/admin/marketSegments">
                                                <i class="icon-double-angle-right"></i>
                                                Market Segments
                                        </a>
                                </li>
                        </ul>
                </li>
                
                <li class="admincontacts">
                        <a href="/admin/contacts/">
                                <i class="icon-list-alt"></i>
                                <span class="menu-text"> Contact Enquiry </span>
                        </a>
                </li>
                
                
                <li class="activities">
                        <a href="#" class="dropdown-toggle">
                                <i class="icon-edit"></i>
                                <span class="menu-text"> Reports </span>
                                <b class="arrow icon-angle-down"></b>
                        </a>
                        <ul class="submenu">
                                <li class="adminActivities">
                                        <a href="/admin/Activities/">
                                                <i class="icon-list-alt"></i>
                                                <span class="menu-text"> Utilisation </span>
                                        </a>
                                </li>
                                <li class="adminActivitiesweekly_report">
                                        <a href="/admin/Activities/weekly_report">
                                                <i class="icon-list-alt"></i>
                                                <span class="menu-text"> Weekly Report </span>
                                        </a>
                                </li>
                                <li class="adminActivitieslookup_chart"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text">Trends Chart</span>', true), array('prefix' => 'admin', 'controller' => 'Activities','action'=>'lookup_chart', 'admin' => true),array('escape'=>false)); ?></li>
                                <li class="adminActivitiesperformance_chart"><?php echo $this->Html->link(__('<i class="icon-list-alt"></i><span class="menu-text">Yield Performance Chart</span>', true), array('prefix' => 'admin', 'controller' => 'Activities','action'=>'performance_chart', 'admin' => true),array('escape'=>false)); ?></li>
                        </ul>
                </li>
                    
                
<!--                <li class="adminsedit">
                        <a href="/admins/edit/">
                                <i class="icon-list-alt"></i><span class="menu-text"> Edit Profile </span>
                        </a>
                </li>
                <li>
                        <a href="/admins/logout/">
                                <i class="icon-list-alt"></i>
                                <span class="menu-text"> Logout </span>
                        </a>
                </li>-->
                
       
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
                        echo $this->Html->link('Pie-Chart','javascript:void(0)', array('class'=>'btn btn-warning btn-mini','onClick'=>"window.open('/admin/sheets/viewchart/$sheetId/pie','WebForms-PieChart','menubar=1,resizable=1,width=750,height=550,algin=center,scrollbars=yes');",'style'=>'margin-bottom:4px;'));
                        echo '&nbsp;&nbsp;';
                        echo $this->Html->link('Line-Chart','javascript:void(0)', array('class'=>'btn btn-warning btn-mini','onClick'=>"window.open('/admin/sheets/viewchart/$sheetId/line','WebForms-PieChart','menubar=1,resizable=1,width=750,height=550,algin=center,scrollbars=yes');",'style'=>'margin-bottom:4px;'));
                       
                        echo $this->Html->link('Download Report as CSV',"export_csv/$sheetId/csv", array('class'=>'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));

                        echo $this->Html->link('Download Report as PDF',"export_pdf/$sheetId/pdf", array('class'=>'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['pms_csv_import'] == 1)){
                            echo $this->Html->link('Import PMS CSV', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_csv' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                       }
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_excel'] == 1)){
                            echo $this->Html->link('Starlite Import', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_excel' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_protel'] == 1)){ 
                           echo $this->Html->link('Protel Import', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_protel' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_protel_grunerbaum'] == 1)){                
                           echo $this->Html->link('Protel Import (Grunerbaum)', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_protel_gb' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_excel_barbados'] == 1)){                
                            echo $this->Html->link('Import RDP (Barbados)', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_excel_barbados' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_txt'] == 1)){                    
                            echo $this->Html->link('Opera Import', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_txt' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                       
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_opera_txt_grand'] == 1)){                    
                            echo $this->Html->link('Opera import incl comps', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_operaexcel' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['4c_import'] == 1)){                    
                            echo $this->Html->link('Import CSV', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_4ccsv' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['4c_cie_import'] == 1)){                    
                            echo $this->Html->link('Import CSV', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_cie' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['lucknam_import'] == 1)){                    
                            echo $this->Html->link('Import CSV', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_luckname' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_simola'] == 1)){                    
                            echo $this->Html->link('Import CSV', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_simola' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_raithwaite'] == 1)){                    
                            echo $this->Html->link('Import CSV', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_raithwaite' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_oceanview'] == 1)){                    
                            echo $this->Html->link('Import CSV', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_oceanview' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        if(($this->params['prefix'] == 'admin') && ($sheet['Sheet']['import_sanbona'] == 1)){                    
                            echo $this->Html->link('Import CSV', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'import_sanbona' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                        }
                        ?>
                    </div>
                    <div class="clear"></div>
            </div>
        <?php } ?>
                
                
                
                
<?php if(($this->params['controller'] == 'advancedSheets' || $this->params['controller'] == 'AdvancedSheets') && ($this->params['action'] == 'admin_webform')){ ?>
    <div id="sheetinfo" style="text-align: center;">
	    <li>Sheet Name : <?=$sheet_name; ?></li>
	    <li>Department Name : <?=$dept_name; ?></li>
	    <li>Username : <?=$username; ?></li>
	    <div id="chart-menu">
		<?php
                echo $this->Html->link('Download Report as CSV',"export_csv/$sheetId", array('class'=>'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
		
                echo $this->Html->link('Download Report as PDF',"export_pdf/$sheetId", array('class'=>'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                if(($this->params['prefix'] == 'admin') && ($data['AdvancedSheet']['import_grunerbaum'] == 1)){
                    echo $this->Html->link('Import Grunerbaum Excel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'AdvancedSheets', 'action' => 'import_grunerbaum' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                if(($this->params['prefix'] == 'admin') && ($data['AdvancedSheet']['import_simola'] == 1)){
                    echo $this->Html->link('Import CSV', array('prefix' => 'admin', 'admin' => true, 'controller' => 'AdvancedSheets', 'action' => 'import_simola' ,$sheetId), array('class' => 'btn btn-warning btn-mini','style'=>'margin-bottom:4px;'));
                }
                ?>
	    </div>
	    <div class="clear"></div>
    </div>
<?php } ?>
        
 </ul><!--/.nav-list-->
        
        <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="icon-double-angle-left"></i>
        </div>
 
 
 <div class="logo" style="float:bottom;text-align:center;vertical-align: bottom;padding-bottom: 2px;bottom: 0px;position: relative;">
     <a href="http://www.revenue-performance.com/" target="_blank">
         <img src="https://myrevenuedashboard.net/img/RP-logo.png" alt="Revenue Performance" />
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