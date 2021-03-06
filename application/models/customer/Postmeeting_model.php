<?php
class Postmeeting_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('dbtables', TRUE);		
	}
	
	function getPostMeetings($page)
	{
		$this->db->order_by('id','desc');
		$this->db->limit(ROWS_PER_PAGE, $page);
		$postMeetingsQuery = $this->db->get_where($this->config->item('ala_post_meeting','dbtables'), array("user_id"=>trim($this->session->userdata("user_id"))));
		return $postMeetingsQuery->result();
	}
	
	function getAllPostMeetings()
	{
		$this->db->order_by('id','desc');
		$this->db->limit('12');
		$postMeetingsQuery = $this->db->get_where($this->config->item('ala_post_meeting','dbtables'), array("user_id"=>trim($this->session->userdata("user_id"))));
		return $postMeetingsQuery->result();
	}
	
	function getAllPostMeetingsByasc()
	{
		$this->db->order_by('id','asc');
		$this->db->limit('12');
		$postMeetingsQuery = $this->db->get_where($this->config->item('ala_post_meeting','dbtables'), array("user_id"=>trim($this->session->userdata("user_id"))));
		return $postMeetingsQuery->result();
	}
	
	function getWeekTags() {
	    $this->db->order_by('id','asc');
		$preMeetingsQuery = $this->db->get_where($this->config->item('ala_tags','dbtables'));
		return $preMeetingsQuery->result();
	}
	
	function getPostMeetingActions($ids)
	{
	    $this->db->select('aa.*, apmam.id as mapping_id');
		$this->db->join($this->config->item('ala_post_meeting_action_mapping','dbtables').' apmam','apmam.action_id=aa.id','left');
		$this->db->where('aa.user_id', trim($this->session->userdata("user_id")));
		$this->db->where('apmam.post_meeting_id', $ids);
		$actionsWithoutPostMeetingsQuery = $this->db->get($this->config->item('ala_actions','dbtables').' aa');
		return $actionsWithoutPostMeetingsQuery->result();
	}
	
	function getPostMeetingActionQuestions($ids)
	{
	   $this->db->order_by('id','desc');
	    $postMeetingsQuery = $this->db->get_where($this->config->item('ala_action_question','dbtables'), array("actionId"=>$ids));
		return $postMeetingsQuery->result();
	}
	
	function getPostMeetingsCount()
	{
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		return $this->db->count_all_results($this->config->item('ala_post_meeting','dbtables'));
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
	
	function insertPostMeeting()
	{
		/*$checkPostMeeting = $this->db->get_where($this->config->item('ala_post_meeting','dbtables'),array("general_topic"=>trim($this->input->post("general_topic")), "user_id"=>trim($this->session->userdata("user_id"))));
		if($checkPostMeeting->row())
		{
			return "exist";
		}
		else
		{*/ 
			$insertArr = array(
								'user_id' => trim($this->session->userdata("user_id")),
								'general_topic' => trim($this->input->post("general_topic")),
								'session_value' => trim($this->input->post("session_value")),
								'notes' => trim($this->input->post("notes")),
								'weekno' => trim($this->input->post("weekno")),
								'week_start' => trim($this->input->post("week_start")),
								'week_end' => trim($this->input->post("week_end")),
								'created_at' => date("Y-m-d H:i:s"),
								'updated_at' => date("Y-m-d H:i:s"),
							  );
			$result = $this->db->insert($this->config->item('ala_post_meeting','dbtables'), $insertArr);
			$postMeetingId =  $this->db->insert_id();
			if($postMeetingId && $postMeetingId>0)
			{
				$hiddenActionIdsArr = explode('|', trim($this->input->post("hiddenActionIds")));
				$insArrActionPmMap = array();
				foreach($hiddenActionIdsArr as $hiddenActionId)
				{
					$insArrActionPmMap[] = array(
													'post_meeting_id'=>$postMeetingId,
													'action_id'=>$hiddenActionId,
													'is_finished'=>0
												);
				}
				$this->db->insert_batch($this->config->item('ala_post_meeting_action_mapping','dbtables'), $insArrActionPmMap);
				$hiddenNextActionIdsArr = explode('|', trim($this->input->post("hiddenNextActionIds")));
				foreach($hiddenNextActionIdsArr as $hiddenNextActionId)
				{
					$data = array('nextweek'=>0);
					$this->db->where('id',$hiddenNextActionId);
					$res = $this->db->update($this->config->item('ala_actions','dbtables'), $data);
				}
			}
			return $postMeetingId;
		/*}*/
	}
	
	function getPostMeetingData($postMeetingId)
	{
		$getPostMeetingQuery = $this->db->get_where($this->config->item('ala_post_meeting','dbtables'),array("id"=>trim($postMeetingId)));
		$postMeetingData = $getPostMeetingQuery->row();
		if($postMeetingData)
		{
			$postMeetingData->actions = array();
			
			$this->db->select('apmam.is_finished,aa.*,aat.title as action_type_title');
			$this->db->where('apmam.post_meeting_id', trim($postMeetingId));
			$this->db->join($this->config->item('ala_actions','dbtables').' aa', 'aa.id=apmam.action_id');
			$this->db->join($this->config->item('ala_action_types','dbtables').' aat', 'aat.id=aa.action_type_id');
			$getActionsQuery = $this->db->get($this->config->item('ala_post_meeting_action_mapping','dbtables').' apmam');
			$getActions = $getActionsQuery->result();
			if($getActions)
			{
				$postMeetingData->actions = $getActions;
			}
		}
		return $postMeetingData;
	}

	function updatePostMeeting($postMeetingId)
	{
		/*$checkPostMeetingQuery = $this->db->get_where($this->config->item('ala_post_meeting','dbtables'),array("general_topic"=>trim($this->input->post("general_topic")), "user_id"=>trim($this->session->userdata("user_id"))));
		$checkPostMeeting = $checkPostMeetingQuery->row();
		
		if($checkPostMeeting && $checkPostMeeting->id!=$postMeetingId)
		{
			return "exist";
		}
		else
		{*/
			$data = array(
							'general_topic' => trim($this->input->post("general_topic")),
							'session_value' => trim($this->input->post("session_value")),
							'notes' => trim($this->input->post("notes")),
							'updated_at' => date("Y-m-d H:i:s"),
						);
			$this->db->where('id',$postMeetingId);
			$res = $this->db->update($this->config->item('ala_post_meeting','dbtables'), $data);
			if($res)
			{
				$this->db->where('post_meeting_id', $postMeetingId);
				$this->db->delete($this->config->item('ala_post_meeting_action_mapping','dbtables'));
				
				$hiddenActionIdsArr = explode('|', trim($this->input->post("hiddenActionIds")));
				$insArrActionPmMap = array();
				foreach($hiddenActionIdsArr as $hiddenActionId)
				{
					$insArrActionPmMap[] = array(
													'post_meeting_id'=>$postMeetingId,
													'action_id'=>$hiddenActionId,
													'is_finished'=>0
												);
				}
				$this->db->insert_batch($this->config->item('ala_post_meeting_action_mapping','dbtables'), $insArrActionPmMap); 
			}
			return $res;
		/*}*/
	}
	
	function deletePostMeeting($postMeetingId)
	{
		$this->db->where('post_meeting_id', $postMeetingId);
		$this->db->delete($this->config->item('ala_post_meeting_action_mapping','dbtables'));
				
		$this->db->where('id', $postMeetingId);
		$res = $this->db->delete($this->config->item('ala_post_meeting','dbtables'));
		return $res;
	}
	
}
?>