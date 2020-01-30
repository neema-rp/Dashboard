<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Faircity Hotel</title>
<link href="/microsite/css/styles.css" rel="stylesheet" type="text/css">
<link href="/microsite/css/slider.css" rel="stylesheet" type="text/css">

<style>

a.redbtn {
    background: none repeat scroll 0 0 #C30F0F;
    border-radius: 5px 5px 5px 5px;
    color: #FFFFFF;
    display: inline-block;
    font-weight: bold;
    padding: 6px 10px;
    text-decoration: none;
}
.inp_txt {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #cccccc;
    border-radius: 5px 5px 5px 5px;
    display: inline-block;
    padding: 5px;
}
input, textarea {
    background-color: #FFFFFF;
    clear: both;
    font-family: "frutiger linotype","lucida grande","verdana",sans-serif;
    padding: 1%;
    width: 80%;
}

/*.booknow{
margin-top:8px;
}*/
.logo{ padding:74px 0 10px; }

#ui-datepicker-div{ font-size:12px; }

#booking-enquiry label{
    font-weight: bold;
}
</style>
</head>

<body>
<div class="main">
<div class="wrapper">
	<header>

<!--<div id="book_now_section" style="font-size: 12px; float: left; padding-top: 1px;">-->
<div id="book_now_section" style="font-size: 12px; float: left; padding: 16px; margin-top: 27px;" class="container iebg">

	    <form id="booking-enquiry" action="">
          <div style="float:left;padding-right:20px;">
            <label for="arrival">Arrival</label>
            <input type="text" class="validate[required] text-input inp_txt wd129" id="arrivalDate" name="arrivalDate">
          </div>
          <div style="float:left;padding-right:20px">
            <label for="departure">Departure</label>
            <input type="text" class="validate[required] text-input inp_txt wd129" id="departure" name="departure">
          </div>
          <div class="short" style="float:left;padding-right:20px">
            <label for="rooms">Rooms</label>
            <div >
              <select id="rooms" name="rooms" class="text-input inp_txt wd129">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </div>
          </div>
          <div class="short" style="float:left;padding-right:20px">
            <label for="adults">Adults</label>
            <div>
              <select id="adults" name="adults"  class="text-input inp_txt wd129">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </div>
          </div>
          <div class="short" style="float:left;padding-right:20px">
            <label for="children">Children</label>
            <div>
              <select id="children" name="children"  class="text-input inp_txt wd129">
				<option value="0">0</option>              
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </div>
          </div>
           <!--<div style="padding-left: 40px; float: left; padding-top: 16px;"><a class="redbtn" onClick="gotoBooking();" id="reservation" href="javascript:void(0);">Check Availability</a></div>-->
       </form>

	</div>

    <div class="right booknow">

<a href="javascript:void(0);" onClick="gotoBooking();">
<!--<a id="book_now" onClick="toggle_book();" href="javascript:void(0)">-->
<img src="/microsite/images/booknow.png" width="214" height="60" alt="">
</a>
</div>
    <div class="logo"><img src="/microsite/images/logo.png" width="430" height="103" alt="">
    </div>
    </header>
    <div class="container iebg" >
    	<div class="heading">A Welcome from Faircity Hotels to Hotel Express</div>
        <div>
        	<div class="leftcol left">

<p><b>Hotel Accommodation and Conferencing Venues</b></p>
<p>The Faircity Group is made up of three and four star conference hotels and serviced apartments in Johannesburg and Pretoria. The hotels are located in key areas, so as to benefit the modern business and leisure traveller.</p>
<br/><p><b>Our Unique Selling Point</b></p>
<p>Every property offers a unique quality that cannot be found at any other hotel. The combination of the city buzz, country relaxation and flexible solutions, gives Faircity Hotels a versatile presence within the industry.</p>
<br/><p><b>Fast Growing</b></p>
<p>Superior amenities and personal service set the Faircity Hotels apart from any other hotel chain. This young group is fast gaining ground. By partnering with reputable local property developers, Faircity Hotels has rapidly built up an enviable portfolio.</p>

		</div>
            <div class="imgcol right"><img src="/microsite/images/Falstaff.jpg" width="306" height="213" alt=""></div>
            <div class="clear dottedline "></div>
        
        </div>
          
      <div>
       	<div class="leftcol right">
<p><b>The Team</b></p>
<p>The hand-picked and highly qualified hotel management and operations team ensures that an optimum accommodation offering is delivered to guests and that service level standards are constantly maintained.</p>

<br/>

<p><b>Faircity’s Brand Promise to You</b></p>

<p>All Faircity staff within the group, strive to provide a comfortable and relaxing experience and professional service to all clients. This experience reflects our Green Business and the speed and efficiency that exists throughout our brand.</p>

<p>No matter our clients’ requests, we ensure that we deliver on time and to complete satisfaction. Our friendly service will meet you at every turn regardless the reason for your visit. Our accommodation, conferencing and restaurant facilities continue providing professional and efficient solutions at cost effective prices.</p>

</div>
            <div class="imgcol left"><img src="/microsite/images/Quartermain.jpg" width="306" height="212" alt=""></div>
            <div class="clear dottedline "></div>
        
      </div>
                <div class="slidr">
                <div class="sliderBx">
            <div class="bxslider">
            <div><img src="/microsite/images/Falstaff.jpg" alt="" width="173" height="116"></div>
            <div><img src="/microsite/images/Grosvenor Gardens.jpg" alt="" width="173" height="116"></div>
            <div><img src="/microsite/images/Mapungubwepage-banner-image.jpg" alt="" width="173" height="116"></div>
            <div><img src="/microsite/images/Quartermain.jpg" alt="" width="173" height="116"></div>
            <div><img src="/microsite/images/Roodevallei.jpg" alt="" width="173" height="116"></div>
            </div>
            </div>
        </div>
                    <div>&nbsp;</div>
                        <div>&nbsp;</div>
    
    </div>
    <footer class="footer">
    <div class="right footerlinks">Copyright2013 <a href="#">Terms &amp; Conditions</a> |   <a href="#">Privacy Policy</a></div>
    <div>
	<a href="http://www.revenue-performance.com/" target="blank">
	<img src="/microsite/images/botlogo.png" width="381" height="45" alt="">
	</a><br>
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
    </footer>

</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.js" type="text/javascript"></script>
 <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
     
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script src="/microsite/script/jquery.bxslider.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('.bxslider').bxSlider({
		 auto: true,
		 pager:false,
  minSlides: 4,
  maxSlides: 4,
  slideWidth: 200,
  slideMargin: 10
});

 $("#arrivalDate, #departure").datepicker();

});   
</script>

<script>
var i=0;
function toggle_book(){
$('#book_now_section').toggle("slow");

if(i==0)
{
// $('#book_now').prop('text','Close');
// $('#book_now').text('Close');
// $('.booknow').css( "margin-top", "12px" );
i=1;
}
else{
// $('#book_now').prop('text','Book Now');
// $('#book_now').text('Book Now');
// $('.booknow').css( "margin-top", "54px" );
i=0;
}

}

function gotoBooking(){

var arrivalDate = $('#arrivalDate').val();

arrivalDate = arrivalDate.replace("/", "%2F");
arrivalDate = arrivalDate.replace("/", "%2F");

var url = 'https://www.yourreservation.net/tb3/index.cfm?bf=FaircityHotels&iataNumber=Hotel%20Express&rateAccessCode=*NXBW%24&plprun=1&_=1371566643316&arrivalDate='+arrivalDate;
 window.open(url, '_blank');

}

</script>


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
<!-- Code For Google Analytics --><!--footer start-->

</body>
</html>
