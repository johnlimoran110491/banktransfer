<?php

include('../service/process.php');
include('../config/mysql_db.php');
$process= new Process();

 $data2 = file_get_contents('php://input');

if($data2==""||$data2==NULL){
  echo  $checkresponse = stripslashes(json_encode(array('status'=>'Error','message'=>'Invalid Data')));
  }else{
   echo $model2 = stripslashes($data2);
   $model3 =json_decode($model2, true);
 
foreach($model3 as $return => $value)
{
  $checkresponse= $value['purpose']; 
  $checkstatus= $value['status']; 
      if($checkstatus== 'Approved')
        {
          $checkresponse;
          $checkstatus;   
    
      $temptobt= $process->TemptoQueue($checkresponse); 
      echo   $temptobtstatus = $temptobt->message;
                    if($temptobtstatus=="Success")
                    {
                        $updateTempBT = $process->UpdateTempbt($checkresponse);
      echo   $updatestatus = $updateTempBT->message;
      
              
                  if( $updatestatus == 'Success')
                  {

                  }
                


                    }
        }
        else{
            $result = array('status' => 'Rejected');
        }

}

  }