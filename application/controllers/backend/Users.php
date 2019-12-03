<?php
class Users extends BE_Controller {

	public function __construct()
    {
        parent::__construct();   
		$this->load->model('user_model');
    } 
	
	public function index($page=0)
	{	
		$this->load->library('pagination');

		$config['base_url'] = $this->config->item("userCtrl")."/index";
		$config['total_rows'] = $this->user_model->getUsersCount();
		/* $config['per_page'] = ROWS_PER_PAGE; */
		$config['per_page'] = 10;
		
		$config['prev_tag_open'] = '<button type="button" class="btn btn-white"><i class="fa fa-chevron-left">';
		$config['prev_tag_close'] = '</i></button>';
		
		$config['next_tag_open'] = '<button type="button" class="btn btn-white"><i class="fa fa-chevron-right">';
		$config['next_tag_close'] = '</i></button>';
		
		$config['cur_tag_open'] = '<button type="button" class="btn btn-primary">';
		$config['cur_tag_close'] = '</button>';	
		
		$config['num_tag_open'] = '<button type="button" class="btn btn-white">';
		$config['num_tag_close'] = '</button>';
		$this->pagination->initialize($config);
		
		$viewArr = array();
		$users = $this->user_model->getUsers($page);
		
		$viewArr["users"] = $users;
		if(isset($_GET["pagination"]))
		{
			$this->load->view('backend/manage_users',$viewArr);
		}
		else
		{
			$viewArr["viewPage"] = "manage_users";
			$this->load->view('backend/layout',$viewArr);
		}
	}
	
	public function addUser()
	{	
		$viewArr = array();
		$viewArr["userData"] = array();
		$viewArr["postData"] = array();
		
		if($this->session->userdata("postData"))
		{
			$viewArr["postData"] = $this->session->userdata("postData");
			$this->session->unset_userdata("postData");
		}
		$html = $this->load->view('backend/add_user',$viewArr,TRUE);
		echo $html;
		exit;
	}
	
	public function editUser($userId)
	{	
		$userData = $this->user_model->getUserData($userId);
		if($userData)
		{
			$viewArr = array();
			$viewArr["postData"] = array();
			
			if($this->session->userdata("postData"))
			{
				$viewArr["postData"] = $this->session->userdata("postData");
				$this->session->unset_userdata("postData");
			}
			
			$viewArr["userData"] = $userData;
			$html = $this->load->view('backend/add_user',$viewArr,TRUE);
		}
		else
		{
			$html = "<h4>No User Data Found.</h4>";
		}
		echo $html;
		exit;
	}
	
	public function uploadImage()
	{
		require_once('simple_image.php');
		$imgAct= new Simple_image();
		
		if($this->input->post("userTempImageName") && trim($this->input->post("userTempImageName"))!="")
		{
			@unlink($this->config->item("userImageTempPath")."/".$this->input->post("userTempImageName"));
		}
		if($_FILES && isset($_FILES["photo"]) && isset($_FILES["photo"]["name"]) && trim($_FILES["photo"]["name"])!="")
		{
			list($nm, $ext) = explode(".",$_FILES['photo']['name']);
			if(in_array($ext,$this->config->item("userImgAllowedExt")) || in_array(strtolower($ext),$this->config->item("userImgAllowedExt")))
			{	
				$fileName = time().".".$ext; 
				$thumbName = time()."_thumb.".$ext; 
				
				$uploadRes = move_uploaded_file($_FILES["photo"]["tmp_name"],$this->config->item("userImageTempPath")."/".$fileName);
				if($uploadRes)
				{
					$thumbPh = $imgAct->createThumb($this->config->item("userImageTempPath"), $this->config->item("userImageTempPath"), "500", $fileName, $thumbName,true,0);
					if ($thumbPh)
					{ 
						@unlink($this->config->item("userImageTempPath")."/".$fileName);
					}
					$message[] = "<p style='color:green;'>File uploaded successfully.</p>";
					echo "<img src='".$this->config->item("userImageTempUrl")."/".$thumbName."' title='User Thumb Photo' alt='User Thumb Photo' class='img-thumbnail' style='max-width:25%;'/><br><input type='hidden' name='userTempImageName' id='userTempImageName' value='".$thumbName."'/>";
					exit;
				}
				else
				{
					$message[] = "<p style='color:red;'>Failed to upload the thumb file.</p>";
					echo "<p><strong>Failed to upload file.</strong></p><br><input type='hidden' name='userTempImageName' id='userTempImageName' value='".(($this->input->post("userTempImageName"))?$this->input->post("userTempImageName"):"")."'/>";
					exit;
				}
			}
			else
			{
				$message[] = "<p style='color:red;'>Invalid file,only image files with extensions:".implode(",",$this->config->item("userImgAllowedExt"))." are allowed.</p>";
				echo "<p><strong>Invalid file,only image files with extensions:".implode(",",$this->config->item("userImgAllowedExt"))." are allowed.</strong></p><br><input type='hidden' name='userTempImageName' id='userTempImageName' value='".(($this->input->post("userTempImageName"))?$this->input->post("userTempImageName"):"")."'/>";
				exit;
			}
		}
		else
		{
			$message[] = "<p style='color:red;'>No file detected by the server.</p>";
			echo "<p><strong>No file detected by the server.</strong></p><br><input type='hidden' name='userTempImageName' id='userTempImageName' value='".(($this->input->post("userTempImageName"))?$this->input->post("userTempImageName"):"")."'/>";
			exit;
		}
	}
	
