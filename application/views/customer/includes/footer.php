 <div class="footer">
                    <div>
                        <strong>Copyright</strong> <?php echo BRAND;?> &copy; <?php echo date('Y').'-'.date('Y', strtotime('+1year', strtotime(date('Y-m-d'))));?>
                    </div>
                </div>
            </div>
        </div>
	</div>
 </div>
 <a style="display:none;" href="javascript:void(0);" class="checkLoginSessionCustomer" data-href="<?php echo $this->config->item("checkLoginSessionCustomer");?>">checkLoginSessionCustomer</a>
	
	<?php $this->load->view('customer/modals/common_modal') ?>
	<?php $this->load->view('customer/modals/error') ?>
	<?php $this->load->view('customer/modals/confirmation') ?>
 
    <!-- Mainly scripts -->
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/ventricle.js"></script>
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/bootstrap.min.js"></script>
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/flot/jquery.flot.js"></script>
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/flot/jquery.flot.spline.js"></script>
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/flot/jquery.flot.resize.js"></script>
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/peity/jquery.peity.min.js"></script>
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/inspinia.js"></script>
    <!--<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/pace/pace.min.js"></script>-->
	<!--<script data-pace-options='{ "elements": { "selectors": [".centralView"] }, "startOnPageLoad": false }' src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/pace/pace.min.js"></script>-->
	
    <!-- jQuery UI -->
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/toastr/toastr.min.js"></script>


    <script>
        $(document).ready(function() {
			<?php if(isset($_GET['first'])){ ?>
            setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 4000
                };
                toastr.success('<?php echo BRAND; ?>', 'Welcome <?php echo trim($this->session->userdata('fname'));?>!');

            }, 1300);
			<?php } ?>
			checkLogin();
        });
		
		function checkLogin()
		{
			$(".checkLoginSessionCustomer").ventricleDirectAjax("GET",'','checkLoginSessionResponse');
			setTimeout(function() {
			  checkLogin();
            }, 3000);
		}
		
		function checkLoginSessionResponse(response)
		{
			if( (typeof response === "object") && (response !== null) )
			{
				if(!response.success)
				{
					window.location.href = "<?php echo $this->config->item("loginCtrlCustomer");?>";
				}
				else
				{
					console.log('Still Logged in!!');
				}
			}
		}
    </script>
</body>
</html>