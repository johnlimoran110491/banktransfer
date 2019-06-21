<?php
require('../lib/topdf/fpdf.php');
$datenow = date('Y/m/d');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',14);
$amount = 100;
$pdf->Write(5,"


$datenow
Malayan Bank
Main / Head Office Branch


Attention		    :	$emailsettings->attention
Reference Number	:   $purpose
 
This is to authorize Malayan Bank Head Office Branch to debit my Peso Account number 001-20-08255-6 and its applicable charges the amount of $amount_word pesos / $tamount PHP and credit to the account specified in the attached file .


Thank you.
  


");
$pdf->Image('../../images/signatures/dens.png',10,100,-300);
$pdf->Write(5,"
Dionisio L. Torres









Ruel Tabago

Authorized Signatory
");
$pdf->Image('../../images/signatures/ruel.png',10,125,-300);

$pdf->Output('F', 'authorization'.$datetime.'.pdf',false);
?>