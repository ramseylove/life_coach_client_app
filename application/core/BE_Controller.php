<?php
class BE_Controller extends MY_Controller
{
  function __construct()
    {
        parent::__construct();
		
		if(!$this->session->userdata("admin_id"))
		{
			redirect($this->config->item("loginCtrl"));
		}
	}

}
?>