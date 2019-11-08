<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Post Meetings</h5>
			</div>
			<div class="ibox-content"><?php /* echo '<pre>'; print_r($lastPreMeeting); print_r($lastPostMeeting); */ ?>
				<div class="row">
					<div class="col-sm-3">
					<?php 
						$disable = 'disable';
						if(!empty($lastPreMeeting) && !empty($lastPostMeeting)) {
							if($lastPreMeeting->weekno > $lastPostMeeting->weekno) {
								$disable = 'enable';
							}
						}else if(!empty($lastPreMeeting) && empty($lastPostMeeting)) {
							$disable = 'enable';
						}
						if($disable == 'enable') {
					?>
						<a class="btn btn-primary btn-rounded" href="<?php echo $this->config->item("addPostMeeting");?>" title="Add New Post Meeting">Add New Post Meeting</a>
						<?php }else { ?>
						<span class="disableded">
							<a class="btn btn-primary btn-rounded disabled disableded" href="javascript:void(0);" title="Add New Post Meeting">Add New Post Meeting</a>
						</span>
						<?php } ?>
					</div>
				</div>
				<?php if(count($postMeetings)>0) { ?>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
						<tr>
							<th>Week Meeting</th>
							<th>Created Date</th>
							<th>General Topic</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
							<?php $i=1; foreach($postMeetings as $postMeeting){ ?>
							<tr id="pmRow_<?php echo $postMeeting->id; ?>">
							
							<td><?php foreach($weekTags as $weekTag) { if($weekTag->id == $postMeeting->weekno) { echo $weekTag->weektag; }} ?></td>
							<td><?php echo date("d/m/Y",strtotime($postMeeting->created_at));?></td>
							
							<td title="<?php echo $postMeeting->general_topic; ?>"><?php echo ((strlen($postMeeting->general_topic)>20)?substr($postMeeting->general_topic, 0, 20).'...':$postMeeting->general_topic);?></td>
							
								<?php
								$adminAllow = $_SESSION['editpermission'];
								if($adminAllow == 0) {
								?>
								<td>
									<a href="<?php echo $this->config->item("editPostMeeting");?>/<?php echo $postMeeting->id;?>" title="Edit Post Meeting - <?php echo $postMeeting->general_topic; ?>"><i class="fa fa-lg fa-edit text-navy"></i></a>
									<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deletePostMeeting");?>/<?php echo $postMeeting->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
								</td>
								<?php }else { ?>
								<td class="disabled">
									<a href="Javascript:void(0);" class="disabled" title="Edit Post Meeting - <?php echo $postMeeting->general_topic; ?>"><i class="fa fa-lg fa-edit text-navy"></i></a>
									<a href="javascript:void(0);" class="delete disabled" data-href="<?php echo $this->config->item("deletePostMeeting");?>/<?php echo $postMeeting->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
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
					<b style="color:#808080;">No Post Meetings Found.</b>
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
		$("#confirmation .modal-title").html('Delete Post Meeting');
		$("#confirmation div.modal-body").html('<h4>Do you want to delete this post-meeting?</h4>');
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