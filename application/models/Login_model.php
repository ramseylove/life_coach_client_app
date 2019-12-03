<?php
class Login_model extends CI_Model{
function __construct()	{		
parent::__construct();		
$this->load->database();		
$this->config->load('dbtables', TRUE);			

}	
function loginCheck(){		
	$query = $this->db->get_where($this->config->item('ala_admin','dbtables'),array("email"=>trim($this->input->post("email")),"password"=>md5(trim($this->input->post("pswd")))));
	return $query->row();
}
function getAdmin()	{
	$query = $this->db->get_where($this->config->item('ala_admin','dbtables'),array("email"=>trim($this->input->post("email")),"id"=>trim($this->session->userData("admin_id")),"password"=>md5(trim($this->input->post("oldPswd")))));
	return $query->row();
}
function finalChangePswd($userId)	{
	$data = array('password' => md5(trim($this->input->post("newPswd"))));
	$this->db->where('id',$userId);
	$res = $this->db->update($this->config->item('ala_admin','dbtables'), $data);
	return $res;
}
function getUserData($userId)
	{
		$getUserQuery = $this->db->get_where($this->config->item('ala_user','dbtables'),array("id"=>trim($userId)));
		$userData = $getUserQuery->row();
		return $userData;
	}
function getDashboardActions()
	{
		$this->db->select('aa.*, apmam.id as mapping_id,apmam.post_meeting_id');
		$this->db->join($this->config->item('ala_post_meeting_action_mapping','dbtables').' apmam','apmam.action_id=aa.id','left');
		$this->db->where('apmam.id IS NULL');
		$actionsWithoutPostMeetingsQuery = $this->db->get($this->config->item('ala_actions','dbtables').' aa');
		$actionsDailyRoutine = $actionsWithoutPostMeetingsQuery->result();
		if($actionsDailyRoutine) {
			foreach($actionsDailyRoutine as $keyDR=>$actionDR) {
				$actionsDailyRoutine[$keyDR]->reminders = array();
				$actionsDailyRoutineRemQuery = $this->db->get_where($this->config->item('ala_action_reminders','dbtables'), array("action_id"=>trim($actionDR->id)));
				$actionsDailyRoutineRem = $actionsDailyRoutineRemQuery->result();
				if($actionsDailyRoutineRem) {
					$actionsDailyRoutine[$keyDR]->reminders = $actionsDailyRoutineRem;
				}
			}
		}
		return $actions = $actionsDailyRoutine;
	}
}
?>