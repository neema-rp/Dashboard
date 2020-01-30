<!DOCTYPE html>
<html lang="en">
	<head>
		<title>
                        <?php __('My Dashboard - Palheiro Nature Estate'); ?>
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
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" />
		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="/css/ace-ie.min.css" />
		<![endif]-->

                <script src="/js/jquery-2.0.3.min.js"></script>
	<body class="navbar-fixed">
            <?php ?>
<style>
    .ace-nav .nav-user-photo {
     margin: 0 0 0 0;
    border-radius: 4px;
    background: #fff;
     border: none;
     max-width: 100px!important; 
    height: 40px;
}
.ace-nav>li.light-blue {
    background: none;
}
.bigger-130 {
    font-size: 156%;
}
body:before{
    position: absolute;
}
</style>
<div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
                <div class="container-fluid">
                        <a href="#" class="brand">
                                <small>
                                        <i class="icon-signal"></i>
                                        MyDashboard
                                </small>
                        </a><!--/.brand-->
                        
                        <ul class="nav ace-nav pull-right">
                                <li class="light-blue">
                                        <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                                <img class="nav-user-photo" src="/files/clientlogos/client153_photo.jpg" alt="logo" />
                                                <span class="user-info">
                                                        <small>Welcome,</small>Palheiro Nature Estate
                                                </span>
                                                <i class="icon-caret-down"></i>
                                        </a>
                                        <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
                                                <li>
                                                    <a href="/">
                                                                <i class="icon-off"></i>
                                                                Login
                                                        </a>
                                                </li>
                                        </ul>
                                </li>
                        </ul>
                        <!--/.ace-nav-->
                </div><!--/.container-fluid-->
        </div><!--/.navbar-inner-->
</div>

<style>
    #flashMessage{  text-align: center; color:#fff; background-color: green;font-weight:bold; width:100%; height:20px; }
</style>

		<div class="main-container container-fluid">
			<a class="menu-toggler" id="menu-toggler" href="#">
				<span class="menu-text"></span>
			</a>
			<div class="main-content">
				<div class="page-content">
					<div class="row-fluid">
						<div class="span10">
							<!--PAGE CONTENT BEGINS-->

                                                        <?php echo $this->Session->flash(); ?>
                                                        
							<div class="space-6"></div>

							<div class="row-fluid">
                                                            <div class="control-group">
                                                                <div class="page-header position-relative">
                                                                        <h1>Palheiro Nature Estate</h1>
                                                                </div>

                                                                <div class="widget-box">
                                                                    <div class="widget-header widget-header-blue widget-header-flat">
                                                                        <h4 class="lighter"> Progress</h4>
                                                                    </div>
                                                                    <div class="widget-body">
                                                                         <div class="widget-main">
                                                                            <div class="row-fluid">
                                                                                <table class='table table-bordered table-hover'>
                                                                                    <tr style='background: #438eb9;'>
                                                                                        <th></th>
                                                                                        <th>Day</th>
                                                                                        <th>Forecast</th>
                                                                                        <th>Budget</th>
                                                                                        <th>LY</th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style='font-weight:bold;'>TrevPAR</td>
                                                                                        <td><?php echo $revenue_data['TrevPAR']['Day']; ?></td>
                                                                                        <td><?php echo $revenue_data['TrevPAR']['Fcst']; ?></td>
                                                                                        <td><?php echo $revenue_data['TrevPAR']['Budget']; ?></td>
                                                                                        <td><?php echo $revenue_data['TrevPAR']['LY']; ?></td>
                                                                                    </tr>
                                                                                    
                                                                                  <?php foreach($child_data as $child){ ?>
                                                                                      <tr>
                                                                                        <td style='font-weight:bold;'><?php echo $child['Client']['hotelname']; ?></td>
                                                                                        <td><?php echo $revenue_data[$child['Client']['id']]['Day']; ?></td>
                                                                                        <td><?php echo $revenue_data[$child['Client']['id']]['Fcst']; ?></td>
                                                                                        <td><?php echo $revenue_data[$child['Client']['id']]['Budget']; ?></td>
                                                                                        <td><?php echo $revenue_data[$child['Client']['id']]['LY']; ?></td>
                                                                                    </tr>
                                                                                  <?php } ?>
                                                                                </table>
                                                                            </div>
                                                                         </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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