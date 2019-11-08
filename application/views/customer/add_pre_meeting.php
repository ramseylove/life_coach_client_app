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
					<form method="POST" id="premFrm" name="premFrm" class="form-horizontal">
					<div class="row">
						<?php
							if(!empty($preMeetingData)) {
								$week = $preMeetingData->weekno;
							}else if(!empty($lastPreMeeting)) {
								$week = $lastPreMeeting->weekno + 1;
							}else {
								$week = 1;
							}
						?>
						<input type="hidden" name="weekno" value="<?php echo $week; ?>">
						<!--select name="weekno" class="form-control">
							<option value="">Select Week</option-->
							<?php /* foreach($weekTags as $week) { */ ?>
								<!--option value="<?php /* echo $week->id; */ ?>" <?php /* if(!empty($preMeetingData) && $week->id == $preMeetingData->weekno) { echo 'selected';  }*/ ?>><?php /* echo $week->weektag; */ ?></option-->
							<?php /* } */ ?>
						<!--/select-->
					</div>
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
									<h3>Last Week's Actions</h3>
									<div class="col-sm-3">
									<strong>Accomplished</strong>
									<?php 
									foreach($lastPreMeetingActions as $lastActions) { 
									if($lastActions->is_finished == 1) {
									?>
										<div class="row">
											<div class="col-md-12">
												<p>
													<?php if(!in_array($lastActions->id,$preaddedAction)) { ?>
													<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("addActionToNextWeek");?>/<?php echo $lastActions->id;?>" modal-title="Move Action - <?php echo $lastActions->action_title; ?>" data-sub-text="Here You Can Move Action To Next Week">
														<i class="fa fa-plus text-navy" aria-hidden="true"></i>
													</a>
													<?php } ?>
													<strong><?php echo $lastActions->action_title; ?></strong>
												</p>
											</div>
										</div>
									<?php }} ?>
									</div>
									<div class="col-sm-3">
									<strong>Incomplete</strong>
									<?php 
									foreach($lastPreMeetingActions as $lastActions) {
									if($lastActions->is_finished == 0) { 
									?>
										<div class="row">
											<div class="col-md-12">
												<p>
													<?php if(!in_array($lastActions->id,$preaddedAction)) { ?>
													<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("addActionToNextWeek");?>/<?php echo $lastActions->id;?>" modal-title="Move Action - <?php echo $lastActions->action_title; ?>" data-sub-text="Here You Can Move Action To Next Week">
														<i class="fa fa-plus text-navy" aria-hidden="true"></i>
													</a>
													<?php } ?>
													<strong><?php echo $lastActions->action_title; ?></strong>
												</p>
											</div>
										</div>
									<?php }} ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p>
										<div class="row">
											<div class="col-md-8">
												<p><h3>This Weeks Actions</h3></p>
												<?php $actionIdArr = array(); foreach(((isset($actionsWithoutPostMeetings))?$actionsWithoutPostMeetings:$preMeetingData->actions) as $action){ $actionIdArr[] = $action->id;?>
												<div class="row">
													<div class="col-md-12">
														<p>
														<strong><?php echo $action->action_title; ?></strong>
														<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editAction");?>/<?php echo $action->id;?>" modal-title="Edit Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Edit Action"><i class="fa fa-lg fa-edit text-navy"></i></a>
														<?php if($action->addedby == 0) { ?>
															<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteAction");?>/<?php echo $action->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
														<?php } ?>
														</p>
													</div>
												</div>
												<?php } ?>
												<input type="hidden" name="hiddenActionIds" id="hiddenActionIds" value="<?php echo implode('|', $actionIdArr); ?>"/>
											</div>
											<div class="col-md-4">
												<a class="btn btn-primary btn-rounded modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addAction").((count($preMeetingData)>0)?'?postMeetingId='.$preMeetingData->id:'');?>" modal-title="Add New Action" data-sub-text="Here you can add a new action.">Add Action</a>
											</div>
										</div>
									</p>
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
		document.premFrm.action = "<?php echo $this->config->item("insertPreMeeting")."/".((count($preMeetingData)>0)?$preMeetingData->id:0);?>";
		document.premFrm.submit();
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