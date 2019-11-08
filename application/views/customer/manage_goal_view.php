<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/iCheck/custom.css" rel="stylesheet">
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/iCheck/icheck.min.js"></script>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Values</h5><?php /* echo '<pre>'; print_r($postMeetings); */ ?>
			</div>
			<div class="ibox-content">
				<?php if(!empty($postMeetings) && count($postMeetings)>0) { ?>
				<div class="row">					
					<div class="col-md-12">				
						<div class="table-responsive">
							<?php
							$gya = 11;
							for($w = 0;$w <= 11;$w++) {
							?>
								<div class="col-md-12 class<?php echo $w; ?>">
								<?php
								for($d = 0;$d <= 11;$d++) { 
									if($gya != $d) {
								?>
									<div class="col-lg-1"></div>
								<?php 
								}else { 
								?>
									<div class="col-lg-1" style="border-top:1px solid #000;border-left:1px solid #000;height:50px;">
									<?php
									if(array_key_exists($gya,$postMeetings)) {
										foreach($weekTags as $weekTag) {
											if($weekTag->id == $postMeetings[$gya]->weekno) {
												echo '<b>'.$weekTag->weektag .'</b>';
											}
										}
										?>
										<br>
										<?php
										foreach($postMeetings[$gya]->actions as $action) {
											echo '<a href="Javascript:void(0)" onclick="get_questions('.$action->id.')">#'.$action->id.''.$action->action_title.'</a><br>';
										}
									}
									?>
									</div>
								<?php  $gya--; } } ?>
								</div>
							<?php } ?>
						</div>
						<div class="table-row">
							<table class="table table-bordered">
								<thead>
								  <tr>
									<th>Date</th>
									<th>Question Answer</th>
								  </tr>
								</thead>
								<tbody class="questionbody">
									<tr><td></td><td>No Data</td></tr>
								</tbody>
							</table>
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
function get_questions(id) {
	var myKeyVals = {'id':id};
	var html = '';
	$.ajax({
		  type: 'POST',
		  url: "getSingleAction",
		  data: myKeyVals,
		  dataType: "json",
		  success: function(resultData) {
			  if(resultData.length > 0) {
				  $.each(resultData, function (i, data) {
					  html = html + '<tr><td>'+data.created_date+'</td><td><b>'+data.question+'</b><br>'+data.question+'</td></tr>';
				  });
				  $('.questionbody').empty();
				  $('.questionbody').append(html);
			  }else {
				  html = html + '<tr><td></td><td>No Data</td></tr>';
				  $('.questionbody').append(html);
			  }
		  }
	});
}
</script>