<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/iCheck/custom.css" rel="stylesheet">
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/iCheck/icheck.min.js"></script>
<div class="row">
	<div class="col-lg-12">
		<?php if(!empty($lastPostMeeting)) { ?>
			<div class="row wrapper border-bottom white-bg page-heading actions-class">
				<div class="row">
					<div class="col-lg-12">
						<h2>Actions</h2>
					</div>
					<hr>
					
					<div class="col-sm-12">
						<a class="btn btn-primary btn-rounded modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addAction");?>" modal-title="Add Action" data-sub-text="Here you can add a new action.">Add Action</a>
					</div>
				</div>
            </div>
			<div class="wrapper wrapper-content animated fadeInRight">	
				<div class="row">
				<?php if(!empty($actions['daily']) && count($actions['daily'])>0) { ?>
					<div class="col-md-6">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Daily-Routines</h5>
							</div>
							<div class="ibox-content">
								<table class="table table-hover">
									<thead>
									<tr>
										<th></th>
										<th>Title</th>
										<th>Action</th>
									</tr>
									</thead>
									<tbody>
										<?php
											$i=0; foreach($actions['daily'] as $action) {
											if($action->nextweek == 0) { 
											if(count($action->reminders)>0) {
											foreach($action->reminders as $remDr) {
										?>
										<tr id="actionRow_<?php echo $action->id;?>">
											<td>
												<div class="checks1">
													<label class="checkers">
													<?php if($remDr->is_finished == 0){ ?>
														<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("completeAction");?>/<?php echo $action->id;?>/<?php echo $remDr->id; ?>" modal-title="Complete Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Complete Action">
													<?php } ?>
															<input type="checkbox" <?php echo(($remDr->is_finished == 1)?'checked':''); ?> id="actionSel_<?php echo $action->id;?>" name="actionSel[]" value="<?php echo $action->id;?>">
															<span class="checkmark"></span>
													<?php if($remDr->is_finished == 0){ ?>
														</a>
													<?php } ?>
													</label>
												</div>
											</td>
											<td>
												<label>
													<?php if($remDr->is_finished == 0){ ?>
														<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("completeAction");?>/<?php echo $action->id;?>/<?php echo $remDr->id; ?>" modal-title="Complete Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Complete Action">
														<?php echo wordwrap($action->action_title,20,"<br />");?>
														</a>
													<?php }else{ ?>
														<?php echo wordwrap($action->action_title,20,"<br />");?>
													<?php } ?>
												</label>
												<?php
													/* $remDRStr = ((trim($remDr->date)!='')?date('m/d/Y', strtotime($remDr->date)).' ':'').((trim($remDr->time)!='')?date('h:i a', strtotime($remDr->time)):''); */
													$created = date('m/d/Y', strtotime($remDr->created_at));
													$datess = date('h:i a', strtotime($remDr->time));
													
												?>
												<p><small><?php echo '('.$created.' '.$datess.')'; 	/* echo $remDRStr; */ ?></small></p>
											</td>
											<td>
												<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editAction");?>/<?php echo $action->id;?>/<?php echo $remDr->id; ?>" modal-title="Edit Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Edit Action"><i class="fa fa-lg fa-edit text-navy"></i></a>
												<?php if($action->addedby != 1){ ?>
													<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteAction");?>/<?php echo $action->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
												<?php } ?>
											</td>
										</tr>
											<?php }}} $i++; } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php }else{ ?>
						<div class="col-md-6">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Daily-Routines</h5>
								</div>
								<div class="ibox-content">
									<center>
										<b style="color:#808080;">No Daily-Routines Found.</b>
									</center>
								</div>
							</div>
						</div>
					<?php } ?>
					<?php  if((!empty($actions['one_time']) && count($actions['one_time'])>0) || (!empty($actions['weekly']) && count($actions['weekly'])>0)) { ?>
					<div class="col-md-6">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Actions</h5>
							</div>
							<div class="ibox-content">
								<table class="table table-hover">
									<thead>
									<tr>
										<th></th>
										<th>Title</th>
										<th>Action</th>
									</tr>
									</thead>
									<tbody>
										<?php 
										$i=0; foreach($actions['one_time'] as $action){ 
										if($action->nextweek == 0) { 
										?>
										<tr id="actionRow_<?php echo $action->id;?>">
										<td>
											<div class="checks1">
												<label class="checkers">
												<?php if($action->is_finished == 0){ ?>
													<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("completeAction");?>/<?php echo $action->id;?>" modal-title="Complete Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Complete Action">
												<?php } ?>
														<input type="checkbox" <?php echo(($action->is_finished == 1)?'checked':''); ?> id="actionSel_<?php echo $action->id;?>" name="actionSel[]" value="<?php echo $action->id;?>">
														<span class="checkmark"></span>
												<?php if($action->is_finished == 0){ ?>
													</a>
												<?php } ?>
												</label>
											</div>
										</td>
										<td>
											<label>
												<?php if($action->is_finished == 0){ ?>
													<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("completeAction");?>/<?php echo $action->id;?>" modal-title="Complete Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Complete Action">
													<?php echo wordwrap($action->action_title,20,"<br />");?>
													</a>
												<?php }else{ ?>
													<?php echo wordwrap($action->action_title,20,"<br />");?>
												<?php } ?>
											</label>
											<?php if(count($action->reminders)>0){ 
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
											?>
												<p><small><?php echo $remOTStr; ?></small></p>
											<?php } ?>
										</td>
										<td>
											<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editAction");?>/<?php echo $action->id;?>" modal-title="Edit Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Edit Action"><i class="fa fa-lg fa-edit text-navy"></i></a>
											<?php if($action->addedby != 1){ ?>
												<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteAction");?>/<?php echo $action->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
											<?php } ?>
										</td>
									  </tr>
										<?php } $i++; } ?>
									  <?php 
									  $i=0; foreach($actions['weekly'] as $action){ 
									  if($action->nextweek == 0) { 
									  ?>
										<tr id="actionRow_<?php echo $action->id;?>">
										<td>
											<div class="checks1">
												<label class="checkers">
												<?php if($action->is_finished == 0){ ?>
													<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("completeAction");?>/<?php echo $action->id;?>" modal-title="Complete Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Complete Action">
												<?php } ?>
														<input type="checkbox" <?php echo(($action->is_finished == 1)?'checked':''); ?> id="actionSel_<?php echo $action->id;?>" name="actionSel[]" value="<?php echo $action->id;?>">
														<span class="checkmark"></span>
												<?php if($action->is_finished == 0){ ?>
													</a>
												<?php } ?>
												</label>
											</div>
										</td>
										<td>
											<label>
												<?php if($action->is_finished == 0){ ?>
													<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("completeAction");?>/<?php echo $action->id;?>" modal-title="Complete Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Complete Action">
													<?php echo wordwrap($action->action_title,20,"<br />");?>
													</a>
												<?php }else{ ?>
													<?php echo wordwrap($action->action_title,20,"<br />");?>
												<?php } ?>
											</label>	
											<?php if(count($action->reminders)>0){ 
												$remDRStr = '(';
												$dri = 0;
												foreach($action->reminders as $remDr)
												{
													$dri++;
													$remDRStr .= $remDr->dayname.': ';
													$remDRStr.= ((trim($remDr->date)!='')?date('m/d/Y', strtotime($remDr->date)).' ':'').((trim($remDr->time)!='')?date('h:i a', strtotime($remDr->time)):'');
													if($dri < count($action->reminders))
													{ 
														$remDRStr.= ',';
													}
													if($dri==count($action->reminders))
													{
														$remDRStr.= ')';
													}
												}
											?>
												<p><small><?php echo $remDRStr; ?></small></p>
											<?php } ?>
										</td>
										<td>
											<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editAction");?>/<?php echo $action->id;?>" modal-title="Edit Action - <?php echo $action->action_title; ?>" data-sub-text="Here You Can Edit Action"><i class="fa fa-lg fa-edit text-navy"></i></a>
											<?php if($action->addedby != 1){ ?>
												<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteAction");?>/<?php echo $action->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
											<?php } ?>
										</td>
									  </tr>
									  <?php } $i++; } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php }else{ ?>
						<div class="col-md-6">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Actions</h5>
								</div>
								<div class="ibox-content">		
									<center>
										<b style="color:#808080;">No Actions Found.</b>
									</center>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php }else { ?>
			<div class="row wrapper border-bottom white-bg page-heading actions-class">
				<div class="row">
					<div class="col-lg-12">
						<h2>Goals and Value Identifiers</h2>
					</div>
					<hr>
					<div class="col-sm-12">
						<?php $adminAllow = $_SESSION['editpermission']; if(empty($lastPostMeeting) || $adminAllow == 0) { ?>
						<a class="btn btn-primary btn-rounded modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addGoal");?>" modal-title="Add New Goal" data-sub-text="Here you can add a new goal.">Add New Goal</a>
					<?php }else { ?>
						<span class="disableded">
							<a class="btn btn-primary btn-rounded disabled disableded" href="javascript:void(0);">Add New Goal</a>
						</span>
					<?php } ?>
					</div>
				</div>
            </div>
			<div class="wrapper wrapper-content animated fadeInRight">	
				<div class="row">
				<?php if(!empty($goals) && count($goals)>0) { ?>
					<div class="col-md-6">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Goals</h5>
							</div>
							<div class="ibox-content">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>Goal</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php $i=0; foreach($goals as $goal){ ?>
										<tr id="goalRow_<?php echo $goal->id;?>">
											<td><?php echo wordwrap($goal->title,20,"<br />");?></td>
											<?php if(empty($lastPostMeeting) || $adminAllow == 0) { ?>
											<td>
												<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editGoal");?>/<?php echo $goal->id;?>" modal-title="Edit Goal - <?php echo $goal->title; ?>" data-sub-text="Here You Can Edit Goal"><i class="fa fa-lg fa-edit text-navy"></i></a>
												<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteGoal");?>/<?php echo $goal->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
											</td>
											<?php }else { ?>
											<td class="disabled">
												<a href="javascript:void(0);" class="modalInvoke disabled" data-href="<?php echo $this->config->item("editGoal");?>/<?php echo $goal->id;?>" modal-title="Edit Goal - <?php echo $goal->title; ?>" data-sub-text="Here You Can Edit Goal"><i class="fa fa-lg fa-edit text-navy"></i></a>
												<a href="javascript:void(0);" class="delete disabled" data-href="<?php echo $this->config->item("deleteGoal");?>/<?php echo $goal->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
											</td>
											<?php } ?>
										  </tr>
									  <?php $i++;} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php }else{ ?>
						<div class="col-md-6">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Goals</h5>
								</div>
								<div class="ibox-content">
									<center>
										<b style="color:#808080;">No Goals Found.</b>
									</center>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="col-md-6">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Value Identifiers</h5>
							</div>
							<div class="ibox-content">
								<a class="btn btn-primary btn-rounded marginclass" href="Javascript:void(0)" data-toggle="modal" data-target="#valuesModel">Click to add new</a>
								<div class="values-class">
								<?php foreach($defaultValues as $dValues) { ?>
									<div class="checks1">
										<label class="checkers">
											<a class="modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addValue");?>/<?php echo $dValues->id; ?>" modal-title="Add New Value Identifier" data-sub-text="Here you can add a new value identifier.">
												<input type="checkbox" id="" name="actionSel" value="<?php echo $dValues->id; ?>" <?php if(in_array($dValues->id ,$userAllValues)) { echo 'checked'; } ?>>
												<span class="checkmark"></span>
											<?php echo $dValues->value_title; ?>
											</a>
										</label>
									</div>
								<?php } ?>
								</div>
								<div class="values-class userValues">
								<?php foreach($userAddedValues as $uValues) { ?>
									<div class="checks1">
										<label class="checkers">
											<a class="modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addValue");?>/<?php echo $uValues->id; ?>" modal-title="Add New Value Identifier" data-sub-text="Here you can add a new value identifier.">
												<input type="checkbox" id="" name="actionSel" value="<?php echo $uValues->id; ?>" <?php if(in_array($uValues->id ,$userAllValues)) { echo 'checked'; } ?>>
												<span class="checkmark"></span>
												<?php echo $uValues->value_title; ?>
											</a>
											<a href="javascript:void(0);" class="delete dleteval" data-href="<?php echo $this->config->item("deleteAddedValue");?>/<?php echo $uValues->id; ?>"><i class="fa fa-sm fa-window-close text-navy"></i></a>
										</label>
									</div>
								<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
	</div>
