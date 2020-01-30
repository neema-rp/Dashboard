<?php
echo $this->Session->flash();
?>
<div class="sheets index">
        <?php echo $this->Form->create('Sheet', array('url'=>array('controller'=>'webservices', 'action'=>'admin_import_lapalm/'.$sheetId),'type' => 'file'));?>
        <fieldset>
        <legend><?php __('Import Excel');?></legend>
        
        <table cellpadding="0" cellspacing="0">
                <tbody>                      
                        <tr>                            
                            <td>
                                <?php echo $this->Form->input('browse_file',array('label'=>false,'div'=>false,'error'=>false,'type'=>'file'));?>            
                            </td>
                        </tr>
                       
                </tbody>
        </table>
        </fieldset>

        <div style="float:left;width:110px;">
            <?php echo $this->Form->submit(__('Submit', true), array('div' => false));
            echo $this->Form->end();?>
        </div>

        <div style="float:left;margin-top:5px;height:40px;">
            <?php
                
                echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'sheets', 'action' => 'webform' ,$sheetId), array('class' => 'new_button', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;'));?>
        </div>
</div>

<?php echo $this->element('admin_left_menu'); ?>