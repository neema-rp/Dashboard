<?php
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />

<style type="text/css">
.AdvancedSheets li{
list-style-type: none;
}
.AdvancedSheets ul {
	padding:0px;
	margin: 0px;
}

.AdvancedSheets li {
	margin: 0 auto 3px;
	padding:8px;
	background-color:#333;
	color:#fff;
	list-style: none;
	width: 700px;
}
</style>

<script>
$(document).ready(function(){
    $("#TemplateAdminEditForm").validationEngine();
    
           $(function() {
                $(".AdvancedSheets ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {

                }
            });
	});
    
    });
</script>


<div class="control-group">
	<div class="page-header position-relative">
                <h1>Admin <small><i class="icon-double-angle-right"></i>Market Segments</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Market Segments</h4>
                </div>
                <div class="widget-body">
                     <div class="widget-main">
                        <div class="row-fluid AdvancedSheets">

                <?php echo $this->Form->create('AdvancedSheet', array('class'=>'form-horizontal'));?>
                
               <?php echo $this->Form->input('name',array('class'=>'validate[required] span6','value'=>$data['AdvancedSheet']['name'])); ?>
                <?php 
                echo $this->Form->input('id',array('type'=>'hidden','value'=>$data['AdvancedSheet']['id'])); 
                echo $this->Form->input('previous_segments',array('type'=>'hidden','value'=>$data['AdvancedSheet']['market_segments'])); 
                ?>

            <label for="TemplateMonthMonth" style="margin-top:40px">Select Market Segment for Sheet</label>
	<table class="table table-striped table-bordered table-hover">
		<tr><th>Market Segment</th></tr>
                <tr>
                    <td class="AdvancedSheets index">
                           <ul>
	<?php
		$indx = 0;
                
$new_columns = array();
$rest_columns = array();
  foreach($sheet_segments as $scols){
    foreach($marketsegments as $key=>$value){
	if($scols == $key){
	    $new_columns[$key] = $value;
	    unset($marketsegments[$key]);
	}
    }
  }
$rest_columns = $marketsegments;
foreach($rest_columns as $key=>$value){
  $new_columns[$key] = $value;
}
unset($marketsegments);
$marketsegments = $new_columns;

                
		foreach($marketsegments as $key => $value){ ?>
		<li id="arrayorder_<?=$key; ?>#0">
                    <label>
                         <?php
                            $rowid = "MarketSegment".$key;
                            if(in_array($key, $sheet_segments)){
                                    echo $this->Form->checkbox($rowid, array('value' => $key, 'checked' => true, 'name'=>'data[MarketSegment][MarketSegment][]'));
                            }else{
                                    echo $this->Form->checkbox($rowid, array('value' => $key, 'name'=>'data[MarketSegment][MarketSegment][]' ,'class'=>'validate[required]'));
                            }  ?>
                        <span class="lbl"> <?php echo $value;  ?> </span>
                           
                        </label>
                   </li>
	<?php $indx += 1; } ?>
                               </ul>
                         </td>
		</tr>
	</table>
            
            <?php
            echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
            echo "&nbsp;&nbsp;";
            echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'advancedSheets', 'action' => 'index',$data['AdvancedSheet']['user_id'],$data['AdvancedSheet']['department_id']), array('class' => 'btn btn-success', 'escape' => false));
            echo $this->Form->end();
            ?>	

</div></div></div></div></div>