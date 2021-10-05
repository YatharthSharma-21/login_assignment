<?php


class Login extends ci_controller
{

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");

		if (isset($_SESSION['userInfo']['userId']) && !empty($_SESSION['userInfo']['userId'])) {
			redirect(base_url('Main'));
			$this->verifyUser($_SESSION['userInfo']['userId'], $_SESSION['userInfo']['password']);
		}
	}

	//load login view
	public function index()
	{
		$data['key'] = 'login';
		$this->load->view('Layout/Header');
		$this->load->view('Registration', $data);
		$this->load->view('Layout/Footer');
	}

	//load registration view
	public function register()
	{
		$data['key'] = 'register';
		$this->load->view('Layout/Header');
		$this->load->view('Registration', $data);
		$this->load->view('Layout/Footer');
	}


	//save user details in table
	public function addUser()
	{
		
		if (
			isset($_POST['email']) && isset($_POST['ename']) && isset($_POST['password']) //check for all details are coming
			&& !empty($_POST['email']) && !empty($_POST['ename']) && !empty($_POST['password'])
		) {
			if ($_POST['password'] != $_POST['cpassword']) { // validate password and confirmed passwwords are same
				$this->session->set_flashdata("error", "Password doesn't match");
				redirect(base_url('Login/register'));
			}

			// validate if user already exist or not
			$email = base64_encode(validateInput($_POST['email']));
			$validate = $this->MainModel->selectAllFromWhere("login", array("email" => $email));

			if ($validate) {
				$this->session->set_flashdata("success", "User already exists");
				redirect(base_url('Login/register'));
			} else {

				//encrypting password
				$password  = hash('sha512', validateInput($_POST['password']));


				// creating user data array to be saved
				$insertData = array(
					'email'	=> $email,
					'password' => $password,
					'name' => base64_encode(validateInput($_POST['ename']))
				);

				// creating uniqe userId
				$insertData['userId'] = $entityData['userId'] =   $this->MainModel->getNewIDorNo("USR-", 'login');

				//saving data and setting result message
				$result = $this->MainModel->insertInto('login', $insertData);
				if ($result) {
					$this->validateMail($insertData);
				} else {
					$this->session->set_flashdata("error", "Details are not saved contact to IT");
					redirect(base_url('Login/register'));
				}
			}
		} else {
			$this->session->set_flashdata("error", "No data found contact to IT");
			redirect(base_url('Login/register'));
		}
	}


	//functinality for user login
	public function verifyUser($mail = '', $pass = '')
	{
		if ($mail != '' && $pass != '') {
			$_POST['email'] = $mail;
			$_POST['password'] = $pass;
		}
		if (isset($_POST['email']) && isset($_POST['password'])) {

			$email = base64_encode(validateInput($_POST['email']));
			$password = hash('sha512', validateInput($_POST['password']));

			$result = $this->MainModel->selectAllFromWhere("login", array("email" => $email, "password" => $password));
			$result1 = $this->MainModel->selectAllFromWhere("login", array("userId" => $email, "password" => $password));

			if (!$result && !$result1) {
				$this->session->set_flashdata("error", "Please enter valid credentials");
				redirect(base_url('Login'));
			} else if (!$result && $result1) {
				$result = $result1;
			}

			if ($result) {
				if ($result[0]['status'] == 'A') {
					// if ($result[0]['isAdmin'] && $result[0]['firstName'] != 'Super') {
					$this->session->set_userdata("userInfo", $result[0]);
					redirect(base_url("Main/profile"));
					// } 
				} else {
					$this->session->set_flashdata("error", "Your account has been deactivated, Please contact to Admin.");
					redirect(base_url('Login/index'));
				}
			} else {

				$this->session->set_flashdata("error", "Please enter valid credentials");
				redirect(base_url('Login/index'));
			}
		} else {
			$this->session->set_flashdata("error", "System error found, Please contact service provider");
		}
	}


	public function validateMail($data)
	{
		if (isset($data) && !empty($data)) {

			$email = base64_decode($data['email']);
			$name = base64_decode($data['name']);
			$otp = $this->randomNumGenerate(4);

			$this->load->helper('email');
			$to = $email;
			$sub = 'Verify Account';
			$mess = 'Dear ' . $name . ',' . '<br>' . 'Your 4 digit verification otp is given below.' . '<br>' . 'OTP: ' . $otp . '<br>';


			if (sentmail($to, $sub, $mess, '')) {
				$this->session->set_userdata("userInfo", array('otp' => $otp));
				$this->session->set_flashdata('success', 'An OTP has been sent to your Email for account verification');
				redirect(base_url('Login/register'));
			} else {
				$this->session->set_flashdata('error', 'Something wrong try again after some time');
				redirect(base_url('Login/register'));
			}
		} else {
			$this->session->set_flashdata('error', 'Please enter your Email');
			redirect(base_url('Login/register'));
		}
	}

	function verifyOtp()
	{
		if (isset($_POST['otp']) && !empty($_POST['otp'])) {
			$otp = validateInput($_POST['otp']);
			$sOtp = $_SESSION['userInfo']['otp'];
			if ($otp == $sOtp) {
				$this->session->set_flashdata("success", "Account Verified");
				$this->session->unset_userdata('userInfo');
				redirect(base_url('Login'));
			} else {
				$this->session->set_flashdata("error", "invalid OTP");
				redirect(base_url('Login/register'));
			}
		} else {
			$this->session->set_flashdata("error", "No data found contact to IT");
			redirect(base_url('Login/register'));
		}
	}

	public function updatePassword()
	{
		if (isset($_POST['new-password']) && isset($_POST['email'])) {
			$pass = $_POST['new-password'];
			$email = $_POST['email'];
			$userResult = $this->MainModel->selectAllFromWhere("login", array("email" => $email));
			//find time difference		

			$date1 = strtotime("+30 minutes", $userResult[0]['req_time']);
			$date2 = strtotime("now");

			if ($userResult[0]['change_pass_req'] == 'y') {
				if ((int)$date2 <= (int)$date1) {
					$result =  $this->MainModel->updateWhere("login", array('password' => $pass, 'change_pass_req' => 'n', 'req_time' => ''), array("email" => $email));
					if ($result) {
						$this->session->set_flashdata("success",  "Password changed Successfully, Login with your new password");
						redirect("login");
					} else {
						$this->session->set_flashdata("success",  "Password could not change");
						redirect(base_url('Login/index'));
					}
				} else {
					$this->session->set_flashdata("error",  "time limit exceeded, Kindly request again.");
					redirect(base_url('Login/index'));
				}
			} else {
				$this->session->set_flashdata("error",  "Link expired ,Request again");
				redirect(base_url('Login/index'));
			}
		} else {
			$this->session->set_flashdata("error",  "No password found");
			redirect(base_url('Login/index'));
		}
	}

	function randomNumGenerate($digit)
	{
		return rand(pow(10, $digit - 1), pow(10, $digit) - 1);
	}
}
