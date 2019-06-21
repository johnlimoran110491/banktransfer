<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

include('../config/mysql_db.php');
include('../../../lib/functions.php');
include('../service/process.php');
date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d H:i:u');
$datetime = date('YmdHis');
$datenow = date('ymdHi');
$path = "../lib/";
$filename = 'Trx_'.$datetime.'.xlsx';
$filepath = "".$path."".$filename."";
$cnumber ='1580029273';
$aname = 'IOT ACH Services Inc';
$anumber = '00120082556';
$atype = 'Savings';
$aiden = 'Account Identity';

$headerlist  = getallheaders();
$auth = "zxcv";
  $json =  json_encode($headerlist);
$someArray = json_decode($json, true);
$s = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVW", 3)), 0, 3);
$purpose =$s.$datenow;
$process = new Process();
$functions = new Functions();
$data = file_get_contents('php://input');
$model = stripslashes($data);
$model = (object)json_decode($model);



    if (isset($model->mpn) && isset($model->rname)&& isset($model->bank) && isset($model->ranumber)&& isset($model->bank)
    && isset($model->tamount) &&  isset($model->pin) &&  isset($model->bal)) {



      if($data==""||$data==NULL){
      echo  $response = stripslashes(json_encode(array('status'=>'Error','message'=>'Invalid Data')));
      }else{
   
        $bank = json_decode($data)->bank;
        $mpn =json_decode($data)->mpn;
        $ranumber = json_decode($data)->ranumber;
        $rname = json_decode($data)->rname;
        $tamount = json_decode($data)->tamount;
        $pin = json_decode($data)->pin;
      $bal =json_decode($data)->bal;
        $array= array($bank, $mpn, $ranumber, $rname,$tamount,$pin,$bal, $purpose, 'Trx '.$datetime);
        $array2= array($bank, $mpn, $ranumber, $rname,$tamount,$pin,$bal, $purpose, 'Trx '.$datetime);
      $timeperiod = $functions->checktime();
      $checknumber = $process->checknumber($mpn);
      
      $result = $functions->info($data);
      
                    if($timeperiod=="early"){
                   /*
                      include('../../../lib/PHPExcel.php');
      
                      // create new PHPExcel object
                $objPHPExcel = new PHPExcel;
                s
                
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
               /* $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
                
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
                $objSheet->getCell('G2')->setValue($bank);
                $objSheet->getCell('H2')->setValue("'".$ranumber."'");
                $objSheet->getCell('I2')->setValue($tamount);
                $objSheet->getCell('J2')->setValue($purpose);
                
                
                
                // autosize the columns
                $objSheet->getColumnDimension('A')->setAutoSize(true);
                $objSheet->getColumnDimension('B')->setAutoSize(true);
                $objSheet->getColumnDimension('C')->setAutoSize(true);
                $objSheet->getColumnDimension('D')->setAutoSize(true);
                $objSheet->getColumnDimension('E')->setAutoSizes(true);
                $objSheet->getColumnDimension('F')->setAutoSize(true);
                $objSheet->getColumnDimension('G')->setAutoSize(true);
                $objSheet->getColumnDimension('H')->setAutoSize(true);
                $objSheet->getColumnDimension('I')->setAutoSize(true);
                $objSheet->getColumnDimension('J')->setAutoSize(true);
                
                
                
                  $objWriter->save($filename);
                  header('Cache-Control: must-revalidate');
                header('Pragma: public');
                  header('Content-Length: ' . filesize($filename));
      */
                $btResult = $functions->CurlToEAC($mpn,$tamount,$purpose,'ANDROID APP');
         
               
                  if($btResult->status==200){ 
                    $insertTempbt= $process->TempBT($model,$purpose);
                    $tempstatus= $insertTempbt->status;
      
                 if ($tempstatus== 200){
                  $logs="";
                  $logs.="Transaction: Bank Transfer for Android App(Success)";
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
                  /* header("Location:https://chatvot.iot.com.ph/chatbot/api/php/incoming/messenger/controller/qrpay/bankdir.php?statrx=1");
      
                   
                    /*
                      $insertResult = $process->Insertapp($model,$purpose);
                      $amount_word = $functions->convert_number_to_words($tamount);
                      //email file
                      //execute java
                      exec('MBInstapayTool2.jar');
                      $emailsettings = $functions->EmailSettings();
                      //include pdf file
                      include('pdf.php');
                      //include email file
                      include('email.php');
                      if(!$mail->Send()){
                        $display= array('status'=> 'failed');
                        echo $displaym= json_encode($display);
                      }else{
                          rename(''.$filename.'', 'history_excel/'.$filename.'');
                        $files = glob('*.{mb,pdf}',GLOB_BRACE); //get all file names
                        foreach($files as $file){
                            if(is_file($file))
                              unlink($file); //delete file
                              
                        }
                          $mail->ClearAddresses();
                          $mail->ClearAttachments();
          */
                          $display= array('status'=> 'success');
                          echo $displaym= json_encode($display);
                   
                    
                      //check if email was sent
                      //export to phpexcel
                    }
              }else{
                $logs="";
                $logs.="Transaction: Bank Transfer Android (failed)";
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
                $display= array('status'=> 'denied');
                echo $displaym= json_encode($display);
                }//end check bt
                  
               
                      // $btResult = $functions->CurlToEACAPP($mpn,$tamount,$pin);
                      
                    //destroy session
                    if(isset($_SESSION)){
                      session_destroy();
                    }
                  }else{
                    $btResult = $functions->CurlToEAC($mpn,$tamount,$purpose,'ANDROID APP');
           
                   if($btResult->status==200){
                    $insertTempbt= $process->TempBT($model,$purpose);
                    $tempstatus= $insertTempbt->status;
                 if ($tempstatus== 200){
      
                  $display= array('status'=> 'success');
                  echo $displaym= json_encode($display);
                  $logs="";
                  $logs.="Transaction: Bank Transfer(Success Queue)";
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
                 
                    
                    }
                    else{
                      $display= array('status'=> 'denied');
                      echo $displaym= json_encode($display);
                      $logs="";
                      $logs.="Transaction: Bank Transfer(Failed Queue)";
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
                  }
      
      
      
      
      
      
      
      
      
      
                      
                  }
   

                }
  else{
     $response = array('status'=>500,'message'=>'denied');
    echo json_encode($response);
  }







            return $data;
            return $mpn;
            return $bank;
            return $ranumber;
            return $rname;
            return $tamount;
            return $pin;

