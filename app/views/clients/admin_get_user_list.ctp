<?php ?>
<script>
function toggle_main_list(div_id) {
      var ele = document.getElementById("sub_Hotel_div_"+div_id);
      var text = document.getElementById("MainText_"+div_id);
      if(ele.style.display == "") {
      ele.style.display = "none";
      text.innerHTML = '<i class="icon-plus"></i>';
      } else {
      ele.style.display = "";
      text.innerHTML = '<i class="icon-minus"></i>';
      }
}

function toggle_dept(div_id) {
      var ele = document.getElementById("department_div_"+div_id);
      var text = document.getElementById("DeptText_"+div_id);
      if(ele.style.display == "") {
      ele.style.display = "none";
      text.innerHTML = '<i class="icon-plus"></i>';
      } else {
      ele.style.display = "";
      text.innerHTML = '<i class="icon-minus"></i>';
      }
}

function toggle_user(div_id) {
      var ele = document.getElementById("user_div_"+div_id);
      var text = document.getElementById("UserText_"+div_id);
      if(ele.style.display == "") {
      ele.style.display = "none";
      text.innerHTML = '<i class="icon-plus"></i>';
      } else {
      ele.style.display = "";
      text.innerHTML = '<i class="icon-minus"></i>';
      }
}


function toggle_1_dept(div_id) {
      var ele = document.getElementById("1department_div_"+div_id);
      var text = document.getElementById("1DeptText_"+div_id);
      if(ele.style.display == "") {
      ele.style.display = "none";
      text.innerHTML = '<i class="icon-plus"></i>';
      } else {
      ele.style.display = "";
      text.innerHTML = '<i class="icon-minus"></i>';
      }
}

function toggle_1_user(div_id) {
      var ele = document.getElementById("1user_div_"+div_id);
      var text = document.getElementById("1UserText_"+div_id);
      if(ele.style.display == "") {
      ele.style.display = "none";
      text.innerHTML = '<i class="icon-plus"></i>';
      } else {
      ele.style.display = "";
      text.innerHTML = '<i class="icon-minus"></i>';
      }
}
</script>


