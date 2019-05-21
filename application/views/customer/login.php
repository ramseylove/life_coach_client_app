<?php echo form_error('email'); ?>
<?php echo form_error('pswd'); ?>
<?php echo ((isset($error_message))?$error_message:"");?>

<div class="loginpage">
        <div class="row">
            <div class="col-md-6" style="margin-left:180px;">
                <div class="ibox-content">
					<center><h3>Customer Login</h3></center>
                    <form class="m-t" role="form" action="<?php echo $this->config->item("loginCheckCustomer");?>" name="loginFrm" id="loginFrm" method="POST">
                        <div class="form-group">
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required="">
                        </div>
                        <div class="form-group">
                            <input type="password" name="pswd" id="pswd" class="form-control" placeholder="Password" required="">
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                        <!--<a href="#">
                            <small>Forgot password?</small>
                        </a>

                        <p class="text-muted text-center">
                            <small>Do not have an account?</small>
                        </p>
                        <a class="btn btn-sm btn-white btn-block" href="register.html">Create an account</a>-->
                    </form>
                    <p class="m-t">
                        <small>Survey Application &copy; 2014</small>
                    </p>
                </div>
            </div>
        </div>
</div>
