<?php ?>
<style>
#holder{
   margin: 0 auto;
    width: 800px;
}

.wht_round_mid{
    /*border: 1px solid #333333;*/
    border: 10px solid #FFFFFF;
    float: left;
    margin: 0 auto;
    width: 800px;
    padding: 15px;
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

<div id="holder">
        <div class="wht_round_top"><img src="/img/wht_round_crnr_top_R.jpg" width="8" height="6" alt="" class="right"></div>
        <div class="wht_round_mid">
            <div class="contact_right">
           
	<div class="contact_lft">
	<h3><?php echo $contents['Content']['title'];?></h3>
        
		<div class="holder">
			<?php echo $contents['Content']['contents'];?>
		</div>
	</div>

    <div class="spacer"></div>
    </div>
    <div class="wht_round_bot"><img src="/img/wht_round_crnr_bot_R.jpg" width="8" height="6" alt="" class="right"></div>
    </div>
    <div class="spacer"><img src="/img/spacer.gif" width="1" height="1" alt=""></div>
