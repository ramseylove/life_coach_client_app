<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/iCheck/custom.css" rel="stylesheet">
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/iCheck/icheck.min.js"></script>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Values</h5>
			</div>
			<div class="ibox-content">
				<?php if(!empty($postMeetings) && count($postMeetings)>0) { ?>
				<div class="row">					
					<div class="col-md-12">				
						<div class="table-responsive">							
							<?php
							$count = count($postMeetings);
							foreach($postMeetings as $postMeet) {
								$count--;
							?>
							<div class="col-lg-12">
							<?php
								for($i = 0;$i <= $count;$i++) {
								if($i != $count) {
							?>
								<div class="col-lg-1"></div>
							<?php }else { ?>
								<div class="col-lg-1" style="border-top:1px solid #000;border-left:1px solid #000;height:50px;">
								<?php echo '<b>'.$postMeet->weekno.'</b>'; ?><br>
								<?php
								foreach($postMeet->actions as $action) {
									echo '<a href="Javascript:void(0)">#'.$action->id.''.$action->action_title.'</a><br>';
								}
								?>
								</div>
							<?php }} ?>
							</div>
							<?php } ?>
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