<?php echo form_error('email'); ?>
<?php echo form_error('pswd'); ?>
<?php echo ((isset($error_message))?$error_message:"");?>
<div id="ediv"style="display:none;"></div>
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
					<div id="logindiv">
						<center>
							<h3>Customer Login</h3>
						</center>
						<form class="m-t" role="form" action="<?php echo $this->config->item("loginCheckCustomer");?>" name="loginFrm" id="loginFrm" method="POST">
							<div class="form-group">
								<input type="email" name="email" id="email" class="form-control" placeholder="Email" required="">
							</div>
							<div class="form-group">
								<input type="password" name="pswd" id="pswd" class="form-control" placeholder="Password" required="">
							</div>
							<button type="submit" class="btn btn-primary block full-width m-b">Login</button>
							<a href="Javascript:void(0);" onclick="showhidelogin(1)">
								<small>Reset password?</small>
							</a>
							<!--<p class="text-muted text-center">
								<small>Do not have an account?</small>
							</p>
							<a class="btn btn-sm btn-white btn-block" href="register.html">Create an account</a>-->
						</form>
					</div>
					<div id="forgotdiv" style="display:none;">
						<center>
							<h3>Reset Password</h3>
						</center>
						<form class="m-t" role="form" action="" name="loginFrm" id="loginFrm" method="POST"  onsubmit="return checkemails()">
							<div class="form-group">
								<input type="email" name="forgot_email" id="forgot_email" class="form-control" placeholder="Email" required="">
							</div>
							<button type="submit" class="btn btn-primary block full-width m-b">Reset Now</button>
							<a href="Javascript:void(0);" onclick="showhidelogin(2)">
								<small>Login</small>
							</a>
						</form>
					</div>
                    <p class="m-t">
                        <small>Ala.expert &copy; 2020</small>
                    </p>
                </div>
            </div>
        </div>
</div>
