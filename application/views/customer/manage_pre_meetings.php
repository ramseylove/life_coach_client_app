<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Pre Meetings</h5>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-sm-3">
					<?php 
						$disable = 'disable';
						if(!empty($lastPreMeeting) && !empty($lastPostMeeting)) {
							/* $weeek = $lastPreMeeting->weekno + 1; */
							if($lastPreMeeting->weekno == $lastPostMeeting->weekno) {
								$disable = 'enable';
							}
						}else if(empty($lastPreMeeting) && !empty($lastPostMeeting)) {
							$disable = 'enable';
						}else if(empty($lastPreMeeting) && empty($lastPostMeeting)) {
							$disable = 'disable';
						}
						if($disable == 'enable') {
					?>
						<a class="btn btn-primary btn-rounded" href="<?php echo $this->config->item("addPreMeeting");?>" title="Add New Pre-Meeting">Add New Pre-Meeting</a>
						<?php }else { ?>
						<span class="disableded">
						<a class="btn btn-primary btn-rounded disabled disableded" href="javascript:void(0);" title="Add New Pre-Meeting">Add New Pre-Meeting</a>
						</span>
						<?php } ?>
					</div>
				</div>
				<?php if(count($preMeetings)>0) { ?>
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
						<tr>
							<th>Week Meeting</th>
							<th>Created Date</th>
							<th>Acknowledgment</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
							<?php $i=0; foreach($preMeetings as $preMeeting){ ?>
							<tr id="pmRow_<?php echo $preMeeting->id;?>">
							<td><?php foreach($weekTags as $weekTag) { if($weekTag->id == $preMeeting->weekno) { echo $weekTag->weektag; }} ?></td>
							<td><?php echo date("m/d/Y",strtotime($preMeeting->created_at));?></td>
							<td title="<?php echo $preMeeting->acknowledgment; ?>"><?php echo ((strlen($preMeeting->acknowledgment)>20)?substr($preMeeting->acknowledgment, 0, 20).'...':$preMeeting->acknowledgment);?></td>
							<?php
								$adminAllow = $_SESSION['adminLogin'];
								if($adminAllow == 1) {
							?>
							<td>
								<a href="<?php echo $this->config->item("editPreMeeting");?>/<?php echo $preMeeting->id;?>" title="Edit Pre-Meeting - <?php echo $preMeeting->acknowledgment; ?>"><i class="fa fa-lg fa-edit text-navy"></i></a>
								<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deletePreMeeting");?>/<?php echo $preMeeting->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
							</td>
							<?php }else { ?>
								<td class="disabled">
									<a href="javascript:void(0);" class="disabled" title="Edit Pre-Meeting - <?php echo $preMeeting->acknowledgment; ?>"><i class="fa fa-lg fa-edit text-navy"></i></a>
									<a href="javascript:void(0);" class="delete disabled" data-href="<?php echo $this->config->item("deletePreMeeting");?>/<?php echo $preMeeting->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
								</td>
							<?php } ?>
						  </tr>
						  <?php $i++;} ?>
						</tbody>
					</table>
				</div>
				<div class="row">
					<div class="col-sm-3 pull-right">
						<div class="btn-group">
							<?php echo $this->pagination->create_links(); ?>
						</div>
					</div>
				</div>
				<?php }else{ ?>
				<center>
					<b style="color:#808080;">No Pre-Meetings Found.</b>
				</center>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$(".delete").click(function(event){
		var _this = $(this);
		$("#confirmation .modal-title").html('Delete Pre Meeting');
		$("#confirmation div.modal-body").html('<h4>Do you want to delete this pre-meeting?</h4>');
		$("#confirmation button.btn-primary").attr('data-href', _this.attr('data-href'));
		$("#confirmation").modal({show:true});
	});
	
	$("#confirmation button.btn-primary").unbind().click(function(){
			$(this).prop('disabled', true);
			$(this).ventricleDirectAjax("GET",'','deleteResponse');
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