<?php ?><!DOCTYPE HTML>

<html>
	<head>
		<title>MyDashBoard</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Revenue-performance is  a advisory firm focusing on Hotel Revenue Management. Discover Revenue Management of hotels and guest houses." />
		<meta name="keywords" content="Revenue Management, Hotel Revenue Management, Strategic Advisory Services, Strategy Consulting Firm, Hotel Business Improvement, Hotel Rate Strategy, Duncan Bramwell, GDS, Hotel Reservations Training, PMS" />
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic" rel="stylesheet" type="text/css" />
		<!--[if lte IE 8]><script src="/css/new-css/ie/html5shiv.js"></script><![endif]-->
		<script src="/js/new-js/jquery.min.js"></script>
		<script src="/js/new-js/skel.min.js"></script>
		<script src="/js/new-js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="/css/new-css/skel-noscript.css" />
			<link rel="stylesheet" href="/css/new-css/style.css" />
			<link rel="stylesheet" href="/css/new-css/style-wide.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="/css/new-css/ie/v8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="/css/new-css/ie/v9.css" /><![endif]-->

<style>
.image:after{
background:none;
}

nav #login-content {
  display: none;
  position: absolute;
  top: 24px;
  right: 0;
  z-index: 999;    
  background: #fff;
  padding: 15px;
  box-shadow: 0 2px 2px -1px rgba(0,0,0,.9);
  border-radius: 3px 0 3px 3px;
}

nav li #login-content {
    right: 13em;
    width: 250px;
    top: 4em; 
}

</style>



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
  
  
  
$(document).ready(function(){
    
    $('.header_nav_links').click(function(){
        if ($("#login-trigger").hasClass('active')){
            $("#login-trigger").next('#login-content').slideToggle();
         $("#login-trigger").toggleClass('active');
        }
    });
    
    
  $('#login-trigger').click(function(){
    $(this).next('#login-content').slideToggle();
    $(this).toggleClass('active');
    if ($(this).hasClass('active')) $(this).find('span').html('&#x25B2;')
      else $(this).find('span').html('&#x25BC;')
    });
    
});

</script>
<!-- Code For Google Analytics -->
                    

	</head>

        
        <body>


<header style="margin-bottom:0px;">
    <div style="height: 60px;" class="sticky-wrapper">
<header style="width:100%;z-index:99%">
    <div class="sticky-wrapper" style="height: 60px;"><div style="height: 60px; left: 0px; position: fixed; top: 0px; z-index: 1001; box-shadow: 0px 1px; width: 100%; background: none repeat scroll 0px 0px rgb(255, 255, 255);" class="sticky-nav stuck">
    	        
        <div style="float: left; padding: 15px 0px; margin-left: 25px;" id="logo">
        	<a style="text-decoration:none;border-bottom:none;" id="goUp" href="#header" title="">
                    <img alt="Revenue Performance Logo" src="/img/logo-rp-hori.png">
                </a>
        </div>
        
        <nav id="menu" style="float:right;margin-right:31px;">
        	<ul id="menu-nav">
            	<li style="list-style: none outside none; display: inline-block; margin-left: 0px; margin-right: 40px; margin-top: 16px;"><a class="header_nav_links" href="#header">Home</a></li>
                
                <li style="list-style:none outside none;display:inline-block;margin-left:0px; margin-right: 40px; margin-top: 16px;">
                    <a id="login-trigger" href="javascript:void(0);">Login</a>
                    <div id="login-content">
                        <form action="/staff/users/alllogin" id="UserStaffAllloginForm" method="post">
                            <div style="text-align: center;color: red;font-size: 12px;"><?php echo $this->Session->flash(); ?></div>
                            
                            <input id="username" class="text" type="text" name="data[User][username]" style="padding: 0.5em 0.5em 0.5em 0.5em;" placeholder="Username" required>   
                            <br/>
                            <input id="password" class="text" type="password" name="data[User][password]" style="padding: 0.5em 0.5em 0.5em 0.5em;" placeholder="Password" required>
                            <br/>
                            <input type="submit" id="submit" value="Log in" class="button" style="padding: 0.5em 0.5em 0.5em 0.5em;" />
                         </form>
                      </div>
                
                </li>
                
                <li style="list-style:none outside none;display:inline-block;margin-left:0px; margin-right: 40px; margin-top: 16px;"><a class="header_nav_links" href="#fourth">Contact</a></li>
                
                <li style="list-style:none outside none;display:inline-block;margin-left:0px; margin-right: 40px; margin-top: 16px;"><a class="header_nav_links" target="_blank" href="calculator.html">Pricing</a></li>
                
                <li style="list-style:none outside none;display:inline-block;margin-left:0px; margin-right: 40px; margin-top: 16px;"><a class="header_nav_links" target="_blank" href="startup/index.php">Startup Form</a></li>
				
            </ul>
        </nav>
        
    </div></div>
