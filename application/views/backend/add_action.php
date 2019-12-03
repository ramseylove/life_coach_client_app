<script src="<?php echo $this->config->item("ckEditorUrl");?>/ckeditor.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/jquery.form.min.js"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/chosen/chosen.jquery.js"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/clockpicker/clockpicker.js"></script>
<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/clockpicker/clockpicker.css" rel="stylesheet">
<link href="<?php echo $this->config->item("inspinia_css_url");?>/pickerstyle.css" rel="stylesheet">
<style>
.popover{
	z-index: 10000;
}
</style>
<div class="ibox-content">
	<div id="actionMessages" tabindex='1'></div>
	<form method="POST" action="<?php echo $this->config->item("insertAction")."/".((count($actionData)>0)?$actionData->id:0);?>" id="actionFrm" name="actionFrm" class="form-horizontal">
		<?php 
		/* echo '<pre>'; print_r($actionData);  */
		?>
		<?php
			if(!empty($actionData)) {
				$week = $actionData->weekno;
			}else if(!empty($lastPostMeeting)) {
				$week = $lastPostMeeting->weekno + 1;
			}else {
				$week = 1;
			}
		?>
		<input type="hidden" name="weekno" value="<?php echo $week; ?>">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<div class="col-sm-10">
						<p><strong>Title</strong><span style="color:red;">&nbsp;*</span></p>
						<input type="text" name="title" id="title" class="form-control" value="<?php echo((count($postData)>0 && isset($postData["title"]))?trim($postData["title"]):((count($actionData)>0)?$actionData->action_title:""));?>"/>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				 <div class="form-group">
					<div class="col-sm-10">
						<p><strong>Type Of Action</strong></p>
						<select class="form-control" name="type" id="type">
							<?php foreach($actionTypeData as $actionType){ ?>
								<option value="<?php echo $actionType->id;?>"<?php echo((count($postData)>0 && isset($postData["type"]) && trim($postData["type"])==$actionType->id)?"selected":((count($actionData)>0 && $actionData->action_type_id==$actionType->id)?"selected":""));?>><?php echo $actionType->title;?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-5">
				 <div class="form-group">
					<div class="col-sm-12">
						<p><strong>Select Goals</strong></p>
						<select data-placeholder="Choose Goal.." class="chosen-select" multiple tabindex="4" name="goals[]" id="goals">
							<?php
								$goalsIdArr = array();
								if(count($actionData)>0)
								{
									foreach($actionData->goals as $goalData)
									{
										$goalsIdArr[] = $goalData->goal_id;
									}
								}
							?>
							<?php foreach($goals as $goal){ ?>
								<option value="<?php echo $goal->id; ?>"<?php echo ((count($goalsIdArr)>0 && in_array($goal->id, $goalsIdArr))?'selected':'');?>><?php echo $goal->title.' - ('.(($goal->is_secondary==0)?'P':'S').')'; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div id="otherdiv" <?php if(!empty($actionData)) { if($actionData->action_type_id==3) { echo 'style="display:none;"'; }else { echo 'style="display:block;"'; } } ?>>
			<div class="col-md-4" id="hideShowDate" <?php echo ((count($actionData)>0 && $actionData->action_type_id==2)?'style="display:none;"':''); ?>>
				<div class="form-group" id="data_1">
					<p><strong>Select Date</strong><span style="color:red;">&nbsp;*</span></p>
					<div class="input-group date">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" readonly name="remDate" id="remDate" class="form-control" value="<?php echo ((count($actionData)>0 && count($actionData->reminders)>0 && $actionData->action_type_id==1)?date('m/d/Y', strtotime($actionData->reminders[0]->date)):date("m/d/Y")); ?>">
					</div>
				</div>
			</div>
			<div class="col-md-4 hideShowtime">
				<div class="form-group">
					<p><strong>Select Time</strong><span style="color:red;">&nbsp;*</span></p>
					<!--div class="input-group clockpicker" data-autoclose="true">
						<input type="text" readonly class="form-control" name="remTime" id="remTime" value="<?php /* echo ((count($actionData)>0 && count($actionData->reminders)>0)?date('h:i a', strtotime($actionData->reminders[0]->time)):date("h:i a")); */?>">
						<span class="input-group-addon">
							<span class="fa fa-clock-o"></span>
						</span>
					</div-->
					<div class="input-group">
						
						<input type="text" readonly class="form-control format_24" id="format_24" name="remTime" value="<?php echo ((count($actionData)>0 && count($actionData->reminders)>0)?date('h:i a', strtotime($actionData->reminders[0]->time)):date("h:i a"));?>">
						<span class="input-group-addon">
							<span class="fa fa-clock-o"></span>
						</span>
						<div class="checks1">
							<label class="checkers">
								<input class="month-check" type="checkbox" name="remTime_check" id="remTime_check" <?php if(!empty($actionData->reminders)) { if($actionData->reminders[0]->daycheck == 1) { echo 'checked'; }} ?>/>
								<span class="checkmark"></span>
							</label>
						</div>
					</div>
					<input type="hidden" id="remTimeCounter" name="remTimeCounter" value="<?php echo ((count($actionData)>0 && count($actionData->reminders)>0 && $actionData->action_type_id==2)?(count($actionData->reminders)-1):0);?>"/>
				</div>
			</div>
			<div class="col-md-4" id="hideShowRemButton" <?php echo ((count($actionData)>0 && $actionData->action_type_id==2)?'':'style="display:none;"');?>>
				<div class="form-group">
					<p>&nbsp;</p>
					<div class="col-sm-10">
						<a class="btn btn-warning" id="addRemTime">Add Reminder</a>
					</div>
				</div>
			</div>
			<div id="addRemTimeDiv" class="hideShowtimeappend">
				<?php if(count($actionData)>0 && count($actionData->reminders)>0 && $actionData->action_type_id==2){ ?>
					<?php $remTimeCounter = 0; foreach($actionData->reminders as $reminder){ ?>
						<?php if($remTimeCounter > 0){ ?>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4 remTime_<?php echo $remTimeCounter;?>">	
									<div class="form-group">		
										<div class="input-group" data-autoclose="true">			
											<input type="text" readonly="" class="form-control format_<?php echo $remTimeCounter;?>" name="remTime_<?php echo $remTimeCounter;?>" id="remTime_<?php echo $remTimeCounter;?>" value="<?php echo date('h:i a', strtotime($reminder->time));?>">			
											<span class="input-group-addon">				
												<span class="fa fa-clock-o"></span>				
												<span class="fa fa-window-close" onclick="removeTimeDiv(<?php echo $remTimeCounter;?>)"></span>
											</span>
											<div class="checks1">
												<label class="checkers">
													<input class="month-check" type="checkbox" name="remTime_check_<?php echo $remTimeCounter;?>" id="remTime_check_<?php echo $remTimeCounter;?>" <?php if($reminder->daycheck == 1) { echo 'checked'; } ?>/>
													<span class="checkmark"></span>
												</label>
											</div>
										</div>	
									</div>
								</div>
							</div>
						</div>
						<script>
							var cou = <?php echo '"'.$remTimeCounter.'"'; ?>;
							var format_new = document.querySelector('.format_'+cou);
							var format_new_picker = new NJTimePicker({
								targetID: 'remTime_'+cou,
								headerText: '12-Hour Picker',
							});
							format_new_picker.on('save', function (data) {
								if (data.fullResult)
									$('#format_'+cou).val(data.fullResult);
							});
						</script>
						<?php } ?>
					<?php $remTimeCounter++; } ?>
				<?php } ?>
			</div>
		</div>
		<div id="weeklyDiv" class="row"  <?php if(!empty($actionData)) { if($actionData->action_type_id==3) { echo 'style="display:block;'; }else { echo 'style="display:none;"'; } }else { echo 'style="display:none;"'; } ?>>
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-6">
						 <div class="form-group">
							<div class="col-sm-12">
							<?php
							$weearray = array();
							$iii = 0;
							if(!empty($actionData) && !empty($actionData->reminders) && $actionData->action_type_id==3) {
								foreach($actionData->reminders as $reminderss) {
									if($reminderss->day_selected == 1) {
										$weearray[$iii] = $reminderss->dayname;
										$iii++;
									}
								}
							}
							?>
								<p><strong>Select Days</strong></p>
								<select data-placeholder="Choose Days.." class="chosen-select" multiple tabindex="4" name="selectdays[]" id="selectdays" onchange="return showweeksval()">
									<option value="week_monday" <?php if(in_array('Monday', $weearray)) { echo 'selected'; } ?>>Monday</option>
									<option value="week_tuesday" <?php if(in_array('Tuesday', $weearray)) { echo 'selected'; } ?>>Tuesday</option>
									<option value="week_wednesday" <?php if(in_array('Wednesday', $weearray)) { echo 'selected'; } ?>>Wednesday</option>
									<option value="week_thursday" <?php if(in_array('Thursday', $weearray)) { echo 'selected'; } ?>>Thursday</option>
									<option value="week_friday" <?php if(in_array('Friday', $weearray)) { echo 'selected'; } ?>>Friday</option>
									<option value="week_satureday <?php if(in_array('Satureday', $weearray)) { echo 'selected'; } ?>">Satureday</option>
									<option value="week_sunday" <?php if(in_array('Sunday', $weearray)) { echo 'selected'; } ?>>Sunday</option>
								</select>
							</div>
						</div>
					<?php
					if(!empty($actionData) && !empty($actionData->reminders) && $actionData->action_type_id==3) {
						foreach($actionData->reminders as $reminderss) {
							$dayname = strtolower($reminderss->dayname);
					?>
						<div id="show_week_<?php echo $dayname; ?>" <?php if($reminderss->day_selected == 1) { echo 'style="display:block;"'; }else { echo 'style="display:none;"'; } ?>>
							<label><?php echo $reminderss->dayname; ?></label>
							<div class="input-group">
								<input type="text" readonly class="form-control week_<?php echo $dayname; ?>" id="week_<?php echo $dayname; ?>" name="week_<?php echo $dayname; ?>" value="<?php if($reminderss->time != '') { echo date('h:i a', strtotime($reminderss->time)); }else { echo date("h:i a"); } ?>">
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
								<div class="checks1">
									<label class="checkers">
										<input class="month-check" type="checkbox" name="<?php echo $dayname; ?>_check" id="<?php echo $dayname; ?>_check" <?php if($reminderss->daycheck == 1) { echo 'checked'; } ?>/>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>
					<?php }}else { ?>
						<div id="show_week_monday" style="display:none;">
							<label>Monday</label>
							<div class="input-group">
								<input type="text" readonly class="form-control week_monday" id="week_monday" name="week_monday" value="<?php echo date("h:i a");?>">
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
								<div class="checks1">
									<label class="checkers">
										<input class="month-check" type="checkbox" name="monday_check" id="monday_check"/>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>
						<div id="show_week_tuesday" style="display:none;">
							<label>Tuesday</label>
							<div class="input-group">
								<input type="text" readonly class="form-control week_tuesday" id="week_tuesday" name="week_tuesday" value="<?php echo date("h:i a");?>">
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
								<div class="checks1">
									<label class="checkers">
										<input class="month-check" type="checkbox" name="tuesday_check" id="tuesday_check"/>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>
						<div id="show_week_wednesday" style="display:none;">
							<label>Wednesday</label>
							<div class="input-group">
								<input type="text" readonly class="form-control week_wednesday" id="week_wednesday" name="week_wednesday" value="<?php echo date("h:i a");?>">
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
								<div class="checks1">
									<label class="checkers">
										<input class="month-check" type="checkbox" name="wednesday_check" id="wednesday_check"/>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>
						<div id="show_week_thursday" style="display:none;">
							<label>Thursday</label>
							<div class="input-group">
								<input type="text" readonly class="form-control week_thursday" id="week_thursday" name="week_thursday" value="<?php echo date("h:i a");?>">
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
								<div class="checks1">
									<label class="checkers">
										<input class="month-check" type="checkbox" name="thursday_check" id="thursday_check"/>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>
						<div id="show_week_friday" style="display:none;">
							<label>Friday</label>
							<div class="input-group">
								<input type="text" readonly class="form-control week_friday" id="week_friday" name="week_friday" value="<?php echo date("h:i a");?>">
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
								<div class="checks1">
									<label class="checkers">
										<input class="month-check" type="checkbox" name="friday_check" id="friday_check"/>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>
						<div id="show_week_satureday" style="display:none;">
							<label>Satureday</label>
							<div class="input-group">
								
								<input type="text" readonly class="form-control week_satureday" id="week_satureday" name="week_satureday" value="<?php echo date("h:i a");?>">
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
								<div class="checks1">
									<label class="checkers">
										<input class="month-check" type="checkbox" name="satureday_check" id="satureday_check"/>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>
						<div id="show_week_sunday" style="display:none;">
							<label>Sunday</label>
							<div class="input-group">
								
								<input type="text" readonly class="form-control week_sunday" id="week_sunday" name="week_sunday" value="<?php echo date("h:i a");?>">
								<span class="input-group-addon">
									<span class="fa fa-clock-o"></span>
								</span>
								<div class="checks1">
									<label class="checkers">
										<input class="month-check" type="checkbox" name="sunday_check" id="sunday_check"/>
										<span class="checkmark"></span>
									</label>
								</div>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix">
		<div class="row">
			<div class="col-md-6">
				<div class="upload-pre-row">
					<div id="forappend">
						<?php
						$i = 0;
						if(!empty($questions) && count($questions)>0){
							foreach($questions as $key => $val) {
							if(isset($remIds) && $remIds != 0) {
							if($remIds == $val->remId) {
						?>
						<div id="uploadpre<?php echo $key; ?>" class="questions">
							<div class="dr-name-fields">
								<input class="form-control" type="text" name="question[]" id="question-<?php echo $key; ?>" value="<?php echo $val->question; ?>" placeholder="Add Question" required />
								<span class="btn btn-default classremove" onclick="return deletefield(<?php echo $key; ?>)">
									<i class="fa fa-times" aria-hidden="true"></i>
								</span>
							</div>
						</div>
							<?php }}else { ?>
						<div id="uploadpre<?php echo $key; ?>" class="questions">
							<div class="dr-name-fields">
								<input class="form-control" type="text" name="question[]" id="question-<?php echo $key; ?>" value="<?php echo $val->question; ?>" placeholder="Add Question" required />
								<span class="btn btn-default classremove" onclick="return deletefield(<?php echo $key; ?>)">
									<i class="fa fa-times" aria-hidden="true"></i>
								</span>
							</div>
						</div>
						<?php } $i++; }} ?>
					</div>
					<div class="add-btn">
						<span class="btn pull-left changeidss" id="<?php echo $i; ?>" onclick="return add_form_field(this.id)">+ Add Question</span>
					</div>
				</div>
			</div>
		</div>
		</div>
		<div id="hiddenpost">
			<input type="hidden" name="general_topic" class="general_topic">
			<input type="hidden" name="session_value" class="session_value">
			<input type="hidden" name="notes" class="notes">
			<input type="hidden" name="nextweek" class="nextweek">
		</div>
		<div class="row">		  
			<div class="col-md-12">	
				<div class="form-group">
					<div class="col-sm-10">
						<?php if(isset($postMeetingId)) { ?>
							<input type="hidden" name="postMeetingId" id="postMeetingId" value="<?php echo trim($postMeetingId); ?>"/>
						<?php } ?>
						<input type="button" class="btn btn-primary" id="saveAction" value="Save"/>
						<button class="btn" data-dismiss="modal">Cancel</button>
					</div>
				</div>		
			</div>
		</div>	 
	</form>
</div>
<script type="text/javascript">
var idCounter = <?php echo ((count($actionData)>0 && count($actionData->reminders)>0 && $actionData->action_type_id==2)?(count($actionData->reminders)-1):0);?>;
var subIdCounterVal = <?php echo ((count($actionData)>0 && count($actionData->reminders)>0 && $actionData->action_type_id==2)?(count($actionData->reminders)-1):0);?>;
$("#addRemTime").click(function(){
	if(idCounter < 4)
	{
		idCounter++;
		subIdCounterVal++;
		var str =	'<div class="row extraTimers">';
			str+= '<div class="col-md-12"><div class="col-md-4 remTime_'+subIdCounterVal+'">';
			str+=	'	<div class="form-group">';
			str+=	'		<div class="input-group" data-autoclose="true">';
			str+=	'			<input type="text" readonly class="form-control format_'+subIdCounterVal+'" name="remTime_'+subIdCounterVal+'" id="remTime_'+subIdCounterVal+'" value="<?php echo date("h:i a");?>">';
			str+=	'			<span class="input-group-addon format_'+subIdCounterVal+'">';
			str+=	'				<span class="fa fa-clock-o"></span>';
			str+=	'				<span class="fa fa-window-close" onclick="removeTimeDiv('+subIdCounterVal+')"></span>';
			str+=	'			</span><div class="checks1"><label class="checkers"><input class="month-check" type="checkbox" name="remTime_check_'+subIdCounterVal+'" id="remTime_check_'+subIdCounterVal+'" /><span class="checkmark"></span></label></div>';
			str+=	'		</div>';
			str+=	'	</div>';
			str+=	'</div>';
			str+=	'</div></div>';
		$("#addRemTimeDiv").append(str);
		let format_new = document.querySelector('.format_'+subIdCounterVal);
		var format_new_picker = new NJTimePicker({
			targetID: 'remTime_'+subIdCounterVal,
			headerText: '12-Hour Picker',
		});
		format_new_picker.on('save', function (data) {
			if (data.fullResult)
				$('#format_'+subIdCounterVal).val(data.fullResult);
		});
		/* $('.clockpicker').clockpicker(); */
	}
	else
	{
		alert('Maximum 5 time-reminders are allowed!');
		return false;
	}
});

$(document).ready(function(){
	$('.chosen-select').chosen({width: "100%"});
	
	$("#saveAction").click(function(){
		$('#remTimeCounter').val(subIdCounterVal);
		$("#actionFrm").ventricleSubmitForm('saveResp');
		$(this).prop('disabled', true);
	});
	
	$('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
				format: 'mm/dd/yyyy',
                autoclose: true
            });
			
	$('.clockpicker').clockpicker();
	
	$("#type").change(function(){
		if($(this).val()==1)
		{	
			$("#weeklyDiv").hide();
			$("#otherdiv").show();
			$("#hideShowDate").show();
			$("#hideShowRemButton").hide();
			$(".hideShowtimeappend").hide();
		}
		else if($(this).val()==2)
		{
			$("#weeklyDiv").hide();
			$("#otherdiv").show();
			$("#hideShowDate").hide();
			$("#hideShowRemButton").show();
			$(".hideShowtimeappend").show();
		}if($(this).val()==3)
		{
			$("#otherdiv").hide();
			$("#weeklyDiv").show();
		}
	});
});

function removeTimeDiv(counter){ 
	idCounter--;
	$(".remTime_"+counter+"").remove();
}

function saveResp(response){
	$(".form-control").css('border','').css('border-width','');
	$("#actionMessages").html("");
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
			
			$("#actionMessages").append(msgStr).focus();
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
				$(".centralView").load(window.location.href+"<?php echo(($_GET && !isset($_GET['pagination']))?'?'.http_build_query($_GET).'&pagination=1':'?pagination=1')?>&heads=1");
				
            }, 500);
		}
	}
	else
	{
		$("#saveAction").prop('disabled', false);
	}
}
function add_form_field(rownum) {
	rownum = parseInt(rownum) + parseInt(1);
	$('.changeidss').attr("id", rownum);
	$('#forappend').append('<div id="uploadpre'+rownum+'" class="questions"><div class="dr-name-fields"><input class="form-control" type="text" name="question[]" id="question-'+rownum+'" value="" placeholder="Add Question" required><span class="btn btn-default classremove" onclick="return deletefield('+rownum+')"><i class="fa fa-times" aria-hidden="true"></i></span></div></div>');
	return false;
}
function deletefield(rownum) {
	$('#uploadpre'+rownum).remove();
	return false;
}