</div>
<div id="valuesModel" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add New Value</h4>
			</div>
			<div class="modal-body">
				<label>Value Title</label>
				<input type="text" class="form-control" name="value_title" id="valueTitle">
				<span id="valueTitleError" class="errr">This is required</span>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="return addValue()">Add</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function () {
	$('.modalInvoke').ventricleModalViewLoad("commonModal");
	
	if(typeof($('[rel="prev"]'))!="undefined")
	{
		$('[rel="prev"]').hide();
		$('.fa-chevron-left').attr("onclick", "location.href='"+$('[rel="prev"]').attr('href')+"'");
	}
	if(typeof($('[rel="next"]'))!="undefined")
	{
		$('[rel="next"]').hide();
		$('.fa-chevron-right').attr("onclick", "location.href='"+$('[rel="next"]').attr('href')+"'");
	}
	
	$(".enDis").click(function(event){
		$(this).ventricleDirectAjax("GET",'','enDisResponse');
	});
	
	$(".delete").click(function(event){
		var _this = $(this);
		$("#confirmation .modal-title").html('Delete Goal');
		$("#confirmation div.modal-body").html('<h4>Do you want to delete this goal?</h4>');
		$("#confirmation button.btn-primary").attr('data-href', _this.attr('data-href'));
		$("#confirmation").modal({show:true});
	});
	
	$("#confirmation button.btn-primary").unbind().click(function(){
			$(this).prop('disabled', true);
			$(this).ventricleDirectAjax("GET",'','deleteResponse');
		});
		
	$('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
});

