<div class="row">
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Users</h5>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-sm-3">
						<a class="btn btn-primary btn-rounded modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addUser");?>" modal-title="Add New User" data-sub-text="Here you can add a new user.">Add New User</a>
					</div>
				</div>
				<?php if(count($users)>0) { ?>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
						<tr>
							<th>Sr No</th>
							<th>Created Date</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
						</thead>
						<tbody>
							<?php $i=0; foreach($users as $user){ ?>
							<tr id="userRow_<?php echo $user->id;?>">
							<td><?php echo ($i+1);?></td>
							<td><?php echo date("d/m/Y",strtotime($user->created_at));?></td>
							<td><?php echo $user->first_name." ".$user->last_name;?></td>
							<td><?php echo $user->email;?></td>
							<td><?php echo $user->phone;?></td>
							<td><?php echo(($user->status==0)?"Enabled":"Disabled");?></td>
							<td>
								<?php if($user->status==0) { ?>
								<a style="text-decoration:none;" href="javascript:void(0);" class="enDis" data-href="<?php echo $this->config->item("changeUserStatus");?>/<?php echo $user->id;?>/1">
									<img alt="disable" title="disable" src="<?php echo $this->config->item("bk_image_url");?>/deactive.png"/>
								</a> 
								<?php }else{ ?>
								<a style="text-decoration:none;" href="javascript:void(0);" class="enDis" data-href="<?php echo $this->config->item("changeUserStatus");?>/<?php echo $user->id;?>/0">
									<img alt="enable" title="enable" src="<?php echo $this->config->item("bk_image_url");?>/active.png"/>
								</a> 
								<?php } ?>
								<?php if($user->editpermission==0) { ?>
									<!--a style="text-decoration:none;" href="javascript:void(0);" onclick="return changepermission(<?php echo $user->id;?>,1)">
										<i class="fa fa-lg fa-unlock text-navy"></i>
									</a-->
								<?php }else{ ?>
									<!--a style="text-decoration:none;" href="javascript:void(0);" onclick="return changepermission(<?php echo $user->id;?>,0)">
										<i class="fa fa-lg fa-lock text-navy"></i>
									</a-->
								<?php } ?>
								<a target="_blank" style="text-decoration:none;" href="<?php echo base_url(); ?>customer/login/loginCheckAdmin/<?php echo $user->id; ?>">
									<i class="fa fa-lg fa-eye text-navy"></i>
								</a>
								<a class="modalInvoke" href="javascript:void(0);" data-href="<?php echo $this->config->item("addActionAdmin");?>/<?php echo $user->id;?>" modal-title="Add Action" data-sub-text="Here you can add a new action."><i class="fa fa-lg fa-tasks text-navy"></i></a><hr>
								<a href="javascript:void(0);" class="modalInvoke" data-href="<?php echo $this->config->item("editUser");?>/<?php echo $user->id;?>" modal-title="Edit User - <?php echo $user->first_name." ".$user->last_name; ?>" data-sub-text="Here You Can Edit User"><i class="fa fa-lg fa-edit text-navy"></i></a>
								<a href="javascript:void(0);" class="delete" data-href="<?php echo $this->config->item("deleteUser");?>/<?php echo $user->id;?>"><i class="fa fa-lg fa-window-close text-navy"></i></a>
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
					<b style="color:#808080;">No Users Found.</b>
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
		$("#confirmation .modal-title").html('Delete User');
		$("#confirmation div.modal-body").html('<h4>Do you want to delete this user?</h4>');
		$("#confirmation").modal({show:true});
		
		$("#confirmation button.btn-primary").click(function(){
			$(this).prop('disabled', true);
			_this.ventricleDirectAjax("GET",'','deleteResponse');
		});
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
function changepermission(id,permission) {
	var myKeyVals = { 'id': id,'permission': permission };
	$.ajax({
		type: 'POST',
		url: "<?php echo base_url(); ?>backend/users/changeUserPermission",
		data: myKeyVals,
		dataType: "json",
		success: function(response) {
			if( (typeof response === "object") && (response !== null) ) {
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
	});
}
</script>