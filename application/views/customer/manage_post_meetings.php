<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Post Meetings</h5>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-sm-3">
						<a class="btn btn-primary btn-rounded" href="<?php echo $this->config->item("addPostMeeting");?>" title="Add New Post Meeting">Add New Post Meeting</a>
					</div>
				</div>
				<?php if(count($postMeetings)>0) { ?>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
						<tr>
							<th>Sr No</th>
							<th>Created Date</th>
							<th>Title</th>
							<th>Type</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
							<?php $i=0; foreach($postMeetings as $postMeeting){ ?>
							<tr id="pmRow_<?php echo $postMeeting->id;?>">
							<td><?php echo ($i+1);?></td>
							<td><?php echo date("d/m/Y",strtotime($goal->created_at));?></td>
							<td><?php echo wordwrap($goal->title,20,"<br />");?></td>
							<td><?php echo(($goal->is_secondary==0)?"Primary":"Secondary");?></td>
							<td><?php echo(($goal->status==0)?"Active":"Disabled");?></td>
							<td>
								<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editGoal");?>/<?php echo $goal->id;?>" modal-title="Edit Goal - <?php echo $goal->title; ?>" data-sub-text="Here You Can Edit Goal"><i class="fa fa-lg fa-edit text-navy"></i></a>
								<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteGoal");?>/<?php echo $goal->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
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
					<b style="color:#808080;">No Post Meetings Found.</b>
				</center>
				<?php } ?>
			</div>
		</div>
	</div>
</div>