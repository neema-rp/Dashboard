<?php ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script>
$(document).ready(function(){
	$("#PromoAdminAddForm").validationEngine();
    });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Add Promotions Calendar <small><i class="icon-double-angle-right"></i> Add</small></h1>
        </div>
    
<?php echo $this->Form->create('Promo', array('url' => array('prefix' => 'admin', 'admin' => true, 'controller' => 'Promos', 'action' => 'settings')));?>

<?php
    echo $this->Form->input('id',array('type'=>'hidden')); 
    echo $this->Form->input('client_id',array('type'=>'hidden')); 
?>

	<div class="input text required">
		<label for="PromoYearYear">Year</label>
		<?php
			echo $this->Form->year('Promo', 2010, 2025, date('Y'), array('empty' => 'Select Year', 'class'=>'validate[required]'));
			echo $this->Form->error('year');
		?>
	</div>

        <div class="input text required">
            <label for="Categories">Categories</label>
            <?php            
            $exp_categories = explode('|',$this->data['Promo']['general_categories']);
            if(count($exp_categories) > 0){ 
              foreach($exp_categories as $key=>$categories){
                if($key!=0){ echo '<br/>'; }
                ?>
                <div id="email<?php echo $key; ?>">
                <?php							
                    echo $this->Form->input('Promo.general_categories'.$key,array('div'=>false,'label'=>false,'value'=>$categories,'style'=>'width:400px;','type'=>'text','name'=>"data[Promo][general_categories][$key]")); 
                
                if($key == (count($exp_categories) -1)){ ?>
                <a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_location(<?php echo $key; ?>)">Add More</a>
                <?php } else { ?>
                <a style="padding-bottom:5px;*line-height: 28px;display:none;" class="new_button" href="javascript:void(0);"  onClick="add_location(<?php echo $key; ?>)">Add More</a>
                <?php } 
                if($key != 0){?>
                    <a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="delete_location(<?php echo $key; ?>)">Delete</a>
                <?php } ?>
                <strong style="display:none;"><?php echo $key; ?></strong>
        </div>
        <?php } } else { ?>
                <div id="email0">	
                        <label for="SheetImportImport" style="float:left;color:#5F5F5F;"><b>Categories *: </b></label>&nbsp;&nbsp;&nbsp;
                        <?php echo $this->Form->input('Promo.general_categories.0',array('div'=>false,'label'=>false,'style'=>'width:400px;','type'=>'text','name'=>"data[Promo][general_categories][0]")); ?>
                        <a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_location(0)">Add More</a>
                        <strong style="display:none;">0</strong>
                </div>
        <?php } ?>
        </div>
         
              
       <div class="input text required">
            <label for="OTACategories">OTA Categories</label>
            <?php            
            $ota_categories = explode('|',$this->data['Promo']['ota_categories']);
            if(count($ota_categories) > 0){ 
              foreach($ota_categories as $key=>$categories){
                if($key!=0){ echo '<br/>'; }
                ?>
                <div id="otacat<?php echo $key; ?>">
                <?php echo $this->Form->input('Promo.ota_categories'.$key,array('div'=>false,'label'=>false,'value'=>$categories,'name'=>"data[Promo][ota_categories][$key]",'style'=>'width:400px;','type'=>'text')); 
                
                if($key == (count($ota_categories) -1)){ ?>
                <a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_category(<?php echo $key; ?>)">Add More</a>
                <?php } else { ?>
                <a style="padding-bottom:5px;*line-height: 28px;display:none;" class="new_button" href="javascript:void(0);"  onClick="add_category(<?php echo $key; ?>)">Add More</a>
                <?php } 
                if($key != 0){?>
                    <a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="delete_category(<?php echo $key; ?>)">Delete</a>
                <?php } ?>
                <row style="display:none;"><?php echo $key; ?></row>
        </div>
        <?php } } else { ?>
                <div id="otacat0">	
                        <label for="SheetImportImport" style="float:left;color:#5F5F5F;"><b>OTA Categories *: </b></label>&nbsp;&nbsp;&nbsp;
                        <?php echo $this->Form->input('Promo.ota_categories.0',array('div'=>false,'label'=>false,'style'=>'width:400px;','type'=>'text','name'=>"data[Promo][ota_categories][0]")); ?>
                        <a style="padding-bottom:5px;*line-height: 28px;" class="new_button" href="javascript:void(0);"  onClick="add_category(0)">Add More</a>
                        <row style="display:none;">0</row>
                </div>
        <?php } ?>
        </div>
                
        <div class="input text required">
		<label for="Promooffers_list">Offers Per Day</label>
		<select style="border:1px solid #ccc;" name="data[Promo][offers_list]" id="offers_list" >
                        <option value="0">Select</option>
                        <?php for($day=1;$day <= 10; $day++){ ?>
                             <option value="<?php echo $day; ?>" <?php if($this->data['Promo']['offers_list'] == $day){ echo 'selected'; } ?> ><?php echo $day; ?></option>
                        <?php } ?>
                </select>
	</div>
                
        <?php
        echo $this->Form->submit(__('Save', true), array('div' => false,'class'=>'btn btn-info'));
        echo "&nbsp;&nbsp;";
        echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'Promos', 'action' => 'index',$this->data['Promo']['client_id']), array('class' => 'btn btn-success', 'escape' => false));
        echo $this->Form->end();
        ?>
                
