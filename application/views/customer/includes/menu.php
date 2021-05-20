<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav metismenu" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element">
					<span>
						<img alt="logo image" style="max-width:70%;" class="img-responsive" src="<?php echo $this->config->item("uploads_url")."/settings/logo.png";?>" />
					</span>
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<span class="clear">
							<span class="block m-t-xs">
								<strong class="font-bold"><?php echo $this->session->userdata('fname')." ".$this->session->userdata('lname');?></strong>
							</span>
							<span class="text-muted text-xs block">
								<?php echo $this->session->userdata('email');?><b class="caret"></b>
							</span>
						</span>
					</a>
					<ul class="dropdown-menu animated fadeInRight m-t-xs">
						<!--<li class="divider"></li>-->
						<li><a href="<?php echo $this->config->item('logoutCustomer');?>">Logout</a></li>
					</ul>
				</div>
				<div class="logo-element">
					ALA 
				</div>
			</li>
			<li <?php echo (($viewPage == 'manage_actions')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("actionCtrl");?>" id="actionCtrl" class="cMenu"><i class="fa fa-th-large"></i>
				<span class="nav-label">Dashboard</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_goals')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("goalCtrl");?>" id="goalCtrl" class="cMenu"><i class="fa fa-bullseye"></i> <span class="nav-label">Goals</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_value_list')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("valueListCtrl");?>" id="valueListCtrl" class="cMenu"><i class="fa fa-address-book"></i> <span class="nav-label">Values</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_value_identifiers')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("valueIdentifierCtrl");?>" id="valueIdentifierCtrl" class="cMenu"><i class="fa fa-address-card"></i> <span class="nav-label">Value Identifier</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_pre_meetings')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("preMeetingCtrl");?>" id="preMeetingCtrl" class="cMenu"><i class="fa fa-handshake-o"></i> <span class="nav-label">Pre-Meeting</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_post_meetings' || $viewPage == 'add_post_meeting')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("postMeetingCtrl");?>" id="postMeetingCtrl" class="cMenu"><i class="fa fa-meetup"></i> <span class="nav-label">Post-Meeting</span></a>
			</li>
			<!--li <?php /* echo (($viewPage == 'manage_allactions')?'class="active"':''); */ ?>>
				<a href="<?php /* echo $this->config->item("actionCtrl"); */ ?>/allActions" id="actionCtrl" class="cMenu"><i class="fa fa-th-large"></i>
				<span class="nav-label">Actions</span></a>
			</li-->
			<li <?php echo (($viewPage == 'manage_action_questions')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("postMeetingCtrl");?>/action_view" id="actionCtrl" class="cMenu"><i class="fa fa-tasks"></i>
				<span class="nav-label">Actions</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_goal_view')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("postMeetingCtrl");?>/goal_view" id="actionCtrl" class="cMenu"><i class="fa fa-eye"></i>
				<span class="nav-label">Goal View</span></a>
			</li>
		</ul>
	</div>
</nav>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Confirm - You will lose entered info?</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="return modelcomform(1)">Yes</button>
        <button type="button" class="btn btn-primary" onclick="return modelcomform(0)">No</button>
      </div>
    </div>
  </div>
</div>