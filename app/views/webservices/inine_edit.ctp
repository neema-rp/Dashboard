<?php ?>
 <script>
	 // Column names must be identical to the actual column names in the database, if you dont want to reveal the column names, you can map them with the different names at the server side.
	 var columns = new Array("notes");
	 var placeholder = new Array("Enter Notes");
	 var inputType = new Array("textarea");
	 var table = "tableDemo";
	 
	 // Set button class names 
	 var savebutton = "ajaxSave";
	 var deletebutton = "ajaxDelete";
	 var editbutton = "ajaxEdit";
	 var updatebutton = "ajaxUpdate";
	 var cancelbutton = "cancel";
	 
	 var saveImage = "/inline-edit/images/save.png"
	 var editImage = "/inline-edit/images/edit.png"
	 var deleteImage = "/inline-edit/images/remove.png"
	 var cancelImage = "/inline-edit/images/back.png"
	 var updateImage = "/inline-edit/images/save.png"

	 // Set highlight animation delay (higher the value longer will be the animation)
	 var saveAnimationDelay = 3000; 
	 var deleteAnimationDelay = 1000;
	  
	 // 2 effects available available 1) slide 2) flash
	 var effect = "flash"; 
  
 </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <script src="/inline-edit/js/script.js"></script>	
<!--  <link rel="stylesheet" href="/inline-edit/css/style.css" />-->

<style type="text/css">
.error{ border: 1px solid red !important; }
.success{ border: 1px solid green !important; }
.bordered {
    *border-collapse: collapse; /* IE7 and lower */
    border-spacing: 0;
/*    width: 80%;    */
}
.bordered img{ width: 20px; height: 20px; }
.bordered textarea{ border: 1px solid #999999; border-radius: 3px; width:100%;height:100%; }
/*.bordered input{ height: 20px; }*/
.bordered {
    border: solid #ccc 1px;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    border-radius: 6px;
    -webkit-box-shadow: 0 1px 1px #ccc; 
    -moz-box-shadow: 0 1px 1px #ccc; 
    box-shadow: 0 1px 1px #ccc;
    font-size: 12px;
}
.bordered tr:hover {
    /*background: #fbf8e9;*/
    background: #feecd8;
    -o-transition: all 0.1s ease-in-out;
    -webkit-transition: all 0.1s ease-in-out;
    -moz-transition: all 0.1s ease-in-out;
    -ms-transition: all 0.1s ease-in-out;
    transition: all 0.1s ease-in-out;     
}
.bordered td, .bordered th { border-left: 1px solid #ccc;border-top: 1px solid #ccc; padding: 10px;text-align: left;vertical-align: top; }
.bordered th {
    background-color: #dce9f9;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#ebf3fc), to(#dce9f9));
    background-image: -webkit-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:    -moz-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:     -ms-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:      -o-linear-gradient(top, #ebf3fc, #dce9f9);
    background-image:         linear-gradient(top, #ebf3fc, #dce9f9);
    -webkit-box-shadow: 0 1px 0 rgba(255,255,255,.8) inset; 
    -moz-box-shadow:0 1px 0 rgba(255,255,255,.8) inset;  
    box-shadow: 0 1px 0 rgba(255,255,255,.8) inset;        
    border-top: none;
    text-shadow: 0 1px 0 rgba(255,255,255,.5); 
}
.bordered td:first-child, .bordered th:first-child { border-left: none; }
.bordered th:first-child {
    -moz-border-radius: 6px 0 0 0;
    -webkit-border-radius: 6px 0 0 0;
    border-radius: 6px 0 0 0;
}
.bordered th:last-child {
    -moz-border-radius: 0 6px 0 0;
    -webkit-border-radius: 0 6px 0 0;
    border-radius: 0 6px 0 0;
}
.bordered th:only-child{
    -moz-border-radius: 6px 6px 0 0;
    -webkit-border-radius: 6px 6px 0 0;
    border-radius: 6px 6px 0 0;
}
.bordered tr:last-child td:first-child {
    -moz-border-radius: 0 0 0 6px;
    -webkit-border-radius: 0 0 0 6px;
    border-radius: 0 0 0 6px;
}
.bordered tr:last-child td:last-child {
    -moz-border-radius: 0 0 6px 0;
    -webkit-border-radius: 0 0 6px 0;
    border-radius: 0 0 6px 0;
}
body{
    color: black;
    font-size: 80%;
    font-family: tahoma, arial, verdana, sans-serif;
}
</style>

<div class="sheets index">
        <fieldset>
            <legend style="color:red;"><?php __('Webform Notes');?></legend>
            
            <input type="hidden" value="<?php echo $sheet_id; ?>" id="sheet_id" />
            <table border="0" class="tableDemo bordered" width="100%">
                    <tr class="ajaxTitle">
                            <th>Sr</th>
                            <th>Notes</th>
                            <th>Created</th>
                            <th>Action</th>
                    </tr>
                    <?php
                    if(count($records)){
                    $i = 1;	
                    $find = array(); $replace = array();
                    $find[] = '’'; // right side single smart quote
                    $find[] = '—'; // em dash
                    $find[] = '–'; // en dash
                    $find[] = '“'; // left side double smart quote
                    $find[] = '�'; // right side double smart quote
                    $find[] = '‘'; // left side single smart quote
                    $find[] = '…'; // elipsis
                    $find[] = '�';
                    $find[] = '?';

                    $replace[] = "'";
                    $replace[] = "-";
                    $replace[] = "-";
                    $replace[] = '"';
                    $replace[] = '"';
                    $replace[] = "'";
                    $replace[] = "...";
                    $replace[] = "";
                    $replace[] = "";
                    
                    foreach($records as $key=>$eachRecord){
                    ?>
                    <tr id="<?=$eachRecord['SheetNote']['id'];?>">
                            <td><?=$i++;?></td>
                            <td class="notes"><?php $notes = $eachRecord['SheetNote']['notes'];
                           // echo nl2br($notes);
                            $newLineArray = array('\r\n','\n\r','\n','\r');
                            $notes1 = str_replace($newLineArray,"<br/>", $notes);
                            $notes1 = nl2br($notes1);
                            //echo str_replace($find, $replace, $notes1);
                            echo $notes1;
                            ?></td>
                            <td><?php echo  date('d-m-Y',strtotime($eachRecord['SheetNote']['created']));?></td>
                            <td>
                                    <a href="javascript:;" id="<?=$eachRecord['SheetNote']['id'];?>" class="ajaxEdit"><img src="" class="eimage"></a>
                                    <a href="javascript:;" id="<?=$eachRecord['SheetNote']['id'];?>" class="ajaxDelete"><img src="" class="dimage"></a>
                            </td>
                    </tr>
                    <?php }
                    }
                    ?>
            </table>
            
        </fieldset>
</div>