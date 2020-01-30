<?php
?> 
<style>
.footrsocial_icons {
    float: left;
    height: 24px;
    padding: 0;
    width: 350px;
    clear:both;
    padding:10px 2px;
}
</style>
<div class=" clear"><img width="1" height="15" src="/img/spacer.gif" alt=""></div>
	<!--footer wrapper-->
    <div class="footer_bg">
    	<!--wrapper start-->
<footer class="footer">
    	
            <a href="http://www.revenue-performance.com" target="blank">
<!--                <img style="margin-top:5px;" class="left mrgT8" alt="" src="/img/RP-logo.png">-->
                <img style="margin-right:5px;height:80px;" class="left mrgT8" alt="Revenue Performance Logo" src="http://academy.revenue-performance.com/img/RP%20Square.jpg">
               
            </a>
            <div class="footrsocial_icons" style="clear:none;">
                    <a href="http://www.facebook.com/pages/Revenue-Performance-Ltd/475381792502114" target="blank">
                            <img src="<?php echo $this->webroot;?>img/social_icon.jpg" width="38" height="24" alt="">
                    </a>

                    <a href="https://twitter.com/Duncan_Bramwell" target="blank">
                            <img src="<?php echo $this->webroot;?>img/social_icon-02.jpg" width="38" height="24" alt="">
                    </a>
                    
                    <a target="_blank" href="http://uk.linkedin.com/in/duncanbramwell">
                            <img src="<?php echo $this->webroot;?>img/linkedin.png" width="38" height="24" alt="">
                    </a>

                    <a target="_blank" href="http://revenue-performance.blogspot.co.uk/">
                            <img src="<?php echo $this->webroot;?>img/blogger.png" width="38" height="24" alt="">
                    </a>
            </div>
            <p class="footer_link">&copy; My Dashboard, All Right Reserved. &nbsp;&nbsp;&nbsp;&nbsp;    <a href="/terms">Terms & Conditions</a> |    <a href="/policy">Privacy Policy</a> | <a href="/contacts">Contact Us</a> </p>
			


          
	</footer>
</div>
<?php echo $this->element('sql_dump'); ?>

<!-- Code For Google Analytics -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34393779-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- Code For Google Analytics -->