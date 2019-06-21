<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
session_start();
date_default_timezone_set('Asia/Manila');
$today = date('Y-m-d H:i:s');
$datenow = date('ymdHi');
include('lib/functions.php');

$display = "";
$functions = new Functions();


/*
$rt1='eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJpYXQiOjE1NTczNjgwNDQsImV4cCI6ZmFsc2UsIm1zaWQiOiIyOTQ2ODY2NDU1MzM5MDgyIn0=.02c6355e448c4858deebde2f6ceca32108dabd20f05f05141282fe226c6b08fa04501058a469fb484a9807cbf7960f0f42e1043d104b990442d826327e164cb7';
$checka	uth = $auth->VerifyToken($rt1);
$payload = $auth->GetPayload($rt1);
$messengerid = $payload->msid;
// $rt1 = '2252169354846627';
$jwt = $auth->GenerateToken('messengerid',$messengerid);
$data = json_encode(array('message'=>$jwt));
*/
$param = (object)$_REQUEST;
$rt1 = base64_decode($param->rt1);
// $mi = '2252169354846627';
$data = array('messengerid'=>$rt1);

 $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

 $result = $functions->info($data);
 $status = $result->status; 


 $s = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVW", 3)), 0, 3);
 $purpose =$s.$datenow;

 $showfees = $functions->fee();

$array = $showfees->messages;
 $fee=  $array[5]->fee;	

if($status!='Success') {
header("Location:https://m.me/EASYasCASH");
}

