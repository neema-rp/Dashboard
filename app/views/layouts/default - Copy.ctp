<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('My Dashboard'); ?>
		<?php echo $title_for_layout; ?>
	</title>
<!--[if IE 6]>
<script src="/js/DD_belatedPNG.js"></script>
<![endif]-->
<!--[if lte IE 8]>
<script language="JavaScript">
(function(){if(!/*@cc_on!@*/0)return;var e = "abbr,article,aside,audio,bb,canvas,datagrid,datalist,details,dialog,eventsource,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video".split(',');for(var i=0;i<e.length;i++){document.createElement(e[i])}})()
</script>
<![endif]-->
<!--[if IE]>
<script src="/js/html5.js" type="text/javascript"></script>
<![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.js" type="text/javascript"></script>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->script(array('jquery-1.6.min.js'));
		//echo $this->Html->script(array('slider.js'));
		echo $this->Html->script('specValidation');
		echo $this->Html->css('styles');
		echo $scripts_for_layout;
	?>
	
	<meta name="google-site-verification" content="mkOceY5c6W4P_WfuuGKjxUuPEBC-RQTcQAcNrkO9AXw" />
	<meta name="msvalidate.01" content="9191759E0DBAD5EFD6DC9EE2DC8F59FA" />
</head>


<body>

<div class="wrapper">
<!--header start-->
<?php echo $this->element('header'); ?>
<!--header end-->
            
<!--<div class="clear"><img src="/img/spacer.gif" width="1" height="15" alt=""></div>-->
            
	<!--content start-->
	<section class="content">
	<?php echo $this->Session->flash(); ?>
	<?php echo $content_for_layout; ?>
	<div class="clear"></div>
	</section>
	<div class="clear"><img width="1" height="15" alt="" src="/img/spacer.gif"></div>
	<!--content end-->
<!--footer start-->
<?php echo $this->element('footer'); ?>
<!--footer start-->
</div>
<!--wrapper start-->
</div>
<!--footer wrapper end-->
</body>
</html>
