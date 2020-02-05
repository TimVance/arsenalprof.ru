$(document).ready(function(){
	
	
	jQuery("#modal-questions-popup").detach().appendTo('body');
	jQuery("#questions-personal-overlay").detach().appendTo('body');

	
	$(document).on('click','#modal-questions-popup #modal_close, #questions-personal-overlay', function(){ 
		$('#modal-questions-popup') 
			.animate({opacity: 0, top: '45%'}, 200,  
				function(){ 
					$(this).css('display', 'none'); 
					$('#questions-personal-overlay').fadeOut(400); 
					
					
					var CapId = $('#captcha-ids-questions').html();
					if(CapId.trim()!=''){
					grecaptcha.reset(
							CapId
					);
					}
					
				}
			);
	});
	//sort
	$(document).on('click','.sotbit_questions_personal_questions .table-personal-questions thead tr th', function(){ 
		
		var Oldsort = $(this).closest('.table-personal-questions').attr('data-sort');
		var Oldby = $(this).closest('.table-personal-questions').attr('data-by');
		
		var by= $(this).data('sort');
		$(this).closest('.table-personal-questions').attr('data-by',by);
		if(Oldby==by)
		{
			if(Oldsort=='asc')
			{
				$(this).closest('.table-personal-questions').attr('data-sort','desc');
				var sort='desc';
			}
			else
			{
				$(this).closest('.table-personal-questions').attr('data-sort','asc');
				var sort='asc';
			}
		}
		else
		{
			$(this).closest('.table-personal-questions').attr('data-sort','asc');
			var sort='asc';
		}
		
		
		$('.sotbit_questions_personal_questions .table-personal-questions tbody  tr').sort(function(a, b) {
			if(sort=='asc')
			{
				return +$(b).find('[data-name='+by+']').attr('data-value') - +$(a).find('[data-name='+by+']').attr('data-value');
			}
			else
			{
				return +$(a).find('[data-name='+by+']').attr('data-value') - +$(b).find('[data-name='+by+']').attr('data-value');
			}
		}).appendTo('.sotbit_questions_personal_questions .table-personal-questions tbody');
	});

	$(document).on('keyup','#add_question  #contentbox',function()
			{
				var MaxInput=$(this).attr('maxlength');
				var box=$(this).val();
				if(box.length <= MaxInput)
				{
					$('#add_question').find('.count-now').html(box.length);
				}
				else{}
				return false;
		});
	
	
	$(document).on('click','.sotbit_questions_personal_questions .personal-edit',
		function(){
		
		SiteDir = $(this).closest(".table-personal-questions").data("site-dir");
		var Id=$(this).data('id');
		var data=$('#PersonalQuestionsParams').html();
		BX.showWait();
		$.ajax({
			type: 'POST',
			url: SiteDir+'bitrix/components/sotbit/reviews.personalquestions/ajax/edit.php',
			data: {data:data,Id:Id},
			success: function(data){
				if(data.length>0)
				{
				var Question = JSON.parse(data);

				//ID
				$('#modal-questions-popup').find('input[name=ID]').attr('value',Id);
				

				//Text
				if($('#modal-questions-popup').find('#question-editor').length>0)
					$('#modal-questions-popup').find('#question-editor').find('iframe').contents().find('body').html(Question['TEXT']);
				else{
					if($('#modal-questions-popup').find('textarea[name=text]').length>0)
					{
						$('#modal-questions-popup').find('textarea[name=text]').html(Question['QUESTION']);
						$('#modal-questions-popup').find('.count-now').html(Question['QUESTION'].length);
					}
				}


				}
				
				
				$('#questions-personal-overlay').fadeIn(400, 
					 	function(){
								var html = document.documentElement;
								var w=$('#modal-questions-popup').outerWidth(true);
								var h=parseInt($('#modal-questions-popup').outerHeight(true));
					  			var left = (html.clientWidth/2)-(w/2);
					  			//var top = (html.clientHeight/2)-(h/2);
					  			
					  			var top = parseInt($(window).scrollTop());
					  			var htmlHeight=parseInt($('html').outerWidth(true));
					  			
					  			top = top + 10;
					  					
							$('#modal-questions-popup') 
								.css('display', 'block') 
								.animate({opacity: 1, top: top, left:left}, 200); 
					});
				
				BX.closeWait();
			},
			error:  function (jqXHR, exception) {
			}
		});
		
		
		

	});
	
	
	$(document).on('submit','#add_question',
			function(){
				SiteDir = $(".table-personal-questions").data("site-dir");
				var input = $(this).serialize();
				

				
				
				$.ajax({
					type: 'POST',
					url: SiteDir+'bitrix/components/sotbit/reviews.personalquestions/ajax/save.php',
					data: input,
					success: function(data){
						
						var CapId = $('#captcha-ids-questions').html();
						
						if(CapId.trim()!='')
							{
						grecaptcha.reset(
								CapId
						);
							}
						
						
						if(data.trim()!='SUCCESS')
						{
							$('#modal-questions-popup').find('.error').html(data).show();
							$('.sotbit_questions_personal_questions .success-delete').hide();
							$('.sotbit_questions_personal_questions .error-delete').hide();
							$('.sotbit_questions_personal_questions .success-edit').hide();
						}
						else
						{
							$('#modal-questions-popup').find('.error').html('').hide();
							SiteDir = $(".table-personal-questions").data("site-dir");
							var data=$('#PersonalQuestionsParams').html();
							BX.showWait();
							$.ajax({
								type: 'POST',
								url: SiteDir+'bitrix/components/sotbit/reviews.personalquestions/ajax/reload.php',
								data: {data:data},
								success: function(data){
									$('#modal-questions-popup').animate({opacity: 0, top: '45%'}, 200,  
											function(){ 
										$(this).css('display', 'none'); 
										$('#questions-personal-overlay').css('display', 'none');
										$('#modal-questions-popup').remove();
										$('#questions-personal-overlay').remove();
										$('.sotbit_questions_personal_questions').wrap('<div></div>').parent().html(data);
										jQuery("#modal-questions-popup").detach().appendTo('body');
										jQuery("#questions-personal-overlay").detach().appendTo('body');
										$('.sotbit_questions_personal_questions .success-edit').show();
										$('.sotbit_questions_personal_questions .error-delete').hide();
										$('.sotbit_questions_personal_questions .success-delete').hide();
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

	$(document).on('click','.sotbit_questions_personal_questions .personal-delete',
			function(){
				SiteDir = $(this).closest(".table-personal-questions").data("site-dir");
				var Id=$(this).data('id');
				//var data=$('#PersonalQuestionsParams').html();
				BX.showWait();
				$.ajax({
					type: 'POST',
					url: SiteDir+'bitrix/components/sotbit/reviews.personalquestions/ajax/delete.php',
					data: {Id:Id},
					success: function(data){
						if(data.trim()!='SUCCESS')
						{
							$('.sotbit_questions_personal_questions .success-delete').hide();
							$('.sotbit_questions_personal_questions .success-edit').hide();
							$('.sotbit_questions_personal_questions .error-delete').html(data).show();
						}
						else
						{
							SiteDir = $(".table-personal-questions").data("site-dir");
							var data=$('#PersonalQuestionsParams').html();
							BX.showWait();
							$.ajax({
								type: 'POST',
								url: SiteDir+'bitrix/components/sotbit/reviews.personalquestions/ajax/reload.php',
								data: {data:data},
								success: function(data){
									$('#modal-questions-popup').remove();
									$('#questions-personal-overlay').remove();
									$('.sotbit_questions_personal_questions').wrap('<div></div>').parent().html(data);
									jQuery("#modal-questions-popup").detach().appendTo('body');
									jQuery("#questions-personal-overlay").detach().appendTo('body');
									$('.sotbit_questions_personal_questions .success-edit').hide();
									$('.sotbit_questions_personal_questions .error-delete').hide();
									$('.sotbit_questions_personal_questions .success-delete').show();
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