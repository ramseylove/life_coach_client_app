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
	
	function getPreMeetingsCount()
	{
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		return $this->db->count_all_results($this->config->item('ala_pre_meeting','dbtables'));
	}	
	
	function getLastPreMeeting()
	{
		$this->db->select('*');
		$this->db->from('ala_pre_meeting');
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		$this->db->order_by('id', 'DESC');
		$this->db->limit('1');
		$query = $this->db->get();
		return $ret = $query->row();
	}
	
	function insertPreMeeting()
	{
		$checkPreMeeting = $this->db->get_where($this->config->item('ala_pre_meeting','dbtables'),array("acknowledgment"=>trim($this->input->post("acknowledgment")), "user_id"=>trim($this->session->userdata("user_id"))));
		if($checkPreMeeting->row())
		{
			return "exist";
		}
		else
		{
			$insertArr = array(
								'user_id' => trim($this->session->userdata("user_id")),
								'general_happiness_level' => trim($this->input->post("general_happiness_level")),
								'acknowledgment' => trim($this->input->post("acknowledgment")),
								'obstacles' => trim($this->input->post("obstacles")),
								'weekno' => trim($this->input->post("weekno")),
								'status' => 1,
								'created_at' => date("Y-m-d H:i:s"),
								'updated_at' => date("Y-m-d H:i:s"),
							  );
			$result = $this->db->insert($this->config->item('ala_pre_meeting','dbtables'), $insertArr);
			$preMeetingId =  $this->db->insert_id();
			return $preMeetingId;
		}
	}
	
	function getPreMeetingData($preMeetingId)
	{
		$getPreMeetingQuery = $this->db->get_where($this->config->item('ala_pre_meeting','dbtables'),array("id"=>trim($preMeetingId)));
		$preMeetingData = $getPreMeetingQuery->row();
		return $preMeetingData;
	}

	function updatePreMeeting($preMeetingId)
	{
		$checkPreMeetingQuery = $this->db->get_where($this->config->item('ala_pre_meeting','dbtables'),array("acknowledgment"=>trim($this->input->post("acknowledgment")), "user_id"=>trim($this->session->userdata("user_id"))));
		$checkPreMeeting = $checkPreMeetingQuery->row();
		
		if($checkPreMeeting && $checkPreMeeting->id!=$preMeetingId)
		{
			return "exist";
		}
		else
		{
			$data = array(
							'general_happiness_level' => trim($this->input->post("general_happiness_level")),
							'acknowledgment' => trim($this->input->post("acknowledgment")),
							'obstacles' => trim($this->input->post("obstacles")),
							'updated_at' => date("Y-m-d H:i:s"),
						);
			$this->db->where('id',$preMeetingId);
			$res = $this->db->update($this->config->item('ala_pre_meeting','dbtables'), $data);
			return $res;
		}
	}
	
	function deletePreMeeting($preMeetingId)
	{
		$this->db->where('id', $preMeetingId);
		$res = $this->db->delete($this->config->item('ala_pre_meeting','dbtables'));
		return $res;
	}
	
}
?>