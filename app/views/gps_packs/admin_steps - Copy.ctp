<?php ?>
<style type="css">
    table tr td{ text-align:left; }
</style>
<script>
$(function() {
   $('#hotel_id').change(function() {
       var val = $(this).val();
       var parent_hotel_id = $('#parent_hotel_id').val();
       window.location = '/admin/GpsPacks/steps/' + parent_hotel_id+'/'+val;
    });
});

function updatemarketplacerule(){
    var con = confirm("Do you want to Update!");
    if (con == true) {
        $('.buttons, .popup_box').hide();
        $('.saveloading').show();
        var mp_type = $('#mp_type').val();
        var mp_namespace = $('#mp_namespace').val();
        var mp_namespacevalue = $('#mp_namespace_value').val();
        var edit_rule = $('.edit_rule').val();
        var rules = [];
        $('.popup_field').each(function(){
            rules_sub = new Array;
            rules_sub['field_name']=$(this).find(".rules_field").val();
            rules_sub['field_value'] =$(this).find(".rule_data").val();
            rules_sub['sub_rule_id'] =$(this).find(".sub_rule_id").val();
            rules.push($.extend({},rules_sub));
        });

        rulesobj = $.extend({},rules);
        var request = $.ajax({
                    url: "UniTechCity.php",
                    type: "POST",
                    data: { dispatch : 'market_place.updatemarketplacerules', mp_namespace:mp_namespace, mp_type:mp_type,mp_namespacevalue:mp_namespacevalue,rule_array:rulesobj,edit_rule:edit_rule},
                    dataType: "text"
                  });
        request.done(function(response){
            if(response){
                //namespacevalue_rules.splice(mp_namespacevalue,1);
                delete namespacevalue_rules[mp_namespacevalue];
                 if($('#market_'+mp_namespacevalue+'').length>0)
                    $('#market_'+mp_namespacevalue+'').remove();
                getmarketplacerules();
                $('.buttons, .popup_box').show();
                $('.saveloading').hide();
                $('.white_content').hide();
                $('.black_overlay').hide();
            }
            else{
                $('.buttons, .popup_box').show();
                $('.saveloading').hide();
                alert("Please try again");
            }
        });
    }
}

</script>

<?php 


if($mode == 'updatemarketplacerules'){
        $market_place_rules_update = new marketPlaceRules($_POST['mp_type']);
        $market_place_rules_update->setNamespace($_POST['mp_namespace'],$_POST['mp_namespacevalue']);
        $market_place_rules_update->getMarketPlaceRules();
        $market_place_addnewrule = new marketPlaceRule();
        $rule_id = $_POST['edit_rule'];
        $flag=1;
        foreach ($_POST['rule_array'] as $key => $value){
            if(isset($value['sub_rule_id'])){
                 $output = $market_place_rules_update->editRule($rule_id,$value['sub_rule_id'],array('field_name'=>$value["field_name"],'field_value'=>$value["field_value"]));
            }
            else{
                $value['rule_id']=$rule_id;
                $output = $market_place_addnewrule->addMarketPlaceRule($value);
            }
        }
        echo $output==true ? true : false;
        exit;
    }
    
    
    if($_REQUEST['market_id'] && $mode == 'addmarket'){
        
        $market_id = $_REQUEST['market_id'];
        $market_desc = addslashes(urldecode($_REQUEST['market_desc']));
        $market_pin = trim($_REQUEST['market_pin']);
        
        // for checking if the pincode is 6 value long and is comma seperated
        if(!preg_match("/^[0-9]{6}(,[0-9]{6})*$/", $market_pin)){
           fn_set_notification("E","", "The value should contain 6 digit number with comma seperated value");
           return array(CONTROLLER_STATUS_OK,"nrh.addmarket&market_id=".$_REQUEST['market_id']);
        }
        
        $market_slug = $_REQUEST['market_slug'];
        $market_name = $_REQUEST['market_name'];
        $status = $_REQUEST['status'];
        $userid = $_SESSION['auth']['user_id']; 
        $email = db_get_field("select email from ?:users where user_id = ?i",$userid);  
        $sql = "UPDATE clues_markets "
                . "SET description = '".$market_desc."',"
                . "pincode = '".$market_pin."' , "
                . "seo_name = '".$market_name."' ,"
                . "status = '".$status."' ,"
                . "updated_by = '".$email."' ,"
                . "updated_date = now() WHERE market_id = '".$market_id."'";
        
        $response = db_query($sql);
        
        // adding the namespace and the marketrules in the market rules tables
        $market_place_rules->setNamespace(marketPlaceRule::MARKETPLACE_NRH_MARKET_NAMESPACE,$market_id);
        //$market_place_rules->addMarketPlaceRule(array(array('field_name'=>'Pincode','field_value'=>$market_pin)));
        
        $rules = $market_place_rules->getMarketPlaceRules();
        //print_r($rules);exit;
        
        Array ( [98] => Array ( [123] => Array ( [Pincode] => 110081 ) ) )
        
        foreach ($rules as $rule_key => $rule_value){
            if($market_id == $rule_id){
                $rule_id = $rule_key;
                foreach($rule_value as $sub_key=>$sub_value){
                    $sub_rule_id = $sub_key;
                    $output = $market_place_rules->editRule($rule_id,$sub_rule_id,array('field_name'=>'Pincode','field_value'=>$market_pin));
                    break;
                }
            }
        }
        
        foreach ($rules as $rule_key => $rule_value){
            $rule_id = $rule_key;
            foreach($rule_value as $sub_key=>$sub_value){
                if( !empty($sub_value[marketPlaceRule::RULE_PINCODE_FIELD]) 
                        && count($sub_value) === 1  ){
                    $sub_rule_id = $sub_key;
                    $output = $market_place_rules->editRule($rule_id,$sub_rule_id,
                                                    array(
                                                        'field_name'=>'Pincode',
                                                        'field_value'=>$market_pin
                                                    )
                                                );
                }
                break;
            }
            break;
        }
        
        if($response){
            fn_set_notification('N', fn_get_lang_var('market update successfully'));
            return array(CONTROLLER_STATUS_REDIRECT, "nrh.managemarket");
        }
    }


