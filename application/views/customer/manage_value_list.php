<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/iCheck/custom.css" rel="stylesheet">
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/iCheck/icheck.min.js"></script>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Values</h5>
			</div>
			<div class="ibox-content">
				<?php if(count($values)>0) { ?>
				<div class="row">					
					<div class="col-md-12">				
						<div class="table-responsive">							
							<table class="table table-hover">
								<thead>
								<tr>
									<th colspan="7">Value Title</th>
								</tr>
								</thead>
								
									<?php
										$i=0;
										$q=0;
										foreach($defaultValues as $value){
										if(!empty($value->savedVals)) {
									?> 
									<tbody class="<?php if ($q % 2 == 0) { echo 'evenclass'; } ?>">
									<tr id="viRows_<?php echo $value->id; ?>" class="clickable" colspan="7" data-toggle="collapse" data-target="#accordion<?php echo $value->id; ?>" onclick="changeicon(this.id)">
									<td colspan="7">
										<div class="i-checks">
											<label>
												<?php echo wordwrap($value->value_title,20,"<br />");?>
											</label>
											<span><i class="fa fa-lg <?php if($i == 0) { echo 'fa-angle-down'; }else { echo 'fa-angle-up'; } ?> text-navy"></i></span>
										</div>
									</td>
									</tr>
									<tbody id="accordion<?php echo $value->id; ?>" class="collapse <?php if($i == 0) { echo 'collapse in'; } ?>">
										<tr>
											<td><b>Current Happiness level:</b> <?php echo $value->savedVals->current_happiness_level; ?></td>
										</tr>
										<tr>
											<td><b>Expected Happiness Level:</b> <?php echo $value->savedVals->expected_happiness_level; ?></td>
										</tr>
										<tr>
											<th>What does this domain mean to you?</th>
										</tr>
										<tr>
											<td><?php echo $value->savedVals->description_0; ?></td>
										</tr>
										<tr>
											<th>What kind of person would you like to be in this domain?</th>
										</tr>
										<tr>
											<td><?php echo $value->savedVals->description_1; ?></td>
										</tr>
										<tr>
											<th>How are you doing in this domain now?</th>
										</tr>
										<tr>
											<td><?php echo $value->savedVals->description_2; ?></td>
										</tr>
										<tr>
											<th>What are some specific short and long-term goals for this domain?</th>
										</tr>
										<tr>
											<td><?php echo $value->savedVals->description_3; ?></td>
										</tr>
									</tbody>
								  <?php
								  $q++;
									}
								  $i++;
								  
								  ?>
								  </tbody>
								  <?php
								  }
								   ?>
								   <?php
									$i=0; 
									foreach($userAddedValues as $value){
									if(!empty($value->savedVals)) {
									?>
									<tbody class="<?php if ($q % 2 == 0) { echo 'evenclass'; } ?>">
									<tr id="viRows_<?php echo $value->id; ?>" class="clickable" colspan="7" class="m-row" colspan="7" data-toggle="collapse" data-target="#accordion<?php echo $value->id; ?>" onclick="changeicon(this.id)">
									<td colspan="7">
										<div class="i-checks">
											<label>
												<?php echo wordwrap($value->value_title,20,"<br />");?>
											</label>
											<span><i class="fa fa-lg fa-angle-up text-navy"></i></span>
										</div>
									</td>
									</tr>
									<tbody id="accordion<?php echo $value->id; ?>" class="m-row collapse">
										<tr>
											<td><b>Current Happiness level:</b> <?php echo $value->savedVals->current_happiness_level; ?></td>
										</tr>
										<tr>
											<td><b>Expected Happiness Level:</b> <?php echo $value->savedVals->expected_happiness_level; ?></td>
										</tr>
										<tr>
											<th>What does this domain mean to you?</th>
										</tr>
										<tr>
											<td><?php echo $value->savedVals->description_0; ?></td>
										</tr>
										<tr>
											<th>What kind of person would you like to be in this domain?</th>
										</tr>
										<tr>
											<td><?php echo $value->savedVals->description_1; ?></td>
										</tr>
										<tr>
											<th>How are you doing in this domain now?</th>
										</tr>
										<tr>
											<td><?php echo $value->savedVals->description_2; ?></td>
										</tr>
										<tr>
											<th>What are some specific short and long-term goals for this domain?</th>
										</tr>
										<tr>
											<td><?php echo $value->savedVals->description_3; ?></td>
										</tr>
									</tbody>
								  <?php
								  $q++;
									}
								  $i++;
								  ?>
								  </tbody>
								  <?php
								  }
								   ?>
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
		$("#confirmation .modal-title").html('Delete Value Identifier');
		$("#confirmation div.modal-body").html('<h4>Do you want to delete this value identifier?</h4>');
		$("#confirmation button.btn-primary").attr('data-href', _this.attr('data-href'));
		$("#confirmation").modal({show:true});
	});
	
	$("#confirmation button.btn-primary").unbind().click(function(){
			$(this).prop('disabled', true);
			$(this).ventricleDirectAjax("GET",'','deleteResponse');
		});
	
	/* $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                }); */
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
function changeicon(bid) {
	if($('#'+bid).find('i').hasClass('fa-angle-up')) {
	   $('#'+bid).find('i').removeClass('fa-angle-up');
	   $('#'+bid).find('i').addClass('fa-angle-down');
	}else {
		$('#'+bid).find('i').removeClass('fa-angle-down');
		$('#'+bid).find('i').addClass('fa-angle-up');
	}
}
</script>
<style>
    .evenclass{background:#eee;}
    </style>
