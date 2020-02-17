<?php echo form_error('email'); ?>
<?php echo form_error('pswd'); ?>
<?php echo ((isset($error_message))?$error_message:"");?>
<style>
    .loginpage {
    min-height: 68vh;
    margin-top: 30px;
}
</style>
<div class="loginpage">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-12">
                <div class="ibox-content">
					<center>
						<img alt="logo image" style="max-width:70%;" height="100" width="100" class="img-responsive" src="<?php echo base_url(); ?>uploads/settings/logo.png">
					</center>
					<center>
						<h3>Reset Password</h3>
					</center>
					<form class="m-t" role="form" action="<?php echo $this->config->item("resetPasswordCustomer");?>" name="restFrm" id="resetFrm" method="POST">
						<input type="hidden" name="userid" value="<?php if(isset($_POST['userid'])) { echo $_POST['userid']; }else { if(isset($userid)) { echo $userid; }} ?>">
						<input type="hidden" name="rendom" value="<?php if(isset($_POST['rendom'])) { echo $_POST['rendom']; }else { if(isset($rendom)) { echo $rendom; }} ?>">
						<div class="form-group">
							<input type="password" name="pswd" id="pswd" class="form-control" placeholder="Password" required="" value="<?php if(isset($_POST['pswd'])) { echo $_POST['pswd']; } ?>">
						</div>
						<div class="form-group">
							<input type="password" name="cpswd" id="cpswd" class="form-control" placeholder="Confirm Password" required="" value="<?php if(isset($_POST['cpswd'])) { echo $_POST['cpswd']; } ?>">
						</div>
						<button type="submit" class="btn btn-primary block full-width m-b">Reset Now</button>
					</form>
                    <p class="m-t">
                        <small>Survey Application &copy; 2014</small>
                    </p>
                </div>
            </div>
        </div>
</div>
