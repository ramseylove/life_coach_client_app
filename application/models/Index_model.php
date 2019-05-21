<?php
class Index_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('dbtables', TRUE);		
	}
	
	function getBannerImages($banner_id = null)
	{
		if($banner_id!=null){

			$activitiesQuery = $this->db->get_where($this->config->item('et_banner','dbtables'),array("id"=>$banner_id));
			return $activitiesQuery->row();
	
		}else{
			
			$activitiesQuery = $this->db->get($this->config->item('et_banner','dbtables'));
			return $activitiesQuery->result();
		}
	}
	
	function getSettings()
	{
		$settingsQuery = $this->db->get($this->config->item('et_settings','dbtables'));
		return $settingsQuery->row();
	}
	
	function getServices()
	{
		$servicesQuery = $this->db->get($this->config->item('et_services','dbtables'));
		return $servicesQuery->result();
	}
	
	function getServiceCms()
	{
		$serviceCMSQuery = $this->db->get_where($this->config->item('et_cms_content','dbtables'),array("page_id"=>2));
		return $serviceCMSQuery->row();
	}
	
	function getBlogs()
	{
		$this->db->order_by('id','desc');
		$blogsQuery = $this->db->get_where($this->config->item('et_blogs','dbtables'),array("status"=>0));
		return $blogsQuery->result();
	}
	
	function getBlogCms()
	{
		$blogCMSQuery = $this->db->get_where($this->config->item('et_cms_content','dbtables'),array("page_id"=>7));
		return $blogCMSQuery->row();
	}
	
	function getTeam()
	{
		$teamQuery = $this->db->get($this->config->item('et_team','dbtables'));
		return $teamQuery->result();
	}
	
	function getTeamCms()
	{
		$teamCMSQuery = $this->db->get_where($this->config->item('et_cms_content','dbtables'),array("page_id"=>3));
		return $teamCMSQuery->row();
	}
	
	function getAboutUs()
	{
		$auQuery = $this->db->get_where($this->config->item('et_services','dbtables'),array("add_to_about_us"=>1));
		return $auQuery->result();
	}
	
	function getAboutUsCms()
	{
		$auCMSQuery = $this->db->get_where($this->config->item('et_cms_content','dbtables'),array("page_id"=>1));
		return $auCMSQuery->row();
	}
	
	function showSectionDetails($sectionId)
	{
		$servicesQuery = $this->db->get_where($this->config->item('et_services','dbtables'),array("id"=>$sectionId));
		return $servicesQuery->row();
	}
	
	function getBlogDetails($slug)
	{
		$blogDetailsQuery = $this->db->get_where($this->config->item('et_blogs','dbtables'),array("blog_slug"=>trim($slug)));
		return $blogDetailsQuery->row();
	}
	
	function getPageDetails($pageId)
	{
		$this->db->select("p.*,c.*");
		$this->db->from($this->config->item('et_cms_pages','dbtables')." p");
		$this->db->join($this->config->item('et_cms_content','dbtables')." c","c.page_id=p.id");
		$this->db->where("p.id",trim($pageId));
		$pageDetailsQuery = $this->db->get();
		return $pageDetailsQuery->row();
	}
}
?>