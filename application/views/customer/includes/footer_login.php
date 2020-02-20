<hr/>
     <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
               Ala.expert
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 text-right">
               <small>© <?php echo date('Y').'-'.date('Y', strtotime('+1year', strtotime(date('Y-m-d'))));?></small>
            </div>
        </div>
    </div>
</body>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/jquery-3.1.1.min.js"></script>
<script>
function showhidelogin(code) {
	if(code == 1) {
		$('#logindiv').hide();
		$('#forgotdiv').show();
	}else {
		$('#forgotdiv').hide();
		$('#logindiv').show();
	}
}
function checkemails() {
	var forgot_email = $('#forgot_email').val();
	var myKeyVals = { 'forgot_email' : forgot_email };
	$.ajax({
      type: 'POST',
      url: "<?php echo base_url(); ?>customer/login/forgotEmailCheck",
      data: myKeyVals,
      dataType: "json",
      success: function(resultData) {
		  $('#ediv').empty();
		  $('#ediv').append(resultData.error_message);
		  $('#ediv').show();
		  setTimeout(function(){ $('#ediv').empty(); $('#ediv').hide(); }, 5000);
	  }
	});
	return false;
}
</script>
</html>