(function () {
	let format_24 = document.querySelector('.format_24');
	var format_24_picker = new NJTimePicker({
		targetID: 'format_24',
		headerText: '12-Hour Picker',
	});
	format_24_picker.on('save', function (data) {
		if(data.fullResult) {
			$('#format_24').val(data.fullResult);
		}
	});
	let week_monday = document.querySelector('.week_monday');
	var week_monday_picker = new NJTimePicker({
		targetID: 'week_monday',
		headerText: '12-Hour Picker',
	});
	week_monday_picker.on('save', function (data) {
		if(data.fullResult) {
			$('#week_monday').val(data.fullResult);
		}
	});
	let week_tuesday = document.querySelector('.week_tuesday');
	var week_tuesday_picker = new NJTimePicker({
		targetID: 'week_tuesday',
		headerText: '12-Hour Picker',
	});
	week_tuesday_picker.on('save', function (data) {
		if(data.fullResult) {
			$('#week_tuesday').val(data.fullResult);
		}
	});
	let week_wednesday = document.querySelector('.week_wednesday');
	var week_wednesday_picker = new NJTimePicker({
		targetID: 'week_wednesday',
		headerText: '12-Hour Picker',
	});
	week_wednesday_picker.on('save', function (data) {
		if(data.fullResult) {
			$('#week_wednesday').val(data.fullResult);
		}
	});
	let week_thursday = document.querySelector('.week_thursday');
	var week_thursday_picker = new NJTimePicker({
		targetID: 'week_thursday',
		headerText: '12-Hour Picker',
	});
	week_thursday_picker.on('save', function (data) {
		if(data.fullResult) {
			$('#week_thursday').val(data.fullResult);
		}
	});
	let week_friday = document.querySelector('.week_friday');
	var week_friday_picker = new NJTimePicker({
		targetID: 'week_friday',
		headerText: '12-Hour Picker',
	});
	week_friday_picker.on('save', function (data) {
		if(data.fullResult) {
			$('#week_friday').val(data.fullResult);
		}
	});
	let week_satureday = document.querySelector('.week_satureday');
	var week_satureday_picker = new NJTimePicker({
		targetID: 'week_satureday',
		headerText: '12-Hour Picker',
	});
	week_satureday_picker.on('save', function (data) {
		if(data.fullResult) {
			$('#week_satureday').val(data.fullResult);
		}
	});
	let week_sunday = document.querySelector('.week_sunday');
	var week_sunday_picker = new NJTimePicker({
		targetID: 'week_sunday',
		headerText: '12-Hour Picker',
	});
	week_sunday_picker.on('save', function (data) {
		if(data.fullResult) {
			$('#week_sunday').val(data.fullResult);
		}
	});
})();
function showweeksval() {
	var string = $("form#actionFrm #selectdays").val();
	var values = string.toString().split(",");
	for(var i = 0;i < values.length;i++) { 
		$('#show_'+values[i]).show();
	}
}
</script>
<style>
.dr-name-fields {
	display: flex;
	margin: 12px 0;
}
.dr-name-fields span.btn.btn-default.classremove {
	border-radius: 0;
	border-left: 0;
}
.add-btn .btn {
	padding: 0;
	margin-bottom: 9px;
}

.month-check {
    vertical-align: top;
    margin: 10px 5px !important;
    display: block;
}
</style>