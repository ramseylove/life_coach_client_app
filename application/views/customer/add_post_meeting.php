<script src="<?php echo $this->config->item("ckEditorUrl");?>/ckeditor.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/jquery.form.min.js"></script>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Post Meetings</h5>
			</div>
			<div class="ibox-content">
				<div id="messages" tabindex='1'></div>
					<form method="POST" id="pmFrm" name="pmFrm" class="form-horizontal">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>General Topic Of Meeting</strong><span style="color:red;">&nbsp;*</span></p>
									<textarea class="form-control" name="general_topic" id="general_topic" cols="50" rows="3"><?php echo((count($postData)>0 && isset($postData["general_topic"]))?trim($postData["general_topic"]):((count($postMeetingData)>0)?$postMeetingData->general_topic:""));?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>What Value Did You Get From This Week's Session?</strong><span style="color:red;">&nbsp;*</span></p>
									<textarea class="form-control" name="session_value" id="session_value" cols="50" rows="3"><?php echo((count($postData)>0 && isset($postData["session_value"]))?trim($postData["session_value"]):((count($postMeetingData)>0)?$postMeetingData->session_value:""));?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>Other Notes</strong><span style="color:red;">&nbsp;*</span></p>
									<textarea class="form-control" name="notes" id="notes" cols="50" rows="3"><?php echo((count($postData)>0 && isset($postData["notes"]))?trim($postData["notes"]):((count($postMeetingData)>0)?$postMeetingData->notes:""));?></textarea>
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
$(document).ready(function(){
	$("#save").click(function(){
		document.pmFrm.action = "<?php echo $this->config->item("insertPostMeeting")."/".((count($postMeetingData)>0)?$postMeetingData->id:0);?>";
		document.pmFrm.submit();
	});
});
</script>