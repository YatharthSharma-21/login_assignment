<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");

		if (!isset($_SESSION['userInfo']['userId']) && empty($_SESSION['userInfo']['userId'])) {
			redirect(base_url('Login'));
		}
	}

	//load profile view
	public function profile()
	{
		$id = $_SESSION['userInfo']['userId'];
		$data['user'] = $this->MainModel->selectAllFromWhere("login", array("userId" => $id));
		$this->load->view('Layout/Header');
		$this->load->view('Layout/NavBar');
		$this->load->view('profile', $data);
		$this->load->view('Layout/Footer');
	}

	function updateUser()
	{
		$res = '';
		$id = $_SESSION['userInfo']['userId'];
		
		if ($_FILES['file']['name'] != '') {
			$res = uploadFile($_FILES, 'uploads');
		}

		if ($res[0] == 'error') {
			$this->session->set_flashdata("error", $res[1]);
			redirect(base_url('Main/profile'));
		} else {
			$insertData = array(
				'name' =>  base64_encode(validateInput($_POST['ename'])),
			);
			if ($res[0] == 'success') {
				$insertData['image_path'] = base64_encode('uploads/' . $res[1]);
			}

			$result = $this->MainModel->updateWhere('login', $insertData, array("userId" => $id));

			if ($result) {
				$this->session->set_flashdata("success", "Details are Updated");
				redirect(base_url('Main/profile'));
			} else {
				$this->session->set_flashdata("error", "Details are not Updated contact to IT");
				redirect(base_url('Main/profile'));
			}
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('userInfo');
		redirect("login");
	}
}
