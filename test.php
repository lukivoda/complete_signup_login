<?php
//include "init/autoload.php";
//include "init/config.php";
//
//$user->sendEmail('nautlija@gmail.com');
//
////$user->redirectTo('index');

//use PHPMailer\PHPMailer\Exception;


//$nameField = $_POST['name'];
//$emailField = $_POST['email'];
//$messageField = $_POST['message'];
//$phoneField = $_POST['contactno'];
//$cityField = $_POST['city'];

//$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
//
//$mail->IsSMTP(); // telling the class to use SMTP
//
//$body = "<p style='color:red'>Body in html</p>";
//
//
//    //$mail->Host       = "mail.gmail.com"; // SMTP server
//    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
//    $mail->SMTPAuth   = true;                  // enable SMTP authentication
//    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
//    $mail->Host       = "smtp.stackmail.com";      // sets GMAIL as the SMTP server
//    $mail->Port       = 465;   // set the SMTP port for the GMAIL server
//    $mail->SMTPKeepAlive = true;
//    $mail->Mailer = "smtp";
//    $mail->Username   = "stevanristov@stevanris.info";  // GMAIL username
//    $mail->Password   = "Steffi12";            // GMAIL password
//    $mail->AddAddress('nautlija@gmail.com', 'abc');
//    $mail->SetFrom('stevanristov@stevanris.info', 'Stevan');
//    $mail->isHTML(true);
//    $mail->Subject = 'Here is the subject';
//    $mail->Body = $body;
//
//    if(!$mail->Send()){
//        echo "Message was not sent</p>\n";
//    }else{
//        echo "Message Sent </p>\n";
//    }


include "init/config.php";

$user->sendEmail(); ?>