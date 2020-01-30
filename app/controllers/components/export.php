<?php
	class ExportComponent extends Object {
	

			function download1($arrays, $type = null) {


			    $string = '';
			    $c=0;
			    foreach($arrays AS $array) {
				$val_array = array();
				$key_array = array();
				foreach($array AS $key => $val) {
				    $key_array[] = $key;
				    $val = str_replace('"', '""', $val);
				    $val_array[] = "\"$val\"";
				}
				// 		if($c == 0) {
				// 		    $string .= implode(",", $key_array)."\n";
				// 		}
				$string .= implode(",", $val_array)."\n";
				$c++;
			    }
			    $filename = "Export_".date('d-M-Y').".".$type;
			    $this->download($string, $filename);

			}

			function download($string, $filename) {
			    header("Pragma: public");
			    header("Expires: 0");
			    header("Cache-Control: private");
			    header("Content-type: application/octet-stream");
			    header("Content-Disposition: attachment; filename=$filename");
			    header("Accept-Ranges: bytes");
			    echo $string;
			    exit;
			}
	}
?>
