<?php ?>
<style>
#holder{
   margin: 0 auto;
    width: 800px;
}

.wht_round_mid{
    border: 1px solid #333333;
    float: left;
    margin: 0 auto;
    width: 800px;
    padding: 15px;
}

.contact_right{
       background-color: #2F2F2F;
    border: 3px solid #C30F0F;
    border-radius: 10px 10px 10px 10px;
    color: #FFFFFF;
    float: right;
    padding: 10px;
    width: 270px;
}
.contact_right a{
    color: #FFFFFF;
    cursor: pointer;

}
#contactID{
    border-right: 1px solid #C30F0F;
    float: left;
    padding-right: 20px;
    width: 450px;

}
.lab110{
    color: #989997;
    display: inline-block;
    font-size: 14px;
    margin: 0 10px 25px 0;
    vertical-align: top;
    width: 110px;
}


.inp_200 {
    border: 1px solid #C1C1C1;
    color: #989997;
    display: inline-block;
    font-size: 14px;
    padding: 6px 2px;
    width: 250px;
}


.inp_cmnt {
    border: 1px solid #C1C1C1;
    color: #989997;
    display: inline-block;
    font-size: 14px;
    margin: 0;
    padding: 6px 2px;
    width: 250px;
}

.inp_tit {
    border: 1px solid #C1C1C1;
    color: #989997;
    display: inline-block;
    font-size: 14px;
    padding: 2px;
    width: 80px;
}


.contact_lft h3 {
    border-bottom: 1px dashed #C30F0F;
    color: #C30F0F;
    font-family: 'HelveticaNeueLTStd37ThCn';
    font-size: 30px;
    font-weight: normal;
    margin: 0 20px 20px 0;
    padding: 0 0 10px;
}
 .wrapper{
  width: 1002px;
}
</style>
<link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
<script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
<script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function(){ jQuery("#contactID").validationEngine(); });
//]]>
</script>
<?php echo $this->Session->flash(); ?>	
<div id="holder">
        <div class="wht_round_top"><img src="/img/wht_round_crnr_top_R.jpg" width="8" height="6" alt="" class="right"></div>
        <div class="wht_round_mid">
            <div class="contact_right"><?php echo $userData['Admin']['address'];?><br>
                <p class="mrgTB5"><span class="">Email:<?php echo $userData['Admin']['email'];?></span></p>
                <p class="mrgTB5"><span class="">Phone:<?php echo $userData['Admin']['phone'];?></span></p>
                <p class="mrgTB5"><span class=""><a href="http://<?php echo $userData['Admin']['website'];?>" target ="_blank" title='Website'><?php echo $userData['Admin']['website'];?></a></span></p>
            </div>

    <?php echo  $form->create('Contact', array('id' => 'contactID', 'class' => "formular", 'onsubmit' => 'return validate_form();'));?>
        <div class="contact_lft">
        <h3>Revenue Performance Ltd. UK</h3>
        
        <div class="holder">
            <label class="lab110">Title :</label>
                <?php echo $this->Form->input("Contact.title",array('class' => 'validate[required] inp_tit', 'label'=>false, 'div'=>false, 'options' => array('Mr' => 'Mr', 'Ms' => 'Ms')));?><br>
            <label class="lab110">Name :</label>
                <?php echo $this->Form->input("Contact.name", array('type'=>'text', 'div'=>false, 'id' => 'tx_fname', 'label'=>false, 'class' => 'validate[required] inp_200'));?><br>
           
    <label class="lab110">Email :</label>
        <?php echo $this->Form->input("Contact.email", array('id' => 'email', 'type'=>'text', 'div'=>false, 'label'=>false, 'class' => 'validate[required] text-input inp_200'));?>
    <br>
    

    <label class="lab110">Comments :</label>
    <?php echo $this->Form->input("Contact.comment", array('type'=>'textarea',  'div'=>false, 'label'=>false, 'id' => 'tx_commnets', 'class' => 'inp_cmnt'));?><br><br>
    <label class="lab110">&nbsp;</label><span class="get_std_btn"><input type="submit" value="Submit" name="action"></span>
    </div>
    </div>
<?php echo $form->end();?>
    <div class="spacer"></div>
    </div>
    <div class="wht_round_bot"><img src="/img/wht_round_crnr_bot_R.jpg" width="8" height="6" alt="" class="right"></div>
    </div>
    <div class="spacer"><img src="/img/spacer.gif" width="1" height="1" alt=""></div>
