<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'vendor\phpmailer\phpmailer\src\Exception.php';
  require 'vendor\phpmailer\phpmailer\src\PHPMailer.php';
  require 'vendor\phpmailer\phpmailer\src\SMTP.php';

  $mail = new PHPMailer();
  $mail->IsSMTP();

  $mail->SMTPDebug  = 0;  
  $mail->SMTPAuth   = TRUE;
  $mail->SMTPSecure = "tls";
  $mail->Port       = 587;
  $mail->Host       = "smtp.gmail.com";
  $mail->Username   = "rg007.rg1819@gmail.com";
  $mail->Password   = "vnvzzxzumzhbcufq";

  $mail->IsHTML(true);
  $mail->AddAddress("gurram.ravishankar@gmail.com", "recipient-name");
  $mail->SetFrom("rg007.rg1819@gmail.com", "set-from-name");
  $mail->AddReplyTo("reply-to-email", "reply-to-name");
  $mail->AddCC("cc-recipient-email", "cc-recipient-name");
  $mail->Subject = "Test is Test Email sent via Gmail SMTP Server using PHP Mailer";
  $content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";

  $mail->MsgHTML($content); 
  if(!$mail->Send()) {
    echo "Error while sending Email.";
    var_dump($mail);
  } else {
    echo "Email sent successfully";
  }
?>