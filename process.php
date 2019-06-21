<?php


include('../config/mysql_db.php');
include('../../../lib/functions.php');
include('../service/process.php');

date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d H:i:u');
$datetime = date('YmdHis');
$path = "../lib/";
$filename = 'Trx_'.$datetime.'.xlsx';
$filepath = "".$path."".$filename."";


session_start();

$_SESSION['username'] = $_POST['purpose'];

$cnumber ='1580029273';
$aname = 'IOT ACH Services Inc';
$anumber = '00120082556';
$atype = 'Savings';
$aiden = 'CS201950688';
$messengerid = $_POST['messengerid'];
$rname = $_POST['rname'];
$rbank = $_POST['bank'];
$ranumber = $_POST['ranumber'];
$tamount = $_POST['tamount'];
$purpose = $_POST['purpose'];
$mpn = $_POST['mpn'];
$bal = $_POST['bal'];
 $otp= $_POST['otpinput']; 
    $process = new Process();
    $functions = new Functions();
 
    $array= array('messengerid'=>$messengerid,'rname'=> $rname,'bank'=>$rbank ,'ranumber'=>$ranumber,'rname'=> $rname,'tamount'=>$tamount,'purpose'=>$purpose,'mpn' =>$mpn,'bal'=>$bal);
    $array2= array('messengerid'=>$messengerid,'rname'=> $rname,'bank'=>$rbank ,'ranumber'=>$ranumber,'rname'=> $rname,'tamount'=>$tamount,'purpose'=>$purpose,'mpn' =>$mpn,'bal'=>$bal, 'ref'=>'Trx '.$datetime);
    $timeperiod = $functions->checktime();
    $checknumber = $process->checknumber($mpn);
 
    $verifyResult = $functions->VerifyOTP($mpn,$otp);
   if($verifyResult->success==true){

      if($timeperiod=="early"){

               $btResult = $functions->CurlToEAC($messengerid,$tamount,$purpose,'MESSENGER');
               
        
               if($btResult->status==200){
              
                $insertTempbt= $process->TempBT($array,$purpose);
                 $tempstatus= $insertTempbt->status;
              if ($tempstatus== 200){
                header("Location:https://chatvot.iot.com.ph/chatbot/api/php/incoming/messenger/controller/qrpay/bankdir.php?statrx=1");
                $logs="";
                $logs.="Transaction: Bank Transfer for Messenger (Success)";
                $logs.="\n";
                $logs.="DATA: ".stripslashes(json_encode($array))." ";
                $logs.="\n";
                $logs.="EAC RESULT: ".stripslashes(json_encode($btResult))." ";
                $logs.="\n";
            
                $logs.=$today;
                $logs.="\n";
                $logs.="======================================================================================";
                $logs.="\n";
                
                $directory = "logs/";
                $txt = ".txt";
                $filename2 = $directory . date('Y-m-d') . $txt;
                $handle = fopen($filename2, 'a+');
                fwrite($handle, $logs);
              }
           /*
                            //export to phpexcel
                   include('../../../lib/PHPExcel.php');

                   // create new PHPExcel object
             $objPHPExcel = new PHPExcel;
             
             
             // set default font
             $objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
             
             // set default font size
             $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
             
             
             // create the writer
             $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
             
             
             /**
             
             * Define currency and number format.
             
             */
             
             // currency format, € with < 0 being in red color
           /*  $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
             
             // number format, with thousands separator and two decimal points.
             $numberFormat = '#,#0.##;[Red]-#,#0.##';
             
             
             
             // writer already created the first sheet for us, let's get it
             $objSheet = $objPHPExcel->getActiveSheet();
             
             // rename the sheet
             $objSheet->setTitle('Sheet1');
             
             
             
             // let's bold and size the header font and write the header
             // as you can see, we can specify a range of cells, like here: cells from A1 to A4
             $objSheet->getStyle('A1:J1')->getFont()->setSize(11);
             // $objSheet->getStyle('A2:I2')->getFont()->setBold(true)->setSize(12);
             $objSheet->getStyle('A2:J2')->getFont()->setSize(11);
             
             $objSheet->getStyle('C2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
             $objSheet->getStyle('H2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
             $objSheet->getStyle('I2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
               
             
             
             
             
             // write header
             
             $objSheet->getCell('A1')->setValue('Customer Number');
             $objSheet->getCell('B1')->setValue('Account Name');
             $objSheet->getCell('C1')->setValue('Account Number');
             $objSheet->getCell('D1')->setValue('Account Type');
             $objSheet->getCell('E1')->setValue('Account Identity');
             $objSheet->getCell('F1')->setValue('Receiver Name');
             $objSheet->getCell('G1')->setValue('Receiver Bank');
             $objSheet->getCell('H1')->setValue('Receiver Account Number');
             $objSheet->getCell('I1')->setValue('Transaction Amount');
             $objSheet->getCell('J1')->setValue('Purpose');
             
             $objSheet->getCell('A2')->setValue($cnumber);
             $objSheet->getCell('B2')->setValue($aname);
             $objSheet->getCell('C2')->setValue("'".$anumber."'");
             $objSheet->getCell('D2')->setValue($atype);
             $objSheet->getCell('E2')->setValue($aiden);
             $objSheet->getCell('F2')->setValue($rname);
             $objSheet->getCell('G2')->setValue($rbank);
             $objSheet->getCell('H2')->setValue("'".$ranumber."'");
             $objSheet->getCell('I2')->setValue($tamount);
             $objSheet->getCell('J2')->setValue($purpose);
             
             
             
             // autosize the columns
             $objSheet->getColumnDimension('A')->setAutoSize(true);
             $objSheet->getColumnDimension('B')->setAutoSize(true);
             $objSheet->getColumnDimension('C')->setAutoSize(true);
             $objSheet->getColumnDimension('D')->setAutoSize(true);
             $objSheet->getColumnDimension('E')->setAutoSize(true);
             $objSheet->getColumnDimension('F')->setAutoSize(true);
             $objSheet->getColumnDimension('G')->setAutoSize(true);
             $objSheet->getColumnDimension('H')->setAutoSize(true);
             $objSheet->getColumnDimension('I')->setAutoSize(true);
             $objSheet->getColumnDimension('J')->setAutoSize(true);
             
             
             
               $objWriter->save($filename);
               header('Cache-Control: must-revalidate');
             header('Pragma: public');
               header('Content-Length: ' . filesize($filename));
                $logs="";
                $logs.="Transaction: Bank Transfer for Messenger";
                $logs.="\n";
                $logs.="DATA: ".stripslashes(json_encode($array))." ";
                $logs.="\n";
                $logs.="EAC RESULT: ".stripslashes(json_encode($btResult))." ";
                $logs.="\n";
            
                $logs.=$today;
                $logs.="\n";
                $logs.="======================================================================================";
                $logs.="\n";
                
                $directory = "logs/";
                $txt = ".txt";
                $filename2 = $directory . date('Y-m-d') . $txt;
                $handle = fopen($filename2, 'a+');
                fwrite($handle, $logs);
                 $insertResult = $process->Insert($array);
                 
                   $amount_word = $functions->convert_number_to_words($tamount);
               //email file
               //execute java
            //   exec('MBInstapayTool2.jar');
               $emailsettings = $functions->EmailSettings();
               //include pdf file
               include('pdf.php');
               //include email file
               include('email.php');
               if(!$mail->Send()){
               header("Location:https://chatvot.iot.com.ph/chatbot/api/php/incoming/messenger/controller/qrpay/bankdir.php?statrx=0");
               }else{
                   rename(''.$filename.'', 'history_excel/'.$filename.'');
                 $files = glob('*.{mb,pdf}',GLOB_BRACE); //get all file names
                 foreach($files as $file){
                     if(is_file($file))
                       unlink($file); //delete file
                       
                 }
                   $mail->ClearAddresses();
                   $mail->ClearAttachments();
                 

                   header("Location:https://chatvot.iot.com.ph/chatbot/api/php/incoming/messenger/controller/qrpay/bankdir.php?statrx=1");
                }
             */
               //check if email was sent
                
           }else{
            $logs="";
            $logs.="Transaction: Bank Transfer (failed)";
            $logs.="\n";
            $logs.="DATA: ".stripslashes(json_encode($array))." ";
            $logs.="\n";
            $logs.="EAC RESULT: ".stripslashes(json_encode($btResult))." ";
            $logs.="\n";
        
            $logs.=$today;
            $logs.="\n";
            $logs.="======================================================================================";
            $logs.="\n";
            
            $directory = "logs/";
            $txt = ".txt";
            $filename2 = $directory . date('Y-m-d') . $txt;
            $handle = fopen($filename2, 'a+');
            fwrite($handle, $logs);
          
                   echo "<script type='text/javascript'>alert('Error requesting transaction!Please try again later');location.href='https://bit.ly/2CDH18E'</script>";
             }//end check bt
             
               
              //destroy session
              if(isset($_SESSION)){
                session_destroy();
              }
            }else{
        
              $btResult = $functions->CurlToEAC($messengerid,$tamount,$purpose,'MESSENGER');
            if($btResult->status==200){
              $insertTempbt= $process->TempBT($array,$purpose);
              $tempstatus= $insertTempbt->status;
           if ($tempstatus== 200){


            $logs="";
            $logs.="Transaction: Bank Transfer for Messenger(Queue success)";
            $logs.="\n";
            $logs.="DATA: ".stripslashes(json_encode($array))." ";
            $logs.="\n";
            $logs.="EAC RESULT: ".stripslashes(json_encode($btResult))." ";
            $logs.="\n";
        
            $logs.=$today;
            $logs.="\n";
            $logs.="======================================================================================";
            $logs.="\n";
            
            $directory = "logs/";
            $txt = ".txt";
            $filename2 = $directory . date('Y-m-d') . $txt;
            $handle = fopen($filename2, 'a+');
            fwrite($handle, $logs);

           }



               
                 
            }else{
              
              $logs="";
              $logs.="Transaction: Bank Transfer (Queue failed)";
              $logs.="\n";
              $logs.="DATA: ".stripslashes(json_encode($array))." ";
              $logs.="\n";
              $logs.="EAC RESULT: ".stripslashes(json_encode($btResult))." ";
              $logs.="\n";
          
              $logs.=$today;
              $logs.="\n";
              $logs.="======================================================================================";
              $logs.="\n";
              
              $directory = "logs/";
              $txt = ".txt";
              $filename2 = $directory . date('Y-m-d') . $txt;
              $handle = fopen($filename2, 'a+');
              fwrite($handle, $logs);
                    echo "<script type='text/javascript'>alert('Error requesting transaction!Please try again later');location.href='https://bit.ly/2CDH18E'</script>";
              }//end check bt



                header("Location:https://chatvot.iot.com.ph/chatbot/api/php/incoming/messenger/controller/qrpay/bankdir.php?statrx=3");
              }
            }
                //OTP VERIFY
          
          else{
            session_start();
            $_SESSION['messengerid'] = $messengerid;
            $_SESSION['cnumber'] = $cnumber;
            $_SESSION['aname'] = $aname;
            $_SESSION['anumber'] = $anumber;
            $_SESSION['aiden'] = $aiden;
            $_SESSION['rname'] = $rname;
            $_SESSION['bank'] = $rbank;
            $_SESSION['ranumber'] = $ranumber;
            $_SESSION['tamount'] = $tamount;
            $_SESSION['purpose'] = $purpose;
            $_SESSION['mpn'] = $mpn;
            $_SESSION['bal'] = $bal;
            $_SESSION['otp'] = $otp;
           echo "<script type='text/javascript'>alert('Please enter correct OTP');location.href='../../../modal_otp/retype_otp.php'</script>";
          
           $logs="";
           $logs.="Transaction: Bank Transfer for Messenger Retype OTP";
           $logs.="\n";
           $logs.="INFO:".stripslashes(json_encode($array))." ";
           $logs.="\n";
           $logs.="DATA:".stripslashes(json_encode($otp)).".".stripslashes(json_encode($mpn)).".".stripslashes(json_encode($messengerid))." ";
           $logs.="\n";
           $logs.="EAC RESULT: ".stripslashes(json_encode($verifyResult))." ";
           $logs.="\n";
         
           $logs.=$today;
           $logs.="\n";
           $logs.="======================================================================================";
           $logs.="\n";
           
           $directory = "../../../assets/php/controller/logs/";
           $txt = ".txt";
           $filename2 = $directory . date('Y-m-d') . $txt;
           $handle = fopen($filename2, 'a+');
           fwrite($handle, $logs);
         
      
          }//


        





                
            

            return $mpn;
            return $bank;
            return $ranumber;
            return $rname;
            return $tamount;
            return $messengerid;

