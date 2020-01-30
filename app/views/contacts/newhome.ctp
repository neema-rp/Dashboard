<?php
$err_msg = "";
 
if (isset($_POST['submit'])) {  

    //$to =   "support@revenue-performance.com";
    $to =   "neema@revenue-performance.com";
    
  // $to =   "neema.tembhurnikar@gmail.com"; 
    
 //  echo '<pre>'; print_r($_POST);
    
    $error = '0';
     if ($_POST['answer'] != $_POST['correct_answer']) {
        $error = '1';
    } 
    
    $name = $_POST['name'];     
    $email = $_POST['email'];   
    $msg = $_POST['message'];
    $subject ="MyDashBoard - Contact Us";

    $message = $name." want to contact you, below are the details<br/>";
    $message .="Name - " . $name. "<br>";
    $message .="Email - " . $email . "<br>";
    $message .="Message - " . $msg . "<br>";

    
    $headers = 'From: Revenue Performance <support@revenue-performance.com>' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
    
     // Always set content-type when sending HTML email
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    
     if($error == '1'){
          echo  $err_msg = "Answer is incorrect";
    }else{    
   
        if(mail($to, $subject, $message, $headers)){
           echo $err_msg = "Thank you for contacting us. We will get in touch with you soon.";
        } else {
           echo $err_msg = "Mail has not been send";
        }    
     
    }
    
       //exit;
    
}


?>

