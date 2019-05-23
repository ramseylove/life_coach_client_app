<?phpclass Value_model extends CI_Model{	function __construct()	{		parent::__construct();		$this->load->database();		$this->config->load('dbtables', TRUE);			}		function getValues()	{		$valuesQuery = $this->db->get_where($this->config->item('ala_value_identifiers','dbtables'), array("user_id"=>trim($this->session->userdata("user_id"))));		return $valuesQuery->result();	}		function getValuesCount()	{		$this->db->where('user_id', trim($this->session->userdata("user_id")));		return $this->db->count_all_results($this->config->item('ala_value_identifiers','dbtables'));	}		function insertValue()	{		$checkValue = $this->db->get_where($this->config->item('ala_value_identifiers','dbtables'),array("title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));		if($checkValue->row())		{			return "exist";		}		else		{			$insertArr = array(								'user_id' => trim($this->session->userdata("user_id")),								'title' => trim($this->input->post("title")),								'created_at' => date("Y-m-d H:i:s"),								'updated_at' => date("Y-m-d H:i:s"),							  );			$result = $this->db->insert($this->config->item('ala_value_identifiers','dbtables'), $insertArr);			$valueId =  $this->db->insert_id();						if($valueId && $valueId>0)			{				$insertDetailsArr = array(											'user_id' => trim($this->session->userdata("user_id")),											'value_identifier_id' => trim($valueId),											'description' => trim($this->input->post("description")),											'current_happiness_level' => trim($this->input->post("current_happiness_level")),											'expected_happiness_level' => trim($this->input->post("expected_happiness_level")),											'created_at' => date("Y-m-d H:i:s"),											'updated_at' => date("Y-m-d H:i:s"),										  );							  				$res = $this->db->insert($this->config->item('ala_value_identifier_details','dbtables'), $insertDetailsArr);			}			return $valueId;		}	}		function getValueData($valueId)	{		$this->db->join($this->config->item('ala_value_identifier_details','dbtables').' avid', 'avid.value_identifier_id=avi.id');		$this->db->where('avi.id', trim($valueId));		$getValueQuery = $this->db->get($this->config->item('ala_value_identifiers','dbtables').' avi');		$valueData = $getValueQuery->row();		return $valueData;	}	function updateValue($valueId)	{		$checkValueQuery = $this->db->get_where($this->config->item('ala_value_identifiers','dbtables'),array("title"=>trim($this->input->post("title")), "user_id"=>trim($this->session->userdata("user_id"))));		$checkValue = $checkValueQuery->row();				if($checkValue && $checkValue->id!=$valueId)		{			return "exist";		}		else		{			$data = array(							'title' => trim($this->input->post("title")),							'updated_at' => date("Y-m-d H:i:s"),						);			$this->db->where('id',$valueId);			$res = $this->db->update($this->config->item('ala_value_identifiers','dbtables'), $data);						if($res)			{				$updateDetailsArr = array(											'description' => trim($this->input->post("description")),											'current_happiness_level' => trim($this->input->post("current_happiness_level")),											'expected_happiness_level' => trim($this->input->post("expected_happiness_level")),											'updated_at' => date("Y-m-d H:i:s"),										  );										  				$this->db->where('value_identifier_id',$valueId);				$this->db->update($this->config->item('ala_value_identifier_details','dbtables'), $updateDetailsArr);			}						return $res;		}	}		function deleteValue($valueId)	{		$this->db->where('id', $valueId);		$res = $this->db->delete($this->config->item('ala_value_identifiers','dbtables'));		if($res)		{			$this->db->where('value_identifier_id', $valueId);			$this->db->delete($this->config->item('ala_value_identifier_details','dbtables'));		}		return $res;	}	}?>