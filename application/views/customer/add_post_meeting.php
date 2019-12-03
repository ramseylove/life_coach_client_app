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
					<?php
						if(!empty($postMeetingData)) {
							$week = $postMeetingData->weekno;
						}else if(!empty($lastPostMeeting)) {
							$week = $lastPostMeeting->weekno + 1;
						}else {
							$week = 1;
						}
						if(!empty($lastPostMeeting)) {
							$datet = $lastPostMeeting->week_end;
							$dates = strtotime("+1 day", strtotime($datet));
							$date = date('Y-m-d h:i:s', $dates);
							$week_start = date('Y-m-d', $dates);
							$date1 = strtotime($date);
							$date2 = strtotime("+6 day", $date1);
							$week_enddate = date('Y-m-d h:i:s', $date2);
							$week_end = date('Y-m-d', $date2);
						}else {
							$date = date('Y-m-d h:i:s');
							$week_start = date('Y-m-d');
							$date1 = strtotime($date);
							$date2 = strtotime("+6 day", $date1);
							$week_enddate = date('Y-m-d h:i:s', $date2);
							$week_end = date('Y-m-d', $date2);
						}
						?>
						<input type="hidden" name="week_start" value="<?php echo $date; ?>"/>
						<input type="hidden" name="week_end" value="<?php echo $week_enddate; ?>"/>
						<input type="hidden" name="weekno" value="<?php echo $week; ?>"/>
						</div>
						</div>
						</div>
						</div>					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<p><strong>General Topic Of Meeting</strong><span style="color:red;">&nbsp;*</span></p>
									<?php if(isset($_SESSION['general_topicw'])) { $postData["general_topic"] = $_SESSION['general_topicw']; unset($_SESSION['general_topicw']); } ?>
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
									<?php if(isset($_SESSION['session_valuew'])) { $postData["session_value"] = $_SESSION['session_valuew']; unset($_SESSION['session_valuew']); } ?>
									<textarea class="form-control" name="session_value" id="session_value" cols="50" rows="3"><?php echo((count($postData)>0 && isset($postData["session_value"]))?trim($postData["session_value"]):((count($postMeetingData)>0)?$postMeetingData->session_value:""));?></textarea>
									
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-sm-10">
									<?php if(isset($_SESSION['notesw'])) { $postData["notes"] = $_SESSION['notesw']; unset($_SESSION['notesw']); } ?>
									<p><strong>Other Notes</strong></p>
									<textarea class="form-control" name="notes" id="notes" cols="50" rows="3"><?php echo((count($postData)>0 && isset($postData["notes"]))?trim($postData["notes"]):((count($postMeetingData)>0)?$postMeetingData->notes:""));?></textarea>
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
									/* if($lastActions->action_type_id == 2) { */
										$reids = 0;
										if(!empty($lastActions->reminders)) {
										foreach($lastActions->reminders as $remder) {
											$reids = $remder->id;
										}}
									?>
										<!--div class="row">
											<div class="col-md-12">
												<p>
													<?php if(!in_array($lastActions->id,$preaddedAction)) { ?>
													<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("addActionToNextWeek");?>/<?php echo $lastActions->id;?>/<?php echo $remder->id;?>" modal-title="Move Action - <?php echo $lastActions->action_title; ?>" data-sub-text="Here You Can Move Action To Next Week">
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
													<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("addActionToNextWeek");?>/<?php echo $lastActions->id;?>/<?php echo $reids; ?>" modal-title="Move Action - <?php echo $lastActions->action_title; ?>" data-sub-text="Here You Can Move Action To Next Week">
														<i class="fa fa-plus text-navy" aria-hidden="true"></i>
													</a>
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
									/* if($lastActions->action_type_id == 2) {*/
										$reids = 0;
										if(!empty($lastActions->reminders)) {
										foreach($lastActions->reminders as $remder) {
											$reids = $remder->id;
										}}
									?>
										<!--div class="row">
											<div class="col-md-12">
												<p>
													<?php if(!in_array($lastActions->id,$preaddedAction)) { ?>
													<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("addActionToNextWeek");?>/<?php echo $lastActions->id;?>/<?php echo $remder->id;?>" modal-title="Move Action - <?php echo $lastActions->action_title; ?>" data-sub-text="Here You Can Move Action To Next Week">
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
													<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("addActionToNextWeek");?>/<?php echo $lastActions->id;?>/<?php echo $reids;?>" modal-title="Move Action - <?php echo $lastActions->action_title; ?>" data-sub-text="Here You Can Move Action To Next Week">
														<i class="fa fa-plus text-navy" aria-hidden="true"></i>
													</a>
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
												<?php $actionIdArr = array();
												 $nextactionIdArr = array();
												if(!empty($actionsWithoutPostMeetings)) {
												foreach(((isset($actionsWithoutPostMeetings))?$actionsWithoutPostMeetings:$postMeetingData->actions) as $action){ 
												if($action->nextweek == 1) {
													$nextactionIdArr[] = $action->id;
													/* if($action->action_type_id == 2) {*/
													$reids = 0;
													if(!empty($action->reminders)) {
													foreach($action->reminders as $remder) {
														$reids = $remder->id;
													}
													}
												?>
												<!--div class="row">
													<div class="col-md-12">
														<p>
														<strong><?php echo $action->action_title; ?></strong>
														<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editAction");?>/<?php echo $action->id;?>/<?php echo $remder->id;?>" modal-title="Edit Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Edit Action"><i class="fa fa-lg fa-edit text-navy"></i></a>
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
														
														<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editAction");?>/<?php echo $action->id;?>/<?php echo $reids; ?>" modal-title="Edit Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Edit Action"><i class="fa fa-lg fa-edit text-navy"></i></a>
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
											<div class="col-md-4">
												<a class="btn btn-primary btn-rounded modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addAction").((count($postMeetingData)>0)?'?postMeetingId='.$postMeetingData->id:'');?>" modal-title="Add New Action" data-sub-text="Here you can add a new action.">Add Action</a>
											</div>
										</div>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">		  
						<div class="col-md-12">	
							<div class="form-group">
								<div class="col-sm-10">
									<input type="button" class="btn btn-primary" id="save" value="Save"/>
									<a class="btn btn-default" href="<?php echo $this->config->item("postMeetingCtrl");?>" data-dismiss="modal">Cancel</a>
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
	$('.modalInvoke').ventricleModalViewLoadForPost("commonModal");
	
	$(".delete").click(function(event){
		var _this = $(this);
		$("#confirmation .modal-title").html('Delete Action');
		$("#confirmation div.modal-body").html('<h4>Do you want to delete this action?</h4>');
		$("#confirmation button.btn-primary").attr('data-href', _this.attr('data-href'));
		$("#confirmation").modal({show:true});
	});
	
	$("#confirmation button.btn-primary").unbind().click(function(){
			/* $(this).prop('disabled', true);
			$(this).ventricleDirectAjax("GET",'','deleteResponse'); */
			var srcUrl = $(this).attr('data-href');
			var formId = 'pmFrm';
			var requestType = 'POST';
			var val1 = $('#general_topic').val();
			var val2 = $('#session_value').val();
			var val3 = $('#notes').val();
			var serializedFormData = (($.trim(formId)!="")?$('#'+formId+'').serializeArray():{});
			var vals = '&general_topic='+val1+'&session_value='+val2+'&notes='+val3;
				serializedFormData = serializedFormData + vals;
				$.ajax({

				  type: requestType,

				  cache: false,

				  url: srcUrl,

				  data:serializedFormData,

				  success: function (resp) {

					var parseResp = JSON.parse(resp);

					window['deleteResponse'](parseResp);

				  }, // success

				  error: function(jqXHR, textStatus, errorThrown) 

				  {

					/* $("#error .modal-title").html('Error Occured!');

					$("#error div.modal-body").html('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');

					$("#error").modal({show:true}); */

				  },

				});
	});
	
	$("#save").click(function(){
		$('.errr').hide();
		var err = 0;
		var msgStr = "";
		var general_topic = $('#general_topic').val();
		var session_value = $('#session_value').val();
		if(general_topic == '') {
			msgStr+= '<div class="alert alert-warning"><p style="color:red;">The General Topic Of Meeting field is required.</p></div>';
			err = 1;
		}
		if(session_value == '') {
			msgStr+= "<div class='alert alert-warning'><p style='color:red;'>The What Value Did You Get From This Week's Session? field is required.</p></div>";
			err = 1;
		}
		if(err == 1) {
			$("#messages").html("");
			$("#messages").append(msgStr).focus();
			return false;
		}else {
			document.pmFrm.action = "<?php echo $this->config->item("insertPostMeeting")."/".((count($postMeetingData)>0)?$postMeetingData->id:0);?>";
			document.pmFrm.submit();
		}
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