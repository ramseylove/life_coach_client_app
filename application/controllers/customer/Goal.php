<?php
class Goal extends CE_Controller {

	public function __construct()
    {		
        parent::__construct();   
		$this->load->model('customer/goal_model');
    } 
	
	public function index()
	{	
		$viewArr = array();
		$goals = $this->goal_model->getGoals();
		
		$viewArr["goals"] = $goals;
		$viewArr["viewPage"] = "manage_goals";
		$this->load->view('customer/layout',$viewArr);
	}
	
	public function addGoal()
	{	
		$viewArr = array();
		$viewArr["goalData"] = array();
		$viewArr["postData"] = array();
	
		if($this->session->userdata("postData"))
		{
			$viewArr["postData"] = $this->session->userdata("postData");
			$this->session->unset_userdata("postData");
		}
		$viewArr["viewPage"] = "add_goal";
		$this->load->view('customer/layout',$viewArr);
	}
	
	public function editGoal($goalId)
	{	
		$goalData = $this->goal_model->getGoalData($goalId);
		if($goalData)
		{
			$viewArr = array();
			$viewArr["postData"] = array();
		
			if($this->session->userdata("postData"))
			{
				$viewArr["postData"] = $this->session->userdata("postData");
				$this->session->unset_userdata("postData");
			}
			
			$viewArr["goalData"] = $goalData;
			$viewArr["viewPage"] = "add_goal";
			$this->load->view('customer/layout',$viewArr);
		}
		else
		{
			redirect($this->config->item("goalCtrl"), 'refresh');
		}
	}
	
	public function insertGoal($goalId=0)
	{
		$message = array();
		$pass = true;
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning"><p style="color:red;">', '</p></div>');
		$this->form_validation->set_rules('name', 'Member name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('designation', 'Member designation', 'trim|required|xss_clean');
		$this->form_validation->set_rules('birthdate', 'Member birthdate', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Member email', 'trim|required|xss_clean|valid_email');
		$this->form_validation->set_rules('phone', 'Member phone', 'trim|required|xss_clean');
		$this->form_validation->set_rules('address', 'Member address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('biodata', 'Member biodata', 'trim|required|xss_clean');
		$this->form_validation->set_rules('workInfo', 'Member workInfo', 'trim|required|xss_clean');
	
		if ($this->form_validation->run() == FALSE)
		{
			$pass = false;
			if(form_error('name'))
			{
				$message[] = form_error('name');
			}
			if(form_error('designation'))
			{
				$message[] = form_error('designation');
			}
			if(form_error('birthdate'))
			{
				$message[] = form_error('birthdate');
			}
			if(form_error('email'))
			{
				$message[] = form_error('email');
			}
			if(form_error('phone'))
			{
				$message[] = form_error('phone');
			}
			if(form_error('address'))
			{
				$message[] = form_error('address');
			}
			if(form_error('biodata'))
			{
				$message[] = form_error('biodata');
			}
			if(form_error('workInfo'))
			{
				$message[] = form_error('workInfo');
			}
		}
		else
		{
			if($goalId==0)
			{
				$res = $this->goal_model->insertStaff();
			}
			else
			{
				$res = $this->goal_model->updateStaff($goalId);
			}
			
			if($res && trim($res)!="exist")
			{
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Staff saved successfully.</p></div>";
			}
			elseif($res && trim($res)=="exist")
			{
				$pass = false;
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Staff with entered email already exists.</p></div>";
			}
		}
		
		$this->session->set_flashdata('message', $message);
		if($pass)
		{
			if($goalId>0)
			{
				$this->session->set_flashdata('staff_id', $goalId);
			}
			redirect($this->config->item("staffCtrl"), 'refresh');
		}
		else
		{
			$this->session->set_userdata("postData",$_POST);
			if($goalId>0)
			{
				redirect($this->config->item("editStaff")."/".$goalId, 'refresh');
			}
			else
			{
				redirect($this->config->item("addStaff"), 'refresh');
			}
		}
	}
	
	public function deleteStaff($goalId,$nxtStaffId)
	{
		$message = array();
		$staffData = $this->goal_model->getStaffData($goalId);
		if($staffData)
		{
			$res = $this->goal_model->deleteStaff($goalId);
			if($res)
			{
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Staff deleted successfully.</p></div>";	
			}
			else
			{
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Failed to delete staff.</p></div>";
			}
		}
		else
		{
			$message[] = "<div class='alert alert-warning'><p style='color:red;'>Staff data not found.</p></div>";
		}
		
		$this->session->set_flashdata('message', $message);
		$this->session->set_flashdata('staff_id', $nxtStaffId);
		redirect($this->config->item("staffCtrl"), 'refresh'); 
	}
	
	public function uploadStaffImages()
	{
		$fileName = trim($_POST["fileName"]);
		$attr = trim($_POST["attr"]);
		$pass = false;
		$NewFileName = "";
		if($_FILES && isset($_FILES[$fileName]) && isset($_FILES[$fileName]["name"]) && trim($_FILES[$fileName]["name"])!="")
		{
			list($nm, $ext) = explode(".",$_FILES[$fileName]['name']);
			if(in_array($ext,$this->config->item("staffImgAllowedExt")) || in_array(strtolower($ext),$this->config->item("staffImgAllowedExt")))
			{				
				$NewFileName = time().mt_rand(999,99999)."_".$attr.".".$ext;
				$fileSize = $_FILES[$fileName]['size'];
				if($fileSize > 10000)
				{
		            $uploadRes = $this->compressImage($_FILES[$fileName]["tmp_name"], $this->config->item("staffImageTempPath")."/".$NewFileName, 90);
		        }
		        else
				{
					$uploadRes = move_uploaded_file($_FILES[$fileName]["tmp_name"],$this->config->item("staffImageTempPath")."/".$NewFileName);
				}
				if($uploadRes)
				{
					$pass = true;
					$message[] = "File uploaded successfully.";
				}
				else
				{
					$message[] = "Failed to upload the image file.";
				}
			}
			else
			{
				$message[] = "Invalid image file,only image files with extensions:".implode(",",$this->config->item("staffImgAllowedExt"))." are allowed.";
			}
		}
		else
		{
			$message[] = "No file detected by the server.";
		}
		
		echo json_encode(array('success'=>$pass,'message'=>$message,'NewFileName'=>$NewFileName));
		exit;
	}
	
	public function compressImage($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
	}
}