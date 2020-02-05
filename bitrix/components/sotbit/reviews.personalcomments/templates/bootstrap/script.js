$(document).ready(function(){
	
	
	jQuery("#modal-comments-popup").detach().appendTo('body');
	jQuery("#comments-personal-overlay").detach().appendTo('body');

	
	$(document).on('click','#modal-comments-popup #modal_close, #comments-personal-overlay', function(){ 
		$('#modal-comments-popup') 
			.animate({opacity: 0, top: '45%'}, 200,  
				function(){ 
					$(this).css('display', 'none'); 
					$('#comments-personal-overlay').fadeOut(400); 
					
					var CapId = $('#captcha-ids-comments').html();
					if(CapId.trim()!=''){
						grecaptcha.reset(
								CapId
						);
						}
					
				}
			);
	});
	//sort
	$(document).on('click','.sotbit_comments_personal_comments .table-personal-comments thead tr th', function(){ 
		
		var Oldsort = $(this).closest('.table-personal-comments').attr('data-sort');
		var Oldby = $(this).closest('.table-personal-comments').attr('data-by');
		
		var by= $(this).data('sort');
		$(this).closest('.table-personal-comments').attr('data-by',by);
		if(Oldby==by)
		{
			if(Oldsort=='asc')
			{
				$(this).closest('.table-personal-comments').attr('data-sort','desc');
				var sort='desc';
			}
			else
			{
				$(this).closest('.table-personal-comments').attr('data-sort','asc');
				var sort='asc';
			}
		}
		else
		{
			$(this).closest('.table-personal-comments').attr('data-sort','asc');
			var sort='asc';
		}
		
		
		$('.sotbit_comments_personal_comments .table-personal-comments tbody  tr').sort(function(a, b) {
			if(sort=='asc')
			{
				return +$(b).find('[data-name='+by+']').attr('data-value') - +$(a).find('[data-name='+by+']').attr('data-value');
			}
			else
			{
				return +$(a).find('[data-name='+by+']').attr('data-value') - +$(b).find('[data-name='+by+']').attr('data-value');
			}
		}).appendTo('.sotbit_comments_personal_comments .table-personal-comments tbody');
	});

	$(document).on('keyup','#add_comment  #contentbox',function()
			{
				var MaxInput=$(this).attr('maxlength');
				var box=$(this).val();
				if(box.length <= MaxInput)
				{
					$('#add_comment').find('.count-now').html(box.length);
				}
				else{}
				return false;
		});
	
	
	$(document).on('click','.sotbit_comments_personal_comments .personal-edit',
		function(){
		
		SiteDir = $(this).closest(".table-personal-comments").data("site-dir");
		var Id=$(this).data('id');
		var data=$('#PersonalCommentsParams').html();
		BX.showWait();
		$.ajax({
			type: 'POST',
			url: SiteDir+'bitrix/components/sotbit/reviews.personalcomments/ajax/edit.php',
			data: {data:data,Id:Id},
			success: function(data){
				if(data.length>0)
				{
				var Comment = JSON.parse(data);

				//ID
				$('#modal-comments-popup').find('input[name=ID]').attr('value',Id);
				

				//Text
				if($('#modal-comments-popup').find('#comment-editor').length>0)
					$('#modal-comments-popup').find('#comment-editor').find('iframe').contents().find('body').html(Comment['TEXT']);
				else{
					if($('#modal-comments-popup').find('textarea[name=text]').length>0)
					{
						$('#modal-comments-popup').find('textarea[name=text]').html(Comment['TEXT']);
						$('#modal-comments-popup').find('.count-now').html(Comment['TEXT'].length);
					}
				}


				}
				
				
				$('#comments-personal-overlay').fadeIn(400, 
					 	function(){
								var html = document.documentElement;
								var w=$('#modal-comments-popup').outerWidth(true);
								var h=parseInt($('#modal-comments-popup').outerHeight(true));
					  			var left = (html.clientWidth/2)-(w/2);
					  			//var top = (html.clientHeight/2)-(h/2);
					  			
					  			var top = parseInt($(window).scrollTop());
					  			var htmlHeight=parseInt($('html').outerWidth(true));
					  			
					  			top = top + 10;
					  					
							$('#modal-comments-popup') 
								.css('display', 'block') 
								.animate({opacity: 1, top: top, left:left}, 200); 
					});
				
				BX.closeWait();
			},
			error:  function (jqXHR, exception) {
			}
		});
		
		
		

	});
	
	
	$(document).on('submit','#add_comment',
			function(){
				SiteDir = $(".table-personal-comments").data("site-dir");
				var input = $(this).serialize();
				

				
				
				$.ajax({
					type: 'POST',
					url: SiteDir+'bitrix/components/sotbit/reviews.personalcomments/ajax/save.php',
					data: input,
					success: function(data){
						
						var CapId = $('#captcha-ids-comments').html();
						if(CapId.trim()!=''){
							grecaptcha.reset(
									CapId
							);
							}
						
						
						if(data.trim()!='SUCCESS')
						{
							$('#modal-comments-popup').find('.error').html(data).show();
							$('.sotbit_comments_personal_comments .success-delete').hide();
							$('.sotbit_comments_personal_comments .error-delete').hide();
							$('.sotbit_comments_personal_comments .success-edit').hide();
						}
						else
						{
							$('#modal-comments-popup').find('.error').html('').hide();
							SiteDir = $(".table-personal-comments").data("site-dir");
							var data=$('#PersonalCommentsParams').html();
							BX.showWait();
							$.ajax({
								type: 'POST',
								url: SiteDir+'bitrix/components/sotbit/reviews.personalcomments/ajax/reload.php',
								data: {data:data},
								success: function(data){
									$('#modal-comments-popup').animate({opacity: 0, top: '45%'}, 200,  
											function(){ 
										$(this).css('display', 'none'); 
										$('#comments-personal-overlay').css('display', 'none');
										$('#modal-comments-popup').remove();
										$('#comments-personal-overlay').remove();
										$('.sotbit_comments_personal_comments').wrap('<div></div>').parent().html(data);
										jQuery("#modal-comments-popup").detach().appendTo('body');
										jQuery("#comments-personal-overlay").detach().appendTo('body');
										$('.sotbit_comments_personal_comments .success-edit').show();
										$('.sotbit_comments_personal_comments .error-delete').hide();
										$('.sotbit_comments_personal_comments .success-delete').hide();
											}
										);


									BX.closeWait();
								},
								error:  function (jqXHR, exception) {
								}
							});
						}
						BX.closeWait();
					},
					error:  function (jqXHR, exception) {
					}
				});
	  
		});

	$(document).on('click','.sotbit_comments_personal_comments .personal-delete',
			function(){
				SiteDir = $(this).closest(".table-personal-comments").data("site-dir");
				var Id=$(this).data('id');
				//var data=$('#PersonalCommentsParams').html();
				BX.showWait();
				$.ajax({
					type: 'POST',
					url: SiteDir+'bitrix/components/sotbit/reviews.personalcomments/ajax/delete.php',
					data: {Id:Id},
					success: function(data){
						if(data.trim()!='SUCCESS')
						{
							$('.sotbit_comments_personal_comments .success-delete').hide();
							$('.sotbit_comments_personal_comments .success-edit').hide();
							$('.sotbit_comments_personal_comments .error-delete').html(data).show();
						}
						else
						{
							SiteDir = $(".table-personal-comments").data("site-dir");
							var data=$('#PersonalCommentsParams').html();
							BX.showWait();
							$.ajax({
								type: 'POST',
								url: SiteDir+'bitrix/components/sotbit/reviews.personalcomments/ajax/reload.php',
								data: {data:data},
								success: function(data){
									$('#modal-comments-popup').remove();
									$('#comments-personal-overlay').remove();
									$('.sotbit_comments_personal_comments').wrap('<div></div>').parent().html(data);
									jQuery("#modal-comments-popup").detach().appendTo('body');
									jQuery("#comments-personal-overlay").detach().appendTo('body');
									$('.sotbit_comments_personal_comments .success-edit').hide();
									$('.sotbit_comments_personal_comments .error-delete').hide();
									$('.sotbit_comments_personal_comments .success-delete').show();
									BX.closeWait();
								},
								error:  function (jqXHR, exception) {
								}
							});

							
						}
						BX.closeWait();
					},
					error:  function (jqXHR, exception) {
					}
				});
	
		});
});