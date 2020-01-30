<!DOCTYPE html>
<html lang="en">
	<head>
		<title>
                        <?php __('My Dashboard'); ?>
                        <?php echo $title_for_layout; ?>
                </title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <meta name="google-site-verification" content="mkOceY5c6W4P_WfuuGKjxUuPEBC-RQTcQAcNrkO9AXw" />
                <meta name="msvalidate.01" content="9191759E0DBAD5EFD6DC9EE2DC8F59FA" />

		<!--basic styles-->
                <?php
                echo $this->Html->css(array('bootstrap.min.css','bootstrap-responsive.min.css','font-awesome.min.css','ace.min.css','ace-responsive.min.css','ace-skins.min.css'));
                ?>

		<!--[if IE 7]>
		  <link rel="stylesheet" href="/css/font-awesome-ie7.min.css" />
		<![endif]-->
		<!--page specific plugin styles-->
		<!--fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300" />
		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="/css/ace-ie.min.css" />
		<![endif]-->

                <script src="/js/jquery-2.0.3.min.js"></script>
	<body class="navbar-fixed">
		<?php echo $this->element('header'); ?>

		<div class="main-container container-fluid">
			<a class="menu-toggler" id="menu-toggler" href="#">
				<span class="menu-text"></span>
			</a>

                   <?php //echo $this->params['controller'];
                   if(($this->params['prefix'] == 'admin') || ($this->params['controller'] == 'admins' && empty($this->params['prefix']))){
                        echo $this->element('admin_left_menu');
                   }elseif(($this->params['prefix'] == 'client') || (($this->params['controller'] == 'clients' || $this->params['controller'] == 'Clients') && empty($this->params['prefix']))){
                       echo $this->element('client_left_menu');
                   }elseif(($this->params['prefix'] == 'staff') || (($this->params['controller'] == 'users' || $this->params['controller'] == 'Users') && empty($this->params['prefix']))){
                       echo $this->element('user_left_menu');
                   } ?>
                    
			<div class="main-content">
                            <?php //echo $this->element('breadcrumbs'); ?>

				<div class="page-content">
					<div class="row-fluid">
						<div class="span12">
							<!--PAGE CONTENT BEGINS-->

                                                        <?php echo $this->Session->flash(); ?>
                                                        
							<div class="space-6"></div>

							<div class="row-fluid">
                                                            <?php echo $content_for_layout; ?>
							</div><!--/row-->

							<div class="hr hr32 hr-dotted"></div>

							<!--PAGE CONTENT ENDS-->
						</div><!--/.span-->
					</div><!--/.row-fluid-->
				</div><!--/.page-content-->

			</div><!--/.main-content-->
		</div><!--/.main-container-->

		<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
			<i class="icon-double-angle-up icon-only bigger-110"></i>
		</a>

                
                <?php
		echo $this->Html->meta('icon');
                echo $this->Html->script(array('bootstrap.min.js',
                    'ace-elements.min.js','ace.min.js','jquery-ui-1.10.3.custom.min.js','jquery.ui.touch-punch.min.js','jquery.slimscroll.min.js'));
                echo $scripts_for_layout;
            	?>
                
                
		<!--<![endif]-->

		<!--[if IE]>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<![endif]-->

		<!--[if !IE]>-->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!--<![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
	</body>
</html>