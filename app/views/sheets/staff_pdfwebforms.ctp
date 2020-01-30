<?php
echo $this->Session->flash();
?>
<div class="control-group">
	<div class="page-header position-relative">
                <h1>Archive <small><i class="icon-double-angle-right"></i>  Department Old Sheets</small></h1>
        </div>
    
        <div class="widget-box">
            <div class="widget-header widget-header-blue widget-header-flat">
                    <h4 class="lighter"> Department Old Sheets</h4>
                </div>
        <div class="widget-body">
             <div class="widget-main">
                <div class="row-fluid">

                <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered table-hover">
		<tbody>
		      <?php //echo $file_path;
		      $files = explode("\n", trim(`find -L $file_path`)); // -L follows symlinks
		    $i=0;  foreach($files as $file){ if($i != '0'){

                        $file1 = str_replace('/kunden/homepages/6/d387780133/htdocs/dashboard/app/webroot','',$file);
 
                        $file = str_replace('/kunden/homepages/6/d387780133/htdocs/dashboard/app/webroot','http://www.myrevenuedashboard.net',$file);

                        $main_path = str_replace ( '/' , '$' , $file1);
				    ?>
			  <tr> <td><a target="_blank" href="<?php echo $file; ?>"><?php echo $file; ?></a>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                                <a style="text-decoration: underline;" onclick="return confirm('Are you sure you want to delete #?');"  href="/sheets/delete_server_sheet/<?php echo $main_path; ?>">Delete</a>
                              </td></tr>
                          
		      <?php } $i++; } ?>
		</tbody>
	</table>
	 </div></div></div></div>
</div>