</header></div>
</header>
            
            
		<!-- Header -->
			<section id="header" class="dark">
				<header>
					<h1>Welcome to MyDashBoard</h1>
					<p>Simpler, Faster, Better Revenue Management for Hotels</p>
				</header>
				<footer>
					<a href="#first" class="button scrolly">Proceed to second phase</a>
				</footer>
			</section>
			
		<!-- First -->
			<section id="first" class="main">
				<header style="padding:4em 0px;padding-bottom:2em;">
					<div class="container">
						<h2>MyDashBoard is an intuitive Revenue Management and Forecasting Tool for Hotels</h2>
						<p>MyDashBoard is built on the principle that the most effective revenue decisions are made by frontline employees who are at the place of interaction between the guest and the business.</p>
					</div>
				</header>
				<div class="content dark style1 featured">
					<div class="container">
						<div class="row">
							<div class="4u">
								<section>
									<span class="feature-icon"><span class="fa fa-clock-o"></span></span>
									<header>
										<h3>Timely</h3>
									</header>
									<p>Focus on future tactics and strategy development, by learning from past performance. Don't wait for things to happen, MyDashBoard makes things happen.</p>
								</section>
							</div>
							<div class="4u">
								<section>
									<span class="feature-icon"><span class="fa fa-bolt"></span></span>
									<header>
										<h3>Efficient</h3>
									</header>
                                                                            <p>Simple graphical analysis, export capability and daily, weekly, monthly alerts.
                                                                                Access on your smartphone, tablet and traditional PC.</p>
                                                                </section>
							</div>
							<div class="4u">
								<section>
									<span class="feature-icon"><span class="fa fa-cloud"></span></span>
									<header>
										<h3>Interactive</h3>
									</header>
									<p>Cloud based technology enables realtime analysis for easy analysis across the entire management team. Interfaces with leading PMS systems.</p>
								</section>
							</div>
						</div>
						<div class="row">
							<div class="12u">
								<footer>
									<a href="#second" class="button scrolly">Proceed</a>
								</footer>
							</div>
						</div>
					</div>
				</div>
			</section>

		<!-- Second -->
			<section id="second" class="main">
				<header>
					<div class="container">
						<h2>Experience the simplicity of a solution that doesn't tie you to your desk.</h2>
						<p>MyDashBoard is a revenue optimising tool for hotel revenue departments. It frees teams to focus on optimising performance in the workplace. No more extensive management time away from the shopfloor; empower those closest to the customer.</p>
					</div>
				</header>
				<div class="content dark style2">
					<div class="container">
						<div class="row">
							<div class="4u">
								<section>
                                                                        <h3>Simplicity</h3>
									<h3>Convert Data into Intelligence</h3>
									<p>Simplicity is about easily reading the stories that your data is tell you. <br/>
                                                                            Simplicity means analysis and understanding can be interpreted by your entire management team and not just by your Revenue Analyst. <br/>
                                                                            Simplicity means Green = Good.<br/>
                                                                            Revenue Management with MyDashBoard comes to life in meaningful and simple to use analytics in the hands of those who are empowered to make a difference.<br/>
                                                                            A year from now you will be please you started today.</p>
									<footer>
										<a href="#third" class="button scrolly">Proceed</a>
									</footer>
								</section>
							</div>
							<div class="8u">
								<div class="row no-collapse">
									<div class="6u"><a href="#" class="image full"><img alt="RP chart-1" src="/img/new-images/pic01.jpg" alt="" /></a></div>
									<div class="6u"><a href="#" class="image full"><img alt="RP chart-2" src="/img/new-images/pic02.jpg" alt="" /></a></div>
								</div>
								<div class="row no-collapse">
									<div class="6u"><a href="#" class="image full"><img alt="RP chart-3" src="/img/new-images/pic03.jpg" alt="" /></a></div>
									<div class="6u"><a href="#" class="image full"><img alt="RP chart-4" src="/img/new-images/pic04.jpg" alt="" /></a></div>
								</div>
								<div class="row no-collapse">
									<div class="6u"><a href="#" class="image full"><img alt="RP chart-5" src="/img/new-images/pic05.jpg" alt="" /></a></div>
									<div class="6u"><a href="#" class="image full"><img alt="RP chart-6" src="/img/new-images/pic06.jpg" alt="" /></a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			
		<!-- Third -->
			<section id="third" class="main">
				<header>
					<div class="container">
						<h2>Focus on what matters most</h2>
						<p>We are often distracted by lengthy and mostly historical reports. We spend more time explaining what has happened, instead of what we need to happen.</p>
					</div>
				</header>
				<div class="content dark style3" style="padding:3em 0;">
					<div class="container">
						<span class="image featured"><img alt="RP Focus" src="/img/new-images/pic07.jpg" alt="" /></span>
					</div>
				</div>
			</section>

                <!-- Fourth -->
			<section id="fourth" class="main">
				<header>
					<div class="container">
						<h2>Contact us for a live demonstration</h2>
						<p>Experience a no obligation demonstration of MyDashBoard's capability through the simplicity of your browser.</p>
					</div>
				</header>
				<div class="content style4 featured">
					<div class="container small">
						<form method="post" action="">
							<div class="row half">
								<div class="6u"><input type="text" name="name" class="text" placeholder="Name" /></div>
								<div class="6u"><input type="text" name="email" class="text" placeholder="Email" /></div>
							</div>
							<div class="row half">
								<div class="12u"><textarea name="message" placeholder="Message"></textarea></div>
							</div>
                                                    
                                                    <?php
                                                    $digit1 = mt_rand(1,20);
                                                    $digit2 = mt_rand(1,20);
                                                    if( mt_rand(0,1) === 1 ) {
                                                            $math = "$digit1 + $digit2";
                                                            $correct_answer = $digit1 + $digit2;
                                                    } else {
                                                            $math = "$digit1 - $digit2";
                                                            $correct_answer = $digit1 - $digit2;
                                                    }

                                                    ?>
                                                    <input name="correct_answer" type="hidden" value="<?php echo $correct_answer; ?>"/>
                                                   <br/><div style="width:40%;" class="row half"> What's <?php echo $math; ?> = <div class="6u" style="float:right;"><input type="text" placeholder="Answer" class="text" name="answer" style="width:150px"></div><br></div>
                                                   
                                                    
							<div class="row">
								<div class="12u">
									<ul class="actions">
										<li><input type="submit" name="submit" class="button" value="Send Message" /></li>
										<li><input type="reset" class="button alt" value="Clear Form" /></li>
                                                                                <li>
<!--										<a href="#header" class="button scrolly">Go To Top</a>-->
									</li>
									</ul>
								</div>
							</div>
						</form>
					</div>
				</div>
			</section>
			
		<!-- Footer -->
			<section id="footer">
				<ul class="icons">
					<li><a href="https://twitter.com/Duncan_Bramwell" target="_blank" class="fa fa-twitter solo"><span>Twitter</span></a></li>
					<li><a href="http://www.facebook.com/pages/Revenue-Performance-Ltd/475381792502114" target="_blank" class="fa fa-facebook solo"><span>Facebook</span></a></li>
					<li><a href="http://uk.linkedin.com/in/duncanbramwell" target="_blank" class="fa fa-linkedin solo"><span>LinkedIn</span></a></li>
					
				</ul>
				<div class="copyright">
					<ul class="menu">
                                        <li><a style="float:left;" href="http://www.revenue-performance.com/" target="_blank"><img src="/img/RP%20Square.jpg"></a><br></li>
                                        <div>&copy; Assegai Holdings Ltd., trading as Revenue Performance. All rights reserved.</div>
                                        </ul>
                                </div>
                </section>
                

	</body>
</html>