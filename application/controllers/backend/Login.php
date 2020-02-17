<?php
class Login extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('login_model');
	}
	public function index()	{
		if($this->session->userdata("admin_id")){
			redirect($this->config->item("dashCtrl"), 'refresh');
		}
		$viewArr = array();
		$viewArr["viewPage"] = "login";
		$this->load->view('backend/layout',$viewArr);
	}
	public function checkLoginSession()	{
		$pass = false;
		if($this->session->userdata("admin_id")){
			$pass = true;
		}
		echo json_encode(array("success"=>$pass));
		exit;
	}
	public function loginCheck(){
		if($this->session->userdata("admin_id")){
			redirect($this->config->item("dashCtrl"), 'refresh');
		}
		$viewArr = array();
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger"><center><b style="color:#333333;">', '</b></center></div>');
		$this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pswd', 'Password', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			$viewArr["viewPage"] = "login";	
			$this->load->view('backend/layout',$viewArr);
		}else{
			$result = $this->login_model->loginCheck();
			if(!$result){
				$error_message = '<div class="alert alert-danger"><center><b style="color:#333333;">invalid username or password</b></center></div>';
				$viewArr["error_message"] = $error_message;
				$viewArr["viewPage"] = "login";
				$this->load->view('backend/layout',$viewArr);
			}else{
				$sessArr= array(
					'admin_id'  => $result->id,
					'admin_fname'  => $result->first_name,
					'admin_lname'  => $result->last_name,
					'admin_email'   => $result->email,
					'admin_phone'   => $result->phone,
				);
				$this->session->set_userdata($sessArr);
				redirect($this->config->item("dashCtrl").'?first=true', 'refresh');
			}
		}
	}
	public function changePswd(){
		if(!$this->session->userdata("admin_id")){
			$this->logout();
		}
		$viewArr = array();
		$viewArr["viewPage"] = "change_pswd";
		$this->load->view('backend/layout',$viewArr);
	}
	public function finalChangePswd(){
		if(!$this->session->userdata("admin_id")){
			$this->logout();
		}
		$message = array();
		$validation = true;
		$pass = true;
		if(trim($this->input->post("email"))==""){
			$validation = false;
			$message[] = "<p style='color:red;'>Invalid Email.</p>";
		}
		if(trim($this->input->post("oldPswd"))==""){
			$validation = false;
			$message[] = "<p style='color:red;'>Invalid old password.</p>";
		}
		if(trim($this->input->post("newPswd"))==""){
			$validation = false;
			$message[] = "<p style='color:red;'>Invalid new password.</p>";
		}
		if(trim($this->input->post("confPswd"))==""){
			$validation = false;
			$message[] = "<p style='color:red;'>Invalid confirm password field value.</p>";
		}
		if(trim($this->input->post("newPswd"))!=trim($this->input->post("confPswd"))){
			$validation = false;
			$message[] = "<p style='color:red;'>New & confirm password values do not match each other.</p>";
		}
		if($validation){
			$checkAdmin = $this->login_model->getAdmin();
			if(!$checkAdmin)
			{
				$pass = false;
				$message[] = "<p style='color:red;'>You have entered invalid email & old password combination.</p>";
			}else{
				$result = $this->login_model->finalChangePswd($checkAdmin->id);
				if($result){
					$message[] = "<p style='color:green;'>Your password updated successfully.</p>";
				}else{
					$pass = false;
					$message[] = "<p style='color:red;'>Failed to update your password.</p>";
				}
			}
		}else{
			$pass = false;
		}
		$this->session->set_flashdata('message', $message);
		if($pass){
			redirect($this->config->item("dashCtrl"));
		}else{
			redirect($this->config->item("changePswd"));
		}
	}
	public function logout(){
		$this->session->sess_destroy();	
		redirect($this->config->item("loginCtrl"), 'refresh');
	}
	public function	sendmail() {
		$dayname = date ('l');
		$currentdate = date('Y-m-d');
		$currenttime = date('H:i:s');
		$currentplus15 = strtotime("+15 minutes", strtotime($currenttime));
		$currenttime15 = date('H:i:s', $currentplus15);
		$actions = $this->login_model->getDashboardActions();
		foreach($actions as $action) {
			$userData = $this->login_model->getUserData($action->user_id);
			if(!empty($userData)) {
				foreach($action->reminders as $remind) {
					if($remind->daycheck == 1 && $action->action_type_id == 1) {
						if($action->is_finished == 0) {
							if($currentdate == $remind->date) {
								if($remind->time >= $currenttime && $remind->time <= $currenttime15) {
									$this->load->library('email');
									$from = 'Ala';
									$to = $userData->email;
									$subject = 'Ala servey application - Action Reminder';
									$message = '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta name="viewport" content="width=device-width" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Alerts e.g. approaching your limit</title><link href="styles.css" media="all" rel="stylesheet" type="text/css" /><style>
	* {
		margin: 0;
		padding: 0;
		font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
		box-sizing: border-box;
		font-size: 14px;
	}
	img {
		max-width: 100%;
	}
	body {
		-webkit-font-smoothing: antialiased;
		-webkit-text-size-adjust: none;
		width: 100% !important;
		height: 100%;
		line-height: 1.6;
	}
	table td {
		vertical-align: top;
	}
	body {
		background-color: #f6f6f6;
	}

	.body-wrap {
		background-color: #f6f6f6;
		width: 100%;
	}
	.container {
		display: block !important;
		max-width: 600px !important;
		margin: 0 auto !important;
		/* makes it centered */
		clear: both !important;
	}
	.content {
		max-width: 600px;
		margin: 0 auto;
		display: block;
		padding: 20px;
	}
	.main {
		background: #fff;
		border: 1px solid #e9e9e9;
		border-radius: 3px;
	}

	.content-wrap {
		padding: 20px;
	}

	.content-block {
		padding: 0 0 20px;
	}

	.header {
		width: 100%;
		margin-bottom: 20px;
	}
	.footer {
		width: 100%;
		clear: both;
		color: #999;
		padding: 20px;
	}
	.footer a {
		color: #999;
	}
	.footer p, .footer a, .footer unsubscribe, .footer td {
		font-size: 12px;
	}
	h1, h2, h3 {
		font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
		color: #000;
		margin: 40px 0 0;
		line-height: 1.2;
		font-weight: 400;
	}
	h1 {
		font-size: 32px;
		font-weight: 500;
	}
	h2 {
		font-size: 24px;
	}
	h3 {
		font-size: 18px;
	}
	h4 {
		font-size: 14px;
		font-weight: 600;
	}
	p, ul, ol {
		margin-bottom: 10px;
		font-weight: normal;
	}
	p li, ul li, ol li {
		margin-left: 5px;
		list-style-position: inside;
	}
	a {
		color: #1ab394;
		text-decoration: underline;
	}
	.btn-primary {
		text-decoration: none;
		color: #FFF;
		background-color: #1ab394;
		border: solid #1ab394;
		border-width: 5px 10px;
		line-height: 2;
		font-weight: bold;
		text-align: center;
		cursor: pointer;
		display: inline-block;
		border-radius: 5px;
		text-transform: capitalize;
	}
	.last {
		margin-bottom: 0;
	}

	.first {
		margin-top: 0;
	}

	.aligncenter {
		text-align: center;
	}

	.alignright {
		text-align: right;
	}

	.alignleft {
		text-align: left;
	}

	.clear {
		clear: both;
	}
	.alert {
		font-size: 16px;
		color: #fff;
		font-weight: 500;
		padding: 20px;
		text-align: center;
		border-radius: 3px 3px 0 0;
	}
	.alert a {
		color: #fff;
		text-decoration: none;
		font-weight: 500;
		font-size: 16px;
	}
	.alert.alert-warning {
		background: #f8ac59;
	}
	.alert.alert-bad {
		background: #ed5565;
	}
	.alert.alert-good {
		background: #1ab394;
	}
	.invoice {
		margin: 40px auto;
		text-align: left;
		width: 80%;
	}
	.invoice td {
		padding: 5px 0;
	}
	.invoice .invoice-items {
		width: 100%;
	}
	.invoice .invoice-items td {
		border-top: #eee 1px solid;
	}
	.invoice .invoice-items .total td {
		border-top: 2px solid #333;
		border-bottom: 2px solid #333;
		font-weight: 700;
	}
	@media only screen and (max-width: 640px) {
		h1, h2, h3, h4 {
			font-weight: 600 !important;
			margin: 20px 0 5px !important;
		}

		h1 {
			font-size: 22px !important;
		}

		h2 {
			font-size: 18px !important;
		}

		h3 {
			font-size: 16px !important;
		}

		.container {
			width: 100% !important;
		}

		.content, .content-wrap {
			padding: 10px !important;
		}

		.invoice {
			width: 100% !important;
		}
	}</style></head><body><table class="body-wrap"><tr><td></td><td class="container" width="600"><div class="content"><table class="main" width="100%" cellpadding="0" cellspacing="0"><tr><td class="alert alert-good">Ala Servey Application</td></tr><tr><td class="content-wrap"><table width="100%" cellpadding="0" cellspacing="0"><tr><td class="content-block">“This is a friendly reminder to complete this action:” '.$action->action_title.'</td></tr><tr><td class="content-block"><a href="'.base_url().'customer/action" class="btn-primary" style="color:#fff;">Go To Dashboard</a></td></tr><tr><td class="content-block">Thanks</td></tr></table></td></tr></table><div class="footer"><table width="100%"><tr><td class="aligncenter content-block"></td></tr></table></div></div></td><td></td></tr></table></tbody></html>';
									$this->email->set_newline("\r\n");
									$this->email->set_mailtype("html");
									$this->email->from($from);
									$this->email->to($to);
									$this->email->subject($subject);
									$this->email->message($message);
									$this->email->send();
								}
							}
						}
					}else if($remind->daycheck == 1 && $action->action_type_id == 3 && $remind->day_selected == 1) {
						if($action->is_finished == 0) {
							if($dayname == $remind->dayname) { 
								if($remind->time >= $currenttime && $remind->time <= $currenttime15) {
									 $this->load->library('email');
									$from = 'Ala';
									$to = $userData->email;
									$subject = 'Ala servey application - Action Reminder';
									$message = '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta name="viewport" content="width=device-width" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Alerts e.g. approaching your limit</title><link href="styles.css" media="all" rel="stylesheet" type="text/css" /><style>
	* {
		margin: 0;
		padding: 0;
		font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
		box-sizing: border-box;
		font-size: 14px;
	}
	img {
		max-width: 100%;
	}
	body {
		-webkit-font-smoothing: antialiased;
		-webkit-text-size-adjust: none;
		width: 100% !important;
		height: 100%;
		line-height: 1.6;
	}
	table td {
		vertical-align: top;
	}
	body {
		background-color: #f6f6f6;
	}

	.body-wrap {
		background-color: #f6f6f6;
		width: 100%;
	}
	.container {
		display: block !important;
		max-width: 600px !important;
		margin: 0 auto !important;
		/* makes it centered */
		clear: both !important;
	}
	.content {
		max-width: 600px;
		margin: 0 auto;
		display: block;
		padding: 20px;
	}
	.main {
		background: #fff;
		border: 1px solid #e9e9e9;
		border-radius: 3px;
	}

	.content-wrap {
		padding: 20px;
	}

	.content-block {
		padding: 0 0 20px;
	}

	.header {
		width: 100%;
		margin-bottom: 20px;
	}
	.footer {
		width: 100%;
		clear: both;
		color: #999;
		padding: 20px;
	}
	.footer a {
		color: #999;
	}
	.footer p, .footer a, .footer unsubscribe, .footer td {
		font-size: 12px;
	}
	h1, h2, h3 {
		font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
		color: #000;
		margin: 40px 0 0;
		line-height: 1.2;
		font-weight: 400;
	}
	h1 {
		font-size: 32px;
		font-weight: 500;
	}
	h2 {
		font-size: 24px;
	}
	h3 {
		font-size: 18px;
	}
	h4 {
		font-size: 14px;
		font-weight: 600;
	}
	p, ul, ol {
		margin-bottom: 10px;
		font-weight: normal;
	}
	p li, ul li, ol li {
		margin-left: 5px;
		list-style-position: inside;
	}
	a {
		color: #1ab394;
		text-decoration: underline;
	}
	.btn-primary {
		text-decoration: none;
		color: #FFF;
		background-color: #1ab394;
		border: solid #1ab394;
		border-width: 5px 10px;
		line-height: 2;
		font-weight: bold;
		text-align: center;
		cursor: pointer;
		display: inline-block;
		border-radius: 5px;
		text-transform: capitalize;
	}
	.last {
		margin-bottom: 0;
	}

	.first {
		margin-top: 0;
	}

	.aligncenter {
		text-align: center;
	}

	.alignright {
		text-align: right;
	}

	.alignleft {
		text-align: left;
	}

	.clear {
		clear: both;
	}
	.alert {
		font-size: 16px;
		color: #fff;
		font-weight: 500;
		padding: 20px;
		text-align: center;
		border-radius: 3px 3px 0 0;
	}
	.alert a {
		color: #fff;
		text-decoration: none;
		font-weight: 500;
		font-size: 16px;
	}
	.alert.alert-warning {
		background: #f8ac59;
	}
	.alert.alert-bad {
		background: #ed5565;
	}
	.alert.alert-good {
		background: #1ab394;
	}
	.invoice {
		margin: 40px auto;
		text-align: left;
		width: 80%;
	}
	.invoice td {
		padding: 5px 0;
	}
	.invoice .invoice-items {
		width: 100%;
	}
	.invoice .invoice-items td {
		border-top: #eee 1px solid;
	}
	.invoice .invoice-items .total td {
		border-top: 2px solid #333;
		border-bottom: 2px solid #333;
		font-weight: 700;
	}
	@media only screen and (max-width: 640px) {
		h1, h2, h3, h4 {
			font-weight: 600 !important;
			margin: 20px 0 5px !important;
		}

		h1 {
			font-size: 22px !important;
		}

		h2 {
			font-size: 18px !important;
		}

		h3 {
			font-size: 16px !important;
		}

		.container {
			width: 100% !important;
		}

		.content, .content-wrap {
			padding: 10px !important;
		}

		.invoice {
			width: 100% !important;
		}
	}</style></head><body><table class="body-wrap"><tr><td></td><td class="container" width="600"><div class="content"><table class="main" width="100%" cellpadding="0" cellspacing="0"><tr><td class="alert alert-good">Ala Servey Application</td></tr><tr><td class="content-wrap"><table width="100%" cellpadding="0" cellspacing="0"><tr><td class="content-block">“This is a friendly reminder to complete this action:” '.$action->action_title.'</td></tr><tr><td class="content-block"><a href="'.base_url().'customer/action" class="btn-primary" style="color:#fff;">Go To Dashboard</a></td></tr><tr><td class="content-block">Thanks</td></tr></table></td></tr></table><div class="footer"><table width="100%"><tr><td class="aligncenter content-block"></td></tr></table></div></div></td><td></td></tr></table></tbody></html>';
									$this->email->set_newline("\r\n");
									$this->email->set_mailtype("html");
									$this->email->from($from);
									$this->email->to($to);
									$this->email->subject($subject);
									$this->email->message($message);
									$this->email->send();
								}
							}
						}
					}else if($remind->daycheck == 1 && $action->action_type_id == 2) {
						if($remind->is_finished == 0) {
							if($remind->time >= $currenttime && $remind->time <= $currenttime15) {
								 $this->load->library('email');
									$from = 'Ala';
									$to = $userData->email;
									$subject = 'Ala servey application - Action Reminder';
									$message = '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta name="viewport" content="width=device-width" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Alerts e.g. approaching your limit</title><link href="styles.css" media="all" rel="stylesheet" type="text/css" /><style>
	* {
		margin: 0;
		padding: 0;
		font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
		box-sizing: border-box;
		font-size: 14px;
	}
	img {
		max-width: 100%;
	}
	body {
		-webkit-font-smoothing: antialiased;
		-webkit-text-size-adjust: none;
		width: 100% !important;
		height: 100%;
		line-height: 1.6;
	}
	table td {
		vertical-align: top;
	}
	body {
		background-color: #f6f6f6;
	}

	.body-wrap {
		background-color: #f6f6f6;
		width: 100%;
	}
	.container {
		display: block !important;
		max-width: 600px !important;
		margin: 0 auto !important;
		/* makes it centered */
		clear: both !important;
	}
	.content {
		max-width: 600px;
		margin: 0 auto;
		display: block;
		padding: 20px;
	}
	.main {
		background: #fff;
		border: 1px solid #e9e9e9;
		border-radius: 3px;
	}

	.content-wrap {
		padding: 20px;
	}

	.content-block {
		padding: 0 0 20px;
	}

	.header {
		width: 100%;
		margin-bottom: 20px;
	}
	.footer {
		width: 100%;
		clear: both;
		color: #999;
		padding: 20px;
	}
	.footer a {
		color: #999;
	}
	.footer p, .footer a, .footer unsubscribe, .footer td {
		font-size: 12px;
	}
	h1, h2, h3 {
		font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
		color: #000;
		margin: 40px 0 0;
		line-height: 1.2;
		font-weight: 400;
	}
	h1 {
		font-size: 32px;
		font-weight: 500;
	}
	h2 {
		font-size: 24px;
	}
	h3 {
		font-size: 18px;
	}
	h4 {
		font-size: 14px;
		font-weight: 600;
	}
	p, ul, ol {
		margin-bottom: 10px;
		font-weight: normal;
	}
	p li, ul li, ol li {
		margin-left: 5px;
		list-style-position: inside;
	}
	a {
		color: #1ab394;
		text-decoration: underline;
	}
	.btn-primary {
		text-decoration: none;
		color: #FFF;
		background-color: #1ab394;
		border: solid #1ab394;
		border-width: 5px 10px;
		line-height: 2;
		font-weight: bold;
		text-align: center;
		cursor: pointer;
		display: inline-block;
		border-radius: 5px;
		text-transform: capitalize;
	}
	.last {
		margin-bottom: 0;
	}

	.first {
		margin-top: 0;
	}

	.aligncenter {
		text-align: center;
	}

	.alignright {
		text-align: right;
	}

	.alignleft {
		text-align: left;
	}

	.clear {
		clear: both;
	}
	.alert {
		font-size: 16px;
		color: #fff;
		font-weight: 500;
		padding: 20px;
		text-align: center;
		border-radius: 3px 3px 0 0;
	}
	.alert a {
		color: #fff;
		text-decoration: none;
		font-weight: 500;
		font-size: 16px;
	}
	.alert.alert-warning {
		background: #f8ac59;
	}
	.alert.alert-bad {
		background: #ed5565;
	}
	.alert.alert-good {
		background: #1ab394;
	}
	.invoice {
		margin: 40px auto;
		text-align: left;
		width: 80%;
	}
	.invoice td {
		padding: 5px 0;
	}
	.invoice .invoice-items {
		width: 100%;
	}
	.invoice .invoice-items td {
		border-top: #eee 1px solid;
	}
	.invoice .invoice-items .total td {
		border-top: 2px solid #333;
		border-bottom: 2px solid #333;
		font-weight: 700;
	}
	@media only screen and (max-width: 640px) {
		h1, h2, h3, h4 {
			font-weight: 600 !important;
			margin: 20px 0 5px !important;
		}

		h1 {
			font-size: 22px !important;
		}

		h2 {
			font-size: 18px !important;
		}

		h3 {
			font-size: 16px !important;
		}

		.container {
			width: 100% !important;
		}

		.content, .content-wrap {
			padding: 10px !important;
		}

		.invoice {
			width: 100% !important;
		}
	}</style></head><body><table class="body-wrap"><tr><td></td><td class="container" width="600"><div class="content"><table class="main" width="100%" cellpadding="0" cellspacing="0"><tr><td class="alert alert-good">Ala Servey Application</td></tr><tr><td class="content-wrap"><table width="100%" cellpadding="0" cellspacing="0"><tr><td class="content-block">“This is a friendly reminder to complete this action:” '.$action->action_title.'</td></tr><tr><td class="content-block"><a href="'.base_url().'customer/action" class="btn-primary" style="color:#fff;">Go To Dashboard</a></td></tr><tr><td class="content-block">Thanks</td></tr></table></td></tr></table><div class="footer"><table width="100%"><tr><td class="aligncenter content-block"></td></tr></table></div></div></td><td></td></tr></table></tbody></html>';
									$this->email->set_newline("\r\n");
									$this->email->set_mailtype("html");
									$this->email->from($from);
									$this->email->to($to);
									$this->email->subject($subject);
									$this->email->message($message);
									$this->email->send();
							}
						}
					}
				}
			}
		} 
	}
	public function dailyactions() {
		$actions = $this->login_model->getDashboardDailyRoutineActions();
		foreach($actions as $action) {
			$res = $this->login_model->insertAction($action);
		}
	}
}
