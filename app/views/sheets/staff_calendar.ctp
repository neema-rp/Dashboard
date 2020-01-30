<?php 
?>
<style>
    table{
        width:100%;
    }
</style>
<script src="/js/coda.js" type="text/javascript"> </script>
                
                <script>
                  $(document).ready(function(){
                     $('#select_department').change(function(){
                        // alert($(this).val());
                       var column_property = $(this).val();
                       var department_id = $('#department_id').val();
                                $('#calendar_view_div').html('<div style="text-align:center;">Loading Please wait....</div>');
                               
                                $.post( 
                                     "/contacts/ajax_calendar/"+department_id+"/"+column_property,
                                     { name: "Zara" },
                                     function(data) {
                                        $('#calendar_view_div').html(data);
                                     }

                                  );
                         
                     }); 
                  });  
function print_report(){
    var prtContent = document.getElementById("legend_result_div");
    var WinPrint = window.open('', '', 'left=0,top=0,width=1000,height=1000,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write('<link rel="stylesheet" type="text/css" href="http://beta.myrevenuedashboard.net/css/bootstrap.min.css" />');
    WinPrint.document.write('<link rel="stylesheet" type="text/css" href="http://beta.myrevenuedashboard.net/css/ace.min.css" />');
    WinPrint.document.write('<link rel="stylesheet" type="text/css" href="http://myrevenuedashboard.net/css/calander.css" />');
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
                  
                </script>
            
<div class="staff index" >
    
     <div style="text-align:right;">
     <a href="javascript:void(0);" onClick="print_report();"><button class="btn btn-app btn-light btn-mini">
    <i class="icon-print bigger-160"></i>
    Print Report
    </button></a>
     </div>
    
	<fieldset id="legend_result_div">
            	<legend>View  <?php echo $department_name; ?> Department Calendar Sheet View for Hotel <?php echo $user_data['Client']['hotelname']; ?></legend>

<input type="hidden" value="<?php echo $department_id; ?>" id="department_id">
    
    <?php echo $form->input('column_property',array('empty' =>array('0' => 'Select'),'style'=>'width:200px;border:1px solid #ccc;','id'=>'select_department','options'=>$columns)); ?>

<div style="width:70%;float:right;margin-top:-40px">
<table border="0" cellspacing="4" cellpadding="4">
    <tr>
        <td style="width:30px;height:20px;background-color:#F4FA58">&nbsp;</td><td>Quiet Day</td>
        <td style="width:30px;height:20px;background-color:#64FE2E">&nbsp;</td><td>Moderate Day</td>
        <td style="width:30px;height:20px;background-color:#0040FF">&nbsp;</td><td>Busy Day</td>
        <td style="width:30px;height:20px;background-color:#FF0000">&nbsp;</td><td>Very Busy Day</td>
    </tr>
</table>
</div>

    <div id="calendar_view_div">
            <?php foreach($all_sheets as $sheets){ ?>
                    <div style="float:left;margin-left:10px;width:31%;">
                    <?php // echo build_calendar($sheets['Sheet']['month'],$sheets['Sheet']['year'],$sheets['Sheet']['id']);
                    echo $calendar = $this->requestAction('/contacts/build_calendar/'.$sheets['Sheet']['month'].'/'.$sheets['Sheet']['year'].'/'.$sheets['Sheet']['id'].'/0/'.$client_id);
                    ?>
                    </div>                
           <?php } ?>
    </div>            

<br/><br/><br/>
</fieldset>
        <br/><br/><br/>
</div>
                
<link rel="stylesheet" href="/css/calander.css" type="text/css" media="screen" charset="utf-8" />
