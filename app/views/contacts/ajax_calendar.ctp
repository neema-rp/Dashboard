<link rel="stylesheet" href="/css/calander.css" type="text/css" media="screen" charset="utf-8" />
		<script src="/js/jquery-1.6.min.js" type="text/javascript"> </script>
		<script src="/js/coda.js" type="text/javascript"> </script>

<?php foreach($all_sheets as $sheets){  
?>
   <div style="float:left;margin-left:10px;width:31%;">
    <?php
    echo $calendar = $this->requestAction('/contacts/build_calendar/'.$sheets['Sheet']['month'].'/'.$sheets['Sheet']['year'].'/'.$sheets['Sheet']['id'].'/'.$column_property.'/'.$client_id);
    ?>
    </div>                
<?php } ?>

