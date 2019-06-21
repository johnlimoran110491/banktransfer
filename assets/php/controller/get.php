<?php
if(isset($_POST['action'])){
    $action = $_POST['action'];
}else{
    header('Location: ../../../index.php');
}
include('../config/mysql_db.php');
include('../service/get.php');
$get = new Get();
if($action=='get_bank'){
    $bankResult = $get->GetBank();
    if($bankResult->status==200){
        $output = "";
        $message = $bankResult->message;
        $output.="<option value='' readonly>---------------SELECT BANK-----------------</option>";
        foreach($message AS $data){
            $output.="<option>".$data['bank']."</option>";
        }//end foreach loop
        $response = $output;
    }
}

echo $response;
?>