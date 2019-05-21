<?php
/*
* Page Name: layout.php
* Purpose: Displays header- dynamic midsection - footer and all other common files
*/
?>

<!-- load session library -->
<?php
$this->load->library('session'); 
$this->load->helper('array');
?>

<!-- Include Header -->
<?php if($this->session->userdata("admin_id")) { ?>
<?php $this->load->view('backend/includes/header');?>
<?php }else{ ?>
<?php $this->load->view('backend/includes/header_login');?>
<?php } ?>

<!-- Load Menu only is user logged in successfully -->
<?php if($this->session->userdata("admin_id")) { ?>
<?php $this->load->view('backend/includes/menu'); ?>
<?php } ?>

<!-- Load Menu only is user logged in successfully -->
<?php if($this->session->userdata("admin_id")) { ?>
<?php $this->load->view('backend/includes/sub_header'); ?>
<?php } ?>

<!-- Load Dynamic View Page -->
<div class="centralView">
	<?php $this->load->view('backend/'.$viewPage); ?>
</div>

<!-- Include  Footer -->
<?php if($this->session->userdata("admin_id")) { ?>
<?php $this->load->view('backend/includes/footer');?>
<?php }else{ ?>
<?php $this->load->view('backend/includes/footer_login');?>
<?php } ?>
