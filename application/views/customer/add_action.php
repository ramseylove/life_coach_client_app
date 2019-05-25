<script src="<?php echo $this->config->item("ckEditorUrl");?>/ckeditor.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/jquery.form.min.js"></script>
<div class="ibox-content">
	<div id="messages" tabindex='1'></div>
		<form method="POST" action="<?php echo $this->config->item("insertAction")."/".((count($actionData)>0)?$actionData->id:0);?>" id="actionFrm" name="actionFrm" class="form-horizontal">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Title</strong><span style="color:red;">&nbsp;*</span></p>
						<input type="text" name="title" id="title" class="form-control" value="<?php echo((count($postData)>0 && isset($postData["title"]))?trim($postData["title"]):((count($actionData)>0)?$actionData->title:""));?>"/>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				 <div class="form-group">
					<div class="col-sm-10">
						<p><strong>Type Of Action</strong></p>
						<select class="form-control" name="type" id="type">
							<?php foreach($actionTypeData as $actionType){ ?>
								<option value="<?php echo $actionType->id;?>"<?php echo((count($postData)>0 && isset($postData["type"]) && trim($postData["type"])==$actionType->id)?"selected":((count($actionData)>0 && $actionData->id==$actionType->id)?"selected":""));?>><?php echo $actionType->title;?></option>
							<?php } ?>
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
	//script to activate CK editor.
	CKEDITOR.replace('description');
	
	$("#save").click(function(){
		CKEDITOR.instances.description.updateElement();
		document.actionFrm.action="<?php echo $this->config->item("insertAction")."/".((count($actionData)>0)?$actionData->id:0);?>";
		$("#actionFrm").ventricleSubmitForm('saveResp');
		$(this).prop('disabled', true);
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