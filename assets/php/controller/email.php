<?php
require('../lib/mailer/phpmailer/5.1/class.phpmailer.php');


  //GMAIL username// 
  $from='john.limoran@iot.com.ph';
//   $to = $emailsettings->malayan;
$to = $emailsettings->to;
  $filemb= 'Trx_'.$datetime.'.mb';
  $filepdf ='Trx_'.$datetime.'.pdf';
  $fileautorization = 'authorization'.$datetime.'.pdf';
  //Gmail password//
  $gmailPass = 'iot123456';
  
  $mail = new PHPMailer();
  $mail->IsSMTP();
  // enable SMTP authentication
  $mail->SMTPAuth = true;
  // sets the prefix to the server
  $mail->SMTPSecure = "ssl";
  // sets GMAIL as the SMTP server
  $mail->Host = 'smtp.gmail.com';
  // set the SMTP port
  $mail->Port = '465';
  // GMAIL username
  $mail->Username = $from;
  // GMAIL password
  $mail->Password = $gmailPass;
  $mail->From = $from;
  $mail->FromName = $from;
  $mail->AddReplyTo($from, $from);
 
  $mail->addAttachment($filemb);
  $mail->addAttachment($filepdf);
  $mail->addAttachment($fileautorization);
  $mail->Subject = 'Fund Transfer Trx_'.$datetime.' '.$tamount;
  $mail->Body = '';
  $mail->MsgHTML('
  '.$datenow.'<br>
  
Malayan Bank<br>
Main / Head Office Branch<br><br><br>


Attention:	'.$emailsettings->attention.'<br>
Reference Number: '.$purpose.'<br><br><br>

This is to authorize Malayan Bank – Head Office Branch to debit my Peso Account number 001-20-08255-6 and its applicable charges the amount of '.$amount_word.' pesos /  '.$tamount.'PHP and credit to the account specified in the attached file '.$filemb.'.
<br><br><br>
Thank you.<br><br>

Dionisio L. Torres<br>
<br>
Ruel Tabago<br><br>
Authorized Signatory');
  $mail->IsHTML(true);
  $mail->AddAddress($to);

// $mail->AddCC('atm@malayanbank.com.ph');

//mail->addBcc('dennis.torres@iot.com.ph');
?>