<script src="<?php echo $this->config->item("ckEditorUrl");?>/ckeditor.js" type="text/javascript"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/jquery.form.min.js"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/chosen/chosen.jquery.js"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/clockpicker/clockpicker.js"></script>
<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/clockpicker/clockpicker.css" rel="stylesheet">
<style>
.popover{
	z-index: 10000;
}
</style>
<div class="ibox-content">
	<div id="actionMessages" tabindex='1'></div>
		<form method="POST" action="<?php echo $this->config->item("insertActionAdmin")."/".$postData['user_id'];?>" id="actionFrm" name="actionFrm" class="form-horizontal">
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
		<div class="row">
			<div class="col-md-4" id="hideShowDate" <?php echo ((count($actionData)>0 && $actionData->action_type_id==2)?'style="display:none;"':''); ?>>
				<div class="form-group" id="data_1">
					<p><strong>Select Date</strong><span style="color:red;">&nbsp;*</span></p>
					<div class="input-group date">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" readonly name="remDate" id="remDate" class="form-control" value="<?php echo ((count($actionData)>0 && count($actionData->reminders)>0 && $actionData->action_type_id==1)?date('m/d/Y', strtotime($actionData->reminders[0]->date)):date("m/d/Y")); ?>">
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<p><strong>Select Time</strong><span style="color:red;">&nbsp;*</span></p>
					<div class="input-group clockpicker" data-autoclose="true">
						<input type="text" readonly class="form-control" name="remTime" id="remTime" value="<?php echo ((count($actionData)>0 && count($actionData->reminders)>0)?date('H:i', strtotime($actionData->reminders[0]->time)):date("H:i"));?>">
						<span class="input-group-addon">
							<span class="fa fa-clock-o"></span>
						</span>
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
		</div>
		<div id="addRemTimeDiv">
			<?php if(count($actionData)>0 && count($actionData->reminders)>0 && $actionData->action_type_id==2){ ?>
				<?php $remTimeCounter = 0; foreach($actionData->reminders as $reminder){ ?>
					<?php if($remTimeCounter > 0){ ?>
					<div class="row">
					<div class="col-md-4" id="remTime_<?php echo $remTimeCounter;?>">	
						<div class="form-group">		
							<div class="input-group clockpicker" data-autoclose="true">			
								<input type="text" readonly="" class="form-control" name="remTime_<?php echo $remTimeCounter;?>" id="remTime_<?php echo $remTimeCounter;?>" value="<?php echo date('H:i', strtotime($reminder->time));?>">			
								<span class="input-group-addon">				
									<span class="fa fa-clock-o"></span>				
									<span class="fa fa-window-close" onclick="removeTimeDiv(<?php echo $remTimeCounter;?>)"></span>			
								</span>		
							</div>	
						</div>
					</div>
					</div>
					<?php } ?>
				<?php $remTimeCounter++; } ?>
			<?php } ?>
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
			str+= '<div class="col-md-4" id="remTime_'+subIdCounterVal+'">';
			str+=	'	<div class="form-group">';
			str+=	'		<div class="input-group clockpicker" data-autoclose="true">';
			str+=	'			<input type="text" readonly class="form-control" name="remTime_'+subIdCounterVal+'" id="remTime_'+subIdCounterVal+'" value="<?php echo date("H:i");?>">';
			str+=	'			<span class="input-group-addon">';
			str+=	'				<span class="fa fa-clock-o"></span>';
			str+=	'				<span class="fa fa-window-close" onclick="removeTimeDiv('+subIdCounterVal+')"></span>';
			str+=	'			</span>';
			str+=	'		</div>';
			str+=	'	</div>';
			str+=	'</div>';
			str+=	'</div>';
		$("#addRemTimeDiv").append(str);
		$('.clockpicker').clockpicker();
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
                autoclose: true
            });
			
	$('.clockpicker').clockpicker();
	
	$("#type").change(function(){
		if($(this).val()==1)
		{
			$("#hideShowDate").show();
			$("#hideShowRemButton").hide();
		}
		else
		{
			$("#hideShowDate").hide();
			$("#hideShowRemButton").show();
		}
	});
});

function removeTimeDiv(counter){
	idCounter--;
	$("#remTime_"+counter+"").remove();
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