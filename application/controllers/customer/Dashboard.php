<?php
class Dashboard extends CE_Controller {

	public function __construct()
    {
        parent::__construct();   
		$this->load->model('customer/dash_model');
    } 
	
	public function index()
	{	
		$viewArr = array();
		
		
		$viewArr["viewPage"] = "dashboard";
		$this->load->view('customer/layout',$viewArr);
	}
	
}