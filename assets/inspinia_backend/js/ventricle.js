(function($){
	$.fn.ventricleModalViewLoad = function(modalID){		
		this.on('click',function(){ 
			var dataURL = $(this).attr('data-href');
			var resu = dataURL.includes("completeAction");
			if(resu) {
				$('#'+modalID+' .modal-title').text($(this).attr('modal-title'));
				$('#'+modalID+' .font-bold').text($(this).attr('data-sub-text'));
				$('#'+modalID+' div.modal-body').load(dataURL,function(er){
					if(er != 1234) {
						$("#"+modalID).modal({show:true});
					}else {
						setTimeout(function() {
							toastr.options = {
								closeButton: true,
								progressBar: true,
								showMethod: 'slideDown',
								timeOut: 3000
							};
							toastr.success('','Action completed successfully');
							$(".centralView").load(window.location.href+"?pagination=1&heads=1");
						}, 500);
					}
				});
			}else {
				$('#'+modalID+' .modal-title').text($(this).attr('modal-title'));
				$('#'+modalID+' .font-bold').text($(this).attr('data-sub-text'));
				$('#'+modalID+' div.modal-body').load(dataURL,function(){
					$("#"+modalID).modal({show:true});
				});
			}
		}); 
	};
	$.fn.ventricleModalViewLoadForPost = function(modalID){
		this.on('click',function(){	
			var dataURL = $(this).attr('data-href');
			$('#'+modalID+' .modal-title').text($(this).attr('modal-title'));
			$('#'+modalID+' .font-bold').text($(this).attr('data-sub-text'));
			$('#'+modalID+' div.modal-body').load(dataURL,function(){
				$("#"+modalID).modal({show:true});
				$('.general_topic').val($('#general_topic').val());
				$('.session_value').val($('#session_value').val());
				$('.notes').val($('#notes').val());
				$('.nextweek').val(1);
			});
		});
	};
	
	$.fn.ventricleSubmitForm = function(functionName){
		var srcUrl = $(this).attr('action');
		var serializedFormData = $(this).serializeArray();
			$.ajax({
			  type: 'POST',
			  cache: false,
			  url: srcUrl,
			  data:serializedFormData,
			  success: function (resp) { 
				var parseResp = JSON.parse(resp);
				window[functionName](parseResp);
			  },
			  error: function(jqXHR, textStatus, errorThrown) 
			  {
				/* $("#error .modal-title").html('Error Occured!');
				$("#error div.modal-body").html('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');
				$("#error").modal({show:true}); */
			  },
			});
	};
	
	$.fn.ventricleDirectAjax = function(requestType,formId,functionName){
		/* var dataS1 = this.data('search1');
		var search1 = {};
		if(!dataS1)
		{ */
			var srcUrl = $(this).attr('data-href');
			var serializedFormData = (($.trim(formId)!="")?$('#'+formId+'').serializeArray():{});
				$.ajax({
				  type: requestType,
				  cache: false,
				  url: srcUrl,
				  data:serializedFormData,
				  success: function (resp) {
					var parseResp = JSON.parse(resp);
					window[functionName](parseResp);
				  }, // success
				  error: function(jqXHR, textStatus, errorThrown) 
				  {
					/* $("#error .modal-title").html('Error Occured!');
					$("#error div.modal-body").html('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');
					$("#error").modal({show:true}); */
				  },
				}); // ajax
			
		 	/* this.data('search1',search1);
		} */
	};
	/* $('#commonModal').on('hide.bs.modal', function (e) {
		$("#mi-modal").modal('show');
		return false;
	}); */
})(jQuery);
/* function modelcomform(confirm){
  if(confirm == 1){
	$("#mi-modal").modal('hide');
	location.reload();
  }else{
	$("#mi-modal").modal('hide');
	return false;
  }
} */