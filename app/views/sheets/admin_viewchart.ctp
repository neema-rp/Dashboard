<?php 
$name = 'Webform';

$chart = ucFirst($type);
if(isset($this->params['pass'][2])){
  $year = $this->params['pass'][2];
}
if(isset($this->params['pass'][3])){
  $col = $this->params['pass'][3];
}
else{

 $col = $default_col['Formula']['column_id']; 
}

?>
<html>
  <head>
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    $(document).ready(function(){
      $("#year").change(function(){
	  window.location = 'http://<?php echo $_SERVER['HTTP_HOST'].'/admin/sheets/viewchart/'.$this->params['pass'][0].'/'.$this->params['pass'][1].'/'; ?>'+ $("#year").val();
	});

      $("#column").change(function(){
	      var year = $("#year").val()
	      if(year == ""){
		window.location = 'http://<?php echo $_SERVER['HTTP_HOST'].'/admin/sheets/viewchart/'.$this->params['pass'][0].'/'.$this->params['pass'][1].'/year/'; ?>'+ $("#column").val()+'/'+$("#month").val();
	      }else{
		  window.location = 'http://<?php echo $_SERVER['HTTP_HOST'].'/admin/sheets/viewchart/'.$this->params['pass'][0].'/'.$this->params['pass'][1].'/'; ?>'+year + '/'+ $("#column").val()+'/'+$("#month").val();
	      }
	});


      $("#month").change(function(){

//alert("sfsdfdf "+$("#month").val());

	      var year = $("#year").val()
	      if(year == ""){
		window.location = 'http://<?php echo $_SERVER['HTTP_HOST'].'/admin/sheets/viewchart/'.$this->params['pass'][0].'/'.$this->params['pass'][1].'/year/'; ?>'+ $("#column").val()+'/'+$("#month").val();
	      }else{
		  window.location = 'http://<?php echo $_SERVER['HTTP_HOST'].'/admin/sheets/viewchart/'.$this->params['pass'][0].'/'.$this->params['pass'][1].'/'; ?>'+year + '/'+ $("#column").val()+'/'+$("#month").val();
	      }
	});
    });


      
    </script>
  </head>
  <body>

<select id="year">
<option value="">--select year--</option>

<?php $curryear = date('Y');
$curryear = $curryear + 1;
for($i=0;$i<4;$i++){ ?>

<option value="<?php echo $curryear; ?>" <?php if(isset($year) && $year == $curryear){ echo "selected = ".$curryear; } ?>><?php echo $curryear; ?></option>
<?php $curryear = $curryear-1;
} ?>


<!--<option value="2011" <?php //if(isset($year) && $year == "2011"){ echo "selected = 2011"; } ?>>2011</option>
<option value="2012" <?php //if(isset($year) && $year == "2012"){ echo "selected = 2012"; } ?>>2012</option>-->
</select>

<?php $month_array = array(
			    "1" => "January",
			    "2" => "February",
			    "3" => "March",
			    "4" => "April",
			    "5" => "May",
			    "6" => "June",
			    "7" => "July",
			    "8" => "August",
			    "9" => "September",
			    "10" => "October",
			    "11" => "November",
			    "12" => "December"
			  )
?>


<?php 
  if(isset($month_array) && !empty($month_array)){
    echo '<select id="month">';
    foreach($month_array as $key_id=>$col_name){
	    if($month == $key_id){
		    $sel = "selected";
	    } else {
		    $sel = "";
	    }
      ?>
<option value="<?=$key_id; ?>" <?=$sel?> > <?=$col_name;?></option>

<?php
    }
    echo '</select>';
}
?>



<?php 
  if(isset($cols_array) && !empty($cols_array)){
    echo '<select id="column">';
    foreach($cols_array as $key_id=>$col_name){
      ?>
<option value="<?=$key_id; ?>" <?php if(isset($col) && $col == $key_id){echo "selected = ".$col_name;} ?> > <?=$col_name;?></option>

<?php
    }
    echo '</select>';
}
?>

<?php
if(empty($arr)){
  echo 'No data found';exit;
}

?>
    <div id="chart_div" style="width: 700px; height: 500px;"></div>
  </body>

<script type="text/javascript">

google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
	var elem1 = '<?php echo $name ;?>'; 

        var data = google.visualization.arrayToDataTable([
          ['Days', 'Total'],
	  <?php foreach($arr as $key => $value) { if($key != "Total"){ ?>
	  ['<?php echo $key ; ?>', <?php echo str_replace(',','',$value) ; ?>],
	  <?php
	  } }
	  ?> 
	  
        ]);

        var options = {
         title: elem1+' - Scores'
        };

        var chart = new google.visualization.<?php echo $chart; ?>Chart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
</script>
</html>