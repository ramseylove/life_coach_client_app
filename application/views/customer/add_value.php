<script src="<?php echo $this->config->item("ckEditorUrl");?>/ckeditor.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/jquery.form.min.js"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>
<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/ionRangeSlider/ion.rangeSlider.css" rel="stylesheet">
<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css" rel="stylesheet">
<div class="ibox-content">
	<div id="messages" tabindex='1'></div>
		<form method="POST" action="<?php echo $this->config->item("insertValue")."/".((count($valueData)>0)?$valueData->id:0);?>" id="valueFrm" name="valueFrm" class="form-horizontal">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Title</strong><span style="color:red;">&nbsp;*</span></p>
						<input type="text" name="title" id="title" class="form-control" value="<?php echo((count($postData)>0 && isset($postData["title"]))?trim($postData["title"]):((count($valueData)>0)?$valueData->title:""));?>"/>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Current Happiness Level</strong><span style="color:red;">&nbsp;*</span></p>
						<div class="currentHappinessLevel"></div>
						<input type="hidden" id="currentHappinessLevelValue" name="current_happiness_level" value="<?php echo((count($postData)>0 && isset($postData["current_happiness_level"]))?trim($postData["current_happiness_level"]):((count($valueData)>0)?$valueData->current_happiness_level:0));?>"/>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Expected Happiness Level</strong><span style="color:red;">&nbsp;*</span></p>
						<div class="expectedHappinessLevel"></div>
						<input type="hidden" id="expectedHappinessLevelValue" name="expected_happiness_level" value="<?php echo((count($postData)>0 && isset($postData["expected_happiness_level"]))?trim($postData["expected_happiness_level"]):((count($valueData)>0)?$valueData->expected_happiness_level:0));?>"/>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Description</strong><span style="color:red;">&nbsp;*</span></p>
						<textarea class="form-control" name="description" id="description" cols="50" rows="5"><?php echo((count($postData)>0 && isset($postData["description"]))?trim($postData["description"]):((count($valueData)>0)?$valueData->description:""));?></textarea>
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
var currentHappinessLevelsaveResult = function (data) {
   $('#currentHappinessLevelValue').val(data.fromNumber);
};
var expectedHappinessLevelsaveResult = function (data) {
    $('#expectedHappinessLevelValue').val(data.fromNumber);
};

$(document).ready(function(){
	//script to activate CK editor.
	CKEDITOR.replace('description');
	
	$("#save").click(function(){
		CKEDITOR.instances.description.updateElement();
		document.valueFrm.action="<?php echo $this->config->item("insertValue")."/".((count($valueData)>0)?$valueData->id:0);?>";
		$("#valueFrm").ventricleSubmitForm('saveResp');
		$(this).prop('disabled', true);
	});
	
	$(".currentHappinessLevel").ionRangeSlider({
            min: 0,
            max: 10,
			from: '<?php echo ((count($valueData)>0)?$valueData->current_happiness_level:0);?>',
            type: 'single',
            step: 1,
            postfix: " point",
            prettify: false,
            hasGrid: false,
			onChange: currentHappinessLevelsaveResult
        });
	$(".expectedHappinessLevel").ionRangeSlider({
            min: 0,
            max: 10,
			from: '<?php echo ((count($valueData)>0)?$valueData->expected_happiness_level:0);?>',
            type: 'single',
            step: 1,
            postfix: " point",
            prettify: false,
            hasGrid: false,
			onChange: expectedHappinessLevelsaveResult
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