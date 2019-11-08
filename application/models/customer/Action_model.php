<?php
class Action_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('dbtables', TRUE);		
	}
	function getActionTypeData()
	{
		$actionTypeQuery = $this->db->get($this->config->item('ala_action_types','dbtables'));
		return $actionTypeQuery->result();
	}
	function getGoals()
	{
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
	function getQuestions($id)
	{
		$this->db->order_by('id', 'asc');
		$goalsQuery = $this->db->get_where($this->config->item('ala_action_question','dbtables'), array("actionId"=>$id, "user_id"=>trim($this->session->userdata("user_id"))));
		return $goalsQuery->result();
	}
	function getActions()
	{
		$actions = array();
		$actionsDailyRoutineQuery = $this->db->get_where($this->config->item('ala_actions','dbtables'), array("user_id"=>trim($this->session->userdata("user_id")), 'action_type_id'=>2));
		$actionsDailyRoutine = $actionsDailyRoutineQuery->result();
		if($actionsDailyRoutine)
		{
			foreach($actionsDailyRoutine as $keyDR=>$actionDR)
			{
				$actionsDailyRoutine[$keyDR]->reminders = array();

				$actionsDailyRoutineRemQuery = $this->db->get_where($this->config->item('ala_action_reminders','dbtables'), array("action_id"=>trim($actionDR->id)));
				$actionsDailyRoutineRem = $actionsDailyRoutineRemQuery->result();
				if($actionsDailyRoutineRem)
				{
					$actionsDailyRoutine[$keyDR]->reminders = $actionsDailyRoutineRem;
				}
			}
		}
		$actions['daily'] = $actionsDailyRoutine;

		$actionsOneTimeQuery = $this->db->get_where($this->config->item('ala_actions','dbtables'), array("user_id"=>trim($this->session->userdata("user_id")), 'action_type_id'=>1));
		$actionsOneTime = $actionsOneTimeQuery->result();
		if($actionsOneTime)
		{
			foreach($actionsOneTime as $keyOT=>$actionOT)
			{
				$actionsOneTime[$keyOT]->reminders = array();

				$actionsOneTimeRemQuery = $this->db->get_where($this->config->item('ala_action_reminders','dbtables'), array("action_id"=>trim($actionOT->id)));
				$actionsOneTimeRem = $actionsOneTimeRemQuery->result();
				if($actionsOneTimeRem)
				{
					$actionsOneTime[$keyOT]->reminders = $actionsOneTimeRem;
				}
			}
		}
		$actions['one_time'] = $actionsOneTime;

		return $actions;
	}
	function getDashboardActions()
	{
		$actions = array();
		$this->db->select('id,weekno');
		$this->db->from('ala_pre_meeting');
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		$this->db->order_by('id', 'DESC');
		$this->db->order_by('weekno', 'DESC');
		$this->db->limit('1');
		$query = $this->db->get();
		$ret = $query->row(); 
		if(!empty($ret)) {
			
			$this->db->select('aa.*, apmam.id as mapping_id,apmam.post_meeting_id');
			$this->db->join($this->config->item('ala_post_meeting_action_mapping','dbtables').' apmam','apmam.action_id=aa.id','left');
			$this->db->where('aa.user_id', trim($this->session->userdata("user_id")));
			$this->db->where('aa.action_type_id', 2);
			$this->db->where('aa.weekno', $ret->weekno);
			$actionsWithoutPostMeetingsQuery = $this->db->get($this->config->item('ala_actions','dbtables').' aa');
			$actionsDailyRoutine = $actionsWithoutPostMeetingsQuery->result();
			
			/* $actionsDailyRoutineQuery = $this->db->get_where($this->config->item('ala_actions','dbtables'), array("user_id"=>trim($this->session->userdata("user_id")), 'action_type_id'=>2));
			$actionsDailyRoutine = $actionsDailyRoutineQuery->result(); */
			if($actionsDailyRoutine)
			{
				foreach($actionsDailyRoutine as $keyDR=>$actionDR)
				{
					$actionsDailyRoutine[$keyDR]->reminders = array();

					$actionsDailyRoutineRemQuery = $this->db->get_where($this->config->item('ala_action_reminders','dbtables'), array("action_id"=>trim($actionDR->id)));
					$actionsDailyRoutineRem = $actionsDailyRoutineRemQuery->result();
					if($actionsDailyRoutineRem)
					{
						$actionsDailyRoutine[$keyDR]->reminders = $actionsDailyRoutineRem;
					}
				}
			}
			$actions['daily'] = $actionsDailyRoutine;
			
			$this->db->select('aa.*, apmam.id as mapping_id,apmam.post_meeting_id');
			$this->db->join($this->config->item('ala_post_meeting_action_mapping','dbtables').' apmam','apmam.action_id=aa.id','left');
			$this->db->where('aa.user_id', trim($this->session->userdata("user_id")));
			$this->db->where('aa.action_type_id', 1);
			$this->db->where('aa.weekno', $ret->weekno);
			$actionsWithoutPostMeetingsQuery1 = $this->db->get($this->config->item('ala_actions','dbtables').' aa');
			$actionsOneTime = $actionsWithoutPostMeetingsQuery1->result();
			
			/* $actionsOneTimeQuery = $this->db->get_where($this->config->item('ala_actions','dbtables'), array("user_id"=>trim($this->session->userdata("user_id")), 'action_type_id'=>1));
			$actionsOneTime = $actionsOneTimeQuery->result(); */
			/* echo '<pre>'; print_r($ret); print_r($ro); print_r($ro1); die; */
			if($actionsOneTime)
			{
				foreach($actionsOneTime as $keyOT=>$actionOT)
				{
					$actionsOneTime[$keyOT]->reminders = array();

					$actionsOneTimeRemQuery = $this->db->get_where($this->config->item('ala_action_reminders','dbtables'), array("action_id"=>trim($actionOT->id)));
					$actionsOneTimeRem = $actionsOneTimeRemQuery->result();
					if($actionsOneTimeRem)
					{
						$actionsOneTime[$keyOT]->reminders = $actionsOneTimeRem;
					}
				}
			}
			$actions['one_time'] = $actionsOneTime;
		}
		return $actions;
	}
	function getActionsCount()
	{
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		return $this->db->count_all_results($this->config->item('ala_actions','dbtables'));
	}
	function insertCompleteAction($actionId)
	{
		$updated_date = date('Y-m-d h:i:s');
		foreach($_POST as $key => $answer) {
			$ids = explode('--',$key);
			$data = array(
				'answer'=>$answer,
				'updated_date'=>$updated_date
			);
			$this->db->where('id',$ids[1]);
			$res = $this->db->update($this->config->item('ala_action_question','dbtables'), $data);
		}
		return $res;
		/* for($i=0; $i<=2; $i++)
		{
			$insertDetailsArr[] = array(
				'user_id' => trim($this->session->userdata("user_id")),
				'action_id' => trim($actionId),
				'feedback_node' => trim($this->input->post("action_complete_".$i."")),
				'identifier' => "action_complete_".$i."",
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
			);
		}
		$res = $this->db->insert_batch($this->config->item('ala_action_user_notes','dbtables'), $insertDetailsArr);
		return $res; */
	}
	function updateActionAsComplete($actionId)
	{
		$data = array(
			'is_finished' => 1,
			'updated_at' => date("Y-m-d H:i:s"),
		);
		$this->db->where('id',$actionId);
		$res = $this->db->update($this->config->item('ala_actions','dbtables'), $data);
		return $res;
	}
	function insertAction()
	{
		$checkAction = $this->db->get_where($this->config->item('ala_actions','dbtables'),array("action_title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));
		if($checkAction->row())
		{
			return "exist";
		}
		else
		{
			$insertArr = array(
				'user_id' => trim($this->session->userdata("user_id")),
				'action_type_id' => trim($this->input->post("type")),
				'action_title' => trim($this->input->post("title")),
				'status' => 1,
				'is_finished' => 0,
				'weekno' => $this->input->post("weekno"),
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
			);
			$result = $this->db->insert($this->config->item('ala_actions','dbtables'), $insertArr);
			$actionId =  $this->db->insert_id();
			if($actionId && $actionId > 0)
			{
				if($this->input->post('goals') && is_array($this->input->post('goals')) && count($this->input->post('goals'))>0)
				{
					$insertBulkActionArr = array();
					foreach($this->input->post('goals') as $goal)
					{
						$insertActionArr = array();
						$insertActionArr['user_id'] = trim($this->session->userdata("user_id"));
						$insertActionArr['action_id'] = trim($actionId);
						$insertActionArr['goal_id'] = trim($goal);
						$insertBulkActionArr[] = $insertActionArr;
					}

					$this->db->insert_batch($this->config->item('ala_action_goal_mapping','dbtables'), $insertBulkActionArr); 
				}
				if($this->input->post('type') && trim($this->input->post('type'))==1)
				{
					$insertRemindersArr = array(
					'action_id' => trim($actionId),
					'date' => date('Y-m-d', strtotime(trim($this->input->post("remDate")))),
					'time' => trim($this->input->post("remTime")).':00',
					'status' => 1,
					'created_at' => date("Y-m-d H:i:s"),
					'updated_at' => date("Y-m-d H:i:s"),
					);

					$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemindersArr);
				}
				elseif($this->input->post('type') && trim($this->input->post('type'))==2)
				{
					$insertBulkRemArr = array();
					$insertBulkRemArr[] = array(
						'action_id' => trim($actionId),
						'time' => trim($this->input->post("remTime")).':00',
						'status' => 1,
						'created_at' => date("Y-m-d H:i:s"),
						'updated_at' => date("Y-m-d H:i:s"),
					);
					for($i=1;$i<=trim($this->input->post("remTimeCounter"));$i++)
					{
						if($this->input->post("remTime_".$i))
						{
							$insertRemArr = array();
							$insertRemArr['action_id'] = trim($actionId);
							$insertRemArr['time'] = trim($this->input->post("remTime_".$i)).':00';
							$insertRemArr['status'] = 1;
							$insertRemArr['created_at'] = date("Y-m-d H:i:s");
							$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
							$insertBulkRemArr[] = $insertRemArr;
						}
					}

					$this->db->insert_batch($this->config->item('ala_action_reminders','dbtables'), $insertBulkRemArr); 
				}
				if($this->input->post("postMeetingId") && trim($this->input->post("postMeetingId"))!='')
				{
					$insertPmMappingArr = array(
						'post_meeting_id'=>trim($this->input->post("postMeetingId")),
						'action_id'=>$actionId,
						'is_finished'=>0
					);
					$this->db->insert($this->config->item('ala_post_meeting_action_mapping','dbtables'), $insertPmMappingArr);
				}
				$created_dates = date('Y-m-d h:i:s');
				foreach($_POST['question'] as $question) {
					$insertQuestionArr = array(
						'actionId'=>$actionId,
						'user_id'=>trim($this->session->userdata("user_id")),
						'question'=>$question,
						'created_date'=>$created_dates
					);
					$this->db->insert($this->config->item('ala_action_question','dbtables'), $insertQuestionArr);
				}
			}
			return $actionId;
		}
	}
	function getActionData($actionId)
	{
		$this->db->where('id', trim($actionId));
		$getActionQuery = $this->db->get($this->config->item('ala_actions','dbtables'));
		$actionData = $getActionQuery->row();
		if($actionData)
		{
			$actionData->goals = array();
			$actionData->reminders = array();

			$this->db->where('action_id', trim($actionId));
			$getActionGoalsQuery = $this->db->get($this->config->item('ala_action_goal_mapping','dbtables'));
			$actionGoalsData = $getActionGoalsQuery->result();
			if($actionGoalsData)
			{
				$actionData->goals = $actionGoalsData;
			}

			$this->db->where('action_id', trim($actionId));
			$getActionRemindersQuery = $this->db->get($this->config->item('ala_action_reminders','dbtables'));
			$actionRemindersData = $getActionRemindersQuery->result();
			if($actionRemindersData)
			{
				$actionData->reminders = $actionRemindersData;
			}
		}
		return $actionData;
	}
	function updateAction($actionId)
	{
		$checkActionQuery = $this->db->get_where($this->config->item('ala_actions','dbtables'),array("action_title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));
		$checkAction = $checkActionQuery->row();

		if($checkAction && $checkAction->id!=$actionId)
		{
			return "exist";
		}
		else
		{
			$data = array(
				'action_type_id' => trim($this->input->post("type")),
				'action_title' => trim($this->input->post("title")),
				'updated_at' => date("Y-m-d H:i:s"),
			);
			$this->db->where('id',$actionId);
			$res = $this->db->update($this->config->item('ala_actions','dbtables'), $data);
			if($res)
			{
				$this->db->where('action_id', $actionId);
				$this->db->delete($this->config->item('ala_action_goal_mapping','dbtables'));
					if($this->input->post('goals') && is_array($this->input->post('goals')) && count($this->input->post('goals'))>0)
					{
						$insertBulkActionArr = array();
						foreach($this->input->post('goals') as $goal)
						{
							$insertActionArr = array();
							$insertActionArr['user_id'] = trim($this->session->userdata("user_id"));
							$insertActionArr['action_id'] = trim($actionId);
							$insertActionArr['goal_id'] = trim($goal);
							$insertBulkActionArr[] = $insertActionArr;
						}

						$this->db->insert_batch($this->config->item('ala_action_goal_mapping','dbtables'), $insertBulkActionArr); 
					}

					$this->db->where('action_id', $actionId);
					$this->db->delete($this->config->item('ala_action_reminders','dbtables'));
					if($this->input->post('type') && trim($this->input->post('type'))==1)
					{
						$insertRemindersArr = array(
							'action_id' => trim($actionId),
							'date' => date('Y-m-d', strtotime(trim($this->input->post("remDate")))),
							'time' => trim($this->input->post("remTime")).':00',
							'status' => 1,
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s"),
						);

						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemindersArr);
					}
					elseif($this->input->post('type') && trim($this->input->post('type'))==2)
					{
						$insertBulkRemArr = array();
						$insertBulkRemArr[] = array(
							'action_id' => trim($actionId),
							'time' => trim($this->input->post("remTime")).':00',
							'status' => 1,
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s"),
						);
						for($i=1;$i<=trim($this->input->post("remTimeCounter"));$i++)
						{
							if($this->input->post("remTime_".$i))
							{
								$insertRemArr = array();
								$insertRemArr['action_id'] = trim($actionId);
								$insertRemArr['time'] = trim($this->input->post("remTime_".$i)).':00';
								$insertRemArr['status'] = 1;
								$insertRemArr['created_at'] = date("Y-m-d H:i:s");
								$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
								$insertBulkRemArr[] = $insertRemArr;
							}
						}

						$this->db->insert_batch($this->config->item('ala_action_reminders','dbtables'), $insertBulkRemArr); 
					}
				$this->db->where('actionId', $actionId);
				$this->db->where('user_id', $this->session->userdata("user_id"));
				$this->db->delete($this->config->item('ala_action_question','dbtables'));
				$created_dates = date('Y-m-d h:i:s');
				foreach($_POST['question'] as $question) {
					$insertQuestionArr = array(
						'actionId'=>$actionId,
						'user_id'=>trim($this->session->userdata("user_id")),
						'question'=>$question,
						'created_date'=>$created_dates
					);
					$this->db->insert($this->config->item('ala_action_question','dbtables'), $insertQuestionArr);
				}
			}
			return $res;
		}
	}
	function deleteAction($actionId)
	{
		$this->db->where('action_id', $actionId);
		$this->db->delete($this->config->item('ala_action_goal_mapping','dbtables'));

		$this->db->where('action_id', $actionId);
		$this->db->delete($this->config->item('ala_action_reminders','dbtables'));
			
		$this->db->where('id', $actionId);
		$res = $this->db->delete($this->config->item('ala_actions','dbtables'));
		return $res;
	}
	function insertActionToNextWeek($actionId)
	{
		$insertArr = array(
			'parent_action' => $actionId,
			'user_id' => trim($this->session->userdata("user_id")),
			'action_type_id' => trim($this->input->post("type")),
			'action_title' => trim($this->input->post("title")),
			'status' => 1,
			'is_finished' => 0,
			'weekno' => $this->input->post("weekno"),
			'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s"),
		);
		$result = $this->db->insert($this->config->item('ala_actions','dbtables'), $insertArr);
		$actionId =  $this->db->insert_id();
		if($actionId && $actionId > 0)
		{
			if($this->input->post('goals') && is_array($this->input->post('goals')) && count($this->input->post('goals'))>0)
			{
				$insertBulkActionArr = array();
				foreach($this->input->post('goals') as $goal)
				{
					$insertActionArr = array();
					$insertActionArr['user_id'] = trim($this->session->userdata("user_id"));
					$insertActionArr['action_id'] = trim($actionId);
					$insertActionArr['goal_id'] = trim($goal);
					$insertBulkActionArr[] = $insertActionArr;
				}

				$this->db->insert_batch($this->config->item('ala_action_goal_mapping','dbtables'), $insertBulkActionArr); 
			}
			if($this->input->post('type') && trim($this->input->post('type'))==1)
			{
				$insertRemindersArr = array(
				'action_id' => trim($actionId),
				'date' => date('Y-m-d', strtotime(trim($this->input->post("remDate")))),
				'time' => trim($this->input->post("remTime")).':00',
				'status' => 1,
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
				);

				$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemindersArr);
			}
			elseif($this->input->post('type') && trim($this->input->post('type'))==2)
			{
				$insertBulkRemArr = array();
				$insertBulkRemArr[] = array(
					'action_id' => trim($actionId),
					'time' => trim($this->input->post("remTime")).':00',
					'status' => 1,
					'created_at' => date("Y-m-d H:i:s"),
					'updated_at' => date("Y-m-d H:i:s"),
				);
				for($i=1;$i<=trim($this->input->post("remTimeCounter"));$i++)
				{
					if($this->input->post("remTime_".$i))
					{
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['time'] = trim($this->input->post("remTime_".$i)).':00';
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$insertBulkRemArr[] = $insertRemArr;
					}
				}

				$this->db->insert_batch($this->config->item('ala_action_reminders','dbtables'), $insertBulkRemArr); 
			}
			if($this->input->post("postMeetingId") && trim($this->input->post("postMeetingId"))!='')
			{
				$insertPmMappingArr = array(
					'post_meeting_id'=>trim($this->input->post("postMeetingId")),
					'action_id'=>$actionId,
					'is_finished'=>0
				);
				$this->db->insert($this->config->item('ala_post_meeting_action_mapping','dbtables'), $insertPmMappingArr);
			}
			$created_dates = date('Y-m-d h:i:s');
			foreach($_POST['question'] as $question) {
				$insertQuestionArr = array(
					'actionId'=>$actionId,
					'user_id'=>trim($this->session->userdata("user_id")),
					'question'=>$question,
					'created_date'=>$created_dates
				);
				$this->db->insert($this->config->item('ala_action_question','dbtables'), $insertQuestionArr);
			}
		}
		return $actionId;
	}
}
?>