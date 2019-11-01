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
		$viewArr["defaultValues"] = $this->value_model->getDefaultValues();
		$viewArr["userAddedValues"] = $this->value_model->getUserAddedValues();
		$userAllValues = $this->value_model->getUserAllValues();
		$addedAll = array();
		$i = 0;
		foreach($userAllValues as $userA) {
			$addedAll[$i] = $userA->default_value_id;
			$i++;
		}
		$viewArr["userAllValues"] = $addedAll;
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
	public function addValue($id)
	{	
		$viewArr = array();
		$viewArr["valueData"] = array();
		/* $viewArr["postData"] = array();
		if($this->session->userdata("postData"))
		{
			$viewArr["postData"] = $this->session->userdata("postData");
			$this->session->unset_userdata("postData");
		} */
		$viewArr['valueIdentifier'] = $this->value_model->getValueIdentifier($id);
		$viewArr['addedValueData'] = $this->value_model->getAddedValueData($id);
		$viewArr['ids'] = $id;
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
			$detailsArr = array();
			foreach($valueData->details as $detRow)
			{
				$detailsArr[$detRow->identifier] = $detRow;
			}
			$valueData->details = $detailsArr;
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
		$this->form_validation->set_rules('description_0', 'What does this domain mean to you?', 'trim|required|xss_clean');
		$this->form_validation->set_rules('description_1', 'What kind of person would you like to be in this domain?', 'trim|required|xss_clean');
		$this->form_validation->set_rules('description_2', 'How are you doing in this domain now?', 'trim|required|xss_clean');
		$this->form_validation->set_rules('description_3', 'What are some specific short and long-term goals for this domain?', 'trim|required|xss_clean');
		$this->form_validation->set_rules('current_happiness_level', 'Value Identifier Current Happiness Level', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('expected_happiness_level', 'Value Identifier Expected Happiness Level', 'trim|required|xss_clean|numeric');
		if ($this->form_validation->run() == FALSE)
		{
			$pass = false;
			if(form_error('title'))
			{
				$message[] = form_error('title');
			}
			if(form_error('description_0'))
			{
				$message[] = form_error('description_0');
			}
			if(form_error('description_1'))
			{
				$message[] = form_error('description_1');
			}
			if(form_error('description_2'))
			{
				$message[] = form_error('description_2');
			}
			if(form_error('description_3'))
			{
				$message[] = form_error('description_3');
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
	public function deleteAddedValue($valueId)
	{
		$pass = false;
		$message = array();
		$valueData = $this->value_model->getAddedValueData($valueId); 
		if(!empty($valueData))
		{
			$res = $this->value_model->deleteAddedValue($valueId);
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
	public function addValueForUser() {
		$pass = false;
		$data = $_POST;
		$res = $this->value_model->addValueForUser();
		if($res == 'exist')
		{
			$valueId = '';
			$message[] = "<div class='alert alert-success'><p style='color:green;'>Value already exist.</p></div>";	
		}
		else
		{
			$valueId = $res;
			$pass = true;
			$message[] = "<div class='alert alert-warning'><p style='color:red;'>Value added successfully.</p></div>";
		}
		echo json_encode(array("success"=>$pass,"message"=>$message,"valueId"=>$valueId));
		exit;
	}
}
?>