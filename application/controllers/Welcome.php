<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/lib/Utility.php');
require_once(APPPATH.'libraries/lib/config.php');
error_reporting(0);
class Welcome extends CI_Controller {

	

	public function index()
	{
		$this->load->view('home');
	}

	public function login()
	{
		$this->load->view('header');
		$this->load->view('login');
		$this->load->view('footer');
	}


public function getProgramData()
{
	$getclasD =  $this->Common_model->GetData("class_prog_mapping","class,program_id","class='".$_POST['class']."'","","","","1");
if(!empty($getclasD->program_id))
{
	$pid = $getclasD->program_id;
}
else {
	$pid = 0;
}
	$getProgram =  $this->Common_model->GetData("student_program","program_name","id IN($pid)","","","","");
	foreach($getProgram as $getProgramRow) {
		$Hdata = "<li><label><b><input name='program_name[]' type='checkbox' class'form-control' value=".$getProgramRow->program_name." id='geproc'>$getProgramRow->program_name</label></b></li>";
     // $Hdata = "<option value=".$getProgramRow->program_name.">$getProgramRow->program_name</option>";
      echo  $Hdata;       
              }   
         exit;     
}

	// public function register()
	// {
	// 	$getSchool =  $this->Common_model->get_data("mst_schools","","","","school_name");
	// 	$getclass =  $this->Common_model->get_data("class_prog_mapping","","","","");
	// 	$data_array=array("title"=>"adcc academy| EPAY",'getSchool'=>$getSchool,'getclass'=>$getclass);
	// 	$this->load->view('header');
	// 	$this->load->view('register',$data_array);
	// 	$this->load->view('footer');
	// }
	public function register()
	{
		$getSchool =  $this->Common_model->get_data("mst_schools","","","","school_name");
		$getclass =  $this->Common_model->get_data("class_prog_mapping","","","","");
		$getProgram = $this->Common_model->get_data("student_program","","","","program_name");

		// $data_array=array("title"=>"adcc academy| EPAY",'getSchool'=>$getSchool,'getclass'=>$getclass);
		$data_array = array(
    "title" => "adcc academy| EPAY",
    'getSchool' => $getSchool,
    'getclass'  => $getclass,
    'getProgram' => $getProgram
);

		$this->load->view('header');
		$this->load->view('register',$data_array);
		$this->load->view('footer');
	}

	public function student_login()
	{
		//print_r($_POST);exit;
		$cond = "mobile='".$_POST['mobile']."'";
		$getData = $this->Common_model->GetData('student_reg','',$cond,'','','','1');
		if(!empty($getData))
		{
			$otp = rand(100000,999999); 
			$adata = array("otp" => $otp);
			$this->Common_model->SaveData('student_reg',$adata,"mobile='".$_POST['mobile']."'");
			redirect(site_url('otp-verify'));		
		}
		else {
			echo "<script>alert('Your mobile number does not exist. Please try again.');</script>";
			redirect('login');
		}
		
		
	}
	public function otp_verify()
	{
		$this->load->view('header');
		$this->load->view('otp');
		$this->load->view('footer');
	}

