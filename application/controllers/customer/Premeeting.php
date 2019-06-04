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
		
		$viewArr["preMeetings"] = $preMeetings;
		
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
	
	public function editPostMeeting($postMeetingId)
	{	
		$postMeetingData = $this->premeeting_model->getPostMeetingData($postMeetingId);
		if($postMeetingData)
		{
			$viewArr = array();
			$viewArr["postData"] = array();
		
			if($this->session->userdata("postData"))
			{
				$viewArr["postData"] = $this->session->userdata("postData");
				$this->session->unset_userdata("postData");
			}
			
			$viewArr["postMeetingData"] = $postMeetingData;
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
			redirect($this->config->item("postMeetingCtrl"), 'refresh');
		}
	}
	
	public function insertPostMeeting($postMeetingId=0)
	{
		$message = array();
		$pass = true;
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning"><p style="color:red;">', '</p></div>');
		$this->form_validation->set_rules('general_topic', 'General Topic', 'trim|required|xss_clean');
		$this->form_validation->set_rules('session_value', 'Session Value', 'trim|required|xss_clean');
		$this->form_validation->set_rules('notes', 'Notes', 'trim|required|xss_clean');
	
		if ($this->form_validation->run() == FALSE)
		{
			$pass = false;
			if(form_error('general_topic'))
			{
				$message[] = form_error('general_topic');
			}
			if(form_error('session_value'))
			{
				$message[] = form_error('session_value');
			}
			if(form_error('notes'))
			{
				$message[] = form_error('notes');
			}
		}
		else
		{
			if($postMeetingId==0)
			{
				$res = $this->premeeting_model->insertPostMeeting();
			}
			else
			{
				$res = $this->premeeting_model->updatePostMeeting($postMeetingId);
			}
			
			if($res && trim($res)!="exist")
			{
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Post Meeting saved successfully.</p></div>";
			}
			elseif($res && trim($res)=="exist")
			{
				$pass = false;
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Post Meeting with entered topic already exists.</p></div>";
			}
		}
	
		$this->session->set_flashdata('message', $message);
		if($pass)
		{
			if($postMeetingId>0)
			{
				$this->session->set_flashdata('post_meeting_id', $postMeetingId);
			}
			redirect($this->config->item("postMeetingCtrl"), 'refresh');
		}
		else
		{
			$this->session->set_userdata("postData",$_POST);
			if($postMeetingId>0)
			{
				redirect($this->config->item("postMeetingCtrl")."/".$postMeetingId, 'refresh');
			}
			else
			{
				redirect($this->config->item("postMeetingCtrl"), 'refresh');
			}
		}
	}
	
	public function deletePostMeeting($postMeetingId)
	{
		$pass = false;
		$message = array();
		$postMeetingData = $this->premeeting_model->getPostMeetingData($postMeetingId);
		if($postMeetingData)
		{
			$res = $this->premeeting_model->deletePostMeeting($postMeetingId);
			if($res)
			{
				$pass = true;
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Post Meeting deleted successfully.</p></div>";	
			}
			else
			{
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Failed to delete post meeting.</p></div>";
			}
		}
		else
		{
			$message[] = "<div class='alert alert-warning'><p style='color:red;'>Post Meeting data not found.</p></div>";
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"postMeetingId"=>$postMeetingId));
		exit;
	}
}