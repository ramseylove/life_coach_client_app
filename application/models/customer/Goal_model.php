<?php
class Goal_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('dbtables', TRUE);		
	}
	
	function getGoals($page)
	{
		$this->db->order_by('id','desc');
		$this->db->limit(ROWS_PER_PAGE, $page);
		$goalsQuery = $this->db->get_where($this->config->item('ala_goals','dbtables'), array("user_id"=>trim($this->session->userdata("user_id"))));
		return $goalsQuery->result();
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
	
	function getGoalsCount()
	{
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		return $this->db->count_all_results($this->config->item('ala_goals','dbtables'));
	}
	
	function insertGoal()
	{
		$checkGoal = $this->db->get_where($this->config->item('ala_goals','dbtables'),array("title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));
		if($checkGoal->row())
		{
			return "exist";
		}
		else
		{
			$insertArr = array(
								'user_id' => trim($this->session->userdata("user_id")),
								'title' => trim($this->input->post("title")),
								'description' => trim($this->input->post("description")),
								'status' => 1,
								'is_secondary' => trim($this->input->post("type")),
								'created_at' => date("Y-m-d H:i:s"),
								'updated_at' => date("Y-m-d H:i:s"),
							  );
			$result = $this->db->insert($this->config->item('ala_goals','dbtables'), $insertArr);
			$goalId =  $this->db->insert_id();
			return $goalId;
		}
	}
	
	function getGoalData($goalId)
	{
		$getGoalQuery = $this->db->get_where($this->config->item('ala_goals','dbtables'),array("id"=>trim($goalId)));
		$goalData = $getGoalQuery->row();
		return $goalData;
	}
	
	function getGoalActions($goalId)
	{
		$this->db->select('ala_action_goal_mapping.*,ala_actions.*');
		$this->db->from('ala_action_goal_mapping');
		$this->db->join('ala_actions', 'ala_actions.id = ala_action_goal_mapping.action_id', 'left');
		$this->db->where('ala_action_goal_mapping.goal_id', $goalId);
		$query = $this->db->get();
		return $query->result();
	}
	
	function updateGoal($goalId)
	{
		$checkGoalQuery = $this->db->get_where($this->config->item('ala_goals','dbtables'),array("title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));
		$checkGoal = $checkGoalQuery->row();
		
		if($checkGoal && $checkGoal->id!=$goalId)
		{
			return "exist";
		}
		else
		{
			$data = array(
							'title' => trim($this->input->post("title")),
							'description' => trim($this->input->post("description")),
							'is_secondary' => trim($this->input->post("type")),
							'updated_at' => date("Y-m-d H:i:s"),
						);
			$this->db->where('id',$goalId);
			$res = $this->db->update($this->config->item('ala_goals','dbtables'), $data);
			return $res;
		}
	}
	
	function deleteGoal($goalId)
	{
		$this->db->where('id', $goalId);
		$res = $this->db->delete($this->config->item('ala_goals','dbtables'));
		return $res;
	}
	
}
?>