</div>

<script>
function add_location(add_id)
{
        var num = parseInt($("strong:last").text());
        if($('#email' + num+'>input').val() == ''){
		alert('Please enter value');
        } else {
            var newNum = new Number(num + 1);
            var newElem = $('#email' + 0).clone().attr('id', 'email' + newNum);
            $('#email' + num).after(newElem);
            $('#email' + newNum+'>label').html('').width('132px');
            $('#email' + newNum+'>label').before('<br/>');
            $('#email' + newNum+'>input').attr('name', 'data[Promo][general_categories]['+newNum+']').attr('value', '').attr('id', 'EmailSummarySheet'+newNum+'email');
            $('#email' + num+'>a:eq(0)').hide();                              
            $('#email' + newNum+'>a').attr('onclick','add_location('+newNum+')').show();
            $('#email' + newNum+'>strong').remove();
            $('#email' + newNum+'>a').after('<strong style="display:none;">'+newNum+'</strong>');
	    $('#email' + newNum+'>a').after('<a onclick="delete_location('+newNum+');" href="javascript:void(0);" class="new_button">Delete</a>');
         }
}
function delete_location(remove_id){
         $('.EmailSummarySheet'+remove_id+'emailformError').remove();
        $("#email"+remove_id).remove();
        var num = parseInt($("strong:last").text());                     
        $('#email' + num+'>a:eq(0)').show();
}

function add_category(add_id)
{
        var num = parseInt($("row:last").text());
        if($('#otacat' + num+'>input').val() == ''){
		alert('Please enter value');
        } else {
            var newNum = new Number(num + 1);
            var newElem = $('#otacat' + 0).clone().attr('id', 'otacat' + newNum);
            $('#otacat' + num).after(newElem);
            $('#otacat' + newNum+'>label').html('').width('132px');
            $('#otacat' + newNum+'>label').before('<br/>');
            $('#otacat' + newNum+'>input').attr('name', 'data[Promo][ota_categories]['+newNum+']').attr('value', '').attr('id', 'EmailSummarySheet'+newNum+'otacat');
            $('#otacat' + num+'>a:eq(0)').hide();                              
            $('#otacat' + newNum+'>a').attr('onclick','add_category('+newNum+')').show();
            $('#otacat' + newNum+'>row').remove();
            $('#otacat' + newNum+'>a').after('<row style="display:none;">'+newNum+'</row>');
	    $('#otacat' + newNum+'>a').after('<a onclick="delete_category('+newNum+');" href="javascript:void(0);" class="new_button">Delete</a>');
         }
}
function delete_category(remove_id){
         $('.EmailSummarySheet'+remove_id+'emailformError').remove();
        $("#otacat"+remove_id).remove();
        var num = parseInt($("row:last").text());                     
        $('#otacat' + num+'>a:eq(0)').show();
}
</script>