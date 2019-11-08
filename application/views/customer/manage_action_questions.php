<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/iCheck/custom.css" rel="stylesheet">
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/iCheck/icheck.min.js"></script>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Week Actions</h5>
			</div>
			<div class="ibox-content">
				<?php if(!empty($postMeetings) && count($postMeetings)>0) { ?>
				<div class="row">					
					<div class="col-md-12">				
						<div class="table-responsive">
							<div class="weeks-row">
							<div class="accordion" id="accordionExample">
							<?php
							$i = 0;
							foreach($postMeetings as $postMeet) {
							?>
								<div class="col-lg-12" >
								<div class="card">
								<div class="card-header" id="headingOne">
								<span id="viRows_<?php echo $postMeet->id; ?>" data-toggle="collapse" data-target="#collapse<?php echo $postMeet->id; ?>" aria-expanded="true" aria-controls="collapse<?php echo $postMeet->id; ?>" onclick="changeicon(this.id)">
								<div class="col-lg-11" >
								<?php 
								foreach($weekTags as $weekTag) {
									if($weekTag->id == $postMeet->weekno) {
										echo '<b>'.$weekTag->weektag .'</b>';
									}
								}
								?>
								</div><div class="col-lg1"><div class="affirmation">
								<i class="fa <?php if($i == 0) { echo 'fa-angle-down'; }else { echo 'fa-angle-up'; } ?> text-navy" aria-hidden="true"></i>
								</div></div></span>
								<hr>
								</div>
								<div class="collapse <?php if($i == 0) { echo 'collapse in'; } ?>" id="collapse<?php echo $postMeet->id; ?>" data-parent="#accordionExample">
								<div class="weeks-inner">
									<div class="row">
										<div class="col-sm-2">
											<div class="date">
												<span>Date</span>
											</div>
										</div>
										<div class="col-sm-7">
											<div class="action">
												<span>Action</span>
											</div>		
										</div>
									</div>
								</div>
								<div class="panel-group" id="accordionExample<?php echo $postMeet->id; ?>">
								<?php $y = 0; foreach($postMeet->actions as $action) { ?>
								<hr>
									<div class="weeks-inner">
										<div id="viRows_<?php echo $postMeet->id; ?><?php echo $action->id; ?>" class="row pointe" data-toggle="collapse" data-target="#collapse<?php echo $postMeet->id; ?><?php echo $action->id; ?>" aria-expanded="true" aria-controls="collapse<?php echo $postMeet->id; ?><?php echo $action->id; ?>"  onclick="changeicon(this.id)">
											<div class="col-sm-2">
											</div>
											<div class="col-sm-7">
												<div class="affirmation">
													<span id="<?php echo $action->id; ?>"><i class="fa fa-angle-up text-navy" aria-hidden="true"></i><?php echo $action->action_title; ?></span>
												</div>		
											</div>
										</div>
									</div>
									
									<div class="collapse <?php if($y == 0) { echo ''; } ?>" id="collapse<?php echo $postMeet->id; ?><?php echo $action->id; ?>" data-parent="#accordionExample<?php echo $postMeet->id; ?>">
									<hr>
									<?php $u = 0; foreach($action->question as $question) { ?>
										<div class="weeks-inner">
											<div class="row">
												<div class="col-sm-2">
													<div class="date">
														<span><?php if($u == 0) { if($question->created_date != '') { echo date('d/m/Y h:ia', strtotime($question->created_date)); }} ?></span>
													</div>
												</div>
												<div class="col-sm-7">
													<div class="action quations">
														<label><?php echo $question->question; ?></label>
														<span><?php echo $question->answer; ?></span>
													</div>		
												</div>
											</div>
										</div>
								<?php $u++; } ?>
									</div>
								<?php $y++; } ?>
								</div>
								</div>
								</div>
								</div>
							<?php $i++; } ?>
						</div>
						</div>
					</div>
				</div>
				
				<?php }else{ ?>
				<center>
					<b style="color:#808080;">No Records Found.</b>
				</center>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
function changeicon(bid) {
	if($('#'+bid).find('i').hasClass('fa-angle-down')) {
	   $('#'+bid).find('i').removeClass('fa-angle-down');
	   $('#'+bid).find('i').addClass('fa-angle-up');
	}else {
		$('#'+bid).find('i').removeClass('fa-angle-up');
		$('#'+bid).find('i').addClass('fa-angle-down');
	}
}
</script>
