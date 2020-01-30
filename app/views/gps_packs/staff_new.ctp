<?php ?>
<script src="/js/chosen.jquery.min.js"></script>

<link rel="stylesheet" href="/css/chosen.css" />
<script type="text/javascript" src="/js/ajax-chosen.js"></script>

<script>
$(document).ready(function(){
        $("#ajaxSegments").ajaxChosen({
                    type: 'GET',
                    url: '/GpsPacks/get_market_segments',
                    dataType: 'json'
            }, function (data) {
                    var terms = {};
                    $.each(data.segments, function (i, val) {
                            terms[i] = val;
                    });
                    return terms;
            });
        
});
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotel <small><i class="icon-double-angle-right"></i> Add GPS Pack</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter">Add New GPS Pack</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">
<?php echo $this->Form->create('GpsPack', array('url' => array('prefix' => 'staff', 'staff' => true, 'controller' => 'GpsPacks', 'action' => 'new')));?>

<?php
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$client_id));
    echo $this->Form->input('id',array('type'=>'hidden')); 
?>

	<fieldset>
 		<legend><?php __('Add GPS Pack'); ?></legend>

	<div class="input text required">
		<label for="GpsPackMonthMonth">Month</label>
		<?php
			echo $this->Form->month('GpsPack', null, array('empty' => 'Select Month', 'class'=>'validate[required]'));
			echo $this->Form->error('month');
		?>
	</div>
	<div class="input text required">
		<label for="GpsPackYearYear">Year</label>
		<?php
			echo $this->Form->year('GpsPack', 2010, 2025, date('Y'), array('empty' => 'Select Year', 'class'=>'validate[required]'));
			echo $this->Form->error('year');
		?>
	</div>
                
        <div class="input">
                <?php
                $market_seg_ids = array();
                if(!empty($gps_settings['GpsSetting']['market_segments'])){
                    $market_seg_ids = explode(',',$gps_settings['GpsSetting']['market_segments']);
                }
                 echo $this->Form->input('MarketSegment', array('multiple' => "multiple", 'div' => false, 'label' => '<b>Select MarketSegment</b> (Type to find and add the Market Segments)','class'=>'span6 validate[required]','id'=>'ajaxSegments','options'=>$marketsegments,'value'=>$market_seg_ids));
                ?>
        </div>

	<?php
        echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
        echo "&nbsp;&nbsp;";
        echo $this->Html->link('Cancel', array('prefix' => 'staff', 'staff' => true, 'controller' => 'GpsPacks', 'action' => 'index',$client_id), array('class' => 'btn btn-success', 'escape' => false));
        echo $this->Form->end();
        ?>
        </div></div></div></div>
</div>