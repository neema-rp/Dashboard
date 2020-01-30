<!DOCTYPE html>
<html lang="en">
	<head>
		<title>
                        <?php __('My Dashboard'); ?>
                        <?php echo $title_for_layout; ?>
                </title>

		<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->

		<!--fonts-->
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300" />

		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
        
        <?php
        echo $this->Html->css(array('bootstrap.min.css','bootstrap-responsive.min.css','font-awesome.min.css','ace-skins.min.css','ace-responsive.min.css','ace.min.css'));
	?>

            <script type="text/javascript" src="/js/jquery-1.6.min.js"></script>
            <script type="text/javascript" src="/js/jquery.validationEngine-en.js"></script>
            <script type="text/javascript" src="/js/jquery.validationEngine.js"></script>
            <link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css" />
                
            <meta name="google-site-verification" content="mkOceY5c6W4P_WfuuGKjxUuPEBC-RQTcQAcNrkO9AXw" />
            <meta name="msvalidate.01" content="9191759E0DBAD5EFD6DC9EE2DC8F59FA" />
        </head>
        
	<body class="login-layout">
		<div class="main-container container-fluid">
			<div class="main-content">
				<div class="row-fluid">
					<div class="span12">
						<div class="login-container">
							<div class="row-fluid">
								<div class="center">
									<h1>
<!--										<i class="icon-leaf green"></i>-->
                                                                            <i class="icon-signal white"></i>
										<span class="red">My</span>
										<span class="white">Dashboard</span>
									</h1>
                                                                         <?php
                                                                           if(($this->params['prefix'] == 'admin') || ($this->params['controller'] == 'admins' && empty($this->params['prefix']))){ ?>
                                                                                <h4 class="blue">Admin Login</h4>
                                                                          <?php }elseif(($this->params['prefix'] == 'subadmin') || ($this->params['controller'] == 'subadmins' && empty($this->params['prefix']))){ ?>
                                                                               <h4 class="blue">Sub-Admin Login</h4>
                                                                          <?php }else{ ?>
                                                                               <h4 class="blue">Hotel Login</h4>
                                                                          <?php } ?>
									
								</div>
							</div>

							<div class="space-6"></div>

							<div class="row-fluid">
								<div class="position-relative">
									<div id="login-box" class="login-box visible widget-box no-border">
										
                                                                            <div style="text-align: center;color: #fff;"><?php echo $this->Session->flash(); ?></div>
                                                                            <?php echo $content_for_layout; ?>
                                                                            
									</div><!--/login-box-->
								</div><!--/position-relative-->
							</div>
						</div>
					</div><!--/.span-->
				</div><!--/.row-fluid-->
			</div>
		</div><!--/.main-container-->

		<!--basic scripts-->

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

                <?php
		echo $this->Html->meta('icon');
		echo $this->Html->script(array('bootstrap.min.js','ace-elements.min.js','ace.min.js'));
                echo $scripts_for_layout;
            	?>
                
		<!--[if IE]>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
                <![endif]-->

		<!--[if !IE]>-->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>
		<!--<![endif]-->

		<!--[if IE]>
                <script type="text/javascript">
                 window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
                </script>
                <![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
	</body>
</html>
