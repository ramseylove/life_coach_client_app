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
		$this->db->limit(ROWS_PER_PAGE, $page*ROWS_PER_PAGE);
		$usersQuery = $this->db->get_where($this->config->item('ala_user','dbtables'), array("archived" => 0));
		return $usersQuery->result();
	}
	
	function getUsersCount()
	{
		return $this->db->count_all_results($this->config->item('ala_user','dbtables'));
	}
	
	function insertUser()
	{
		$checkUserEmail = $this->db->get_where($this->config->item('ala_user','dbtables'),array("email"=>trim($this->input->post("email"))));
		$checkUserPhone = $this->db->get_where($this->config->item('ala_user','dbtables'),array("phone"=>trim($this->input->post("phone"))));
		if($checkUserEmail->row())
		{
			return "email_exist";
		}
		elseif($checkUserPhone->row())
		{
			return "phone_exist";
		}
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
		
		$checkUserPhoneQuery = $this->db->get_where($this->config->item('ala_user','dbtables'),array("phone"=>trim($this->input->post("phone"))));
		$checkUserPhone = $checkUserPhoneQuery->row();
		
		if($checkUserEmail && $checkUserEmail->id!=$userId)
		{
			return "email_exist";
		}
		elseif($checkUserPhone && $checkUserPhone->id!=$userId)
		{
			return "phone_exist";
		}
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
		$data = array( 
						'archived' => 1	   
					);
		$this->db->where('id',$userId);
		$res = $this->db->update($this->config->item('ala_user','dbtables'), $data);
		return $res;
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
	function insertAction($userId)
	{
		$checkAction = $this->db->get_where($this->config->item('ala_actions','dbtables'),array("action_title"=>trim($this->input->post("title")), "user_id"=>trim($userId)));
		if($checkAction->row())
		{
			return "exist";
		}
		else
		{
			$insertArr = array(
								'user_id' => trim($userId),
								'addedby' => 1,
								'action_type_id' => trim($this->input->post("type")),
								'action_title' => trim($this->input->post("title")),
								'status' => 1,
								'is_finished' => 0,
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
						$insertActionArr['user_id'] = trim($userId);
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
			}
			return $actionId;
		}
	}
}
?>