else{
	$logs="";
	$logs.="Transaction: Bank Transfer for Messenger";
	$logs.="\n";
	$logs.="DATA: ".stripslashes(json_encode($param)).".".stripslashes(json_encode($rt1))." ";
	$logs.="\n";
	$logs.="EAC RESULT: ".stripslashes(json_encode($data))." ";
	$logs.="\n";

	$logs.=$today;
	$logs.="\n";
	$logs.="======================================================================================";
	$logs.="\n";
	
	$directory = "assets/php/controller/logs/";
	$txt = ".txt";
	$filename2 = $directory . date('Y-m-d') . $txt;
	$handle = fopen($filename2, 'a+');
	fwrite($handle, $logs);





echo $bal = $result->bal;
	$mpn = $result->acct_mpn;

	$display.="
	<body>
<div class='container'>
		<div class='row main'>
			<div class='main-login main-center' >
		<h1 align='center'><a id='headermain' >BANK</a><a id ='headermain2'> Transfer</a></h1>
		<br>
			<h5  align='center'id='headermain3' >Transfer fee of 25php</h5>
			<h5  align='center'id='headermain3' >Transfers are not real time and may take up to 24 hours to be received by the beneficiary</h5>
		<br>
		<br>
			
				<form id='myForm'method='POST' action='modal_otp/samplepost.php' enctype='multipart/form-data'onsubmit='return closeSelf(this);'>
				<input type='hidden' class='form-control' name='url' id='url' value='".$url."'>
									<input type='hidden' class='form-control' name='messengerid' id='messengerid' value='".$rt1."'>
									<input type='hidden' class='form-control' name='bal' id='bal' value='".$bal."'>
									<input type='hidden' class='form-control' name='fee' id='fee' value='".$fee."'>
									<input type='hidden' class='form-control' name='mpn' id='mpn' value='".$mpn."'>
							
									<input type='hidden' class='form-control' name='cnumber' id='cnumber'  value='' required=''>
									<input  type='hidden' class='form-control' name='aname' id='aname'  value=''   required=''readonly/>
									<input type='hidden' class='form-control' name='anumber' id='anumber'value=''  required='' readonly />
									<input type='hidden' class='form-control' name='atype' id='atype' value='' required=''/>
									<input type='hidden' class='form-control' name='aiden' id='aiden' value=''   required=''/>

					<div class='form-group'>
						<label for='password' class='cols-sm-2 control-label'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Reference Number</label>
						<div class='cols-sm-10'>
							<div class='input-group'>
								<span class='input-group-addon'></span>
								<input type='text' class='form-control' maxlength=16 value='".$purpose."' name='purpose' id='purpose' readonly/>
								</div> 
						</div>  
											</div>
											
					<div class='form-group'>
						<label for='password' class='cols-sm-2 control-label'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bank</label>
						<div class='cols-sm-10'>
							<div class='input-group'>
								<span class='input-group-addon'></span>
								
									<select id='banklist' value='' name='bank' class='form-control' required=''>    
																					
									</select>
																			</div>
													</div>
													</div>
													
					<div class='form-group'>
						<label for='password' class='cols-sm-2 control-label'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Account Name</label>
						<div class='cols-sm-10'>
							<div class='input-group'>
								<span class='input-group-addon'></span>
								<input type='text' class='form-control' name='rname' id='rname' placeholder='Enter the Receiver Account Name' required=''/>
								</div> 
						</div> 
					</div>

					<div class='form-group'>
						<label for='password' class='cols-sm-2 control-label'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Account Number</label>
						<div class='cols-sm-10'>
							<div class='input-group'>
								<span class='input-group-addon'></span>
								<input type='text' class='form-control' name='ranumber' id='ranumber'  placeholder='Enter the Receiver Account Number' required=''/>
								</div> 
						</div> 
					</div>
							
					<div class='form-group'>
						<label for='password' class='cols-sm-2 control-label'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transfer Amount</label>
						<div class='cols-sm-10'>
							<div class='input-group'>	
								<span class='input-group-addon'></span>
								<input type='number' class='form-control' name='tamount' id='tamount' max='50000' min='1' step='1' placeholder='Enter the Transaction Amount' required=''/>
								</div> 
						</div> 
					</div> 
					<p id='erroramt' hidden class='cols-sm-2 control-label'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The amount you've entered exceeds
					 your current balance</p>
					
				
					<label for='password' >To further ensure the security of your online banking transactions,
You will be receiving a One-Time Password(OTP) via your registered mobile phone number.</label>
				
											<div class='col-sm-2 form-group col-sm-offset-10'>
											<br>
						<input type='submit'id='submit'  name='submit' class='btn btn-success btn-lg btn-block login-button' value='Proceed' title='Submit'>
											</div>
							
				</form>
			
				
				<div class='modal fade' id='myModal' role='dialog'>
	<div class='modal-dialog modal-sm'>
	
		<!-- Modal content-->
		<div class='modal-content-success'>
			<div class='modal-header'>
				
				<h4 class='modal-title'></h4>
			</div><!-- <div class='modal-header'> -->
			<div class='modal-body'>
				<p class='alertmodal'>Your transaction is already in progress, Please wait for the sms notification to complete the transfer. 
			Thanks</p>
			</div> <!-- <div class='modal-body'> -->
			<div class='modal-footer'>
				
			</div> <!--   <div class='modal-footer'> -->
		</div><!--   <div class='modal-content'> -->
		
	</div>
</div>
<div class='modal fade' id='myModal2' role='dialog'>
	<div class='modal-dialog modal-sm'>
	
		<!-- Modal content-->
		<div class='modal-content-warning'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal'>&times;</button>
				<h4 class='modal-title'></h4>
			</div><!-- <div class='modal-header'> -->
			<div class='modal-body'>
				<p class='alertmodal'>The amount you've entered exceeds your current balance</p>
			</div> <!-- <div class='modal-body'> -->
			<div class='modal-footer'>
			 
			</div> <!--   <div class='modal-footer'> -->
		</div><!--   <div class='modal-content'> -->
		
	</div>
</div>

				
	 


			</div> <!--	<div class='main-login main-center'> -->
		</div>	<!--	<div class='row main'>-->
	</div> <!--	<div class='container'>-->

</body>
	";
	echo $display;
	include('js.php');



}


    include('html.php');
 

?>