function enDisResponse(response)
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
		   
			$(".centralView").load(window.location.href+"<?php echo(($_GET && !isset($_GET['pagination']))?'?'.http_build_query($_GET).'&pagination=1':'?pagination=1')?>&heads=1");
			
		}, 500);
	}
}

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
			$(".centralView").load(window.location.href+"<?php echo(($_GET && !isset($_GET['pagination']))?'?'.http_build_query($_GET).'&pagination=1':'?pagination=1')?>&heads=1");
			
		}, 500);
	}
}
function addValue() {
	var valueTitle = $('#valueTitle').val();
	if(valueTitle == '') {
		$('#valueTitleError').show();
		return false;
	}
	var myKeyVals = { 'value_title': valueTitle };
	$.ajax({
		type: 'POST',
		url: "<?php echo base_url(); ?>customer/value/addValueForUser",
		data: myKeyVals,
		dataType: "json",
		success: function(response) { console.log(response);
			if( (typeof response === "object") && (response !== null) ) {
				setTimeout(function() {
					toastr.options = {
						closeButton: true,
						progressBar: true,
						showMethod: 'slideDown',
						timeOut: 3000
					};
					$.each(response.message, function( index, value ) {
						if(response.success) {
							toastr.success('',$(value).text());
						}else {
							toastr.error('',$(value).text());
						}
					});
					$(".centralView").load(window.location.href+"<?php echo(($_GET && !isset($_GET['pagination']))?'?'.http_build_query($_GET).'&pagination=1':'?pagination=1')?>&heads=1");
				}, 500);
			}
			$('.close').click();
			$('#valueTitleError').hide();
			$('#valueTitle').val('');
		}
	});
}
$( "#valuesModel" ).on('hidden.bs.modal', function (e) {
	$('#valueTitleError').hide();
	$('#valueTitle').val('');
});
</script>
<style>
.nj-picker{display:none;}
</style>