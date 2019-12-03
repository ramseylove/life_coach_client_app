<?php
class User_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('dbtables', TRUE);		
	}
	
	function getUsers($page)
	{	
		
		$this->db->limit(10, $page);
		$usersQuery = $this->db->get_where($this->config->item('ala_user','dbtables'), array("archived" => 0));
		/* echo $this->db->last_query(); die; */
		return $usersQuery->result();
	}
	
	function getUsersCount()
	{
		return $this->db->count_all_results($this->config->item('ala_user','dbtables'));
	}
	
	function insertUser()
	{
		$checkUserEmail = $this->db->get_where($this->config->item('ala_user','dbtables'),array("email"=>trim($this->input->post("email"))));
		/* $checkUserPhone = $this->db->get_where($this->config->item('ala_user','dbtables'),array("phone"=>trim($this->input->post("phone")))); */
		if($checkUserEmail->row())
		{
			return "email_exist";
		}
		/* elseif($checkUserPhone->row())
		{
			return "phone_exist";
		} */
		else
		{
			$insertArr = array(
								'admin_id' => trim($this->session->userdata('admin_id')),
								'created_at' => date("Y-m-d H:i:s"),
								'updated_at' => date("Y-m-d H:i:s"),
								'first_name' =>  trim($this->input->post("first_name")),
								'last_name' =>  trim($this->input->post("last_name")),
								'email' => trim($this->input->post("email")),
								'phone' => trim($this->input->post("phone")),
								'password' => MD5(trim($this->input->post("password"))),
								'status' => trim($this->input->post("status")),
								'editpermission' => 0,
							  );
			$result = $this->db->insert($this->config->item('ala_user','dbtables'), $insertArr);
			$userId =  $this->db->insert_id();
			return $userId;
		}
	}
	
	function getUserData($userId)
	{
		$getUserQuery = $this->db->get_where($this->config->item('ala_user','dbtables'),array("id"=>trim($userId)));
		$userData = $getUserQuery->row();
		return $userData;
	}
	
	function updateUserImage($lastId,$fileName)
	{
		$data = array( 
						'photo' => $fileName			   
					);
		$this->db->where('id',$lastId);
		$res = $this->db->update($this->config->item('ala_user','dbtables'), $data);
		return $res;
	}

	function updateUser($userId)
	{
		$checkUserEmailQuery = $this->db->get_where($this->config->item('ala_user','dbtables'),array("email"=>trim($this->input->post("email"))));
		$checkUserEmail = $checkUserEmailQuery->row();
		
		/* $checkUserPhoneQuery = $this->db->get_where($this->config->item('ala_user','dbtables'),array("phone"=>trim($this->input->post("phone"))));
		$checkUserPhone = $checkUserPhoneQuery->row(); */
		
		if($checkUserEmail && $checkUserEmail->id!=$userId)
		{
			return "email_exist";
		}
		/* elseif($checkUserPhone && $checkUserPhone->id!=$userId)
		{
			return "phone_exist";
		} */
		else
		{
			$data = array(
							'updated_at' => date("Y-m-d H:i:s"),
							'first_name' =>  trim($this->input->post("first_name")),
							'last_name' =>  trim($this->input->post("last_name")),
							'email' => trim($this->input->post("email")),
							'phone' => trim($this->input->post("phone")),
							'status' => trim($this->input->post("status")),
						);
						
			if($this->input->post("password") && trim($this->input->post("password"))!='')
			{
				$data['password'] = MD5(trim($this->input->post("password")));
			}
			$this->db->where('id',$userId);
			$res = $this->db->update($this->config->item('ala_user','dbtables'), $data);
			return $res;
		}
	}
	
	function changeUserStatus($userId,$status)
	{
		$data = array( 
						'status' => $status		   
					);
		$this->db->where('id',$userId);
		$res = $this->db->update($this->config->item('ala_user','dbtables'), $data);
		return $res;
	}
	function changeUserPermission($userId,$status)
	{
		$data = array( 
						'editpermission' => $status		   
					);
		$this->db->where('id',$userId);
		$res = $this->db->update($this->config->item('ala_user','dbtables'), $data);
		return $res;
	}
	function deleteUser($userId)
	{
		/* $data = array( 
						'archived' => 1	   
					);
		$this->db->where('id',$userId);
		$res = $this->db->update($this->config->item('ala_user','dbtables'), $data); */
		$this->db->where('id', $userId);
		$res = $this->db->delete($this->config->item('ala_user','dbtables'));
		return $res;
	}
	function getLastPostMeeting($user_id)
	{
		$this->db->select('*');
		$this->db->from('ala_post_meeting');
		$this->db->where('user_id', $user_id);
		$this->db->order_by('id', 'DESC');
		$this->db->order_by('weekno', 'DESC');
		$this->db->limit('1');
		$query = $this->db->get();
		return $ret = $query->row();
	}
	function getActionTypeData()
	{
		$actionTypeQuery = $this->db->get($this->config->item('ala_action_types','dbtables'));
		return $actionTypeQuery->result();
	}
	function getGoals($user_id)
	{
		$goalsQuery = $this->db->get_where($this->config->item('ala_goals','dbtables'), array("user_id"=>trim($user_id)));
		return $goalsQuery->result();
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
					'addedby' => 1,

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