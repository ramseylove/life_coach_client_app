<?php
class Login_model extends CI_Model{
	function __construct()	{
		parent::__construct();
		$this->load->database();
		$this->config->load('dbtables', TRUE);
	}
	function loginCheck(){
		$query = $this->db->get_where($this->config->item('ala_user','dbtables'),array("email"=>trim($this->input->post("email")),"password"=>md5(trim($this->input->post("pswd")))));
		return $query->row();
	}
	function forgotEmailCheck($email){
		$query = $this->db->get_where($this->config->item('ala_user','dbtables'),array("email"=>trim($email)));
		return $query->row();
	}
	function forgotRandomCheck($id,$random) {
	    $query = $this->db->get_where($this->config->item('ala_user','dbtables'),array("random"=>trim($random),"id"=>$id));
		return $query->row();
	}
	function loginCheckAdmin($userid){
		$query = $this->db->get_where($this->config->item('ala_user','dbtables'),array("id"=>$userid));
		return $query->row();
	}
	function getUser()	{
		$query = $this->db->get_where($this->config->item('ala_user','dbtables'),array("email"=>trim($this->input->post("email")),"id"=>trim($this->session->userData("user_id")),"password"=>md5(trim($this->input->post("oldPswd")))));
		return $query->row();
	}
	function finalChangePswd($userId)	{
		$data = array(
		'password' => md5(trim($this->input->post("newPswd")))
		);
		$this->db->where('id',$userId);
		$res = $this->db->update($this->config->item('ala_user','dbtables'), $data);
		return $res;
	}
	function updateuserrandom($email,$random) {
	   $data = array(
		'random' => $random
		);
		$this->db->where('email',$email);
		$res = $this->db->update($this->config->item('ala_user','dbtables'), $data);
		return $res; 
	}
	function updatehePassword($pass,$rendom,$id){
	     $data = array(
		'random' => '',
		'password' => md5(trim($pass))
		);
		$this->db->where('id',$id);
		$res = $this->db->update($this->config->item('ala_user','dbtables'), $data); 
		return $res; 
	}
}
?>