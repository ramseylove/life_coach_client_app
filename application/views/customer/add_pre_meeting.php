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
					<form method="POST" id="premFrm" name="premFrm" class="form-horizontal actionclss">
					<div class="row">
						<?php
							if(!empty($preMeetingData)) {
								$week = $preMeetingData->weekno;
							}else if(!empty($lastPreMeeting)) {
								$week = $lastPreMeeting->weekno + 1;
							}else {
								$week = 2;
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
									<div class="col-sm-5">
									<strong>Accomplished</strong>
									<?php
									if(!empty($actionsWithoutPostMeetings)) {
									foreach($actionsWithoutPostMeetings as $lastActions) {
									if($lastActions->is_finished == 1 && $lastActions->nextweek == 0) {
										/* if($lastActions->action_type_id == 2) {
										if(!empty($lastActions->reminders)) {
										foreach($lastActions->reminders as $remder) { */
									?>
										<!--div class="row">
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
										</div-->
										<?php /* }}}else { */ ?>
										<div class="row">
											<div class="col-md-12">
												<p>
													<?php if(!in_array($lastActions->id,$preaddedAction)) { ?>
													<!--a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("addActionToNextWeek");?>/<?php echo $lastActions->id;?>" modal-title="Move Action - <?php echo $lastActions->action_title; ?>" data-sub-text="Here You Can Move Action To Next Week">
														<i class="fa fa-plus text-navy" aria-hidden="true"></i>
													</a-->
													<?php } ?>
													<strong><?php echo $lastActions->action_title; ?></strong>
													<p><small>
														<?php
														$remDRStr = '';
														if($lastActions->action_type_id == 2) {
															if(!empty($lastActions->reminders)) {
																$ii = 0;
																foreach($lastActions->reminders as $remder) {
																	if($ii == 0) {
																		$remDRStr = ((trim($remder->date)!='')?date('m/d/Y', strtotime($remder->date)).' ':'').((trim($remder->time)!='')?date('h:i a', strtotime($remder->time)):'');
																	}else{
																		$remDRStr .= ','.((trim($remder->date)!='')?date('m/d/Y', strtotime($remder->date)).' ':'').((trim($remder->time)!='')?date('h:i a', strtotime($remder->time)):'');
																	}
																	$ii++;
																}
															}
															echo '('.$remDRStr.')'; 
														}else if($lastActions->action_type_id == 1) {
															if(count($lastActions->reminders)>0){ 
															$remOTStr = '(';
															$oti = 0;
															foreach($lastActions->reminders as $remOt)
															{
																$oti++;
																$remOTStr.= ((trim($remOt->date)!='')?date('m/d/Y', strtotime($remOt->date)).' ':'').((trim($remOt->time)!='')?date('h:i a', strtotime($remOt->time)):'');
																if($oti < count($lastActions->reminders))
																{
																	$remOTStr.= ',';
																}
																if($oti==count($lastActions->reminders))
																{
																	$remOTStr.= ')';
																}
															}
															}
															echo $remOTStr;
														}else if($lastActions->action_type_id == 3) {
															if(count($lastActions->reminders)>0){
															$dri = 0;
															foreach($lastActions->reminders as $remDr)
															{
																if($remDr->day_selected == 1) {
																	if($dri == 0) {
																		$remDRStr .= $remDr->dayname.': ';
																		$remDRStr.= ((trim($remDr->date)!='')?date('m/d/Y', strtotime($remDr->date)).' ':'').((trim($remDr->time)!='')?date('h:i a', strtotime($remDr->time)):'');
																	}else {
																		$remDRStr .= ','.$remDr->dayname.': ';
																		$remDRStr.= ((trim($remDr->date)!='')?date('m/d/Y', strtotime($remDr->date)).' ':'').((trim($remDr->time)!='')?date('h:i a', strtotime($remDr->time)):'');
																	}
																	$dri++;
																}
															}
															}
															echo '('.$remDRStr.')';
														}
														?>
														</small></p>
												</p>
											</div>
										</div>
										<?php /* } */}}} ?>
									</div>
									<div class="col-sm-5">
									<strong>Incomplete</strong>
									<?php
									if(!empty($actionsWithoutPostMeetings)) {
									foreach($actionsWithoutPostMeetings as $lastActions) {
									if($lastActions->is_finished == 0 && $lastActions->nextweek == 0) {
										/* if($lastActions->action_type_id == 2) {
										if(!empty($lastActions->reminders)) {
										foreach($lastActions->reminders as $remder) { */
									?>
										<!--div class="row">
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
										</div-->
										<?php /* }}}else { */ ?>
										<div class="row">
											<div class="col-md-12">
												<p>
													<?php if(!in_array($lastActions->id,$preaddedAction)) { ?>
													<!--a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("addActionToNextWeek");?>/<?php echo $lastActions->id;?>" modal-title="Move Action - <?php echo $lastActions->action_title; ?>" data-sub-text="Here You Can Move Action To Next Week">
														<i class="fa fa-plus text-navy" aria-hidden="true"></i>
													</a-->
													<?php } ?>
													<strong><?php echo $lastActions->action_title; ?></strong>
													<p><small>
														<?php
														$remDRStr = '';
														if($lastActions->action_type_id == 2) {
															if(!empty($lastActions->reminders)) {
																$ii = 0;
																foreach($lastActions->reminders as $remder) {
																	if($ii == 0) {
																		$remDRStr = ((trim($remder->date)!='')?date('m/d/Y', strtotime($remder->date)).' ':'').((trim($remder->time)!='')?date('h:i a', strtotime($remder->time)):'');
																	}else{
																		$remDRStr .= ','.((trim($remder->date)!='')?date('m/d/Y', strtotime($remder->date)).' ':'').((trim($remder->time)!='')?date('h:i a', strtotime($remder->time)):'');
																	}
																	$ii++;
																}
															}
															echo '('.$remDRStr.')'; 
														}else if($lastActions->action_type_id == 1) {
															if(count($lastActions->reminders)>0){ 
															$remOTStr = '(';
															$oti = 0;
															foreach($lastActions->reminders as $remOt)
															{
																$oti++;
																$remOTStr.= ((trim($remOt->date)!='')?date('m/d/Y', strtotime($remOt->date)).' ':'').((trim($remOt->time)!='')?date('h:i a', strtotime($remOt->time)):'');
																if($oti < count($lastActions->reminders))
																{
																	$remOTStr.= ',';
																}
																if($oti==count($lastActions->reminders))
																{
																	$remOTStr.= ')';
																}
															}
															}
															echo $remOTStr;
														}else if($lastActions->action_type_id == 3) {
															if(count($lastActions->reminders)>0){
															$dri = 0;
															foreach($lastActions->reminders as $remDr)
															{
																if($remDr->day_selected == 1) {
																	if($dri == 0) {
																		$remDRStr .= $remDr->dayname.': ';
																		$remDRStr.= ((trim($remDr->date)!='')?date('m/d/Y', strtotime($remDr->date)).' ':'').((trim($remDr->time)!='')?date('h:i a', strtotime($remDr->time)):'');
																	}else {
																		$remDRStr .= ','.$remDr->dayname.': ';
																		$remDRStr.= ((trim($remDr->date)!='')?date('m/d/Y', strtotime($remDr->date)).' ':'').((trim($remDr->time)!='')?date('h:i a', strtotime($remDr->time)):'');
																	}
																	$dri++;
																}
															}
															}
															echo '('.$remDRStr.')';
														}
														?>
														</small></p>
												</p>
											</div>
										</div>
										<?php /* } */}}} ?>
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
												<?php 
												$actionIdArr = array();
												$nextactionIdArr = array();
												if(!empty($actionsWithoutPostMeetings)) {
												foreach(((isset($actionsWithoutPostMeetings))?$actionsWithoutPostMeetings:$preMeetingData->actions) as $action){ $actionIdArr[] = $action->id;
												if($action->nextweek == 1) {
													$nextactionIdArr[] = $action->id;
													/* if($action->action_type_id == 2) {
													if(!empty($action->reminders)) {
													foreach($action->reminders as $remder) { */
												?>
												<!--div class="row">
													<div class="col-md-12">
														<p>
														<strong><?php echo $action->action_title; ?></strong>
														<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editAction");?>/<?php echo $action->id;?>" modal-title="Edit Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Edit Action"><i class="fa fa-lg fa-edit text-navy"></i></a>
														<?php if($action->addedby == 0) { ?>
															<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteAction");?>/<?php echo $action->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
														<?php } ?>
														</p>
													</div>
												</div-->
												<?php /* }}}else { */ ?>
												<div class="row">
													<div class="col-md-12">
														<p>
														<strong><?php echo $action->action_title; ?></strong>
														<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editAction");?>/<?php echo $action->id;?>" modal-title="Edit Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Edit Action"><i class="fa fa-lg fa-edit text-navy"></i></a>
														<?php if($action->addedby == 0) { ?>
															<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteAction");?>/<?php echo $action->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
														<?php } ?>
														<p><small>
														<?php
														$remDRStr = '';
														if($action->action_type_id == 2) {
															if(!empty($action->reminders)) {
																$ii = 0;
																foreach($action->reminders as $remder) {
																	if($ii == 0) {
																		$remDRStr = ((trim($remder->date)!='')?date('m/d/Y', strtotime($remder->date)).' ':'').((trim($remder->time)!='')?date('h:i a', strtotime($remder->time)):'');
																	}else{
																		$remDRStr .= ','.((trim($remder->date)!='')?date('m/d/Y', strtotime($remder->date)).' ':'').((trim($remder->time)!='')?date('h:i a', strtotime($remder->time)):'');
																	}
																	$ii++;
																}
															}
															echo '('.$remDRStr.')'; 
														}else if($action->action_type_id == 1) {
															if(count($action->reminders)>0){ 
															$remOTStr = '(';
															$oti = 0;
															foreach($action->reminders as $remOt)
															{
																$oti++;
																$remOTStr.= ((trim($remOt->date)!='')?date('m/d/Y', strtotime($remOt->date)).' ':'').((trim($remOt->time)!='')?date('h:i a', strtotime($remOt->time)):'');
																if($oti < count($action->reminders))
																{
																	$remOTStr.= ',';
																}
																if($oti==count($action->reminders))
																{
																	$remOTStr.= ')';
																}
															}
															}
															echo $remOTStr;
														}else if($action->action_type_id == 3) {
															if(count($action->reminders)>0){
															$dri = 0;
															foreach($action->reminders as $remDr)
															{
																if($remDr->day_selected == 1) {
																	if($dri == 0) {
																		$remDRStr .= $remDr->dayname.': ';
																		$remDRStr.= ((trim($remDr->date)!='')?date('m/d/Y', strtotime($remDr->date)).' ':'').((trim($remDr->time)!='')?date('h:i a', strtotime($remDr->time)):'');
																	}else {
																		$remDRStr .= ','.$remDr->dayname.': ';
																		$remDRStr.= ((trim($remDr->date)!='')?date('m/d/Y', strtotime($remDr->date)).' ':'').((trim($remDr->time)!='')?date('h:i a', strtotime($remDr->time)):'');
																	}
																	$dri++;
																}
															}
															}
															echo '('.$remDRStr.')';
														}
														?>
														</small></p>
														</p>
													</div>
												</div>
													<?php /* } */}else { 
													$actionIdArr[] = $action->id;
												}}} ?>
												<input type="hidden" name="hiddenNextActionIds" id="hiddenNextActionIds" value="<?php echo implode('|', $nextactionIdArr); ?>"/>
												<input type="hidden" name="hiddenActionIds" id="hiddenActionIds" value="<?php echo implode('|', $actionIdArr); ?>"/>
											</div>
											<!--div class="col-md-4">
												<a class="btn btn-primary btn-rounded modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addAction").((count($preMeetingData)>0)?'?postMeetingId='.$preMeetingData->id:'');?>" modal-title="Add New Action" data-sub-text="Here you can add a new action.">Add Action</a>
											</div-->
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
									<textarea class="form-control" name="acknowledgment" id="acknowledgment" cols="50" rows="3" placeholder=""><?php echo((count($postData)>0 && isset($postData["acknowledgment"]))?trim($postData["acknowledgment"]):((count($preMeetingData)>0)?$preMeetingData->acknowledgment:""));?></textarea>
									
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>Obstacles</strong></p>
									<textarea class="form-control" name="obstacles" id="obstacles" cols="50" rows="3" placeholder=""><?php echo((count($postData)>0 && isset($postData["obstacles"]))?trim($postData["obstacles"]):((count($preMeetingData)>0)?$preMeetingData->obstacles:""));?></textarea>
								</div>
							</div>
						</div>
					</div> 
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>What are the key areas to discuss during our upcoming session?</strong></p>
									<textarea class="form-control" name="upcoming_session" id="upcoming_session" cols="50" rows="3" placeholder=""><?php echo((count($postData)>0 && isset($postData["upcoming_session"]))?trim($postData["upcoming_session"]):((count($preMeetingData)>0)?$preMeetingData->upcoming_session:""));?></textarea>
								</div>
							</div>
						</div>
					</div> 
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>What would be your most valued outcome as a result of this session?</strong></p>
									<textarea class="form-control" name="valued_outcome" id="valued_outcome" cols="50" rows="3" placeholder=""><?php echo((count($postData)>0 && isset($postData["valued_outcome"]))?trim($postData["valued_outcome"]):((count($preMeetingData)>0)?$preMeetingData->valued_outcome:""));?></textarea>
								</div>
							</div>
						</div>
					</div> 
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>Additional Comment</strong></p>
									<textarea class="form-control" name="additional_comment" id="additional_comment" cols="50" rows="3" placeholder=""><?php echo((count($postData)>0 && isset($postData["additional_comment"]))?trim($postData["additional_comment"]):((count($preMeetingData)>0)?$preMeetingData->additional_comment:""));?></textarea>
								</div>
							</div>
						</div>
					</div> 
					<div class="row">		  
						<div class="col-md-12">	
							<div class="form-group">
								<div class="col-sm-10">
									<input type="button" class="btn btn-primary" id="save" value="Save"/>
									<a class="btn btn-default" href="<?php echo $this->config->item("preMeetingCtrl");?>" data-dismiss="modal">Cancel</a>
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
		$('.errr').hide();
		var err = 0;
		var msgStr = "";
		var acknowledgment = $('#acknowledgment').val();
		if(acknowledgment == '') {
			msgStr+= '<div class="alert alert-warning"><p style="color:red;">The Acknowledgment field is required.</p></div>';
			err = 1;
		}
		if(err == 1) {
			$("#messages").html("");
			$("#messages").append(msgStr).focus();
			return false;
		}else {
			document.premFrm.action = "<?php echo $this->config->item("insertPreMeeting")."/".((count($preMeetingData)>0)?$preMeetingData->id:0);?>";
			document.premFrm.submit();
		}
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