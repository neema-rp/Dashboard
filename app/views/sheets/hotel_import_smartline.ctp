<?php echo $this->Session->flash(); ?>
<div class="sheets index">
        <?php echo $this->Form->create('Sheet', array('url'=>array('controller'=>'sheets', 'action'=>'hotel_import_smartline/'.$clientId),'type' => 'file'));?>
        <fieldset>
            <legend><?php echo $hotelname; ?><?php __(' - Import Excel');?> </legend>
            <table cellpadding="0" cellspacing="0">
                    <tbody>  
                            <tr>
                                <td class="labeltd">Year </td>
                                <td><?php //echo date('Y'); ?>
                                <?php   $selectYear = date('Y');
                                 echo $this->Form->year('Sheet.year', 2015, 2025, $selectYear, array('empty' => 'Select Year', 'value' => $selectYear,'class'=>'validate[required]'));
                                  echo $this->Form->error('year'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="labeltd">Month : </td> 
                                <td><?php
                                        echo $this->Form->month('Sheet.month', null, array('empty' => 'Select Month', 'class'=>'validate[required]'));
                                        echo $this->Form->error('month');
                                ?>
                                </td>
                            </tr>
                            <tr> 
                                <td class="labeltd">Browse file : </td>
                                <td>
                                    <?php echo $this->Form->input('browse_file',array('label'=>false,'div'=>false,'error'=>false,'type'=>'file'));?>            
                                </td>
                            </tr>

                    </tbody>
            </table>
        </fieldset>
        <div style="float:left;width:110px;">
            <?php echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
            echo $this->Form->end();?>
        </div>
</div>
<style>
    td{ padding: 8px; }
    .labeltd{ font-weight: bold; }
    fieldset {
        width: 70%;
        border: 1px solid #ccc;
        padding: 24px;
    }
    legend{ width:auto; }
</style>
<script>
    $(document).ready(function(){
        $('.breadcrumbs').hide();
        $('.dropdown-toggle').hide();
    });
</script>