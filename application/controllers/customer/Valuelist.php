<?php
class Valuelist extends CE_Controller {
	public function __construct()
    {		
        parent::__construct();   
		$this->load->model('customer/valuelist_model');
    } 
	public function index($page=0)
	{
		$this->load->library('pagination');
		$config['base_url'] = $this->config->item("valueListCtrl")."/index";
		$config['total_rows'] = $this->valuelist_model->getValuesCount();
		$config['per_page'] = ROWS_PER_PAGE;
		$config['prev_tag_open'] = '<button type="button" class="btn btn-white"><i class="fa fa-chevron-left">';
		$config['prev_tag_close'] = '</i></button>';
		$config['next_tag_open'] = '<button type="button" class="btn btn-white"><i class="fa fa-chevron-right">';
		$config['next_tag_close'] = '</i></button>';
		$config['cur_tag_open'] = '<button type="button" class="btn btn-primary">';
		$config['cur_tag_close'] = '</button>';
		$config['num_tag_open'] = '<button type="button" class="btn btn-white">';
		$config['num_tag_close'] = '</button>';
		$this->pagination->initialize($config);
		$viewArr = array();
		$values = $this->valuelist_model->getValues($page);
		$viewArr["values"] = $values;
		$viewArr["defaultValues"] = $this->valuelist_model->getDefaultValues();
		$viewArr["userAddedValues"] = $this->valuelist_model->getUserAddedValues();
		/* $viewArr["allValues"] = $this->valuelist_model->getUserAllValues(); */
		$addedAll = array();
		foreach($viewArr["defaultValues"] as $keys => $vals) {
			 $savedVals = $this->valuelist_model->getValueIdentifier($vals->id);
			 if(!empty($savedVals)) {
				$viewArr["defaultValues"][$keys]->savedVals = $savedVals;
			 }else {
				$viewArr["defaultValues"][$keys]->savedVals = ''; 
			 }
		}
		foreach($viewArr["userAddedValues"] as $keys => $vals) {
			 $savedVals = $this->valuelist_model->getValueIdentifier($vals->id);
			 if(!empty($savedVals)) {
				$viewArr["userAddedValues"][$keys]->savedVals = $savedVals;
			 }else {
				$viewArr["userAddedValues"][$keys]->savedVals = ''; 
			 }
		}
		/* $i = 0;
		foreach($viewArr["allValues"] as $userA) {
			$addedAll[$i] = $userA->default_value_id;
			$i++;
		}
		$viewArr["userAllValues"] = $addedAll; */
		if(isset($_GET["pagination"]))
		{
			$this->load->view('customer/manage_value_list',$viewArr);
		}
		else
		{
			$viewArr["viewPage"] = "manage_value_list";
			$this->load->view('customer/layout',$viewArr);
		}
	}
}
?>