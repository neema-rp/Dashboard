<?php
ob_start();
App::import('Vendor','tcpdf');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8");
	
$date = date('Y-m-d');
$htms = '';
$htms.='<table border="">';
$htms.='<tr><td>Department Sheet : '.$sheet_name.'</td></tr>';
$htms.='<tr><td>Department Name  : '.$dept_name.'</td></tr>';
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
$html234  .= '<div class="center_content_pages" style="width:100%;margin:auto;padding:10px;">
            <div class="advanced_sheet_div">
                <table border="0" width="100%" style="margin-bottom:10px;" cellspacing="0" cellpadding="4">
                    <tr>
                        <td style="font-weight:bold;background-color:#BDBDBD;">&nbsp;</td>
                        <td style="font-weight:bold;background-color:#BDBDBD;">Date</td>';
                       if(!empty($marketSegments)){
                                foreach($marketSegments as $market){
                                    $html234  .= '<td style="font-weight:bold;background-color:#BDBDBD;">'.$market.'</td>';
                           }
                          }
                          $html234  .= '<td style="font-weight:bold;background-color:#BDBDBD;">Total</td>
                    </tr>';
                     if(!empty($final_array)){
                            $i = '1'; 
                            $segmentBOB = array(); $segmentADR = array();
                            foreach($final_array as $day => $colsArray){
                                $daystart = '1'; $bob_array = array(); $adr_array = array();
                                $bob_total = '0';
                                foreach($colsArray as $col => $resultArray){
                                $row_total = '0';
                                if($daystart == '1'){  
                                    $style='style="border-top:2px solid #888181;"';
                                    $style1='style="border-top:2px solid #888181;font-weight:bold;"';
                                }else{
                                    $style='';
                                    $style1='style="font-weight:bold;"';
                                }
                                
                                $weekday= date("w", strtotime(str_replace('/','-',$day)) ); //0-sunday & 6-saturday
                                if($weekday == '5' || $weekday == '6' || $weekday == '0'){
                                    $style = 'style="background-color:#BCDDC9;"';
                                    if($daystart == '1'){
                                        $style1 = 'style="border-top:2px solid #888181;font-weight:bold;background-color:#BCDDC9;"';
                                    }else{
                                        $style1 = 'style="font-weight:bold;background-color:#BCDDC9;"';
                                    }
                                }
                                $html234  .= '<tr '.$style1.'>';
                                    $html234  .= '<td '.$style1.'>'.$col.'</td>';
                                    $html234  .= '<td '.$style1.'>'.$day.'</td>';
                                     foreach($resultArray as $seg_key=>$segment_vals){
                                         
                                         if($col == 'BOB'){
                                            $bob_array[$seg_key] = $segment_vals;
                                            $segmentBOB[$seg_key][$day] = $segment_vals;
                                        }
                                        if($col == 'ADR'){
                                            $adr_array[$seg_key] = $segment_vals;
                                            $segmentADR[$seg_key][$day] = $segment_vals;
                                        }
                                         
                                        $row_total = $row_total + $segment_vals;
                                            $html234  .= '<td '.$style1.'>'.$segment_vals.'</td>';
                                     }
                                     
                                     
                                      $revenue_total = '0';
                                      if(!empty($bob_array) && !empty($adr_array)){
                                          foreach($bob_array as $bobkey => $bobval){
                                              $revenue_total = $revenue_total + ($bobval * $adr_array[$bobkey]);
                                          }
                                      }
                                      if($col == 'BOB'){
                                          $bob_total = $row_total;
                                      }
                                      if($col == 'ADR'){
                                          $row_total = $revenue_total/$bob_total;
                                         $row_total = number_format($row_total, 2);
                                      }
                                     
                                     
                                     $html234  .= '<td '.$style1.'>'.$row_total.'</td></tr>';
                            $daystart = '0'; }
                            $i++; }
                      }
                      if(!empty($final_array_total)){
                            foreach($final_array_total as $day => $colsArray){
                                 $bobFinal = '0';
                                foreach($colsArray as $col => $resultArray){
                                $row_total = '0';
                            
                                $html234  .= '<tr>
                                    <td style="font-weight:bold;background-color:#BDBDBD;">'.$col.'</td>
                                    <td style="font-weight:bold;background-color:#BDBDBD;">'.$day.'</td>';
                                     foreach($resultArray as $seg_key=>$segment_vals){
                                         
                                         if($col == 'ADR'){
                                          $bobFinal = $colsArray['BOB'][$seg_key];
                                          
                                          $revenueFinal = '0';
                                          if(!empty($segmentBOB[$seg_key]) && !empty($segmentADR[$seg_key])){
                                              foreach($segmentBOB[$seg_key] as $bobkey => $bobval){
                                                   $revenueFinal = $revenueFinal + ($bobval * $segmentADR[$seg_key][$bobkey]);
                                              }
                                          }
                                          $segment_vals = $revenueFinal/$bobFinal;
                                          $segment_vals = number_format($segment_vals, 2);
                                      }
                                         
                                        $row_total = $row_total + $segment_vals;
                                            $html234  .= '<td style="font-weight:bold;background-color:#BDBDBD;">'.$segment_vals.'</td>';
                                     }
                                     $html234  .= '<td style="font-weight:bold;background-color:#BDBDBD;">'.$row_total.'</td></tr>';
                            }
                             }
                      }
                      if(!empty($total_rows_array)){ 
                            foreach($total_rows_array as $day => $colsArray){
                                foreach($colsArray as $col => $resultArray){
                                $row_total = '0';
                                 
                                $html234  .= '<tr><td style="font-weight:bold;background-color:#BDBDBD;">'.$col.'</td>
                                    <td style="font-weight:bold;background-color:#BDBDBD;">&nbsp;</td>';
                                    foreach($resultArray as $seg_key=>$segment_vals){
                                           if($col == 'Revenue' || $col == 'Rev Fcst' || $col == 'Pickup Req'){
                                                $segment_vals = (int)$segment_vals;
                                           }
                                           if($col == 'Sell Rate'){
                                               $segment_vals = round($segment_vals,'2');
                                           }
                                           $row_total = $row_total + $segment_vals;
                                           
                                        $html234  .= '<td style="font-weight:bold;background-color:#BDBDBD;">'.$segment_vals.'</td>';
                                    }
                                    if($col == 'Sell Rate'){
                                      $row_total = $sell_rate_total;
                                    }
                                    if($col == 'ADR Fcst'){
                                      $row_total = $adr_fcst_total;
                                    }
                                    $html234  .= '<td style="font-weight:bold;background-color:#BDBDBD;">'.$row_total.'</td></tr>';
                            }
                             }
                      }
                $html234  .= '</table></div></div>';

//echo $html234;
//exit;
                
                
$pdf->SetXY(125,15);

$pdf->SetXY(5,50);
$pdf->writeHTML($html234, true, false, true, false, '');

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