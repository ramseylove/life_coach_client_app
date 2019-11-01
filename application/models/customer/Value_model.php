<?php
class Value_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('dbtables', TRUE);		
	}
	
	function getValues($page)
	{
		$this->db->order_by('id','desc');
		$this->db->limit(ROWS_PER_PAGE, $page);
		$valuesQuery = $this->db->get_where($this->config->item('ala_value_identifiers','dbtables'), array("user_id"=>trim($this->session->userdata("user_id"))));
		return $valuesQuery->result();
	}
	
	function getUserAllValues()
	{
		$valuesQuery = $this->db->get_where($this->config->item('ala_value_identifiers','dbtables'), array("user_id"=>trim($this->session->userdata("user_id"))));
		return $valuesQuery->result();
	}
	
	function getValuesCount()
	{
		$this->db->where('user_id', trim($this->session->userdata("user_id")));
		return $this->db->count_all_results($this->config->item('ala_value_identifiers','dbtables'));
	}
	
	function getDefaultValues()
	{
	    $checkDefaultValue = $this->db->get_where($this->config->item('ala_default_values','dbtables'),array("addedby"=>0));
	   return $checkDefaultValueR = $checkDefaultValue->result();
	}
	
	function getUserAddedValues()
	{
	    $checkDefaultValue = $this->db->get_where($this->config->item('ala_default_values','dbtables'),array("addedby"=>trim($this->session->userdata("user_id"))));
	     return $checkDefaultValueR = $checkDefaultValue->result();
	}
	
	function addValueForUser()
	{
	    $checkDefaultValue = $this->db->get_where($this->config->item('ala_default_values','dbtables'),array("value_title"=>trim($this->input->post("value_title")), "addedby"=>0));
	    $checkUserValue = $this->db->get_where($this->config->item('ala_default_values','dbtables'),array("value_title"=>trim($this->input->post("value_title")), "addedby"=>trim($this->session->userdata("user_id"))));
	    if($checkDefaultValue->row() || $checkUserValue->row())
		{
			return "exist";
		}
		else
		{
		    $insertArr = array(
								'addedby' => trim($this->session->userdata("user_id")),
								'value_title' => trim($this->input->post("value_title")),
							  );
			$result = $this->db->insert($this->config->item('ala_default_values','dbtables'), $insertArr);
			return $valueId =  $this->db->insert_id();
		}
	}
	
	function insertValue()
	{
		$checkValue = $this->db->get_where($this->config->item('ala_value_identifiers','dbtables'),array("title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));
		if($checkValue->row())
		{
			return "exist";
		}
		else
		{
			$insertArr = array(
								'user_id' => trim($this->session->userdata("user_id")),
								'default_value_id' => $this->input->post("default_value_id"),
								'title' => trim($this->input->post("title")),
								'current_happiness_level' => trim($this->input->post("current_happiness_level")),
								'expected_happiness_level' => trim($this->input->post("expected_happiness_level")),
								'description_0' => trim($this->input->post("description_0")),
								'description_1' => trim($this->input->post("description_1")),
								'description_2' => trim($this->input->post("description_2")),
								'description_3' => trim($this->input->post("description_3")),
								'created_at' => date("Y-m-d H:i:s"),
								'updated_at' => date("Y-m-d H:i:s"),
							  );
			$result = $this->db->insert($this->config->item('ala_value_identifiers','dbtables'), $insertArr);
			$valueId =  $this->db->insert_id();
			
		/*	if($valueId && $valueId>0)
			{
				$insertDetailsArr = array();
				for($i=0; $i<=3; $i++)
				{
					$insertDetailsArr[] = array(
													'user_id' => trim($this->session->userdata("user_id")),
													'value_identifier_id' => trim($valueId),
													'description' => trim($this->input->post("description_".$i."")),
													'identifier' => "description_".$i."",
													'created_at' => date("Y-m-d H:i:s"),
													'updated_at' => date("Y-m-d H:i:s"),
												);
				}
				$res = $this->db->insert_batch($this->config->item('ala_value_identifier_details','dbtables'), $insertDetailsArr);
			} */
			return $valueId;
		}
	}
	
	function getValueData($valueId)
	{
		$this->db->where('id', trim($valueId));
		$getValueQuery = $this->db->get($this->config->item('ala_value_identifiers','dbtables'));
		$valueData = $getValueQuery->row();
		if($valueData)
		{
			$valueData->details = array();
			$this->db->where('value_identifier_id', trim($valueId));
			$getValueDetailsQuery = $this->db->get($this->config->item('ala_value_identifier_details','dbtables'));
			$getValueDetails = $getValueDetailsQuery->result();
			if($getValueDetails)
			{
				$valueData->details = $getValueDetails;
			}
		}
		return $valueData;
	}

	function updateValue($valueId)
	{
		$checkValueQuery = $this->db->get_where($this->config->item('ala_value_identifiers','dbtables'),array("title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));
		$checkValue = $checkValueQuery->row();
		
		if($checkValue && $checkValue->id!=$valueId)
		{
			return "exist";
		}
		else
		{
			$data = array(
							'title' => trim($this->input->post("title")),
							'current_happiness_level' => trim($this->input->post("current_happiness_level")),
							'expected_happiness_level' => trim($this->input->post("expected_happiness_level")),
							'description_0' => trim($this->input->post("description_0")),
								'description_1' => trim($this->input->post("description_1")),
								'description_2' => trim($this->input->post("description_2")),
								'description_3' => trim($this->input->post("description_3")),
							'updated_at' => date("Y-m-d H:i:s"),
						);
			$this->db->where('id',$valueId);
			$res = $this->db->update($this->config->item('ala_value_identifiers','dbtables'), $data);
			
			/* if($res)
			{
				$this->db->where('value_identifier_id', $valueId);
				$this->db->delete($this->config->item('ala_value_identifier_details','dbtables'));
			
				$insertDetailsArr = array();
				for($i=0; $i<=3; $i++)
				{
					$insertDetailsArr[] = array(
													'user_id' => trim($this->session->userdata("user_id")),
													'value_identifier_id' => trim($valueId),
													'description' => trim($this->input->post("description_".$i."")),
													'identifier' => "description_".$i."",
													'created_at' => date("Y-m-d H:i:s"),
													'updated_at' => date("Y-m-d H:i:s"),
												);
				}
				$res = $this->db->insert_batch($this->config->item('ala_value_identifier_details','dbtables'), $insertDetailsArr);
			} */
			
			return $res;
		}
	}
	
	function deleteValue($valueId)
	{
		$this->db->where('id', $valueId);
		$res = $this->db->delete($this->config->item('ala_value_identifiers','dbtables'));
		if($res)
		{
			$this->db->where('value_identifier_id', $valueId);
			$this->db->delete($this->config->item('ala_value_identifier_details','dbtables'));
		}
		return $res;
	}
	
	function getAddedValueData($id)
	{
	    $checkDefaultValue = $this->db->get_where($this->config->item('ala_default_values','dbtables'),array("id"=>$id));
	     return $checkDefaultValueR = $checkDefaultValue->row();
	}
	
	function getValueIdentifier($id)
	{
	    $checkDefaultValue = $this->db->get_where($this->config->item('ala_value_identifiers','dbtables'),array("default_value_id"=>$id, 'user_id' => trim($this->session->userdata("user_id"))));
	     return $checkDefaultValueR = $checkDefaultValue->row();
	}
	
	function deleteAddedValue($valueId)
	{
		$this->db->where('id', $valueId);
		$res = $this->db->delete($this->config->item('ala_default_values','dbtables'));
		return $res;
	}
}
?>