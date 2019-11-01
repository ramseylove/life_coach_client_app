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
					SA 
				</div>
			</li>
			<li <?php echo (($viewPage == 'manage_actions')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("actionCtrl");?>" id="actionCtrl" class="cMenu"><i class="fa fa-th-large"></i>
				<span class="nav-label">Dashboard</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_goals')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("goalCtrl");?>" id="goalCtrl" class="cMenu"><i class="fa fa-th-large"></i> <span class="nav-label">Goal Setting</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_value_identifiers')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("valueIdentifierCtrl");?>" id="valueIdentifierCtrl" class="cMenu"><i class="fa fa-th-large"></i> <span class="nav-label">Value Identifier</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_pre_meetings')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("preMeetingCtrl");?>" id="preMeetingCtrl" class="cMenu"><i class="fa fa-th-large"></i> <span class="nav-label">Pre-Meeting</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_post_meetings' || $viewPage == 'add_post_meeting')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("postMeetingCtrl");?>" id="postMeetingCtrl" class="cMenu"><i class="fa fa-th-large"></i> <span class="nav-label">Post-Meeting</span></a>
			</li>
			<li <?php echo (($viewPage == 'manage_allactions')?'class="active"':''); ?>>
				<a href="<?php echo $this->config->item("actionCtrl");?>/allActions" id="actionCtrl" class="cMenu"><i class="fa fa-th-large"></i>
				<span class="nav-label">Actions</span></a>
			</li>
		</ul>
	</div>
</nav>