<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Goals</h5>
			</div>			
			<div class="ibox-content">
				<div class="row">
					<div class="col-sm-3">
					<?php $adminAllow = $_SESSION['editpermission']; if(empty($lastPostMeeting) || $adminAllow == 0) { ?>
						<a class="btn btn-primary btn-rounded modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addGoal");?>" modal-title="Add New Goal" data-sub-text="Here you can add a new goal.">Add New Goal</a>
					<?php }else { ?>
						<span class="disableded">
							<a class="btn btn-primary btn-rounded disabled disableded" href="javascript:void(0);">Add New Goal</a>
						</span>
					<?php } ?>
					</div>
				</div>
				<?php if(!empty($goals) && count($goals)>0) { ?>
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
						<tr>
							<th>Goal</th>
							<th>Type</th>
							<!--th>Status</th-->
							<th>Created Date</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
							<?php $i=0; foreach($goals as $goal){ ?>
							<tr id="goalRow_<?php echo $goal->id;?>">
							<td><?php echo wordwrap($goal->title,20,"<br />");?></td>
							<td><?php echo(($goal->is_secondary==0)?"Primary":"Secondary");?></td>
							<!--td><?php/*  echo(($goal->status==0)?"Active":"Disabled"); */?></td-->
							<td><?php echo date("m/d/Y",strtotime($goal->created_at));?></td>
							<td>
							<a href="<?php echo $this->config->item("viewchart");?>/<?php echo $goal->id;?>"><i class="fa fa-bar-chart text-navy"></i></a>
							<?php if(empty($lastPostMeeting) || $adminAllow == 0) { ?>
							<span>
								<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editGoal");?>/<?php echo $goal->id;?>" modal-title="Edit Goal - <?php echo $goal->title; ?>" data-sub-text="Here You Can Edit Goal"><i class="fa fa-lg fa-edit text-navy"></i></a>
								<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteGoal");?>/<?php echo $goal->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
							</span>
							<?php }else { ?>
							<span class="disabled">
								<a href="javascript:void(0);" class="modalInvoke disabled" data-href="<?php echo $this->config->item("editGoal");?>/<?php echo $goal->id;?>" modal-title="Edit Goal - <?php echo $goal->title; ?>" data-sub-text="Here You Can Edit Goal"><i class="fa fa-lg fa-edit text-navy"></i></a>
								<a href="javascript:void(0);" class="delete disabled" data-href="<?php echo $this->config->item("deleteGoal");?>/<?php echo $goal->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
							</span>
							<?php } ?>
							</td>
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
					<b style="color:#808080;">No Goals Found.</b>
				</center>
				<?php } ?>
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
		   
			$(".centralView").load(window.location.href+"<?php echo(($_GET && !isset($_GET['pagination']))?'?'.http_build_query($_GET).'&pagination=1':'?pagination=1')?>");
			
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
			$(".centralView").load(window.location.href+"<?php echo(($_GET && !isset($_GET['pagination']))?'?'.http_build_query($_GET).'&pagination=1':'?pagination=1')?>");
			
		}, 500);
	}
}
</script>