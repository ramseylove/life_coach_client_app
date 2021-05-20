<script src="<?php echo $this->config->item("ckEditorUrl");?>/ckeditor.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/jquery.form.min.js"></script>
<div class="ibox-content">
	<div id="messages" tabindex='1'></div>
		<form method="POST" action="<?php echo $this->config->item("insertGoal")."/".((count($goalData)>0)?$goalData->id:0);?>" id="goalFrm" name="goalFrm" class="form-horizontal">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Title</strong><span style="color:red;">&nbsp;*</span></p>
						<input type="text" name="title" id="title" class="form-control" value="<?php echo((count($postData)>0 && isset($postData["title"]))?trim($postData["title"]):((count($goalData)>0)?$goalData->title:""));?>"/>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Description</strong><span style="color:red;">&nbsp;*</span></p>
						<textarea class="form-control" name="description" id="description" cols="50" rows="5"><?php echo((count($postData)>0 && isset($postData["description"]))?trim($postData["description"]):((count($goalData)>0)?$goalData->description:""));?></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row">		  
			<div class="col-md-12">
				 <div class="form-group">
					<div class="col-sm-10">
						<p><strong>Type Of Goal</strong></p>
						<select class="form-control" name="type" id="type">
							<option value="0"<?php echo((count($postData)>0 && isset($postData["type"]) && trim($postData["type"])==0)?"selected":((count($goalData)>0 && $goalData->is_secondary==0)?"selected":""));?>>Primary</option>
							<option value="1"<?php echo((count($postData)>0 && isset($postData["type"]) && trim($postData["type"])==1)?"selected":((count($goalData)>0 && $goalData->is_secondary==1)?"selected":""));?>>Secondary</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row">		  
			<div class="col-md-12">	
				<div class="form-group">
					<div class="col-sm-10">
						<input type="button" class="btn btn-primary" id="save" value="Save" onclick="return validate()" />
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
		document.goalFrm.action="<?php echo $this->config->item("insertGoal")."/".((count($goalData)>0)?$goalData->id:0);?>";
		$("#goalFrm").ventricleSubmitForm('saveResp');
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
				$(".centralView").load(window.location.href+"<?php echo(($_GET && !isset($_GET['pagination']))?'?'.http_build_query($_GET).'&pagination=1':'?pagination=1')?>&heads=1");
				
            }, 500);
		}
	}
	else
	{
		$("#save").prop('disabled', false);
	}
}
</script>