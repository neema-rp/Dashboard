<?php ?>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {
	$("#inputField").datepicker({ dateFormat: 'yy-mm-dd' });
	$("#inputField1").datepicker({ dateFormat: 'yy-mm-dd' });

 });

function trim(str)
{
if(!str || typeof str != 'string')
return null;
return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
}

function toggle_client(client_id) {
	
	var img_src = $('#ClientText_'+client_id).html();
	if(img_src == '<i class="icon-plus"></i>') {
		$('#ClientText_'+client_id).html('<i class="icon-minus"></i>');
		$(".client_div_"+client_id).show();
	}else{
		$('#ClientText_'+client_id).html('<i class="icon-plus"></i>');
		$(".client_div_"+client_id).hide();
	}
}

</script>
<style>
#searchBox {
	padding:10px 0px;
}

#searchBox td{
	vertical-align:middle;
}
</style>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Utilisation <small><i class="icon-double-angle-right"></i> Clients Activity</small></h1>
        </div>
<?php echo $form->create('Activity', array('style' => 'width:100%;')); ?>
    <div align="right">			
            <table cellspacing="0" cellpadding="0" border="0" width="100%" id="searchBox">
                <tbody>
                    <tr>
                        <td style="width: 50%; vertical-align: inherit; padding-right: 10px;padding-top: 20px;" class="userIndex">Search By Date Range</td>
                        <?php $date = date('Y-m-d');?>
                        <td class="userIndex" style="">From:(YYYY-mm-dd)<input  id="inputField" type="text" name='data[Activity][field1]' value="<?php echo $this->data['Activity']['field1'];?>" style="width: 230px;border:1px solid #ccc;"></td>
                        <td style="" class="userIndex">To:(YYYY-mm-dd)<input  id="inputField1" type="text" name='data[Activity][value1]' value="<?php echo $this->data['Activity']['value1'];?>" style="width: 220px;border:1px solid #ccc;"></td>
                    </tr>
                </tbody>
            </table>
            <hr style="width:100%; border-color:#DDDDDD;" />
            <table width="60%" cellspacing="5" cellpadding="0" border="0" id="searchBox">
                    <tr>
                            <td><?php $options=array('hotelname' => 'Hotel Name'); 
                            echo $form->select('field',$options,$selected, array('empty' => false,'style' => 'width:200px;border:1px solid #ccc;')); ?></td>
                            <td><?php echo $form->hidden('search', array('value' => true));
                            echo $form->select('value',$clients_list,null, array('empty' => '-- Select Hotel --','style' => 'width:200px;border:1px solid #ccc;'));  ?> 
                            </td>
                            <td>&nbsp;<input type="submit" value="Search" name="button" class="btn btn-danger" id="button" />&nbsp;</td>
                            <td>
                            <?php echo $this->Html->link('Clear', array('controller' => 'activities', 'action' => 'index'), array('class' => 'btn btn-info', 'escape' => false));?>
                            </td>
                    </tr>
            </table>
    </div>

            
		<table class="table table-striped table-bordered table-hover">
				<tr>
					<th>#</th>
					<th>Hotel Name</th>
					<th>Total Login</th>
					<th>Spent Hours</th>
					<th>Last Login</th>
					<th>Tracked IP</th>
				</tr>
			<?php 
			$i = 0;
			if (!empty($activities))
			{
				foreach ($activities as $key => $activitieslist)
				{
				?>
				<tr>
					<td>
                                            <?php echo ++$i;?>
                                            <a id="ClientText_<?php echo $activitieslist['Activity']['client_id'];?>" href="javascript:toggle_client('<?php echo $activitieslist['Activity']['client_id'];?>');" style="text-decoration: none; vertical-align: bottom;"><i class="icon-plus"></i></a>
                                        </td>
					<td><?php echo $activitieslist['Activity']['hotelname'] ?></td>
					<td><?php echo $activitieslist[0]['total_login'];?></td>
					<td><?php echo (empty($activitieslist['Activity']['total_login_time']))?'NULL':$activitieslist['Activity']['total_login_time'];?></td>
					<td><?php echo $activitieslist['Activity']['logged_in_time'];?></td>
					<td><?php echo $activitieslist['Activity']['logged_in_ip']?></td>
				</tr>
				
				<?php $user_details = array ();
                                $client_id = $activitieslist['Activity']['client_id'];
                                $field1 = (isset($this->data['Activity']['field1'])&&!empty($this->data['Activity']['field1']))?$this->data['Activity']['field1']:'';
                                $value1 = (isset($this->data['Activity']['value1'])&&!empty($this->data['Activity']['value1']))?$this->data['Activity']['value1']:'';
                                    $user_details = $this->requestAction('/Activities/get_details/'.$client_id.'/'.$field1.'/'.$value1);
					if(!empty($user_details)){ 
                                            ?>
					<?php
						$k = 1;
						foreach($user_details as $key=>$user_detail) { ?>
						<tr  style="display:none;" class="client_div_<?php echo $activitieslist['Activity']['client_id'];?>">
							<td style="background:none repeat scroll 0 0 #FFFFFF;border-left:1px solid #DDDDDD;padding-left:12px;"><?php echo $k.')';?></td>
							<td style="background:none repeat scroll 0 0 #FFFFFF;"><?php echo $user_detail['user_name']; ?></td>
							<td style="text-align:center;background:none repeat scroll 0 0 #FFFFFF;"><?php echo $user_detail['total_login'];?></td>
							<td style="background:none repeat scroll 0 0 #FFFFFF;"><?php echo (empty($user_detail['total_login_time']))?'NULL':$user_detail['total_login_time'];?></td>
							<?php $last_login=(!empty($user_detail['last_login']) && $user_detail['last_login']!='0000-00-00 00:00:00')?date('d-M-Y h:i:s A', strtotime($user_detail['last_login'])):'N/A'; ?>							
							<td style="background:none repeat scroll 0 0 #FFFFFF;"><?php echo $last_login;?></td>
							<td style="background:none repeat scroll 0 0 #FFFFFF;"><?php echo $user_detail['logged_in_ip']?></td>
						</tr>	
					<?php	$k++; } ?>
						
					<?php } else { ?>
						<tr style="display:none;" class="client_div_<?php echo $activitieslist['Activity']['client_id'];?>">
							<td colspan="7" style="text-align:center;background:none repeat scroll 0 0 #FFFFFF;border-left:1px solid #DDDDDD;border-right:1px solid #DDDDDD;">::Records Not found::</td>
						</tr>
				<?php } ?>
			<?php }
			
			} else {?>
				<tr>
					<th colspan="7" width="100%"  style="text-align:center;">::Records Not found::</th>
				</tr>	
			<?php }?>
		</table>

			<div align="center" style="float:none;">
				<label class="btnredlbl" style="display:inline;"><input type="submit" name="data[Activity][report]" value="Download Report" class="btn btn-danger"></label>
			</div>
				<?php echo $form->end(); ?>
	
</div>
<script>

$(function() {

function format(data) {
// 	return data.name + "   " + data.to;
	return data.to;
}

$("#hotel_name").autocomplete('/messages/get_all_hotel_names/'+$('#hotel_name').val(), {
	max:false,
	multiple: false,
	dataType: "json",
	parse: function(data) {

		return $.map(data, function(row) {
// console.log(row);
			return {
				data: row,
// 				value: row.name,
// 				result: row.name
				value: row.name,
				result: row.to
			}
		});
	},
	formatItem: function(item) {
		return format(item);
		
	},
	formatResult: function(item) {
// 		return item.name;
		return item.to;
	}
});

});
</script>