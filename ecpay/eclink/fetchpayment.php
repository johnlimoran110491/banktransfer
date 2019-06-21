
<?php
date_default_timezone_set("Asia/Manila");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('default_socket_timeout', 600); 
include('function/functions.php');
include('function/mysql_db.php');
include('function/authentication.php');
$broadcast = new Broadcast();
$display = "";



$strddate = date('m/d/y');
$datenow = date("Y-m-d H:i:s");



  try{
    $soapclient = new SoapClient('https://ecpay.ph/eclink/?wsdl');
    $param=array('strdate' => $strddate);

    $auth = array(
        'merchantCode' => 'IOT',
        'merchantKey' => '9B0D557D9BA047279F12AD8C3DCB2AE4'
      
    );
  

    $header = new SoapHeader('https://ecpay.ph/eclink','AuthHeader',$auth,false);
    $soapclient->__setSoapHeaders($header);



    $response =$soapclient->FetchPayments($param);
 

    $array = json_decode(json_encode($response), true);
  

  
    $output ='';
      foreach($array as $item) {



    $item2 = json_decode($item, true);
    
              foreach($item2 as $new2)
              {
                
            
         
               $result =$new2['result'];
               if($result == "No Results Found")
               {
                echo "no item";
               }
               else{
                        
        $output ="<table class='table table-striped'><thead><tr><td>ReferenceNo</td><td>Amount</td><td>PaymentStatus</td>
        <td>TransDate</td>  <td>ExpiryDate</td><td>PaymentDate</td></tr></thead>";
                $result2 = json_decode($result, true);
              
                foreach($result2 as $new3)
                {
              
                    $refnumber =$new3['ReferenceNo'];
                   $amount1 =$new3['Amount'];
                 $amount =   number_format((float)$amount1, 2, '.', '');
                  $paymentstatus =$new3['PaymentStatus'];
                  $TransDatestring =$new3['TransDate'];
    
               $TransDatetrim =  trim($TransDatestring,"/(Date)/");
               $TransDate= date("Y-m-d H:i:s ",($TransDatetrim/1000));
               $ExpiryDatestring =$new3['ExpiryDate'];
               $ExpiryDatetrim =  trim($ExpiryDatestring,"/(Date)/");
               $ExpiryDate= date("Y-m-d H:i:s ",($ExpiryDatetrim/1000));
               $PaymentDatestring =$new3['PaymentDate'];
               $PaymentDatetrim =  trim($PaymentDatestring,"/(Date)/");
               $PaymentDate= date("Y-m-d H:i:s ",($PaymentDatetrim/1000));

           
               $output .="<tbody><tr><td>".$refnumber."</br></td>
               <td>".$amount."</br></td>
               <td>".$paymentstatus."</br>  </td>
               <td>".$TransDate."</br></td>
               <td>".$ExpiryDate."</br></td>
               <td>".$PaymentDate."</br></td>
               </tr></tbody>";
           
              

                $updatePayment =  $broadcast->updatePayment($refnumber, $paymentstatus);  
              $statement = $updatePayment->message;
           
                $confirmpayment = $broadcast->confirmPayment($refnumber,'0');
      
               $statement = $confirmpayment->statement;
         
         $count = $confirmpayment->message;
     
      
               if($count > 0)
               {
                $result = $statement->fetchAll();
                
                foreach($result as $row)
               {
                $messengeridpaid =  $row["messengerid"];
                 $amt =  $row["amt"];
          
                $mpnpaid =  $row["mobile"];
                
                $refnupaid =  $row["refnumber"];
                $expiredatepaid = $row["expirydate"];
                $transdatepaid = $row["transdate"];
                $array = array('mobile' => $mpnpaid);
                
                $accountkey = $broadcast->accountkey($array);
                  $accountkey->status;
                 if($accountkey->status == 200)
                 {
                   $authen = new Authentication();
                    $token = $authen->signed_token($accountkey->token, $amt, $accountkey->jwt, $mpnpaid, $refnumber , "MESSENGER");
                   $cashin = $broadcast->cashin($token); 
                   echo "cashin ".json_encode($cashin);
                  if($cashin->status == "Success")
                  {
                    $updatePayment2 =  $broadcast->updatePayment2($refnumber);  
                    echo  "</br>update 2".$message = $updatePayment2->message;
                    $datapaid = array('messengerid' => $messengeridpaid , 'amt' => $amount, 'mobile' => $mpnpaid, 'refnumber' => $refnupaid,  
                    'expirydate' => $expiredatepaid,  'transactiondate' => $transdatepaid,);
                    $logs="";
                    $logs.="Paid Transaction: Ecpay (Messenger)";
                    $logs.="\n";
                    $logs.="DATA: ".stripslashes(json_encode($datapaid))." ";
                    $logs.="\n";
                    $logs.="Cashin Result: ".stripslashes(json_encode($cashin))." ";
                    $logs.="\n";
                    $logs.=$PaymentDate;
                    $logs.="\n";
                    $logs.="======================================================================================";
                    $logs.="\n";
                    
                    $directory = "logs/";
                    $txt = ".txt";
                    $filename2 = $directory . date('Y-m-d') . $txt;
                 
                    $handle = fopen($filename2, 'a+');
                    
                    if(is_resource($handle)) {
                        fwrite($handle, $logs);
                        fclose($handle);
                    } else {
                        // Handle error if needed
                    }
               
                  }
                
             
                
                 }
                 
                 
                 
                }
             
              
               }
          
               
               
                
               }
      

         
          
       
           
           
            }
       
            
     
            

          
              }
              $output .='</table>';
         
      }
      echo $output;
   
    
    }catch(Exception $e){
      echo $e->getMessage();
    }

?>




