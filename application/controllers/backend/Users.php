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
		$config['per_page'] = ROWS_PER_PAGE;
		
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
		
		$viewArr["surveyPages"] = $this->user_model->getSurveyPages();
		
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
			$viewArr["surveyPages"] = $this->user_model->getSurveyPages();

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
		$message = array();
		$pass = true;
		
		$this->form_validation->set_error_delimiters('<p style="color:red;">', '</p>');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email ID', 'trim|required|xss_clean');
		if($userId == 0)
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		}
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			$pass = false;
			if(form_error('first_name'))
			{
				$message['first_name'] = form_error('first_name');
			}
			if(form_error('last_name'))
			{
				$message['last_name'] = form_error('last_name');
			}
			if(form_error('email'))
			{
				$message['email'] = form_error('email');
			}
			if($userId == 0)
			{
				if(form_error('password'))
				{
					$message['password'] = form_error('password');
				}
			}
			if(form_error('phone'))
			{
				$message['phone'] = form_error('phone');
			}	
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
				$message[] = "<p style='color:green;'>User saved successfully.</p>";
				if($this->input->post('surveyPages') && is_array($this->input->post('surveyPages')))
				{
					$saveRes = $this->user_model->saveSurveyPagesForUser((($userId==0)?$res:$userId));
					if($saveRes)
					{
						$message[] = "<p style='color:green;'>Survey Pages saved successfully.</p>";
					}
					else
					{
						$message[] = "<p style='color:red;'>Failed to save survey pages.</p>";
					}
				}
				
				if($this->input->post("userTempImageName") && trim($this->input->post("userTempImageName"))!="")
				{
					if(file_exists($this->config->item("userImageTempPath")."/".trim($this->input->post("userTempImageName"))))
					{
						$uploadRes = rename($this->config->item("userImageTempPath")."/".trim($this->input->post("userTempImageName")),$this->config->item("userImagePath")."/".trim($this->input->post("userTempImageName")));
						
						if($uploadRes)
						{
							$message[] = "<p style='color:green;'>Image uploaded and moved successfully.</p>";
							
							$imageUpdateRes = $this->user_model->updateUserImage((($userId==0)?$res:$userId), trim($this->input->post("userTempImageName")));
							
							if($imageUpdateRes)
							{
								$message[] = "<p style='color:green;'>Image name saved successfully.</p>";
							}
							else
							{
								$message[] = "<p style='color:red;'>Failed to save image name.</p>";
							}
						}
						else
						{
							$message[] = "<p style='color:red;'>Image upload failed.</p>";
						}
					}
					else
					{
						$message[] = "<p style='color:red;'>Image does not exists at temp path.</p>";
					}
				}
			}
			elseif(trim($res)=="email_exist")
			{
				$pass = false;
				$message[] = "<p style='color:red;'>User with entered email already exists.</p>";
			}
			elseif(trim($res)=="phone_exist")
			{
				$pass = false;
				$message[] = "<p style='color:red;'>User with entered phone already exists.</p>";
			}
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"userId"=>$userId));
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
	
	public function deleteUser($userId)
	{
		$pass = false;
		$message = array();
		$userData = $this->user_model->getUserData($userId);
		if($userData)
		{
			if(@unlink($this->config->item("userImagePath")."/".$userData->photo))
			{
				$message[] = "<p style='color:green;'>User thumb image deleted successfully.</p>";
			}
			else
			{
				$message[] = "<p style='color:red;'>Failed to delete User thumb image.</p>";
			}
			
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
	
}