<?php
class Login_model extends CI_Model{	
function __construct()	{		
parent::__construct();		
$this->load->database();		
$this->config->load('dbtables', TRUE);			

}	
function loginCheck(){		
	$query = $this->db->get_where(
	$this->config->item('ala_admin','dbtables'),array("email"=>trim($this->input->post("email")),"password"=>md5(trim($this->input->post("pswd")))));		return $query->row();	}		function getAdmin()	{		$query = $this->db->get_where($this->config->item('ala_admin','dbtables'),array("email"=>trim($this->input->post("email")),"id"=>trim($this->session->userData("admin_id")),"password"=>md5(trim($this->input->post("oldPswd")))));		return $query->row();	}		function finalChangePswd($userId)	{		$data = array( 						'password' => md5(trim($this->input->post("newPswd")))		   					);		$this->db->where('id',$userId);		$res = $this->db->update($this->config->item('ala_admin','dbtables'), $data);		return $res;	}	}?>