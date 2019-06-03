<?php
class Value extends CE_Controller {

	public function __construct()
    {		
        parent::__construct();   
		$this->load->model('customer/value_model');
    } 
	
	public function index($page=0)
	{
		$this->load->library('pagination');

		$config['base_url'] = $this->config->item("valueIdentifierCtrl")."/index";
		$config['total_rows'] = $this->value_model->getValuesCount();
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
		$values = $this->value_model->getValues($page);
		
		$viewArr["values"] = $values;
		
		if(isset($_GET["pagination"]))
		{
			$this->load->view('customer/manage_value_identifiers',$viewArr);
		}
		else
		{
			$viewArr["viewPage"] = "manage_value_identifiers";
			$this->load->view('customer/layout',$viewArr);
		}
	}
	
	public function addValue()
	{	
		$viewArr = array();
		$viewArr["valueData"] = array();
		$viewArr["postData"] = array();
	
		if($this->session->userdata("postData"))
		{
			$viewArr["postData"] = $this->session->userdata("postData");
			$this->session->unset_userdata("postData");
		}
	
		$html = $this->load->view('customer/add_value',$viewArr,TRUE);
		echo $html;
		exit;
	}
	
	public function editValue($valueId)
	{	
		$valueData = $this->value_model->getValueData($valueId);
		if($valueData)
		{
			$viewArr = array();
			$viewArr["postData"] = array();
		
			if($this->session->userdata("postData"))
			{
				$viewArr["postData"] = $this->session->userdata("postData");
				$this->session->unset_userdata("postData");
			}
			
			$viewArr["valueData"] = $valueData;
			$html = $this->load->view('customer/add_value',$viewArr,TRUE);
		}
		else
		{
			$html = "<h4>No Value Identifier Data Found.</h4>";
		}
		echo $html;
		exit;
	}
	
	public function insertValue($valueId=0)
	{
		$message = array();
		$pass = true;
		
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning"><p style="color:red;">', '</p></div>');
		$this->form_validation->set_rules('title', 'Value Identifier Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', 'Value Identifier Description', 'trim|required|xss_clean');
		$this->form_validation->set_rules('current_happiness_level', 'Value Identifier Current Happiness Level', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('expected_happiness_level', 'Value Identifier Expected Happiness Level', 'trim|required|xss_clean|numeric');
	
		if ($this->form_validation->run() == FALSE)
		{
			$pass = false;
			if(form_error('title'))
			{
				$message[] = form_error('title');
			}
			if(form_error('description'))
			{
				$message[] = form_error('description');
			}
			if(form_error('current_happiness_level'))
			{
				$message[] = form_error('current_happiness_level');
			}
			if(form_error('expected_happiness_level'))
			{
				$message[] = form_error('expected_happiness_level');
			}
		}
		else
		{
			if($valueId==0)
			{
				$res = $this->value_model->insertValue();
			}
			else
			{
				$res = $this->value_model->updateValue($valueId);
			}
			
			if($res && trim($res)!="exist")
			{
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Value Indentifier saved successfully.</p></div>";
				if($valueId==0)
				{
					$valueId = $res;
				}
			}
			elseif($res && trim($res)=="exist")
			{
				$pass = false;
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Value Indentifier with entered title already exists.</p></div>";
			}
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"valueId"=>$valueId));
		exit;
	}
	
	public function deleteValue($valueId)
	{
		$pass = false;
		$message = array();
		$valueData = $this->value_model->getValueData($valueId);
		if($valueData)
		{
			$res = $this->value_model->deleteValue($valueId);
			if($res)
			{
				$pass = true;
				$message[] = "<div class='alert alert-success'><p style='color:green;'>Value Indentifier deleted successfully.</p></div>";	
			}
			else
			{
				$message[] = "<div class='alert alert-warning'><p style='color:red;'>Failed to delete value indentifier.</p></div>";
			}
		}
		else
		{
			$message[] = "<div class='alert alert-warning'><p style='color:red;'>Value Indentifier data not found.</p></div>";
		}
		
		echo json_encode(array("success"=>$pass,"message"=>$message,"valueId"=>$valueId));
		exit;
	}
}