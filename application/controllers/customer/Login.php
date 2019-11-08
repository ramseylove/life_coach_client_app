<?php
class Login extends MY_Controller {

	public function __construct()
    {
        parent::__construct();   
		$this->load->model('customer/login_model');
    } 
	
	public function index()
	{	
		if($this->session->userdata("user_id"))
		{ 
			redirect($this->config->item("actionCtrl"), 'refresh');
		}
		$viewArr = array();
		$viewArr["viewPage"] = "login";
		$this->load->view('customer/layout',$viewArr);
	}
	
	public function checkLoginSession()
	{
		$pass = false;
		if($this->session->userdata("user_id"))
		{
			 $pass = true;
		}
		echo json_encode(array("success"=>$pass));
		exit;
	}
	
	public function loginCheck(){
			if($this->session->userdata("user_id"))
			{ 
				 redirect($this->config->item("actionCtrl"), 'refresh');
			}
			$viewArr = array();
			
			$this->form_validation->set_error_delimiters('<div class="alert alert-danger"><center><b style="color:#333333;">', '</b></center></div>');
			$this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean');
			$this->form_validation->set_rules('pswd', 'Password', 'trim|required|xss_clean');
			
			if ($this->form_validation->run() == FALSE){
				$viewArr["viewPage"] = "login";
				$this->load->view('customer/layout',$viewArr);
			}else{	
				$result = $this->login_model->loginCheck();
				if(!$result){
					$error_message = '<div class="alert alert-danger"><center><b style="color:#333333;">invalid username or password</b></center></div>';
					$viewArr["error_message"] = $error_message;	
					$viewArr["viewPage"] = "login";
					$this->load->view('customer/layout',$viewArr);				
				}else if($result->status == 1){
					$error_message = '<div class="alert alert-danger"><center><b style="color:#333333;">Your account is disabled, please contact admin</b></center></div>';
					$viewArr["error_message"] = $error_message;	
					$viewArr["viewPage"] = "login";
					$this->load->view('customer/layout',$viewArr);				
				}else {
					
					$sessArr= array(
						'user_id'  => $result->id,
						'fname'  => $result->first_name,
						'lname'  => $result->last_name,
						'email'   => $result->email,
						'phone'   => $result->phone,
						'editpermission'   => $result->editpermission,
						'adminLogin'   => 0,
					 );						
					 $this->session->set_userdata($sessArr);
					 redirect($this->config->item("actionCtrl").'?first=true', 'refresh');			
				}
		}
	}
	
	public function loginCheckAdmin($userid){
		unset($_SESSION['user_id']);
		unset($_SESSION['fname']);
		unset($_SESSION['lname']);
		unset($_SESSION['email']);
		unset($_SESSION['phone']);
		unset($_SESSION['adminLogin']);
		$result = $this->login_model->loginCheckAdmin($userid);
		if(!$result){
			$error_message = '<div class="alert alert-danger"><center><b style="color:#333333;">User not found with this id</b></center></div>';
			$viewArr["error_message"] = $error_message;	
			$viewArr["viewPage"] = "login";
			$this->load->view('customer/layout',$viewArr);	
		}else {
			$sessArr= array(
				'user_id'  => $result->id,
				'fname'  => $result->first_name,
				'lname'  => $result->last_name,
				'email'   => $result->email,
				'phone'   => $result->phone,
				'editpermission'   => $result->editpermission,
				'adminLogin'   => 1,
			 );						
			 $this->session->set_userdata($sessArr);
			 redirect($this->config->item("actionCtrl").'?first=true', 'refresh');
		}
	}
	
	public function changePswd()
	{
		if(!$this->session->userdata("user_id"))
		{
			$this->logout();
		}
		$viewArr = array();
		$viewArr["viewPage"] = "change_pswd";
		$this->load->view('backend/layout',$viewArr);
	}
	
	public function finalChangePswd()
	{
		if(!$this->session->userdata("user_id"))
		{
			$this->logout();
		}
		
		$message = array();
		$validation = true;
		$pass = true;
		
		if(trim($this->input->post("email"))=="")
		{
			$validation = false;
			$message[] = "<p style='color:red;'>Invalid Email.</p>";
		}
		if(trim($this->input->post("oldPswd"))=="")
		{
			$validation = false;
			$message[] = "<p style='color:red;'>Invalid old password.</p>";
		}
		if(trim($this->input->post("newPswd"))=="")
		{
			$validation = false;
			$message[] = "<p style='color:red;'>Invalid new password.</p>";
		}
		if(trim($this->input->post("confPswd"))=="")
		{
			$validation = false;
			$message[] = "<p style='color:red;'>Invalid confirm password field value.</p>";
		}
		if(trim($this->input->post("newPswd"))!=trim($this->input->post("confPswd")))
		{
			$validation = false;
			$message[] = "<p style='color:red;'>New & confirm password values do not match each other.</p>";
		}
		
		if($validation)
		{
			$checkAdmin = $this->login_model->getAdmin();
			if(!$checkAdmin)
			{
				$pass = false;
				$message[] = "<p style='color:red;'>You have entered invalid email & old password combination.</p>";
			}
			else
			{
				$result = $this->login_model->finalChangePswd($checkAdmin->id);
				if($result)
				{
					$message[] = "<p style='color:green;'>Your password updated successfully.</p>";
				}
				else
				{
					$pass = false;
					$message[] = "<p style='color:red;'>Failed to update your password.</p>";
				}
			}
		}
		else
		{
			$pass = false;
		}
		
		$this->session->set_flashdata('message', $message);
		if($pass)
		{
			redirect($this->config->item("dashCtrlCustomer"));
		}
		else
		{
			redirect($this->config->item("changePswd"));
		}
	}
	
	public function logout()
	{
		unset($_SESSION['user_id']);
		unset($_SESSION['fname']);
		unset($_SESSION['lname']);
		unset($_SESSION['email']);
		unset($_SESSION['phone']);
		unset($_SESSION['adminLogin']);
		/* $this->session->sess_destroy(); */		
		redirect($this->config->item("loginCtrlCustomer"), 'refresh');
	}
}