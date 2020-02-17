<script src="<?php echo $this->config->item("ckEditorUrl");?>/ckeditor.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/jquery.form.min.js"></script>
<div class="ibox-content">
	<div id="messages" tabindex='1'></div>
		<form method="POST" action="<?php echo $this->config->item("insertUser")."/".((count($userData)>0)?$userData->id:0);?>" id="userFrm" name="userFrm" class="form-horizontal">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>First Name</strong><span style="color:red;">&nbsp;*</span></p>
						<input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo((count($postData)>0 && isset($postData["first_name"]))?trim($postData["first_name"]):((count($userData)>0)?$userData->first_name:""));?>"/>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Last Name</strong><span style="color:red;">&nbsp;*</span></p>
						<input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo((count($postData)>0 && isset($postData["last_name"]))?trim($postData["last_name"]):((count($userData)>0)?$userData->last_name:""));?>"/>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Email</strong><span style="color:red;">&nbsp;*</span></p>
						<input type="text" class="form-control" name="email" id="email" value="<?php echo((count($postData)>0 && isset($postData["email"]))?trim($postData["email"]):((count($userData)>0)?$userData->email:""));?>"/>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Phone</strong></p>
						<input type="text" class="form-control" name="phone" id="phone" value="<?php echo((count($postData)>0 && isset($postData["phone"]))?trim($postData["phone"]):((count($userData)>0)?$userData->phone:""));?>"/>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Password</strong><span style="color:red;">&nbsp;*</span></p>
						<input type="password" class="form-control" name="password" id="password"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Profile Photo</strong></p>
						<input class="form-control" type="file" name="photo" id="photo"/>
						<?php if(count($userData)>0) { ?>
							<hr>
							<div id="userThumb">
								<img title="current image for user profile" alt="existing Image-<?php echo $userData->photo;?>" src="<?php echo $this->config->item("uploads_url");?>/users/<?php echo $userData->photo;?>" class="img-thumbnail" style="max-width:25%;">
							</div>
						<?php }else{ ?>
							<div id="userThumb">
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row">		  
			<div class="col-md-12">
				 <div class="form-group">
					<div class="col-sm-10">
						<p><strong>Status</strong></p>
						<select class="form-control" name="status" id="status">
							<option value="0"<?php echo((count($postData)>0 && isset($postData["status"]) && trim($postData["status"])==0)?"selected":((count($userData)>0 && $userData->status==0)?"selected":""));?>>Enabled</option>
							<option value="1"<?php echo((count($postData)>0 && isset($postData["status"]) && trim($postData["status"])==1)?"selected":((count($userData)>0 && $userData->status==1)?"selected":""));?>>Disabled</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row">		  
			<div class="col-md-12">	
				<div class="form-group">
					<div class="col-sm-10">
						<input type="button" class="btn btn-primary" id="save" value="Save"/>
						<button class="btn" data-dismiss="modal">Cancel</button>
					</div>
				</div>		
			</div>
		</div>	 
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
    if($('#description').length > 0)
	{
		//script to activate CK editor.
		CKEDITOR.replace('description');
	}
	
	$("#save").click(function(){
		if($('#description').length > 0)
		{
			CKEDITOR.instances.description.updateElement();
		}
		document.userFrm.action="<?php echo $this->config->item("insertUser")."/".((count($userData)>0)?$userData->id:0);?>";
		$("#userFrm").ventricleSubmitForm('saveResp');
		$(this).prop('disabled', true);
	});
	
	 $("#photo").change(function(){
		document.userFrm.action="<?php echo $this->config->item("uploadImageUser"); ?>";
		$("#userFrm").ajaxForm({target: '#userThumb'}).submit();
	 });
});

function saveResp(response){
	$(".form-control").css('border','').css('border-width','');
	$("#messages").html("");
	if( (typeof response === "object") && (response !== null) )
	{
		if(!response.success)
		{	
			var msgStr = "";
			$.each(response.message, function( index, value ) {
			  msgStr+= value;
			  if( Object.prototype.toString.call(index) == '[object String]' ) {
				   $("[name='"+index+"']").css('border','solid red').css('border-width','1px');
				}
			});
			
			$("#messages").append(msgStr).focus();
			$("#save").prop('disabled', false);
		}
		else
		{
			setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 3000
                };
				
				$.each(response.message, function( index, value ) {
				  toastr.success('',$(value).text());
				});
               
				$('#commonModalClose').trigger('click');
				$(".centralView").load(window.location.href+"<?php echo(($_GET && !isset($_GET['pagination']))?'?'.http_build_query($_GET).'&pagination=1':'?pagination=1')?>");
				
            }, 500);
		}
	}
	else
	{
		$("#save").prop('disabled', false);
	}
}
</script>