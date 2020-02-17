<?php
class Premeeting_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('dbtables', TRUE);		
	}
	
	function getPreMeetings($page)
	{
		$this->db->order_by('id','desc');
		$this->db->limit(ROWS_PER_PAGE, $page);
		$preMeetingsQuery = $this->db->get_where($this->config->item('ala_pre_meeting','dbtables'), array("user_id"=>trim($this->session->userdata("user_id"))));
		return $preMeetingsQuery->result();
	}
	
	function getWeekTags() {
	    $this->db->order_by('id','asc');
		$preMeetingsQuery = $this->db->get_where($this->config->item('ala_tags','dbtables'));
		return $preMeetingsQuery->result();
	}
	
	function getPreMeetingsCount()
	{
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		return $this->db->count_all_results($this->config->item('ala_pre_meeting','dbtables'));
	}
	
	function getActionsWithoutPostMeetings()
	{
		$this->db->select('aa.*, apmam.id as mapping_id');
		$this->db->join($this->config->item('ala_post_meeting_action_mapping','dbtables').' apmam','apmam.action_id=aa.id','left');
		$this->db->where('aa.user_id', trim($this->session->userdata("user_id")));
		$this->db->where('apmam.id IS NULL');
		$actionsWithoutPostMeetingsQuery = $this->db->get($this->config->item('ala_actions','dbtables').' aa');
		$result = $actionsWithoutPostMeetingsQuery->result();
		if(!empty($result)) {
    	    foreach($result as $key => $res) {
    	        $this->db->select('*');
        		$this->db->from('ala_action_reminders');
        		$this->db->where('action_id', $res->id);
        		$query = $this->db->get();
        	    $result[$key]->reminders = $query->result(); 
    	    }
    	}
    	return $result;
	}
	
	function getLastPreMeetingActions($week)
	{
		$this->db->select('aa.*, apmam.id as mapping_id');
		$this->db->join($this->config->item('ala_post_meeting_action_mapping','dbtables').' apmam','apmam.action_id=aa.id','left');
		$this->db->where('aa.user_id', trim($this->session->userdata("user_id")));
	    $this->db->where('aa.weekno', $week);
		$actionsWithoutPostMeetingsQuery = $this->db->get($this->config->item('ala_actions','dbtables').' aa');
		return $actionsWithoutPostMeetingsQuery->result();
	}
	
	function getLastPostMeeting()
	{
		$this->db->select('*');
		$this->db->from('ala_post_meeting');
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		$this->db->order_by('id', 'DESC');
		$this->db->order_by('weekno', 'DESC');
		$this->db->limit('1');
		$query = $this->db->get();
		return $ret = $query->row();
	}
	
	function getLastPreMeeting()
	{
		$this->db->select('*');
		$this->db->from('ala_pre_meeting');
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		$this->db->order_by('id', 'DESC');
		$this->db->order_by('weekno', 'DESC');
		$this->db->limit('1');
		$query = $this->db->get();
		return $ret = $query->row();
	}
	
	function insertPreMeeting()
	{
		/*$checkPreMeeting = $this->db->get_where($this->config->item('ala_pre_meeting','dbtables'),array("acknowledgment"=>trim($this->input->post("acknowledgment")), "user_id"=>trim($this->session->userdata("user_id"))));
		if($checkPreMeeting->row())
		{
			return "exist";
		}
		else
		{*/
			$insertArr = array(
								'user_id' => trim($this->session->userdata("user_id")),
								'general_happiness_level' => trim($this->input->post("general_happiness_level")),
								'acknowledgment' => trim($this->input->post("acknowledgment")),
								'obstacles' => trim($this->input->post("obstacles")),
								'upcoming_session' => trim($this->input->post("upcoming_session")),
								'valued_outcome' => trim($this->input->post("valued_outcome")),
								'additional_comment' => trim($this->input->post("additional_comment")),
								'weekno' => trim($this->input->post("weekno")),
								'status' => 1,
								'created_at' => date("Y-m-d H:i:s"),
								'updated_at' => date("Y-m-d H:i:s"),
							  );
			$result = $this->db->insert($this->config->item('ala_pre_meeting','dbtables'), $insertArr);
			$preMeetingId =  $this->db->insert_id();
			return $preMeetingId;
		/*}*/
	}
	
	function getPreMeetingData($preMeetingId)
	{
		$getPreMeetingQuery = $this->db->get_where($this->config->item('ala_pre_meeting','dbtables'),array("id"=>trim($preMeetingId)));
		$preMeetingData = $getPreMeetingQuery->row();
		return $preMeetingData;
	}

	function updatePreMeeting($preMeetingId)
	{
		/*$checkPreMeetingQuery = $this->db->get_where($this->config->item('ala_pre_meeting','dbtables'),array("acknowledgment"=>trim($this->input->post("acknowledgment")), "user_id"=>trim($this->session->userdata("user_id"))));
		$checkPreMeeting = $checkPreMeetingQuery->row();
		
		if($checkPreMeeting && $checkPreMeeting->id!=$preMeetingId)
		{
			return "exist";
		}
		else
		{*/
			$data = array(
							'general_happiness_level' => trim($this->input->post("general_happiness_level")),
							'acknowledgment' => trim($this->input->post("acknowledgment")),
							'obstacles' => trim($this->input->post("obstacles")),
							'upcoming_session' => trim($this->input->post("upcoming_session")),
							'valued_outcome' => trim($this->input->post("valued_outcome")),
							'additional_comment' => trim($this->input->post("additional_comment")),
							'weekno' => trim($this->input->post("weekno")),
							'updated_at' => date("Y-m-d H:i:s"),
						);
			$this->db->where('id',$preMeetingId);
			$res = $this->db->update($this->config->item('ala_pre_meeting','dbtables'), $data);
			return $res;
		/*}*/
	}
	
	function deletePreMeeting($preMeetingId)
	{
		$this->db->where('id', $preMeetingId);
		$res = $this->db->delete($this->config->item('ala_pre_meeting','dbtables'));
		return $res;
	}
	
}
?>