<?php ?>
<script>
$(document).ready(function(){
    $("#ClientAdd").validationEngine();
    });
</script>
<?php echo $this->Session->flash(); ?>

<div class="control-group">
	<div class="page-header position-relative">
                <h1><?php __('Daily Flash For : '); ?><?php echo $hotelname; ?> <small><i class="icon-double-angle-right"></i></small></h1>
        </div>
    
            <a href="/staff/users/daily_flash/"><input type="button" class="btn btn-info" value="Input Today's Flash Data" /></a>
            <br/><br/>
            
            <div class="row-fluid">
                <h3 class="header smaller lighter green">Input Data for</h3>
            
            <?php echo $this->Form->create('DailyFlash', array('url'=>array('controller'=>'users', 'action'=>'staff_flash')));?>
            
            <?php echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$clientId));
            echo $this->Form->input('new_input',array('type'=>'hidden','value'=>'1'));
            ?>
            
            <select style="border:1px solid #ccc;" name="data[month]" id="month">
                <option value="0">Select Month</option>
                <option value="1">January</option>            
                 <option value="2">February</option>            
                <option value="3">March</option>
                <option value="4">April</option>            
                 <option value="5">May</option>            
                <option value="6">June</option>
                <option value="7">July</option>            
                 <option value="8">August</option>            
                <option value="9">September</option>
                <option value="10">October</option>            
                 <option value="11">November</option>            
                <option value="12">December</option>
           </select>

        <select style="border:1px solid #ccc;" name="data[year]" id="year">
            <option value="0">Select Year</option>
            <option value="<?php echo date('Y',strtotime('-1 Year')); ?>"><?php echo date('Y',strtotime('-1 Year')); ?></option>            
             <option value="<?php echo date('Y'); ?>" selected><?php echo date('Y'); ?></option>            
            <option value="<?php echo date('Y',strtotime('+1 Year')); ?>"><?php echo date('Y',strtotime('+1 Year')); ?></option>
       </select>
                
        <select style="border:1px solid #ccc;" name="data[flash_date]" id="flash_date" >
                <option value="0">Select Date</option>
                <?php for($day=1;$day <= 31; $day++){ ?>
                     <option value="<?php echo $day; ?>" <?php if(date('d',strtotime('-1 Day')) == $day){ echo 'selected'; } ?>><?php echo $day; ?></option>
                <?php } ?>
        </select>
                
            <br/><br/>
        <div style="width:210px;">
        <?php
            echo $this->Form->submit(__('Input Data', true), array('div' => false,'class'=>'btn btn-info'));
            echo $this->Form->end();
        ?>
        </div>    
            <br/>
    </div>
            
            <div class="row-fluid">
                <h3 class="header smaller lighter green">View Previous Report</h3>
                        
            <?php echo $this->Form->create('DailyFlash', array('url'=>array('controller'=>'users', 'action'=>'staff_flash')));?>
            
            <?php echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$clientId));
            echo $this->Form->input('new_input',array('type'=>'hidden','value'=>'0'));
            ?>
            
            <select style="border:1px solid #ccc;" name="data[month]" id="month">
                <option value="0">Select Month</option>
                <option value="1">January</option>            
                 <option value="2">February</option>            
                <option value="3">March</option>
                <option value="4">April</option>            
                 <option value="5">May</option>            
                <option value="6">June</option>
                <option value="7">July</option>            
                 <option value="8">August</option>            
                <option value="9">September</option>
                <option value="10">October</option>            
                 <option value="11">November</option>            
                <option value="12">December</option>
           </select>

        <select style="border:1px solid #ccc;" name="data[year]" id="year">
            <option value="0">Select Year</option>
            <option value="<?php echo date('Y',strtotime('-1 Year')); ?>"><?php echo date('Y',strtotime('-1 Year')); ?></option>            
             <option value="<?php echo date('Y'); ?>" selected><?php echo date('Y'); ?></option>            
            <option value="<?php echo date('Y',strtotime('+1 Year')); ?>"><?php echo date('Y',strtotime('+1 Year')); ?></option>
       </select>
                
        <select style="border:1px solid #ccc;" name="data[flash_date]" id="flash_date" >
                <option value="0">Select Date</option>
                <?php for($day=1;$day <= 31; $day++){ ?>
                     <option value="<?php echo $day; ?>" <?php if(date('d',strtotime('-1 Day')) == $day){ echo 'selected'; } ?>><?php echo $day; ?></option>
                <?php } ?>
        </select>
                
            <br/><br/>
        <div style="width:210px;">
        <?php 
            echo $this->Form->submit(__('View Report', true), array('div' => false,'class'=>'btn btn-info'));
            echo $this->Form->end();
        ?>
        </div>
            
        </div>   
            
            <br/>
            
            <div class="row-fluid">
                <h3 class="header smaller lighter green">Un-Verified Reports</h3>
                
        <table>
            <tr><th>Un-Verified Reports</th></tr>
            <?php if(!empty($unverifiedFlash)){ 
                foreach($unverifiedFlash as $flash_veri){?>
                <tr><td><a href="/staff/users/daily_flash/<?php echo $flash_veri['DailyFlash']['date']; ?>/<?php echo $flash_veri['DailyFlash']['id']; ?>"><?php echo $flash_veri['DailyFlash']['date']; ?></a></td></tr>
            <?php } }else{ ?>
                <tr><td> No Report Available </td></tr>
            <?php } ?>
            
        </table>
             </div>            

</div>
