<script src="<?php echo $this->config->item("ckEditorUrl");?>/ckeditor.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/jquery.form.min.js"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>
<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/ionRangeSlider/ion.rangeSlider.css" rel="stylesheet">
<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css" rel="stylesheet">
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Pre Meetings</h5>
			</div>
			<div class="ibox-content">
				<div id="messages" tabindex='1'></div>
					<form method="POST" id="pmFrm" name="pmFrm" class="form-horizontal">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>General Happiness Level This Week</strong><span style="color:red;">&nbsp;*</span></p>
									<div class="generalHappinessLevel"></div>
									<input type="hidden" id="generalHappinessLevelValue" name="general_happiness_level" value="<?php echo((count($postData)>0 && isset($postData["general_happiness_level"]))?trim($postData["general_happiness_level"]):((count($preMeetingData)>0)?$preMeetingData->general_happiness_level:0));?>"/>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>Last Week's Actions:</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>Acknowledgment</strong><span style="color:red;">&nbsp;*</span></p>
									<textarea class="form-control" name="acknowledgment" id="acknowledgment" cols="50" rows="3" placeholder="Enter Acknowledgment Of These Actions."><?php echo((count($postData)>0 && isset($postData["acknowledgment"]))?trim($postData["acknowledgment"]):((count($preMeetingData)>0)?$preMeetingData->acknowledgment:""));?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>Obstacles</strong><span style="color:red;">&nbsp;*</span></p>
									<textarea class="form-control" name="obstacles" id="obstacles" cols="50" rows="3" placeholder="What Came In Your Way Of Completing Some Actions?"><?php echo((count($postData)>0 && isset($postData["obstacles"]))?trim($postData["obstacles"]):((count($preMeetingData)>0)?$preMeetingData->obstacles:""));?></textarea>
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
		</div>
	</div>
</div>
<script type="text/javascript">
var generalHappinessLevelsaveResult = function (data) {
   $('#generalHappinessLevelValue').val(data.fromNumber);
};

$(document).ready(function(){
	$('.modalInvoke').ventricleModalViewLoad("commonModal");
	
	$(".delete").click(function(event){
		var _this = $(this);
		$("#confirmation .modal-title").html('Delete Action');
		$("#confirmation div.modal-body").html('<h4>Do you want to delete this action?</h4>');
		$("#confirmation button.btn-primary").attr('data-href', _this.attr('data-href'));
		$("#confirmation").modal({show:true});
	});
	
	$("#confirmation button.btn-primary").unbind().click(function(){
			$(this).prop('disabled', true);
			$(this).ventricleDirectAjax("GET",'','deleteResponse');
	});
	
	$("#save").click(function(){
		document.pmFrm.action = "<?php echo $this->config->item("insertPostMeeting")."/".((count($preMeetingData)>0)?$preMeetingData->id:0);?>";
		document.pmFrm.submit();
	});
	
	$(".generalHappinessLevel").ionRangeSlider({
            min: 0,
            max: 10,
			from: '<?php echo ((count($preMeetingData)>0)?$preMeetingData->general_happiness_level:0);?>',
            type: 'single',
            step: 1,
            postfix: " point",
            prettify: false,
            hasGrid: false,
			onChange: generalHappinessLevelsaveResult
    });
});

function deleteResponse(response)
{
	if( (typeof response === "object") && (response !== null) )
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
		   
			$('#confModalClose').trigger('click');
			$("#confirmation button.btn-primary").prop('disabled', false);
			$(".centralView").load(window.location.href+"<?php echo(($_GET && !isset($_GET['pagination']))?'?'.http_build_query($_GET).'&pagination=1':'?pagination=1')?>");
			
		}, 500);
	}
}
</script>