	public function otp_verification()
	{
		$cond = "otp='".$_POST['otp']."'";
		$getData = $this->Common_model->GetData('student_reg','',$cond,'','','','1');
		if (!empty($getData)) {
			$_SESSION['adccepay'] = $getData;
			redirect(site_url('dashboard'));
		}
		else {
			echo "<script>alert('OTP is incorrect. Please try again')</script>";
			redirect(site_url('login'));
		}
	}
public function order_pay()
{
	if(!empty($_POST['TxnRefNo']))
	{
		$data_Arr = array('program' => $_POST['program'], 'fee_amt'=>$_POST['Amount'],'student_id'=>$_SESSION['adccepay']->id,'mobile'=>$_SESSION['adccepay']->mobile,'transation_id'=>$_POST['TxnRefNo']);
		$this->Common_model->SaveData('student_fee_details',$data_Arr);
		$this->load->view('header');
		$this->load->view('process-pay');
		$this->load->view('footer');
	}
	else {
		redirect('dashboard');
	}
}

public function response()
{	
$utility = new Utility();
$logFilePath = 'sale_log.log';

$EncKey = ENC_KEY;
$SECURE_SECRET = SECURE_SECRET;

/* Get the response from url */
$paymentResponse = $_GET['paymentResponse'];

// URL decode the parameter
$decodedJson = urldecode($paymentResponse);

// Parse the JSON
$jsonData 	= json_decode($decodedJson, true);
$EncData 	= $jsonData['EncData']; 
$merchantId = $jsonData['MerchantId'];
$bankID  	= $jsonData['BankId'];
$terminalId = $jsonData['TerminalId'];

if($bankID == "" || $merchantId == "" || $terminalId == "" || $EncData == "")
{
	echo "Invalid data";
	exit;
}
if(empty($bankID) || empty($merchantId) || empty($terminalId) || empty($EncData) )
{
	echo "Invalid data";
	exit;
}

$fomattedEncData = str_replace(" ", "+", $EncData);
$data = $utility->decrypt($fomattedEncData, $EncKey); 

$dataArray = explode("::",$data);
foreach ($dataArray as $key => $value) 
{
	$valuesplit = explode("||",$value);
	$dataFromPostFromPG[$valuesplit[0]] = urldecode($valuesplit[1]);
}

/* SecureHash got in reply */
$SecureHash = $dataFromPostFromPG['SecureHash'];

/* Log the response */
$currentTime = date('d-m-Y H:i:s'); // Get current timestamp with date and time
$logRequest = 'Response:'."[$currentTime]"; // Include timestamp in the log message
$logRequest .= json_encode($dataFromPostFromPG);
$logFile = fopen($logFilePath, 'a');
fwrite($logFile, $logRequest . PHP_EOL.PHP_EOL);
fclose($logFile); 

/* remove SecureHash from data */	
unset($dataFromPostFromPG['SecureHash']);
/* remove null or empty data */
$resData = array_filter($dataFromPostFromPG);		

/* sort data array */
ksort($resData);

/* convert hash to uppercase becuase gateway needs it in uppercase  */
$SecureHash_final = strtoupper($utility->generateSecurehash($resData));

$hashValidated = 'Invalid Hash';
$hashValidated = 'CORRECT';

if( $SecureHash_final == $SecureHash )
{
	if($resData['ResponseCode']==00)
	{
		$data_Arr = array('transation_id' => $resData['TxnRefNo'], 'bank_trans_id'=>$resData['RetRefNo'],'bank_remark'=>$resData['Message'],'payment_status'=>'Done','payment_date'=>$resData['RespDate'].' '.$resData['RespTime']);
		$this->Common_model->SaveData('student_fee_details',$data_Arr,"transation_id='".$resData['TxnRefNo']."'");
		redirect('dashboard');
	}
	else {
	
		$data_Arr = array('transation_id' => $resData['TxnRefNo'], 'bank_trans_id'=>$resData['RetRefNo'],'bank_remark'=>$resData['Message'],'payment_status'=>'Done','payment_date'=>$resData['RespDate'].' '.$resData['RespTime']);
		$this->Common_model->SaveData('student_fee_details',$data_Arr,"transation_id='".$resData['TxnRefNo']."'");
		redirect('dashboard');
	}
} else {
	$hashValidated = 'Invalid Hash';
	echo 'Invalid Hash';
}
}

public function dashboard()
{
	if(!empty($_SESSION['adccepay']))
	{
		$getData = $this->Common_model->GetData('student_reg','',"id='".$_SESSION['adccepay']->id."'",'','','','1');
		$data['studentfeeD'] = $getData;
		$this->load->view('header');
		$this->load->view('dashboard',$data);
		$this->load->view('footer');
	}
	else {
		redirect(site_url('login'));
	}	
}
public function resstatus()
{
	print_r($_REQUEST);exit;
}
public function logout()
{
	unset($_SESSION['adccepay']);
	redirect(site_url('login'));
}
	public function save_registration_data()
	{
		$pp = implode(",",$_POST['program_name']);
		$name = trim($_POST['name']);
		$mobile = trim($_POST['mobile']);
		$school = trim($_POST['school']);
		$program_name = $_POST['program_name'];
		$class = trim($_POST['class']);
		$academic_sess = trim($_POST['academic_sess']);
		$cond = "mobile='".$_POST['mobile']."'";
		$getData = $this->Common_model->GetData('student_reg','',$cond,'','','','1');
		if(empty($getData)) { 
		$otp = rand(100000,999999); 
			
	        $arrayR = array('name' => $name,'mobile'=>$mobile,'school'=>$school,'program'=>$pp,'class'=>$class,'otp'=>$otp,'session'=>$academic_sess);
		    $this->Common_model->SaveData('student_reg',$arrayR);
	    	redirect(site_url('otp-verify'));
	    } else {
	    	echo "<script>alert('Your Mobile No Already exist!');</script>";
	    	redirect('login');
	    }
		
	}

public function payment_history()
{

	if(!empty($_SESSION['adccepay']))
	{
		$getData = $this->Common_model->GetData('student_fee_details','',"student_id='".$_SESSION['adccepay']->id."'",'','','','');
		$data['studentfeeD'] = $getData;
		$this->load->view('header');
	    $this->load->view('payment-history',$data);
	    $this->load->view('footer');	
	}
	else {
		redirect(site_url('login'));
	}	
}

public function payment_invoice($sid)
{
	if(!empty($_SESSION['adccepay']))
	{
		$getData = $this->Common_model->GetData('student_fee_details','',"id='".$sid."'",'','','','1');
		$getSchData = $this->Common_model->GetData('student_reg','',"id='".$getData->student_id."'",'','','','1');
		$getAmountNumber = $this->convert_number($getData->fee_amt);
		$data['studentfeeD'] = $getData;
		$data['school'] = $getSchData;
		$data['word'] = $getAmountNumber;
		$this->load->view('header');
	    $this->load->view('invoice',$data);
	    $this->load->view('footer');	
	}
	else {
		redirect(site_url('login'));
	}
}	

public function about_us()
{
	$this->load->view('header');
	$this->load->view('about-us');
	$this->load->view('footer');
}

public function contact_us()
{
	$this->load->view('header');
	$this->load->view('contact-us');
	$this->load->view('footer');
}
public function term_condition()
{
	$this->load->view('header');
	$this->load->view('term-condition');
	$this->load->view('footer');
}
public function privacy_policy()
{
	$this->load->view('header');
	$this->load->view('privacy-policy');
	$this->load->view('footer');
}
public function cancalation_refund_policy()
{
	$this->load->view('header');
	$this->load->view('refund-policy');
	$this->load->view('footer');
}
	

	public function getStudentAjax()
	{
		if($_POST['school'])
		{
			$data_array['getStudent'] =  $this->Common_model->get_data("mgs_employees","school='".$_POST['school']."' and status='Active'");
			$this->load->view('append_student',$data_array);
		}else
		{
			echo "1";exit;
		}
	}
}