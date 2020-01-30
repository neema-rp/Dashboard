<?php echo $this->Html->script(array('jquery.min', '1.8-jquery-ui.min'));?>
<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST'];?>/ckeditor/sample.js"></script>

<script type="text/javascript">
//<![CDATA[
$(function()
{
	var config = {
		extraPlugins : 'tableresize',
		extraPlugins : 'autogrow',
		autoGrow_maxHeight : 500,
		removePlugins : 'resize',
		enterMode : CKEDITOR.ENTER_BR,
		shiftEnterMode: CKEDITOR.ENTER_BR,
		toolbar:
		[
			['Source','-','Bold','Italic','Underline','-', 'NumberedList', 'BulletedList', '-','Link','Unlink'],
			['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],['TextColor'],['Table'],'/',['Font','FontSize']
		]
	};
	// Initialize the editor.
	// Callback function can be passed and executed after full instance creation.
	$('.jquery_ckeditor').ckeditor(config);
});
//]]>
</script>
<!-- This <div> holds alert messages to be display in the sample page. -->
<div id="alerts">
	<noscript>
		<p>
			<strong>CKEditor requires JavaScript to run</strong>. In a browser with no JavaScript
			support, like yours, you should still see the contents (HTML data) and you should
			be able to edit it normally, without a rich editor interface.
		</p>
	</noscript>
</div>

<div class="contents view">

<fieldset>
		<legend><?php echo $contents['Content']['pages']; ?></legend>
	<?php echo  $form->create('Content');?>
		<table width="100%">
			<tbody>
				<tr>
					<td style="padding-bottom:20px;"><span style="float:left;"><?php echo $this->Html->link('Back', array('prefix' => 'admin', 'admin' => true, 'controller' => 'contents', 'action' => 'index'), array('class' => 'new_button', 'escape' => false));?></span></td>
				</tr>
				<tr>
					<td style="font-weight: normal;"><?php echo $this->Form->input("Content.contents", array('type' =>'textarea', 'cols'=>'56', 'rows'=>'15', 'class' => 'jquery_ckeditor', 'id' => 'editor1' ,'label' =>false));?></td>
				</tr>
				<tr>
					<td align="left">
						<span><?php echo $this->Form->submit(__('Submit', true), array('div' => false)); echo $this->Form->end();?></span>
						<span style="margin-left:10px;"><?php echo $this->Html->link('Cancel', array('prefix' => 'admin', 'admin' => true, 'controller' => 'contents', 'action' => 'index'), array('class' => 'new_button', 'escape' => false));?></span>
					</td>
				</tr>
			</tbody>
			</table>	
	</fieldset>
</div>

<?php echo $this->element('admin_left_menu'); ?>
