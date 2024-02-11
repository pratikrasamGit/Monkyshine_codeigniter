<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->bdp = $this->db->dbprefix;
		$this->return_data = array('status' => false, 'msg' => 'Error', 'login' => false, 'data' => array());
	}

	public function index()
	{
		//echo 'hello'.$this->session->userdata('rudra_admin_name');exit;
		if ($this->session->userdata('rudra_admin_logged_in')) {
			//echo 'hello'.$this->session->userdata('rudra_admin_name');exit;
			$data['pageTitle'] = "Admin Dashboard";
			$data['page_template'] = "empty";
			
			$this->load->view('crm/template', $data);
		} else {
			//echo 'hello'.$this->session->userdata('rudra_admin_name');exit;
			redirect(site_url('admin-login'), 'refresh');
		}
	}


	public function login()
	{
		$data['page_title'] = " CRM Login";
		$this->load->view('crm/login', $data);
	}

	public function check_login_admin()
	{
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		} else {
			
			$username = $this->input->post('username', true);
			$password = SHA1($this->input->post('password', true));
			$check = $this->db->get_where($this->bdp . 'admin', array('username' => $username, 'password' => $password))->row();
			//echo $this->db->last_query().'<br>'.md5(123456);
			//print_r($check);exit;
			if(!empty($check))
			{
				if ($check->status == 1) {
					$adminlogin = array(
						'rudra_admin_status'            => $check->status,
						'rudra_admin_id'            => $check->id,
						'rudra_admin_name'          => $check->name,
						'rudra_admin_username'          => $check->username,
						'rudra_admin_logged_in'         => TRUE,
						'rudra_admin_utype'         => 1,


					);
					$return = 'admin';
					$post = ($_POST['req_uri'] != '' ? ($_POST['req_uri'] == 'admin' ? $return : $_POST['req_uri']) : $return);
					$return_url = $post;
					$this->session->set_userdata($adminlogin);
					$this->return_data['msg'] = "Login Successfull, redirecting...";
					$this->return_data['login'] = true;
					$this->return_data['status'] = true;
					$this->return_data['data'] = array('url' => base_url("$return_url"));
				} 
				if ($check->status == 0) {
					$this->return_data['msg'] = "Account deactivated, Please contact to admin.";
				}
			} else {
				$this->return_data['msg'] = "Wrong credentials, Please try again";
			}

			echo json_encode($this->return_data);
			exit;
		}
	}
	public function logout()
	{
		session_destroy();
		redirect(base_url('crm/admin'), 'refresh');
	}


	
}