	public function insertUser($userId=0)
	{
		$messageq = array();
		$pass = true;
		
		$this->form_validation->set_error_delimiters('<p style="color:red;">', '</p>');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email ID', 'trim|required|xss_clean');
		if($userId == 0)
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		}
		/* $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean'); */
		
		if ($this->form_validation->run() == FALSE)
		{
			$pass = false;
			if(form_error('first_name'))
			{
				$messageq['first_name'] = form_error('first_name');
			}
			if(form_error('last_name'))
			{
				$messageq['last_name'] = form_error('last_name');
			}
			if(form_error('email'))
			{
				$messageq['email'] = form_error('email');
			}
			if($userId == 0)
			{
				if(form_error('password'))
				{
					$messageq['password'] = form_error('password');
				}
			}
			/* if(form_error('phone'))
			{
				$messageq['phone'] = form_error('phone');
			}	 */
		}
		else
		{
			if($userId==0)
			{
				$res = $this->user_model->insertUser();
			}
			else
			{
				$res = $this->user_model->updateUser($userId);
			}
			
			if($res && !in_array(trim($res), array('email_exist', 'phone_exist')))
			{
				$messageq[] = "<p style='color:green;'>User saved successfully.</p>";
				
				if($this->input->post("userTempImageName") && trim($this->input->post("userTempImageName"))!="")
				{
					if(file_exists($this->config->item("userImageTempPath")."/".trim($this->input->post("userTempImageName"))))
					{
						$uploadRes = rename($this->config->item("userImageTempPath")."/".trim($this->input->post("userTempImageName")),$this->config->item("userImagePath")."/".trim($this->input->post("userTempImageName")));
						
						if($uploadRes)
						{
							$messageq[] = "<p style='color:green;'>Image uploaded and moved successfully.</p>";
							
							$imageUpdateRes = $this->user_model->updateUserImage((($userId==0)?$res:$userId), trim($this->input->post("userTempImageName")));
							
							if($imageUpdateRes)
							{
								$messageq[] = "<p style='color:green;'>Image name saved successfully.</p>";
							}
							else
							{
								$messageq[] = "<p style='color:red;'>Failed to save image name.</p>";
							}
						}
						else
						{
							$messageq[] = "<p style='color:red;'>Image upload failed.</p>";
						}
					}
					else
					{
						$messageq[] = "<p style='color:red;'>Image does not exists at temp path.</p>";
					}
				}
			$this->load->library('email');
			$from = 'Ala';
			$to = $this->input->post("email");
			$subject = 'Ala servey application - New Account';
			
			$message = '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta name="viewport" content="width=device-width" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Alerts e.g. approaching your limit</title><link href="styles.css" media="all" rel="stylesheet" type="text/css" /><style>
* {
    margin: 0;
    padding: 0;
    font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    box-sizing: border-box;
    font-size: 14px;
}
img {
    max-width: 100%;
}
body {
    -webkit-font-smoothing: antialiased;
    -webkit-text-size-adjust: none;
    width: 100% !important;
    height: 100%;
    line-height: 1.6;
}
table td {
    vertical-align: top;
}
body {
    background-color: #f6f6f6;
}

.body-wrap {
    background-color: #f6f6f6;
    width: 100%;
}
.container {
    display: block !important;
    max-width: 600px !important;
    margin: 0 auto !important;
    /* makes it centered */
    clear: both !important;
}
.content {
    max-width: 600px;
    margin: 0 auto;
    display: block;
    padding: 20px;
}
.main {
    background: #fff;
    border: 1px solid #e9e9e9;
    border-radius: 3px;
}

.content-wrap {
    padding: 20px;
}

.content-block {
    padding: 0 0 20px;
}

.header {
    width: 100%;
    margin-bottom: 20px;
}
.footer {
    width: 100%;
    clear: both;
    color: #999;
    padding: 20px;
}
.footer a {
    color: #999;
}
.footer p, .footer a, .footer unsubscribe, .footer td {
    font-size: 12px;
}
h1, h2, h3 {
    font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
    color: #000;
    margin: 40px 0 0;
    line-height: 1.2;
    font-weight: 400;
}
h1 {
    font-size: 32px;
    font-weight: 500;
}
h2 {
    font-size: 24px;
}
h3 {
    font-size: 18px;
}
h4 {
    font-size: 14px;
    font-weight: 600;
}
p, ul, ol {
    margin-bottom: 10px;
    font-weight: normal;
}
p li, ul li, ol li {
    margin-left: 5px;
    list-style-position: inside;
}
a {
    color: #1ab394;
    text-decoration: underline;
}
.btn-primary {
    text-decoration: none;
    color: #FFF;
    background-color: #1ab394;
    border: solid #1ab394;
    border-width: 5px 10px;
    line-height: 2;
    font-weight: bold;
    text-align: center;
    cursor: pointer;
    display: inline-block;
    border-radius: 5px;
    text-transform: capitalize;
}
.last {
    margin-bottom: 0;
}

.first {
    margin-top: 0;
}

.aligncenter {
    text-align: center;
}

.alignright {
    text-align: right;
}

.alignleft {
    text-align: left;
}

.clear {
    clear: both;
}
.alert {
    font-size: 16px;
    color: #fff;
    font-weight: 500;
    padding: 20px;
    text-align: center;
    border-radius: 3px 3px 0 0;
}
.alert a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
}
.alert.alert-warning {
    background: #f8ac59;
}
.alert.alert-bad {
    background: #ed5565;
}
.alert.alert-good {
    background: #1ab394;
}
.invoice {
    margin: 40px auto;
    text-align: left;
    width: 80%;
}
.invoice td {
    padding: 5px 0;
}
.invoice .invoice-items {
    width: 100%;
}
.invoice .invoice-items td {
    border-top: #eee 1px solid;
}
.invoice .invoice-items .total td {
    border-top: 2px solid #333;
    border-bottom: 2px solid #333;
    font-weight: 700;
}
@media only screen and (max-width: 640px) {
    h1, h2, h3, h4 {
        font-weight: 600 !important;
        margin: 20px 0 5px !important;
    }

    h1 {
        font-size: 22px !important;
    }

    h2 {
        font-size: 18px !important;
    }

    h3 {
        font-size: 16px !important;
    }

    .container {
        width: 100% !important;
    }

    .content, .content-wrap {
        padding: 10px !important;
    }

    .invoice {
        width: 100% !important;
    }
}</style></head><body><table class="body-wrap"><tr><td></td><td class="container" width="600"><div class="content"><table class="main" width="100%" cellpadding="0" cellspacing="0"><tr><td class="alert alert-good">Ala Servey Application</td></tr><tr><td class="content-wrap"><table width="100%" cellpadding="0" cellspacing="0"><tr><td class="content-block">Ala Servey new account email</td></tr><tr><td class="content-block">Your Ala servey account is created by admin please check below login credetials:<br>Username: '.$this->input->post("email").'<br>Password: '.$this->input->post("password").'</td></tr><tr><td class="content-block"><a href="'.base_url().'customer" class="btn-primary" style="color:#fff;">Go To Site</a></td></tr><tr><td class="content-block">Thanks</td></tr></table></td></tr></table><div class="footer"><table width="100%"><tr><td class="aligncenter content-block"></td></tr></table></div></div></td><td></td></tr></table></tbody></html>';
			$this->email->set_newline("\r\n");
			$this->email->set_mailtype("html");
			$this->email->from($from);
			$this->email->to($to);
			$this->email->subject($subject);
			$this->email->message($message);
				if($this->email->send()) {
					$messageq[] = "<p style='color:green;'>Email sent successfully.</p>";
				}else {
					$messageq[] = "<p style='color:red;'>Email not sent to user.</p>";
				}
			}
			elseif(trim($res)=="email_exist")
			{
				$pass = false;
				$messageq[] = "<p style='color:red;'>User with entered email already exists.</p>";
			}
			elseif(trim($res)=="phone_exist")
			{
				$pass = false;
				$messageq[] = "<p style='color:red;'>User with entered phone already exists.</p>";
			}
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$messageq,"userId"=>$userId));
		exit;
	}
	
	public function changeUserStatus($userId,$status)
	{
		$pass = false;
		$message = array();
		$res = $this->user_model->changeUserStatus($userId,$status);
		if($res)
		{
			$pass = true;
			$message[] = "<p style='color:green;'>User status changed successfully.</p>";
		}
		else
		{
			$message[] = "<p style='color:red;'>Failed to change user status.</p>";
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"userId"=>$userId));
		exit;
	}
	public function changeUserPermission()	{
		$pass = false;
		$userId = $_POST['id'];
		$permission = $_POST['permission'];
		$message = array();
		$res = $this->user_model->changeUserPermission($userId,$permission);
		if($res) {
			$pass = true;
			$message[] = "<p style='color:green;'>User permission changed successfully.</p>";
		}else {
			$message[] = "<p style='color:red;'>Failed to change user permission.</p>";
		}
		echo json_encode(array("success"=>$pass,"message"=>$message,"userId"=>$userId));
		exit;
	}
	public function deleteUser($userId)
	{
		$pass = false;
		$message = array();
		$userData = $this->user_model->getUserData($userId);
		if($userData)
		{
			$res = $this->user_model->deleteUser($userId);
			if($res)
			{
				$pass = true;
				$message[] = "<p style='color:green;'>User deleted successfully.</p>";	
			}
			else
			{
				$message[] = "<p style='color:red;'>Failed to delete User.</p>";
			}
		}
		else
		{
			$message[] = "<p style='color:red;'>User data not found.</p>";
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"userId"=>$userId));
		exit;
	}
	
	public function addActionAdmin($user_id)
	{	
		$viewArr = array();
		$viewArr["actionData"] = array();
		$viewArr["postData"] = array();
		$viewArr["postData"]["user_id"] = $user_id;
		$viewArr["actionTypeData"] = $this->user_model->getActionTypeData();
		$viewArr["lastPostMeeting"] = $this->user_model->getLastPostMeeting($user_id);
		$viewArr["goals"] = $this->user_model->getGoals($user_id);
		$html = $html = $this->load->view('backend/add_action',$viewArr,TRUE);
		echo $html;
		exit;
	}
	public function insertActionAdmin($userId=0)
	{
		$message = array();
		$pass = true;
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning"><p style="color:red;">', '</p></div>');
		$this->form_validation->set_rules('title', 'Action Title', 'trim|required|xss_clean');
		if($this->input->post('type') && trim($this->input->post('type'))==1)
		{
			$this->form_validation->set_rules('remDate', 'Goal Description', 'trim|required|xss_clean');
		}
		$this->form_validation->set_rules('remTime', 'Goal Description', 'trim|required|xss_clean');
	
		if ($this->form_validation->run() == FALSE)
		{
			$pass = false;
			if(form_error('title'))
			{
				$message[] = form_error('title');
			}
			if($this->input->post('type') && trim($this->input->post('type'))==1)
			{
				if(form_error('remDate'))
				{
					$message[] = form_error('remDate');
				}
			}
			if(form_error('remTime'))
			{
				$message[] = form_error('remTime');
			}
		}
		if(!$this->input->post('goals') || !is_array($this->input->post('goals')) || (is_array($this->input->post('goals')) && count($this->input->post('goals'))==0))
		{
			$pass = false;
			$message[] = '<div class="alert alert-warning"><p style="color:red;">Invalid Goals. Please select correct goals.</p></div>';
		}
		
		if($pass)
		{
			/* if($actionId==0)
			{ */
				$res = $this->user_model->insertAction($userId);
			/* }
			else
			{
				$res = $this->user_model->updateAction($actionId);
			} */
			
			if($res && trim($res)!="exist")
			{
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Action saved successfully.</p></div>";
			}
			elseif($res && trim($res)=="exist")
			{
				$pass = false;
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Action with entered title already exists.</p></div>";
			}
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"userId"=>$userId));
		exit;
	}

}