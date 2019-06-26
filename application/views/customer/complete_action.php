<div class="ibox-content">
	<div id="actionCompleteMessages" tabindex='1'></div>
		<form method="POST" action="<?php echo $this->config->item("insertCompleteAction")."/".((count($actionData)>0)?$actionData->id:0);?>" id="actionCompleteFrm" name="actionCompleteFrm" class="form-horizontal">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Answer - 1</strong><span style="color:red;">&nbsp;*</span></p>
						<textarea class="form-control" name="action_complete_0" id="action_complete_0" cols="50" rows="2"><?php echo((count($postData)>0 && isset($postData["action_complete_0"]))?trim($postData["action_complete_0"]):'');?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Answer - 2</strong><span style="color:red;">&nbsp;*</span></p>
						<textarea class="form-control" name="action_complete_1" id="action_complete_1" cols="50" rows="3"><?php echo((count($postData)>0 && isset($postData["action_complete_1"]))?trim($postData["action_complete_1"]):'');?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Answer - 3</strong><span style="color:red;">&nbsp;*</span></p>
						<textarea class="form-control" name="action_complete_2" id="action_complete_2" cols="50" rows="3"><?php echo((count($postData)>0 && isset($postData["action_complete_2"]))?trim($postData["action_complete_2"]):'');?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">		  
			<div class="col-md-12">	
				<div class="form-group">
					<div class="col-sm-10">
						<input type="button" class="btn btn-primary" id="saveAction" value="Save"/>
						<button class="btn" data-dismiss="modal">Cancel</button>
					</div>
				</div>		
			</div>
		</div>	 
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$("#saveAction").click(function(){
		$("#actionCompleteFrm").ventricleSubmitForm('saveResp');
		$(this).prop('disabled', true);
	});
});

function saveResp(response){
	$(".form-control").css('border','').css('border-width','');
	$("#actionCompleteMessages").html("");
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
			
			$("#actionCompleteMessages").append(msgStr).focus();
			$("#saveAction").prop('disabled', false);
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
		$("#saveAction").prop('disabled', false);
	}
}
</script>