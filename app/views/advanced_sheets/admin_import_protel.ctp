<?php

?>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotel <small><i class="icon-double-angle-right"></i> Import</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Protel Import</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

        <?php echo $this->Form->create('AdvancedSheet', array('url'=>array('controller'=>'advancedSheets', 'action'=>'admin_import_protel/'.$sheetId),'type' => 'file'));?>
        <table class="table table-striped table-bordered table-hover">
                <tbody>
                        <tr>                            
                            <td>
                                <?php echo $this->Form->input('browse_file',array('label'=>false,'div'=>false,'error'=>false,'type'=>'file'));?>            
                            </td>
                        </tr>
                       
                </tbody>
        </table>
                    
        <?php
        echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
        echo "&nbsp;&nbsp;";
        echo $this->Html->link('Cancel', array('prefix' => 'staff', 'staff' => true, 'controller' => 'AdvancedSheets', 'action' => 'webform',$sheetId), array('class' => 'btn btn-success', 'escape' => false));
        echo $this->Form->end();
        ?>
        </div></div></div></div>
</div>