<?php
class Goal extends CE_Controller {

	public function __construct()
    {		
        parent::__construct();   
		$this->load->model('customer/goal_model');
    } 
	
	public function index($page=0)
	{	
		$this->load->library('pagination');

		$config['base_url'] = $this->config->item("goalCtrl")."/index";
		$config['total_rows'] = $this->goal_model->getGoalsCount();
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
		$goals = $this->goal_model->getGoals();
		
		$viewArr["goals"] = $goals;
		
		if(isset($_GET["pagination"]))
		{
			$this->load->view('customer/manage_goals',$viewArr);
		}
		else
		{
			$viewArr["viewPage"] = "manage_goals";
			$this->load->view('customer/layout',$viewArr);
		}
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
	
		$html = $this->load->view('customer/add_goal',$viewArr,TRUE);
		echo $html;
		exit;
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
			$html = $this->load->view('customer/add_goal',$viewArr,TRUE);
		}
		else
		{
			$html = "<h4>No Service Data Found.</h4>";
		}
		echo $html;
		exit;
	}
	
	public function insertGoal($goalId=0)
	{
		$message = array();
		$pass = true;
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning"><p style="color:red;">', '</p></div>');
		$this->form_validation->set_rules('title', 'Goal Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', 'Goal Description', 'trim|required|xss_clean');
	
		if ($this->form_validation->run() == FALSE)
		{
			$pass = false;
			if(form_error('title'))
			{
				$message[] = form_error('title');
			}
			if(form_error('description'))
			{
				$message[] = form_error('description');
			}
		}
		else
		{
			if($goalId==0)
			{
				$res = $this->goal_model->insertGoal();
			}
			else
			{
				$res = $this->goal_model->updateGoal($goalId);
			}
			
			if($res && trim($res)!="exist")
			{
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Goal saved successfully.</p></div>";
			}
			elseif($res && trim($res)=="exist")
			{
				$pass = false;
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Goal with entered title already exists.</p></div>";
			}
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"goalId"=>$goalId));
		exit;
	}
	
	public function deleteGoal($goalId)
	{
		$pass = false;
		$message = array();
		$goalData = $this->goal_model->getGoalData($goalId);
		if($goalData)
		{
			$res = $this->goal_model->deleteGoal($goalId);
			if($res)
			{
				$pass = true;
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Goal deleted successfully.</p></div>";	
			}
			else
			{
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Failed to delete goal.</p></div>";
			}
		}
		else
		{
			$message[] = "<div class='alert alert-warning'><p style='color:red;'>Goal data not found.</p></div>";
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"goalId"=>$goalId));
		exit;
	}
}