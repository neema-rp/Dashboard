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

<style type="text/css">
input { font-size:100%; border:1px solid #ccc; }
</style>

<div class="Gps form">
    
<?php echo $this->Form->create('GpsPack', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'segments')));?>
    
    <?php
    echo $this->Form->input('client_id',array('type'=>'hidden','value'=>$GpsPack['GpsPack']['client_id']));
    echo $this->Form->input('id',array('type'=>'hidden','value'=>$GpsPack['GpsPack']['id']));
    
    //echo '<pre>'; print_r($GpsPack); print_r($gps_settings); echo '</pre>';
    
    ?>
    
	<fieldset>
 		<legend><?php __('GPS - Market Segments'); ?></legend>
                <div class="input">
                        <?php
                        echo $this->Form->input('MarketSegment', array('multiple' => "multiple",'div' => false, 'label' => 'Select MarketSegment','class'=>'span6 validate[required]','id'=>'ajaxSegments','options'=>$marketsegments,'value'=>$market_seg_ids));
                         //echo $this->Form->input('MarketSegment', array('multiple' => "multiple",'div' => false, 'label' => 'Select MarketSegment','class'=>'span6 validate[required]','id'=>'ajaxSegments','options'=>$marketsegments,'value'=>$market_seg_ids));
                        ?>
                    
                </div>
        </fieldset>
<div>
    <br/><br/>
<?php
echo $this->Form->submit(__('Submit', true), array('div' => false,'class'=>'btn btn-info'));
echo '&nbsp;&nbsp;&nbsp;&nbsp;';
echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'index',$GpsPack['GpsPack']['client_id']), array('class' => 'btn btn-danger', 'escape' => false));
echo $this->Form->end(); ?>
</div>
</div>