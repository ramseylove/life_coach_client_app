<?phpclass Action_model extends CI_Model{	function __construct()	{		parent::__construct();		$this->load->database();		$this->config->load('dbtables', TRUE);			}		function getActionTypeData()	{		$actionTypeQuery = $this->db->get($this->config->item('ala_action_types','dbtables'));		return $actionTypeQuery->result();	}		function getGoals()	{		$goalsQuery = $this->db->get_where($this->config->item('ala_goals','dbtables'), array("user_id"=>trim($this->session->userdata("user_id"))));		return $goalsQuery->result();	}		function getActionsCount()	{		$this->db->where('user_id', trim($this->session->userdata("user_id")));		return $this->db->count_all_results($this->config->item('ala_actions','dbtables'));	}		function insertAction()	{		$checkAction = $this->db->get_where($this->config->item('ala_actions','dbtables'),array("action_title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));		if($checkAction->row())		{			return "exist";		}		else		{			$insertArr = array(								'user_id' => trim($this->session->userdata("user_id")),								'action_type_id' => trim($this->input->post("type")),								'action_title' => trim($this->input->post("title")),								'status' => 1,								'is_finished' => 0,								'created_at' => date("Y-m-d H:i:s"),								'updated_at' => date("Y-m-d H:i:s"),							  );			$result = $this->db->insert($this->config->item('ala_actions','dbtables'), $insertArr);			$actionId =  $this->db->insert_id();			if($actionId && $actionId > 0)			{				if($this->input->post('goals') && is_array($this->input->post('goals')) && count($this->input->post('goals'))>0)				{					$insertBulkActionArr = array();					foreach($this->input->post('goals') as $goal)					{						$insertActionArr = array();						$insertActionArr['user_id'] = trim($this->session->userdata("user_id"));						$insertActionArr['action_id'] = trim($actionId);						$insertActionArr['goal_id'] = trim($goal);						$insertBulkActionArr[] = $insertActionArr;					}										$this->db->insert_batch($this->config->item('ala_action_goal_mapping','dbtables'), $insertBulkActionArr); 				}				if($this->input->post('type') && trim($this->input->post('type'))==1)				{					$insertRemindersArr = array(										'action_id' => trim($actionId),										'date' => date('Y-m-d', strtotime(trim($this->input->post("remDate")))),										'time' => trim($this->input->post("remTime")).':00',										'status' => 1,										'created_at' => date("Y-m-d H:i:s"),										'updated_at' => date("Y-m-d H:i:s"),									  );									  					$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemindersArr);				}				/* else				{					$insertBulkActionArr = array();					$insertBulkActionArr[] = array(														'action_id' => trim($actionId),														'time' => trim($this->input->post("remTime")).':00',														'status' => 1,														'created_at' => date("Y-m-d H:i:s"),														'updated_at' => date("Y-m-d H:i:s"),												  );					foreach($this->input->post('goals') as $goal)					{						$insertActionArr = array();						$insertActionArr['user_id'] = trim($this->session->userdata("user_id"));						$insertActionArr['action_id'] = trim($actionId);						$insertActionArr['goal_id'] = trim($goal);						$insertBulkActionArr[] = $insertActionArr;					}										$this->db->insert_batch($this->config->item('ala_action_goal_mapping','dbtables'), $insertBulkActionArr); 				} */			}			return $actionId;		}	}		function getActionData($actionId)	{		$getActionQuery = $this->db->get_where($this->config->item('ala_actions','dbtables'),array("id"=>trim($actionId)));		$actionData = $getActionQuery->row();		return $actionData;	}	function updateAction($actionId)	{		$checkGoalQuery = $this->db->get_where($this->config->item('ala_goals','dbtables'),array("title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));		$checkGoal = $checkGoalQuery->row();				if($checkGoal && $checkGoal->id!=$goalId)		{			return "exist";		}		else		{			$data = array(							'title' => trim($this->input->post("title")),							'description' => trim($this->input->post("description")),							'is_secondary' => trim($this->input->post("type")),							'updated_at' => date("Y-m-d H:i:s"),						);			$this->db->where('id',$goalId);			$res = $this->db->update($this->config->item('ala_goals','dbtables'), $data);			return $res;		}	}		function deleteGoal($goalId)	{		$this->db->where('id', $goalId);		$res = $this->db->delete($this->config->item('ala_goals','dbtables'));		return $res;	}	}?>