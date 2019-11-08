<?php
class Action extends CE_Controller {

	public function __construct()
    {		
        parent::__construct();   
		$this->load->model('customer/action_model');
    } 
	
	public function index($page=0)
	{	
		$this->load->library('pagination');

		$config['base_url'] = $this->config->item("goalCtrl")."/index";
		$config['total_rows'] = $this->action_model->getActionsCount();
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
		$actions = $this->action_model->getDashboardActions($page);
		
		$viewArr["actions"] = $actions;
		
		if(isset($_GET["pagination"]))
		{
			$this->load->view('customer/manage_actions',$viewArr);
		}
		else
		{
			$viewArr["viewPage"] = "manage_actions";
			$this->load->view('customer/layout',$viewArr);
		}
	}
	
	public function allActions($page=0)
	{	
		$this->load->library('pagination');

		$config['base_url'] = $this->config->item("goalCtrl")."/index";
		$config['total_rows'] = $this->action_model->getActionsCount();
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
		$actions = $this->action_model->getActions($page);
		
		$viewArr["actions"] = $actions;
		
		if(isset($_GET["pagination"]))
		{
			$this->load->view('customer/manage_allactions',$viewArr);
		}
		else
		{
			$viewArr["viewPage"] = "manage_allactions";
			$this->load->view('customer/layout',$viewArr);
		}
	}
	
	public function addAction()
	{	
		$viewArr = array();
		$viewArr["actionData"] = array();
		$viewArr["postData"] = array();
		$viewArr["actionTypeData"] = $this->action_model->getActionTypeData();
		$viewArr["lastPostMeeting"] = $this->action_model->getLastPostMeeting();
		$viewArr["goals"] = $this->action_model->getGoals();
		if($_GET && isset($_GET['postMeetingId']))
		{
			$viewArr["postMeetingId"] =	trim($_GET['postMeetingId']);
		}
		
		if($this->session->userdata("postData"))
		{
			$viewArr["postData"] = $this->session->userdata("postData");
			$this->session->unset_userdata("postData");
		}
	
		$html = $this->load->view('customer/add_action',$viewArr,TRUE);
		echo $html;
		exit;
	}
	
	public function completeAction($actionId)
	{	
		$actionData = $this->action_model->getActionData($actionId);
		if($actionData)
		{
			$viewArr = array();
			$viewArr["actionData"] = $actionData;
			$viewArr["postData"] = array();
			
			if($this->session->userdata("postData"))
			{
				$viewArr["postData"] = $this->session->userdata("postData");
				$this->session->unset_userdata("postData");
			}
			$viewArr['questions'] = $this->action_model->getQuestions($actionId);
			$html = $this->load->view('customer/complete_action',$viewArr,TRUE);
		}
		else
		{
			$html = "<h4>No Action Data Found.</h4>";
		}
		echo $html;
		exit;
	}
	
	public function editAction($actionId)
	{	
		$actionData = $this->action_model->getActionData($actionId);
		if($actionData)
		{
			$viewArr = array();
			$viewArr["postData"] = array();
		
			if($this->session->userdata("postData"))
			{
				$viewArr["postData"] = $this->session->userdata("postData");
				$this->session->unset_userdata("postData");
			}
			
			$viewArr["actionData"] = $actionData;
			$viewArr["actionTypeData"] = $this->action_model->getActionTypeData();
			$viewArr["goals"] = $this->action_model->getGoals();
			$viewArr["questions"] = $this->action_model->getQuestions($actionId);
			$html = $this->load->view('customer/add_action',$viewArr,TRUE);
		}
		else
		{
			$html = "<h4>No Action Data Found.</h4>";
		}
		echo $html;
		exit;
	}
	
	public function addActionToNextWeek($actionId)
	{	
		$actionData = $this->action_model->getActionData($actionId);
		if($actionData)
		{
			$viewArr = array();
			$viewArr["postData"] = array();
		
			if($this->session->userdata("postData"))
			{
				$viewArr["postData"] = $this->session->userdata("postData");
				$this->session->unset_userdata("postData");
			}
			
			$viewArr["actionData"] = $actionData;
			$viewArr["actionTypeData"] = $this->action_model->getActionTypeData();
			$viewArr["goals"] = $this->action_model->getGoals();
			$viewArr["questions"] = $this->action_model->getQuestions($actionId); 
			$html = $this->load->view('customer/add_actionto_next_week',$viewArr,TRUE);
		}
		else
		{
			$html = "<h4>No Action Data Found.</h4>";
		}
		echo $html;
		exit;
	}
	
	public function insertActionToNextWeek($actionId=0)
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
		foreach($_POST['question'] as $question) {
			if($question == '') {
				$pass = false;
				$message[] = '<div class="alert alert-warning"><p style="color:red;">Please add all questions text. Questions should not be empty.</p></div>';
				break;
			}
		}
		if($pass)
		{
			if($actionId==0)
			{
				$pass = false;
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Action id not found for insert.</p></div>";
			}
			else
			{
				$res = $this->action_model->insertActionToNextWeek($actionId);
			}
			
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
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"actionId"=>$actionId));
		exit;
	}
	
	public function insertCompleteAction($actionId=0)
	{
		$message = array();
		$pass = true;
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning"><p style="color:red;">', '</p></div>');
		/* $this->form_validation->set_rules('action_complete_0', 'Answer-1', 'trim|required|xss_clean');
		$this->form_validation->set_rules('action_complete_1', 'Answer-2', 'trim|required|xss_clean');
		$this->form_validation->set_rules('action_complete_2', 'Answer-3', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() == FALSE)
		{
			$pass = false;
			if(form_error('action_complete_0'))
			{
				$message[] = form_error('action_complete_0');
			}
			if(form_error('action_complete_1'))
			{
				$message[] = form_error('action_complete_1');
			}
			if(form_error('action_complete_2'))
			{
				$message[] = form_error('action_complete_2');
			}
		} */
		foreach($_POST as $question) {
			if($question == '') {
				$pass = false;
				$message[] = '<div class="alert alert-warning"><p style="color:red;">Please add all answers text. Answers should not be empty.</p></div>';
				break;
			}
		}
		if($pass)
		{
			$res = $this->action_model->insertCompleteAction($actionId);
			if($res)
			{
				$this->action_model->updateActionAsComplete($actionId);
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Action Completed successfully.</p></div>";
			}
			else
			{
				$pass = false;
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Failed to complete this action. Please try again.</p></div>";
			}
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"actionId"=>$actionId));
		exit;
	}	
	
	public function insertAction($actionId=0)
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
		foreach($_POST['question'] as $question) {
			if($question == '') {
				$pass = false;
				$message[] = '<div class="alert alert-warning"><p style="color:red;">Please add all questions text. Questions should not be empty.</p></div>';
				break;
			}
		}
		if($pass)
		{
			if($actionId==0)
			{
				$res = $this->action_model->insertAction();
			}
			else
			{
				$res = $this->action_model->updateAction($actionId);
			}
			
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
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"actionId"=>$actionId));
		exit;
	}
	
	public function deleteAction($actionId)
	{
		$pass = false;
		$message = array();
		$actionData = $this->action_model->getActionData($actionId);
		if($actionData)
		{
			$res = $this->action_model->deleteAction($actionId);
			if($res)
			{
				$pass = true;
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Action deleted successfully.</p></div>";	
			}
			else
			{
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Failed to delete action.</p></div>";
			}
		}
		else
		{
			$message[] = "<div class='alert alert-warning'><p style='color:red;'>Action data not found.</p></div>";
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"actionId"=>$actionId));
		exit;
	}
}