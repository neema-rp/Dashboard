<?php ?>
<?php 
$gps_month = $GpsPack['GpsPack']['month'];
$gps_month = sprintf("%02d", $gps_month);
$year = $GpsPack['GpsPack']['year'];
?>

<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __('GPS '); ?> <small><i class="icon-double-angle-right"></i>Edit GPS Steps</small></h1>
        </div>
                <?php
                if(!empty($gps_id)){ ?>
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        
                        <?php 
                        $gpsPackSteps['1'] = 'GM Summary';
                        $gpsPackSteps['2'] = 'Summary';
                        $gpsPackSteps['3'] = 'Market';
                        $gpsPackSteps['4'] = 'Competitor Activity';
                        $gpsPackSteps['5'] = 'Activity '.date("F", mktime(0, 0, 0, $gps_month, 1));
                        $gpsPackSteps['6'] = 'Activity '.date("F", mktime(0, 0, 0, ($gps_month+1), 1));
                        $gpsPackSteps['7'] = 'Activity '.date("F", mktime(0, 0, 0, ($gps_month+2), 1));
                        $gpsPackSteps['8'] = 'Activity '.date("F", mktime(0, 0, 0, ($gps_month+3), 1));
                        $gpsPackSteps['9'] = 'Top Producers';
                        $gpsPackSteps['14'] = 'Channels';
                        $gpsPackSteps['15'] = 'Channels Year';
                        $gpsPackSteps['16'] = 'Geo Year';
                        $gpsPackSteps['17'] = 'Prov Year';
                        $gpsPackSteps['18'] = 'RoomTypes';
                        $gpsPackSteps['20'] = 'Future Activity';
                        $gpsPackSteps['21'] = 'Reputation';
                        $gpsPackSteps['22'] = 'Config';
                        
                        $gps_steps_ids = array();
                        if(!empty($gps_settings['GpsSetting']['access_steps'])){
                            if($gps_settings['GpsSetting']['access_steps'] != 'ALL'){
                                $gps_steps_ids = explode(',',$gps_settings['GpsSetting']['access_steps']);
                             }
                        }
                        
                        foreach($gpsPackSteps as $step_id=>$gps_step){ 
                            $style='';
                            
                            if($gps_settings['GpsSetting']['access_steps'] != 'ALL'){
                                if($step_id == '5' || $step_id == '6' || $step_id == '7' || $step_id == '8'){ 
                                    if(!in_array('5-8',$gps_steps_ids)){
                                        $style='display:none;';
                                    }
                                }elseif($step_id == '14' || $step_id == '15'){ 
                                    if(!in_array('14-15',$gps_steps_ids)){
                                        $style='display:none;';
                                    }
                                }elseif($step_id == '18' || $step_id == '19'){ 
                                    if(!in_array('18-19',$gps_steps_ids)){
                                        $style='display:none;';
                                    }
                                }else{
                                    if(!in_array($step_id,$gps_steps_ids)){
                                        $style='display:none;';
                                    }
                                }
                            }
                            ?>
                            <tr  style="<?php echo $style; ?>">
                                <td><?php echo $gps_step; ?></td>
                                <td class="sheetIndex buttonscol">
                                        <?php echo $this->Html->link('Edit', array('prefix' => 'client', 'client' => true, 'controller' => 'GpsPacks', 'action' => 'edit',$step_id,$GpsPack['GpsPack']['id']), array('class' => '', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;')); ?>
                                </td>
                            </tr>
                       <?php $i++; } ?>
                    </tbody>
        	</table>
               <?php } ?>
</div>