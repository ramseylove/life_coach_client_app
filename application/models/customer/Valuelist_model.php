<?php
class Valuelist_model extends CI_Model
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
	function getUserAllValues()
	{
		$valuesQuery = $this->db->get_where($this->config->item('ala_value_identifiers','dbtables'), array("user_id"=>trim($this->session->userdata("user_id"))));
		return $valuesQuery->result();
	}
	function getValueIdentifier($id)
	{
		$checkDefaultValue = $this->db->get_where($this->config->item('ala_value_identifiers','dbtables'),array("default_value_id"=>$id, 'user_id' => trim($this->session->userdata("user_id"))));
		return $checkDefaultValueR = $checkDefaultValue->row();
	}
}
?>