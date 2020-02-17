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
		/* if(!empty($ret)) { */
			
			$this->db->select('aa.*, apmam.id as mapping_id,apmam.post_meeting_id');
			$this->db->join($this->config->item('ala_post_meeting_action_mapping','dbtables').' apmam','apmam.action_id=aa.id','left');
			$this->db->where('aa.user_id', trim($this->session->userdata("user_id")));
			$this->db->where('aa.action_type_id', 2);
			$this->db->where('apmam.id IS NULL');
			/* $this->db->where('aa.weekno', $ret->weekno); */
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
			$this->db->where('aa.action_type_id', 3);
			$this->db->where('apmam.id IS NULL');
			$actionsWithoutPostMeetingsQuery = $this->db->get($this->config->item('ala_actions','dbtables').' aa');
			$actionsDailyRoutine = $actionsWithoutPostMeetingsQuery->result();
			if($actionsDailyRoutine)
			{
				foreach($actionsDailyRoutine as $keyDR=>$actionDR)
				{
					$actionsDailyRoutine[$keyDR]->reminders = array();

					$actionsDailyRoutineRemQuery = $this->db->get_where($this->config->item('ala_action_reminders','dbtables'), array("action_id"=>trim($actionDR->id),"day_selected"=>1));
					$actionsDailyRoutineRem = $actionsDailyRoutineRemQuery->result();
					if($actionsDailyRoutineRem)
					{
						$actionsDailyRoutine[$keyDR]->reminders = $actionsDailyRoutineRem;
					}
				}
			}
			$actions['weekly'] = $actionsDailyRoutine;
			
			$this->db->select('aa.*, apmam.id as mapping_id,apmam.post_meeting_id');
			$this->db->join($this->config->item('ala_post_meeting_action_mapping','dbtables').' apmam','apmam.action_id=aa.id','left');
			$this->db->where('aa.user_id', trim($this->session->userdata("user_id")));
			$this->db->where('aa.action_type_id', 1);
			$this->db->where('apmam.id IS NULL');
			/* $this->db->where('aa.weekno', $ret->weekno); */
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
		/* } */
		return $actions;
	}
	function getActionsCount()
	{
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		return $this->db->count_all_results($this->config->item('ala_actions','dbtables'));
	}
	function insertCompleteAction($actionId)
	{
	    $res = '';
		$updated_date = date('Y-m-d h:i:s');
		$datas = $_POST;
		unset($datas['remId']);
		foreach($datas as $key => $answer) {
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
	function updateRemindersAsComplete($remId) {
	    $data = array(
			'is_finished' => 1,
		);
		$this->db->where('id',$remId);
		$res = $this->db->update($this->config->item('ala_action_reminders','dbtables'), $data);
		return 1;
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
	    /*$datess = str_replace('/', '-', $this->input->post("remDate"));
        $datesave = date('Y-m-d', strtotime($datess));*/
		/*echo '<pre>'; print_r($_POST); die;*/
		$checkAction = $this->db->get_where($this->config->item('ala_actions','dbtables'),array("action_title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));
		if($checkAction->row())
		{
			return "exist";
		}
		else
		{
			if(isset($_POST['nextweek']) && $_POST['nextweek'] == 1) {
				$insertArr = array(
					'user_id' => trim($this->session->userdata("user_id")),
					'action_type_id' => trim($this->input->post("type")),
					'action_title' => trim($this->input->post("title")),
					'status' => 1,
					'is_finished' => 0,
					'nextweek' => 1,
					'weekno' => $this->input->post("weekno"),
					'created_at' => date("Y-m-d H:i:s"),
					'updated_at' => date("Y-m-d H:i:s"),
				);
			}else {
				$insertArr = array(
					'user_id' => trim($this->session->userdata("user_id")),
					'action_type_id' => trim($this->input->post("type")),
					'action_title' => trim($this->input->post("title")),
					'status' => 1,
					'is_finished' => 0,
					'nextweek' => 0,
					'weekno' => $this->input->post("weekno"),
					'created_at' => date("Y-m-d H:i:s"),
					'updated_at' => date("Y-m-d H:i:s"),
				);
			}
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
            		$datesave = date('Y-m-d', strtotime($this->input->post("remDate")));
					if(isset($_POST['remTime_check'])) {
						$daycheck = 1;
					}else {
						$daycheck = 0;
					}
					$insertRemindersArr = array(
					'action_id' => trim($actionId),
					'date' => $datesave,
					'time' => date('H:i:s', strtotime($this->input->post("remTime"))),
					'status' => 1,
					'daycheck' => $daycheck,
					'created_at' => date("Y-m-d H:i:s"),
					'updated_at' => date("Y-m-d H:i:s"),
					);

					$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemindersArr);
				}
				elseif($this->input->post('type') && trim($this->input->post('type'))==2)
				{
					$insertBulkRemArr = array();
					if(isset($_POST['remTime_check'])) {
						$daycheck = 1;
					}else {
						$daycheck = 0;
					}
					$insertBulkRemArr[] = array(
						'action_id' => trim($actionId),
						'time' => date('H:i:s', strtotime($this->input->post("remTime"))),
						'status' => 1,
						'daycheck' => $daycheck,
						'created_at' => date("Y-m-d H:i:s"),
						'updated_at' => date("Y-m-d H:i:s"),
					);
					for($i=1;$i<=trim($this->input->post("remTimeCounter"));$i++)
					{
						if($this->input->post("remTime_".$i))
						{
							$insertRemArr = array();
							if(isset($_POST['remTime_check_'.$i])) {
								$daycheck = 1;
							}else {
								$daycheck = 0;
							}
							$insertRemArr['action_id'] = trim($actionId);
							$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("remTime_".$i)));
							$insertRemArr['status'] = 1;
							$insertRemArr['daycheck'] = $daycheck;
							$insertRemArr['created_at'] = date("Y-m-d H:i:s");
							$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
							$insertBulkRemArr[] = $insertRemArr;
						}
					}

					$this->db->insert_batch($this->config->item('ala_action_reminders','dbtables'), $insertBulkRemArr); 
				}
				elseif($this->input->post('type') && trim($this->input->post('type'))==3)
				{
					$insertRemArr = array();
					$insertRemArr['action_id'] = trim($actionId);
					$insertRemArr['dayname'] = 'Monday';
					if(isset($_POST['monday_check'])) {
						$insertRemArr['daycheck'] = 1;
					}else {
						$insertRemArr['daycheck'] = 0;
					}
					if(in_array('week_monday',$_POST['selectdays'])) {
						$insertRemArr['day_selected'] = 1;
					}else {
						$insertRemArr['day_selected'] = 0;
					}
					$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_monday")));
					$insertRemArr['status'] = 1;
					$insertRemArr['created_at'] = date("Y-m-d H:i:s");
					$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
					$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
					$insertRemArr = array();
					$insertRemArr['action_id'] = trim($actionId);
					$insertRemArr['dayname'] = 'Tuesday';
					if(isset($_POST['tuesday_check'])) {
						$insertRemArr['daycheck'] = 1;
					}else {
						$insertRemArr['daycheck'] = 0;
					}
					if(in_array('week_tuesday',$_POST['selectdays'])) {
						$insertRemArr['day_selected'] = 1;
					}else {
						$insertRemArr['day_selected'] = 0;
					}
					$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_tuesday")));
					$insertRemArr['status'] = 1;
					$insertRemArr['created_at'] = date("Y-m-d H:i:s");
					$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
					$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
					$insertRemArr = array();
					$insertRemArr['action_id'] = trim($actionId);
					$insertRemArr['dayname'] = 'Wednesday';
					if(isset($_POST['wednesday_check'])) {
						$insertRemArr['daycheck'] = 1;
					}else {
						$insertRemArr['daycheck'] = 0;
					}
					if(in_array('week_wednesday',$_POST['selectdays'])) {
						$insertRemArr['day_selected'] = 1;
					}else {
						$insertRemArr['day_selected'] = 0;
					}
					$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_wednesday")));
					$insertRemArr['status'] = 1;
					$insertRemArr['created_at'] = date("Y-m-d H:i:s");
					$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
					$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr); 
					$insertRemArr = array();
					$insertRemArr['action_id'] = trim($actionId);
					$insertRemArr['dayname'] = 'Thursday';
					if(isset($_POST['thursday_check'])) {
						$insertRemArr['daycheck'] = 1;
					}else {
						$insertRemArr['daycheck'] = 0;
					}
					if(in_array('week_thursday',$_POST['selectdays'])) {
						$insertRemArr['day_selected'] = 1;
					}else {
						$insertRemArr['day_selected'] = 0;
					}
					$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_thursday")));
					$insertRemArr['status'] = 1;
					$insertRemArr['created_at'] = date("Y-m-d H:i:s");
					$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
					$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr); 
					$insertRemArr = array();
					$insertRemArr['action_id'] = trim($actionId);
					$insertRemArr['dayname'] = 'Friday';
					if(isset($_POST['friday_check'])) {
						$insertRemArr['daycheck'] = 1;
					}else {
						$insertRemArr['daycheck'] = 0;
					}
					if(in_array('week_friday',$_POST['selectdays'])) {
						$insertRemArr['day_selected'] = 1;
					}else {
						$insertRemArr['day_selected'] = 0;
					}
					$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_friday")));
					$insertRemArr['status'] = 1;
					$insertRemArr['created_at'] = date("Y-m-d H:i:s");
					$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
					$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
					$insertRemArr = array();
					$insertRemArr['action_id'] = trim($actionId);
					$insertRemArr['dayname'] = 'Satureday';
					if(isset($_POST['satureday_check'])) {
						$insertRemArr['daycheck'] = 1;
					}else {
						$insertRemArr['daycheck'] = 0;
					}
					if(in_array('week_satureday',$_POST['selectdays'])) {
						$insertRemArr['day_selected'] = 1;
					}else {
						$insertRemArr['day_selected'] = 0;
					}
					$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_satureday")));
					$insertRemArr['status'] = 1;
					$insertRemArr['created_at'] = date("Y-m-d H:i:s");
					$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
					$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
					$insertRemArr = array();
					$insertRemArr['action_id'] = trim($actionId);
					$insertRemArr['dayname'] = 'Sunday';
					if(isset($_POST['sunday_check'])) {
						$insertRemArr['daycheck'] = 1;
					}else {
						$insertRemArr['daycheck'] = 0;
					}
					if(in_array('week_sunday',$_POST['selectdays'])) {
						$insertRemArr['day_selected'] = 1;
					}else {
						$insertRemArr['day_selected'] = 0;
					}
					$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_sunday")));
					$insertRemArr['status'] = 1;
					$insertRemArr['created_at'] = date("Y-m-d H:i:s");
					$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
					$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr); 
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
				if($this->input->post('type') && trim($this->input->post('type'))==2)
				{
				if(isset($_POST['question'])) {
					$created_dates = date('Y-m-d h:i:s');
					$this->db->select('*');
					$this->db->from('ala_action_reminders');
					$this->db->where('action_id', $actionId);
					$query = $this->db->get();
					$results = $query->result();
					foreach($results as $resu) {
						foreach($_POST['question'] as $question) {
							if($question != '') {
								$insertQuestionArr = array(
									'actionId'=>$actionId,
									'remId'=>$resu->id,
									'user_id'=>trim($this->session->userdata("user_id")),
									'question'=>$question,
									'created_date'=>$created_dates
								);
								$this->db->insert($this->config->item('ala_action_question','dbtables'), $insertQuestionArr);
							}
						}
					}
				}
				}else {
				if(isset($_POST['question'])) {
					$created_dates = date('Y-m-d h:i:s');
					foreach($_POST['question'] as $question) {
						if($question != '') {
							$insertQuestionArr = array(
								'actionId'=>$actionId,
								'user_id'=>trim($this->session->userdata("user_id")),
								'question'=>$question,
								'created_date'=>$created_dates
							);
							$this->db->insert($this->config->item('ala_action_question','dbtables'), $insertQuestionArr);
						}
					}
				}
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
		/* echo '<pre>'; print_r($_POST); die; */
		/*$checkActionQuery = $this->db->get_where($this->config->item('ala_actions','dbtables'),array("action_title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));
		$checkAction = $checkActionQuery->row();

		if($checkAction && $checkAction->id!=$actionId)
		{
			return "exist";
		}
		else
		{*/
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
					    $datesave = date('Y-m-d', strtotime($this->input->post("remDate")));
						if(isset($_POST['remTime_check'])) {
							$daycheck = 1;
						}else {
							$daycheck = 0;
						}
						$insertRemindersArr = array(
							'action_id' => trim($actionId),
							'date' => $datesave,
							'time' => date('H:i:s', strtotime($this->input->post("remTime"))),
							'status' => 1,
							'daycheck' => $daycheck,
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s"),
						);

						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemindersArr);
					}
					elseif($this->input->post('type') && trim($this->input->post('type'))==2)
					{
						$insertBulkRemArr = array();
						if(isset($_POST['remTime_check'])) {
							$daycheck = 1;
						}else {
							$daycheck = 0;
						}
						$insertBulkRemArr[] = array(
							'action_id' => trim($actionId),
							'time' => date('H:i:s', strtotime($this->input->post("remTime"))),
							'status' => 1,
							'daycheck' => $daycheck,
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s"),
						);
						for($i=1;$i<=trim($this->input->post("remTimeCounter"));$i++)
						{
							if($this->input->post("remTime_".$i))
							{
								$insertRemArr = array();
								if(isset($_POST['remTime_check_'.$i])) {
									$daycheck = 1;
								}else {
									$daycheck = 0;
								}
								$insertRemArr['action_id'] = trim($actionId);
								$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("remTime_".$i)));
								$insertRemArr['status'] = 1;
								$insertRemArr['daycheck'] = $daycheck;
								$insertRemArr['created_at'] = date("Y-m-d H:i:s");
								$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
								$insertBulkRemArr[] = $insertRemArr;
							}
						}

						$this->db->insert_batch($this->config->item('ala_action_reminders','dbtables'), $insertBulkRemArr); 
					}
					elseif($this->input->post('type') && trim($this->input->post('type'))==3)
					{
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Monday';
						if(isset($_POST['monday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_monday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_monday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Tuesday';
						if(isset($_POST['tuesday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_tuesday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_tuesday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Wednesday';
						if(isset($_POST['wednesday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_wednesday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_wednesday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr); 
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Thursday';
						if(isset($_POST['thursday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_thursday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_thursday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr); 
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Friday';
						if(isset($_POST['friday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_friday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_friday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Satureday';
						if(isset($_POST['satureday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_satureday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_satureday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Sunday';
						if(isset($_POST['sunday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_sunday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_sunday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr); 
					}
				$this->db->where('actionId', $actionId);
				$this->db->where('user_id', $this->session->userdata("user_id"));
				$this->db->delete($this->config->item('ala_action_question','dbtables'));
				if($this->input->post('type') && trim($this->input->post('type'))==2)
				{
				if(isset($_POST['question'])) {
					$created_dates = date('Y-m-d h:i:s');
					$this->db->select('*');
					$this->db->from('ala_action_reminders');
					$this->db->where('action_id', $actionId);
					$query = $this->db->get();
					$results = $query->result();
					foreach($results as $resu) {
						foreach($_POST['question'] as $question) {
							if($question != '') {
								$insertQuestionArr = array(
									'actionId'=>$actionId,
									'remId'=>$resu->id,
									'user_id'=>trim($this->session->userdata("user_id")),
									'question'=>$question,
									'created_date'=>$created_dates
								);
								$this->db->insert($this->config->item('ala_action_question','dbtables'), $insertQuestionArr);
							}
						}
					}
				}
				}else {
				if(isset($_POST['question'])) {
					$created_dates = date('Y-m-d h:i:s');
					foreach($_POST['question'] as $question) {
						if($question != '') {
							$insertQuestionArr = array(
								'actionId'=>$actionId,
								'user_id'=>trim($this->session->userdata("user_id")),
								'question'=>$question,
								'created_date'=>$created_dates
							);
							$this->db->insert($this->config->item('ala_action_question','dbtables'), $insertQuestionArr);
						}
					}
				}
				}
			}
			return $res;
		/*}*/
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
		if(isset($_POST['nextweek']) && $_POST['nextweek'] == 1) {
			$insertArr = array(
				'parent_action' => $actionId,
				'user_id' => trim($this->session->userdata("user_id")),
				'action_type_id' => trim($this->input->post("type")),
				'action_title' => trim($this->input->post("title")),
				'status' => 1,
				'is_finished' => 0,
				'nextweek' => 1,
				'weekno' => $this->input->post("weekno"),
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
			);
		}else {
			$insertArr = array(
				'parent_action' => $actionId,
				'user_id' => trim($this->session->userdata("user_id")),
				'action_type_id' => trim($this->input->post("type")),
				'action_title' => trim($this->input->post("title")),
				'status' => 1,
				'is_finished' => 0,
				'nextweek' => 0,
				'weekno' => $this->input->post("weekno"),
				'created_at' => date("Y-m-d H:i:s"),
				'updated_at' => date("Y-m-d H:i:s"),
			);
		}
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
			    $datesave = date('Y-m-d', strtotime($this->input->post("remDate")));
				$insertRemindersArr = array(
				'action_id' => trim($actionId),
				'date' => $datesave,
				'time' => date('H:i:s', strtotime($this->input->post("remTime"))),
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
					'time' => date('H:i:s', strtotime($this->input->post("remTime"))),
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
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("remTime_".$i)));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$insertBulkRemArr[] = $insertRemArr;
					}
				}

				$this->db->insert_batch($this->config->item('ala_action_reminders','dbtables'), $insertBulkRemArr); 
			}
			elseif($this->input->post('type') && trim($this->input->post('type'))==3)
					{
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Monday';
						if(isset($_POST['monday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_monday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_monday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Tuesday';
						if(isset($_POST['tuesday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_tuesday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_tuesday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Wednesday';
						if(isset($_POST['wednesday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_wednesday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_wednesday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr); 
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Thursday';
						if(isset($_POST['thursday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_thursday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_thursday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr); 
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Friday';
						if(isset($_POST['friday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_friday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_friday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Satureday';
						if(isset($_POST['satureday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_satureday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_satureday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr);
						$insertRemArr = array();
						$insertRemArr['action_id'] = trim($actionId);
						$insertRemArr['dayname'] = 'Sunday';
						if(isset($_POST['sunday_check'])) {
							$insertRemArr['daycheck'] = 1;
						}else {
							$insertRemArr['daycheck'] = 0;
						}
						if(in_array('week_sunday',$_POST['selectdays'])) {
							$insertRemArr['day_selected'] = 1;
						}else {
							$insertRemArr['day_selected'] = 0;
						}
						$insertRemArr['time'] = date('H:i:s', strtotime($this->input->post("week_sunday")));
						$insertRemArr['status'] = 1;
						$insertRemArr['created_at'] = date("Y-m-d H:i:s");
						$insertRemArr['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert($this->config->item('ala_action_reminders','dbtables'), $insertRemArr); 
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
			if($this->input->post('type') && trim($this->input->post('type'))==2)
			{
				if(isset($_POST['question'])) {
					$created_dates = date('Y-m-d h:i:s');
					$this->db->select('*');
					$this->db->from('ala_action_reminders');
					$this->db->where('action_id', $actionId);
					$query = $this->db->get();
					$results = $query->result();
					foreach($results as $resu) {
						foreach($_POST['question'] as $question) {
							if($question != '') {
								$insertQuestionArr = array(
									'actionId'=>$actionId,
									'remId'=>$resu->id,
									'user_id'=>trim($this->session->userdata("user_id")),
									'question'=>$question,
									'created_date'=>$created_dates
								);
								$this->db->insert($this->config->item('ala_action_question','dbtables'), $insertQuestionArr);
							}
						}
					}
				}
			}else {
				if(isset($_POST['question'])) {
					$created_dates = date('Y-m-d h:i:s');
					foreach($_POST['question'] as $question) {
						if($question != '') {
							$insertQuestionArr = array(
								'actionId'=>$actionId,
								'user_id'=>trim($this->session->userdata("user_id")),
								'question'=>$question,
								'created_date'=>$created_dates
							);
							$this->db->insert($this->config->item('ala_action_question','dbtables'), $insertQuestionArr);
						}
					}
				}
			}
		}
		return $actionId;
	}
}
?>