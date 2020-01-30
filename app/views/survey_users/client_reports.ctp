<?php ?>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {
	$("#inputField").datepicker({ dateFormat: 'yy-mm-dd' });
	$("#inputField1").datepicker({ dateFormat: 'yy-mm-dd' });
 });
</script>

<div class="control-group">
	<div class="page-header position-relative">
                <h1>Guests <small><i class="icon-double-angle-right"></i>Survey Report</small></h1>
        </div>

            <?php echo $form->create('SurveyUser'); 
            echo $form->hidden('client_id', array('value' => $client_id));
            ?>
		<div align="right">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" id="searchBox">
                    <tbody>
                    <tr>
                        <td style="text-align: left;font-weight: bold;">Search By Date Range</td>
                        <td style="float:right">
                            <?php echo $this->Html->link('View Guest', array('prefix' => 'client', 'client' => true, 'controller' => 'SurveyUsers', 'action' => 'index',$client_id,'0'), array('escape' => false,'class'=>'btn btn-info')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="userIndex" style="">From:(YYYY-mm-dd)<input  id="inputField" type="text" name='data[SurveyUser][field1]' value="" style="width: 230px;border:1px solid #ccc;" /></td>
                        <td style="" class="userIndex">To:(YYYY-mm-dd)<input  id="inputField1" type="text" name='data[SurveyUser][value1]' value="" style="width: 220px;border:1px solid #ccc;" /></td>
                    </tr>
                    </tbody>
                    </table>
                </div>
                <div align="center">
                            <input type="submit" name="data[SurveyUser][report]" value="Download Detailed Report" class="btn btn-info" />
                </div>
            <?php echo $form->end(); ?>
</div>