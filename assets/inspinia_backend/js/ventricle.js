(function($){
	$.fn.ventricleModalViewLoad = function(modalID){
		this.on('click',function(){
			var dataURL = $(this).attr('data-href');
			$('#'+modalID+' .modal-title').text($(this).attr('modal-title'));
			$('#'+modalID+' .font-bold').text($(this).attr('data-sub-text'));
			$('#'+modalID+' div.modal-body').load(dataURL,function(){
				$("#"+modalID).modal({show:true});
			});
		}); 
	};
	
	$.fn.ventricleSubmitForm = function(functionName){
		var srcUrl = $(this).attr('action');
		var serializedFormData = $(this).serializeArray();
		console.log(serializedFormData);
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
				$("#error .modal-title").html('Error Occured!');
				$("#error div.modal-body").html('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');
				$("#error").modal({show:true});
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
					$("#error .modal-title").html('Error Occured!');
					$("#error div.modal-body").html('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');
					$("#error").modal({show:true});
				  },
				}); // ajax
			
		/* 	this.data('search1',search1);
		} */
	};
})(jQuery);