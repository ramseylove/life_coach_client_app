<?php
class Dashboard extends BE_Controller {

	public function __construct()
    {
        parent::__construct();   
		$this->load->model('dash_model');
    } 
	
	public function index()
	{	
		$viewArr = array();
		
		$stats = $this->dash_model->getDashStats();
		$viewArr["stats"] = $stats;
		$viewArr["viewPage"] = "dashboard";
		$this->load->view('backend/layout',$viewArr);
	}
	
}