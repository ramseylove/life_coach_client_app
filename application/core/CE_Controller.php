<?php
class CE_Controller extends MY_Controller
{
  function __construct()
    {
        parent::__construct();
		
		if(!$this->session->userdata("user_id"))
		{
			redirect($this->config->item("loginCtrlCustomer"));
		}
	}

}
?>