<div class="control-group">
	<div class="page-header position-relative">
                <h1>Hotels <small><i class="icon-double-angle-right"></i> overview</small></h1>
        </div>

    <div>
	<table class="table table-striped table-bordered table-hover">
            <thead>
	<tr>
		<th>Hotelname</th>
		<th>Username</th>
		<th>Departments</th>
		<th>Users</th>
	</tr>
        </thead>
	<?php

	$i = 0;

	foreach ($clients as $client):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
		<a id="MainText_<?php echo $client['Client']['id'] ?>" href="javascript:toggle_main_list('<?php echo $client['Client']['id'] ?>');" style="text-decoration: none; font-weight:bold;vertical-align: sub;"><i class="icon-plus"></i></a>
		<?php echo $client['Client']['hotelname']; ?>&nbsp;</td>
		<td><?php echo $client['Client']['username']; ?>&nbsp;</td>
		<td>
		<a id="DeptText_<?php echo $client['Client']['id'] ?>" href="javascript:toggle_dept('<?php echo $client['Client']['id'] ?>');" style="text-decoration: none; font-weight:bold;vertical-align: sub;"><i class="icon-plus"></i></a>
		</td>
		<td>
		<a id="UserText_<?php echo $client['Client']['id'] ?>" href="javascript:toggle_user('<?php echo $client['Client']['id'] ?>');" style="text-decoration: none; font-weight:bold;vertical-align: sub;"><i class="icon-plus"></i></a>
		</td>
	</tr>

	<tr style="display:none;" id="department_div_<?php echo $client['Client']['id'] ?>" class="">
		<td colspan="4" style="background:none repeat scroll 0 0 #F5F5F5">
			<?php if(!empty($client['Department'])){ ?>
			<table class="table table-striped table-bordered table-hover">
				<tr><td colspan="2"><h2>Departments ( <?php echo $client['Client']['hotelname']; ?> )</h2></td></tr>
				<tr><th>Id</th><th>Department Name</th></tr>
				<?php foreach($client['Department'] as $departments){ ?>
				<tr><td><?php echo $departments['id']; ?></td><td><?php echo $departments['name']; ?></td></tr>
				<?php } ?>
			</table>
			<?php } ?>
		</td>
	</tr>
	<tr style="display:none;" id="user_div_<?php echo $client['Client']['id'] ?>" class="">
		<td colspan="4" style="background:none repeat scroll 0 0 #F5F5F5">
			<?php if(!empty($client['User'])){ ?>
				<table class="table table-striped table-bordered table-hover">
				<tr><td colspan="3"><h2>Users ( <?php echo $client['Client']['hotelname']; ?> )</h2></td></tr>
				<tr><th>Username</th><th>Email</th><th>Department</th></tr>
				<?php foreach($client['User'] as $users){ ?>
				<tr><td><?php echo $users['username']; ?></td><td><?php echo $users['email']; ?></td><td><?php echo $users['department_name']; ?></td></tr>
				<?php } ?>
			</table>
			<?php } ?>
		</td>
	</tr>


	<tr  id="sub_Hotel_div_<?php echo $client['Client']['id'] ?>" style="display:none;">
		<td colspan="4" style="background:none repeat scroll 0 0 #F5F5F5">
			<?php $subhotel_list = $this->requestAction("/clients/get_subhotel_list/".$client['Client']['id']);

			if(!empty($subhotel_list)){ ?>

			<table class="table table-striped table-bordered table-hover">
			<tr>
				<th>Hotelname</th>
				<th>Username</th>
				<th>Departments</th>
				<th>Users</th>
			</tr>

			<?php
			    foreach ($subhotel_list as $sub_client):
			    ?>
			    <tr>
				    <td><?php echo $sub_client['Client']['hotelname']; ?>&nbsp;</td>
				    <td><?php echo $sub_client['Client']['username']; ?>&nbsp;</td>
				    <td>
				    <a id="1DeptText_<?php echo $sub_client['Client']['id'] ?>" href="javascript:toggle_1_dept('<?php echo $sub_client['Client']['id'] ?>');" style="text-decoration: none; font-weight:bold;vertical-align: sub;"><i class="icon-plus"></i></a>
				    </td>
				    <td>
				    <a id="1UserText_<?php echo $sub_client['Client']['id'] ?>" href="javascript:toggle_1_user('<?php echo $sub_client['Client']['id'] ?>');" style="text-decoration: none; font-weight:bold;vertical-align: sub;"><i class="icon-plus"></i></a>
				    </td>
			    </tr>

			    <tr style="display:none;" id="1department_div_<?php echo $sub_client['Client']['id'] ?>" class="">
				    <td colspan="4" style="background:none repeat scroll 0 0 #F5F5F5">
					    <?php if(!empty($sub_client['Department'])){ ?>
					    <table class="table table-striped table-bordered table-hover">
						    <tr><td colspan="2"><h2>Departments ( <?php echo $client['Client']['hotelname']; ?> =>  <?php echo $sub_client['Client']['hotelname']; ?>)</h2></td></tr>
						    <tr><td>Id</td><td>Department Name</td></tr>
						    <?php foreach($sub_client['Department'] as $departments1){ ?>
						    <tr><td><?php echo $departments1['id']; ?></td><td><?php echo $departments1['name']; ?></td></tr>
						    <?php } ?>
					    </table>
					    <?php } ?>
				    </td>
			    </tr>
			    <tr style="display:none;" id="1user_div_<?php echo $sub_client['Client']['id'] ?>" class="">
				    <td colspan="4" style="background:none repeat scroll 0 0 #F5F5F5">
					    <?php if(!empty($sub_client['User'])){ ?>
					    <table class="table table-striped table-bordered table-hover">
					    <tr><td colspan="3"><h2>Users  ( <?php echo $client['Client']['hotelname']; ?> =>  <?php echo $sub_client['Client']['hotelname']; ?>)</h2></td></tr>
					    <tr><th>Username</th><th>Email</th><th>Department</th></tr>
					    <?php foreach($sub_client['User'] as $users1){ ?>
					    <tr><td><?php echo $users1['username']; ?></td><td><?php echo $users1['email']; ?></td><td><?php echo $users1['department_name']; ?></td></tr>
					    <?php } ?>
					    </table>
					    <?php } ?>
				    </td>
			    </tr>

			    <?php endforeach; ?>
			    </table>
			    <?php } ?>
		</td>
	</tr>

<?php endforeach; ?>
	</table>
</div>

</div>