$output = $market_place_rules_update->editRule($rule_id,$value['sub_rule_id'],array('field_name'=>$value["field_name"],'field_value'=>$value["field_value"]));


$output = $market_place_rules_update->editRule($rule_id,$value['sub_rule_id'],array('field_name'=>'market_pin','field_value'=>$_POST['market_pin']));


Array ( [market_name] => test 12345678 [market_desc] => tere [market_pin] => 654321 [market_slug] => [status] => 1 [save] => Save )

    public function editRule( $rule_id , $rule_detail_id ,$rule ){
        
        if (marketPlaceRule::isValidInput($rule_id)
                || marketPlaceRule::isValidInput($rule_detail_id) 
                || empty($this->_marketplaceType) 
                || empty($this->_namespace) 
                || empty($this->_namespaceValue)
                || empty($this->_rules_object[$rule_id])
                || !is_object($this->_rules_object[$rule_id][$rule_detail_id])
                ) {
            return false;
        }        
        
        return $this->_rules_object[$rule_id][$rule_detail_id]->editMarketPlaceRule( $rule ); 
        
    }

    public function editMarketPlaceRule ( $rule ){
        
        if( empty( $this->_ruleDetailId )
                || empty( $this->_ruleId ) 
                || !$this->isValidField( $rule )
                ) {
            return false;
        }
        
        $this->_fieldName   = $rule['field_name'] ;
        $this->_fieldValue  = $rule['field_value'] ;
        $date = date(self::DATE_FORMAT);
        $update_query = "UPDATE 
                            clues_marketplace_rule_details 
                         SET 
                            field_name = ?s,
                            field_value = ?s,
                            updated_on = ?s
                         WHERE 
                            rule_detail_id = ?i
                         LIMIT 1 ";
        
        $sanitize_query = db_quote($update_query , $this->_fieldName, $this->_fieldValue ,$date, $this->_ruleDetailId  );
        
        $update = $this->dbQueryUpdate(
                    $sanitize_query , 
                    array(
                        'rule_id'        => $this->_ruleId , 
                        'rule_detail_id' => $this->_ruleDetailId ,
                        'marketplace_type'=> marketPlaceConstants::MARKET_PLACE_NRH
                        )
                    );
        
         self::log( 
                "marketRule edit query ( $sanitize_query )  Rule id $this->_ruleId and rule_detail_id : $this->_ruleDetailId : Update status : $update ", 
                Registry::get('config.LOG_LEVELS.DEBUG') ,self::LOGGER_MODULE_MARKETPLACE );
         
         
        return true;
    }
    
?>

<div class="Gps form">
    	<fieldset>
 		<legend><?php __('Assign Users to Each Steps'); ?></legend>
                
                <table width="100%" cellspacing="0" cellpadding="0" border="0" id="searchBox">
                    <tbody>
                            <tr>
                                    <td class="sheetIndex buttonscol">
                                            <?php echo $this->Html->link("GPS Pack", array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'index', $client_id), array('escape' => false, 'style' => 'text-decoration:none;width:200px;','class'=>'addbutton')); ?>
                                    </td>
                            </tr>
                    </tbody>
        	</table>
                
                <input type="hidden" id="parent_hotel_id" value="<?php echo $client_id; ?>"/>
                <?php
                if(!empty($selected_child_hotel)){
                    $client_id = $selected_child_hotel;
                }
                if(!empty($child_data)){
                    echo $this->Form->input('hotel_id',array('type'=>'select','id'=>'hotel_id','empty'=>'Select Hotel','options'=>$child_data,'label'=>'Select Hotel','value'=>$client_id)); ?>
                <?php } ?>
                
                
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <th>Steps</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        if(!empty($users)){
                        foreach($users as $user){
                        ?>
                            <tr>
                                <td><?php echo $user['User']['firstname'].'&nbsp;'.$user['User']['lastname']; ?></td>
                                <td class="sheetIndex buttonscol">
                                    <?php echo $this->Html->link('Assign User', array('prefix' => 'admin', 'admin' => true, 'controller' => 'GpsPacks', 'action' => 'assign',$user['User']['id'],$client_id), array('class' => '', 'escape' => false,'style'=>'padding-bottom:5px;*line-height: 28px;')); ?>
                                </td>
                            </tr>
                       <?php } 
                       } ?>
                    </tbody>
        	</table>
              
	</fieldset>
</div>

<?php echo $this->element('admin_left_menu'); ?>