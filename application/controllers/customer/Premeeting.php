<?php
class Premeeting extends CE_Controller {

	public function __construct()
    {		
        parent::__construct();   
		$this->load->model('customer/premeeting_model');
    } 
	
	public function index($page=0)
	{	
		$this->load->library('pagination');

		$config['base_url'] = $this->config->item("preMeetingCtrl")."/index";
		$config['total_rows'] = $this->premeeting_model->getPreMeetingsCount();
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
		$preMeetings = $this->premeeting_model->getPreMeetings($page);
		$viewArr["lastPreMeeting"] = $this->premeeting_model->getLastPreMeeting();
		$viewArr["lastPostMeeting"] = $this->premeeting_model->getLastPostMeeting();
		$viewArr["preMeetings"] = $preMeetings;
		
		$viewArr["weekTags"] = $this->premeeting_model->getWeekTags();
		
		if(isset($_GET["pagination"]))
		{
			$this->load->view('customer/manage_pre_meetings',$viewArr);
		}
		else
		{
			$viewArr["viewPage"] = "manage_pre_meetings";
			$this->load->view('customer/layout',$viewArr);
		}
	} 
	
	public function addPreMeeting()
	{	
		$viewArr = array();
		$viewArr["preMeetingData"] = array();
		$viewArr["postData"] = array();
		$viewArr["lastPreMeeting"] = $this->premeeting_model->getLastPreMeeting();
		if(!empty($viewArr["lastPreMeeting"])) {
			$viewArr["lastPreMeetingActions"] = $this->premeeting_model->getLastPreMeetingActions($viewArr["lastPreMeeting"]->weekno);
		}else {
			$viewArr["lastPreMeetingActions"] = '';
		}
		$viewArr["actionsWithoutPostMeetings"] = $this->premeeting_model->getActionsWithoutPostMeetings();
		$preadd = array();
		foreach($viewArr["actionsWithoutPostMeetings"] as $key => $preadded) {
			if($preadded->parent_action != '') {
				$preadd[$key] = $preadded->parent_action;
			}
		}
		$viewArr["preaddedAction"] = $preadd;
		
		$viewArr["weekTags"] = $this->premeeting_model->getWeekTags();
		if($this->session->userdata("postData"))
		{
			$viewArr["postData"] = $this->session->userdata("postData");
			$this->session->unset_userdata("postData");
		}
		
		if(isset($_GET["pagination"]))
		{
			$this->load->view('customer/add_pre_meeting',$viewArr);
		}
		else
		{
			$viewArr["viewPage"] = "add_pre_meeting";
			$this->load->view('customer/layout',$viewArr);
		}
	}
	
	public function editPreMeeting($preMeetingId)
	{	
		$preMeetingData = $this->premeeting_model->getPreMeetingData($preMeetingId);
		if($preMeetingData)
		{
			$viewArr = array();
			$viewArr["postData"] = array();
			$viewArr["weekTags"] = $this->premeeting_model->getWeekTags();
			if($this->session->userdata("postData"))
			{
				$viewArr["postData"] = $this->session->userdata("postData");
				$this->session->unset_userdata("postData");
			}
			
			$viewArr["preMeetingData"] = $preMeetingData;
			if(isset($_GET["pagination"]))
			{
				$this->load->view('customer/add_pre_meeting',$viewArr);
			}
			else
			{
				$viewArr["viewPage"] = "add_pre_meeting";
				$this->load->view('customer/layout',$viewArr);
			}
		}
		else
		{
			redirect($this->config->item("preMeetingCtrl"), 'refresh');
		}
	}
	
	public function insertPreMeeting($preMeetingId=0)
	{
		$message = array();
		$pass = true;
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning"><p style="color:red;">', '</p></div>');
		$this->form_validation->set_rules('acknowledgment', 'Acknowledgment', 'trim|required|xss_clean');
		$this->form_validation->set_rules('obstacles', 'Obstacles', 'trim|required|xss_clean');
		$this->form_validation->set_rules('weekno', 'weekno', 'trim|required|xss_clean');
	
		if ($this->form_validation->run() == FALSE)
		{
			$pass = false;
			if(form_error('acknowledgment'))
			{
				$message[] = form_error('acknowledgment');
			}
			if(form_error('obstacles'))
			{
				$message[] = form_error('obstacles');
			}
			if(form_error('weekno'))
			{
				$message[] = form_error('weekno');
			}
		}
		else
		{
			if($preMeetingId==0)
			{
				$res = $this->premeeting_model->insertPreMeeting();
			}
			else
			{
				$res = $this->premeeting_model->updatePreMeeting($preMeetingId);
			}
			
			if($res && trim($res)!="exist")
			{
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Pre Meeting saved successfully.</p></div>";
			}
			elseif($res && trim($res)=="exist")
			{
				$pass = false;
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Pre Meeting with entered topic already exists.</p></div>";
			}
		}
	
		$this->session->set_flashdata('message', $message);
		if($pass)
		{
			if($preMeetingId>0)
			{
				$this->session->set_flashdata('pre_meeting_id', $preMeetingId);
			}
			redirect($this->config->item("preMeetingCtrl"), 'refresh');
		}
		else
		{
			$this->session->set_userdata("postData",$_POST);
			if($preMeetingId>0)
			{
				redirect($this->config->item("preMeetingCtrl")."/".$preMeetingId, 'refresh');
			}
			else
			{
				redirect($this->config->item("preMeetingCtrl"), 'refresh');
			}
		}
	}
	
	public function deletePreMeeting($preMeetingId)
	{
		$pass = false;
		$message = array();
		$preMeetingData = $this->premeeting_model->getPreMeetingData($preMeetingId);
		if($preMeetingData)
		{
			$res = $this->premeeting_model->deletePreMeeting($preMeetingId);
			if($res)
			{
				$pass = true;
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Pre Meeting deleted successfully.</p></div>";	
			}
			else
			{
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Failed to delete pre meeting.</p></div>";
			}
		}
		else
		{
			$message[] = "<div class='alert alert-warning'><p style='color:red;'>Pre Meeting data not found.</p></div>";
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"preMeetingId"=>$preMeetingId));
		exit;
	}
}