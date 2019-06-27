<link href="<?php echo $this->config->item("inspinia_css_url");?>/plugins/iCheck/custom.css" rel="stylesheet">
<script src="<?php echo $this->config->item("inspinia_js_url");?>/plugins/iCheck/icheck.min.js"></script>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Value Identifiers</h5>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-sm-3">
						<a class="btn btn-primary btn-rounded modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addValue");?>" modal-title="Add New Value Identifier" data-sub-text="Here you can add a new value identifier.">Add New VI</a>
					</div>
				</div>
				<?php if(count($values)>0) { ?>
				<div class="row">
					<div class="col-md-4">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
								<tr>
									<th></th>
									<th>Title</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
									<?php $i=0; foreach($values as $value){ ?>
									<tr id="viRow_<?php echo $value->id;?>">
									<td>
										<div class="i-checks">
											<input type="checkbox" id="viSel_<?php echo $value->id;?>" name="viSel[]" value="<?php echo $value->id;?>"></td>
										</div>
									<td>
										<div class="i-checks">
											<label>
												<?php echo wordwrap($value->title,20,"<br />");?>
											</label>
										</div>
									</td>
									<td>
										<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editValue");?>/<?php echo $value->id;?>" modal-title="Edit Value Identifier - <?php echo $value->title; ?>" data-sub-text="Here You Can Edit Value Identifier"><i class="fa fa-lg fa-edit text-navy"></i></a>
										<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteValue");?>/<?php echo $value->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
									</td>
								  </tr>
								  <?php $i++;} ?>
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