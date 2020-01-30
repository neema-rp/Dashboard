<?php
ob_start();
App::import('Vendor','tcpdf');
// echo '<pre>';
// print_r($rest_values);exit;

//overriding constant from tcpdf config filename

//define ('PDF_PAGE_ORIENTATION', 'L'); //L - Landscape;
//Setting for Landscape is done in "/vendors/tcpdf/config/tcpdf_config.php"

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8");
	
	
    //$pdf->Image( $imgPath, 10, 12, 8, 11, $type = '', $link = '', $align = 'centre',$resize = true, $dpi = 300, $palign = '', $ismask = false, $imgmask = false,  	$border = 1, $fitbox = false, $hidden = false, $fitonpage = false);
	//$pdf->Ln();
// $dept_obj = ClassRegistry::init('Department');
// $dept_name = $dept_obj->field('name',array('id'=>$user_data['Sheet']['department_id']));

  $dept_obj = ClassRegistry::init('Department');
  $dept_name = $dept_obj->field('name',array('id'=>$sheet_data['Sheet']['department_id']));

	$date = date('Y-m-d');
	$htms = '';
	$htms.='<table border="">';
	
	
	$htms.='<tr><td>Department Sheet : '.$sheet_data['Sheet']['name'].'</td></tr>';
	$htms.='<tr><td>Department Name  : '.$dept_name.'</td></tr>';
	$htms.='<tr><td>Staff User : '.$user_data['User']['firstname'].' '.$user_data['User']['lastname'].'</td></tr>';
	$htms.='<tr><td>Downloaded Date : '."$date".'</td></tr>';
	
	$htms.='</table>'; 
	
$pdf->SetCreator(PDF_CREATOR);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetHeaderMargin(-1);
$pdf->SetFooterMargin(-2);
$textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans'

$pdf->SetAuthor("Revenue Performance at www.myrevenuedashboard.net");
$pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);
$pdf->setHeaderFont(array($textfont,'',8));
$pdf->xheadercolor = array(150,0,0);
$pdf->xheadertext = 'Selected ';
$pdf->xfootertext = "Copyright &copy; Revenue Performance. All rights reserved.";

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
//set auto page breaks 
$pdf->SetAutoPageBreak(true); 

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

// add a page (required with recent versions of tcpdf)
$pdf->AddPage();
$pdf->SetAutoPageBreak(true); 

// Now you position and print your page content 
  ##############text area##########
       //$pdf->writeHTMLCell(110, 20, 30, 15, '<span style="font-size:20; color:#9E292B"><b>Making Maza code</b></span>', 0, 2, 0, true);
	
$pdf->SetFillColorArray(array(255, 255, 255));
$pdf->SetTextColor(0, 0, 0);





if(!empty($clienImage))
{
	$ext = pathinfo($clienImage, PATHINFO_EXTENSION);
	if($ext == "png" || $ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "bmp")
	{
		$exts = findexts($clienImage);	// Image example
		$imgPath = WWW_ROOT.'files'.DS.'clientlogos'.DS.$clienImage;
		
		$pdf->Image($imgPath, 245,32, 40, 14, $exts, '', '', true, 150);

	}
}

$pdf->SetXY(5, 25);
$pdf->writeHTML($htms, true, false, true, false, '');

$html234  = "\n".'<table cellpadding="2" cellspacing="1" border="1">';
$i=0;
foreach($rest_values as $values){

//print_r($values); exit;

//style="background-color:red;" 

if($i > 0){
  $arrtmp = explode("/", $values[1]);
  $dateStr = $arrtmp[1]."/".$arrtmp[0]."/".$arrtmp[2];
  $day = date('N', strtotime($dateStr));
}else{
  $day = "NA";
}

$i++;

$html234 .= '<tr>';
    	for($i=0; $i<count($values); $i++){
	      if($day == "5" || $day == "6" || $day == "7"){
			$html234 .= '<td style="background-color:#DEDEDE; font-size:22px; text-align: center;">'.$values[$i].'</td>';
	      }else{
			$html234 .= '<td style="font-size:22px; text-align: center;">'.$values[$i].'</td>';
	      }
	}
$html234 .= '</tr>';    
}
$html234.='</table>';
$html_res['values'] = $html234;
// echo '<pre>';print_r($user_data);exit;


$pdf->SetXY(125,15);
$pdf->writeHTML($user_data['Client']['hotelname'], true, false, true, false, '');




$pdf->SetXY(5,50);
$pdf->writeHTML($html_res['values'], true, false, true, false, '');

	 
ob_end_clean();
//Close and output PDF document
$pdf->Output("Report_".date('d-M-Y').".pdf", 'D');

exit;

function findexts ($filename) 
 { 
	$filename = strtolower($filename) ;
	$exts = split("[/\\.]", $filename) ;
	$n = count($exts)-1;
	$exts = $exts[$n];
	return $exts;
 } 
?>