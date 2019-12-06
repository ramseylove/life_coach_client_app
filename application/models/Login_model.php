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

	function getDashboardDailyRoutineActions()
	{
		$this->db->select('aa.*, apmam.id as mapping_id,apmam.post_meeting_id');
		$this->db->join($this->config->item('ala_post_meeting_action_mapping','dbtables').' apmam','apmam.action_id=aa.id','left');
		$this->db->where('aa.action_type_id = 2');
		$this->db->where('apmam.id IS NULL');
		$actionsWithoutPostMeetingsQuery = $this->db->get($this->config->item('ala_actions','dbtables').' aa');
		$actionsDailyRoutine = $actionsWithoutPostMeetingsQuery->result();
		if($actionsDailyRoutine) {
			foreach($actionsDailyRoutine as $keyDR=>$actionDR) {
				$actionsDailyRoutine[$keyDR]->reminders = array();
				$actionsDailyRoutineRemQuery = $this->db->get_where($this->config->item('ala_action_reminders','dbtables'), array("action_id"=>trim($actionDR->id),"cron_added"=>0));
				$actionsDailyRoutineRem = $actionsDailyRoutineRemQuery->result();
				if($actionsDailyRoutineRem) {
					$actionsDailyRoutine[$keyDR]->reminders = $actionsDailyRoutineRem;
					if(!empty($actionsDailyRoutine[$keyDR]->reminders)) {
						foreach($actionsDailyRoutine[$keyDR]->reminders as $keyDR1=>$actionDR1) {
							$actionsDailyRoutine[$keyDR]->reminders[$keyDR1]->questions = array();
							$actionsDailyRoutineRemQuery1 = $this->db->get_where($this->config->item('ala_action_question','dbtables'), array("remId"=>trim($actionDR1->id),"actionId"=>trim($actionDR1->action_id)));
							$actionsDailyRoutineRem1 = $actionsDailyRoutineRemQuery1->result();
							if(!empty($actionsDailyRoutineRem1)) {
								$actionsDailyRoutine[$keyDR]->reminders[$keyDR1]->questions = $actionsDailyRoutineRem1;
							}else {
								$actionsDailyRoutine[$keyDR]->reminders[$keyDR1]->questions = '';
							}
						}
					}
				}
			}
		}
		 /* echo '<pre>'; print_r($actionsDailyRoutine); die; */
		return $actions = $actionsDailyRoutine;
	}
	function insertAction($action) {
		if(!empty($action->reminders)) {
			foreach($action->reminders as $remder) {
				$insertRemArr['action_id'] = trim($remder->action_id);
				$insertRemArr['dayname'] = $remder->dayname;
				$insertRemArr['day_selected'] = $remder->day_selected;
				$insertRemArr['daycheck'] = $remder->daycheck;
				$insertRemArr['date'] = $remder->date;
				$insertRemArr['time'] = $remder->time;
				$insertRemArr['status'] = $remder->status;
				$insertRemArr['is_finished'] = '';
				$insertRemArr['cron_added'] = 1;
				$insertRemArr['created_at'] = date("Y-m-d H:i:s");
				$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
				$result = $this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
				$remidss =  $this->db->insert_id();
				$created_dates = date('Y-m-d h:i:s');
				if(!empty($remder->questions)) {
					foreach($remder->questions as $question) {
						$insertQuestionArr = array(
							'actionId'=>$remder->action_id,
							'remId'=>$remidss,
							'user_id'=>$action->user_id,
							'question'=>$question->question,
							'created_date'=>$created_dates
						);
						$this->db->insert($this->config->item('ala_action_question','dbtables'), $insertQuestionArr);
					}
				}
			}
		}
    }
}
?>