<div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i></a>
        </div>
            <ul class="nav navbar-top-links navbar-right">
			<?php if($_SESSION['adminLogin'] == 1) { ?>
			<li>
			<span class="m-r-sm text-muted">
			<a href="<?php echo base_url(); ?>backend/users"><?php echo 'Go To Admin';?></a>
			</span>
			</li>
			<?php } ?>				
                <li>
                    <span class="m-r-sm text-muted"><?php echo BRAND;?></span>
                </li>
                <li>
                    <a href="<?php echo $this->config->item('logoutCustomer');?>">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>
        </nav>
</div>