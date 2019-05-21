<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends FE_Controller {

	public $isHomePage = false;
	
	public function __construct()
    {
        parent::__construct(); 
		$this->load->model('index_model');		
    } 
	
	public function index()
	{	
		redirect($this->config->item('loginCtrlCustomer'));
	}
	
	function sendContactInfo()
	{
		$message = array();
		
		include_once 'assets/securimage/securimage.php';
		$securimage = new Securimage();
		if($securimage->check(trim($_POST['captcha_code']))==false) 
		{
			$message[] = '<p>Invalid Captcha code.Please enter valid captcha code.</p>';
			$this->session->set_flashdata('postArr', $_POST);
		}
		else
		{
			$subject = 'Contact Response : '.BRAND.' Website - '.date("d/m/Y").'';
			$msg = $this->load->view('email_templates/contact_us',$_POST,TRUE);
			$resEmail = $this->sendMailUsingPhpMailer($this->config->item("contactMeEmail"),$subject,$msg);
			
			if($resEmail)
			{
				$message[] = '<p>Email sent successfully. Thank you!!</p>';
			}
			else
			{
				$message[] = '<p>Failed to send an email. Please try again after some time.</p>';
				$this->session->set_flashdata('postArr', $_POST);
			}
		}
		
		$this->session->set_flashdata('message', $message);
		redirect($this->config->item("indexCtrl")."#contact");
	}
	
	function getAboutUsCms()
	{
		$aboutUsCms = $this->index_model->getAboutUsCms();
		return $aboutUsCms;
	}
	
	function getServiceCms()
	{
		$serviceCms = $this->index_model->getServiceCms();
		return $serviceCms;
	}
	
	function getBlogCms()
	{
		$blogCms = $this->index_model->getBlogCms();
		return $blogCms;
	}
	
	function getTeamCms()
	{
		$teamCms = $this->index_model->getTeamCms();
		return $teamCms;
	}
	
	function showSectionDetails($sectionId=0)
	{
		$viewArr = array();
		$sectionDetails = $this->index_model->showSectionDetails($sectionId);
		if($sectionDetails)
		{
			$settings = $this->index_model->getSettings();
			$aboutUs = $this->index_model->getAboutUs();
			
			$viewArr['sectionDetails'] = $sectionDetails;
			$viewArr['settings'] = $settings;
			$viewArr['aboutUs'] = $aboutUs;
			
			$viewArr["viewPage"] = "section_details";
			$this->load->view('frontend/layout',$viewArr);
		}
		else
		{
			redirect(base_url());
		}
	}
	
	function blogs($slug="")
	{
		$viewArr = array();
		$blogDetails = $this->index_model->getBlogDetails($slug);
		if($blogDetails)
		{
			$settings = $this->index_model->getSettings();
			$aboutUs = $this->index_model->getAboutUs();
			
			$viewArr['blogDetails'] = $blogDetails;
			$viewArr['settings'] = $settings;
			$viewArr['aboutUs'] = $aboutUs;
			
			$viewArr["viewPage"] = "blog_details";
			$this->load->view('frontend/layout',$viewArr);
		}
		else
		{
			redirect(base_url());
		}
	}
	
	function cms($pageId=0)
	{
		$viewArr = array();
		$pageDetails = $this->index_model->getPageDetails($pageId);
		if($pageDetails)
		{
			$settings = $this->index_model->getSettings();
			$aboutUs = $this->index_model->getAboutUs();
			
			$viewArr['pageDetails'] = $pageDetails;
			$viewArr['settings'] = $settings;
			$viewArr['aboutUs'] = $aboutUs;
			
			$viewArr["viewPage"] = "cms_details";
			$this->load->view('frontend/layout',$viewArr);
		}
		else
		{
			redirect(base_url());
		}
	}
}