$(document).ready(function(){
	
	
	jQuery("#modal-reviews-popup").detach().appendTo('body');
	jQuery("#reviews-personal-overlay").detach().appendTo('body');

	
	$(document).on('click','#modal-reviews-popup #modal_close, #reviews-personal-overlay', function(){ 
		$('#modal-reviews-popup') 
			.animate({opacity: 0, top: '45%'}, 200,  
				function(){ 
					$(this).css('display', 'none'); 
					$('#reviews-personal-overlay').fadeOut(400); 
					
					
					var CapId = $('#captcha-ids-reviews').html();
					if(CapId.trim()!='')
					{
				grecaptcha.reset(
						CapId
				);
					}
					
				}
			);
	});
	//sort
	$(document).on('click','.sotbit_reviews_personal_reviews .table-personal-reviews thead tr th', function(){ 
		
		var Oldsort = $(this).closest('.table-personal-reviews').attr('data-sort');
		var Oldby = $(this).closest('.table-personal-reviews').attr('data-by');
		
		var by= $(this).data('sort');
		$(this).closest('.table-personal-reviews').attr('data-by',by);
		if(Oldby==by)
		{
			if(Oldsort=='asc')
			{
				$(this).closest('.table-personal-reviews').attr('data-sort','desc');
				var sort='desc';
			}
			else
			{
				$(this).closest('.table-personal-reviews').attr('data-sort','asc');
				var sort='asc';
			}
		}
		else
		{
			$(this).closest('.table-personal-reviews').attr('data-sort','asc');
			var sort='asc';
		}
		
		
		$('.sotbit_reviews_personal_reviews .table-personal-reviews tbody  tr').sort(function(a, b) {
			if(sort=='asc')
			{
				return +$(b).find('[data-name='+by+']').attr('data-value') - +$(a).find('[data-name='+by+']').attr('data-value');
			}
			else
			{
				return +$(a).find('[data-name='+by+']').attr('data-value') - +$(b).find('[data-name='+by+']').attr('data-value');
			}
		}).appendTo('.sotbit_reviews_personal_reviews .table-personal-reviews tbody');
	});

	$(document).on('keyup','#add_review  #contentbox',function()
			{
				var MaxInput=$(this).attr('maxlength');
				var box=$(this).val();
				if(box.length <= MaxInput)
				{
					$('#add_review').find('.count-now').html(box.length);
				}
				else{}
				return false;
		});
	
	
	$(document).on('click','.sotbit_reviews_personal_reviews .personal-edit',
		function(){
		
		SiteDir = $(this).closest(".table-personal-reviews").data("site-dir");
		var Id=$(this).data('id');
		var data=$('#PersonalReviewsParams').html();
		BX.showWait();
		$.ajax({
			type: 'POST',
			url: SiteDir+'bitrix/components/sotbit/reviews.personalreviews/ajax/edit.php',
			data: {data:data,Id:Id},
			success: function(data){
				if(data.length>0)
				{
				var Review = JSON.parse(data);

				//ID
				$('#modal-reviews-popup').find('input[name=ID]').attr('value',Id);
				
				
				//Rating
				$('#modal-reviews-popup').find('.rating_selection [type=radio]').each(function(k) {
					if(parseInt(k)==parseInt(Review['RATING']-1))
						$(this).attr('checked','checked');
				});
				//Title
				if($('#modal-reviews-popup').find('input[name=title]').length>0)
					$('#modal-reviews-popup').find('input[name=title]').attr('value',Review['TITLE']);
				//Text
				if($('#modal-reviews-popup').find('#review-editor').length>0)
					$('#modal-reviews-popup').find('#review-editor').find('iframe').contents().find('body').html(Review['TEXT']);
				else{
					if($('#modal-reviews-popup').find('textarea[name=text]').length>0)
					{
						$('#modal-reviews-popup').find('textarea[name=text]').html(Review['TEXT']);
						$('#modal-reviews-popup').find('.count-now').html(Review['TEXT'].length);
					}
				}
				//Recommendated
				$('#modal-reviews-popup').find('input[name=RECOMMENDATED]').each(function(k) {
					if($(this).attr('value')==Review['RECOMMENDATED'])
					{
						$(this).attr('checked','checked');
					}
					else
					{
						$(this).removeAttr('checked');
					}
						
				});
				//Photo
				$('#modal-reviews-popup #preview-photo').html('');
				if(Review['THUMB_IMAGE'].length>0)
				{
					var previewWidth = $('#preview-photo').data('thumb-width');
					var previewHeight = $('#preview-photo').data('thumb-height');
					
					$.each(Review['BIG_IMAGE'], function (index, value) {
						$('#modal-reviews-popup #preview-photo').append(
								'<li data-id="'+value+'" style="width:'+previewWidth+'px;height:'+previewHeight+'px;background:url('+value+') no-repeat;background-size: 100% 100%;"><span class="delete"><i class="fa fa-times"></i></span></li>'
						);
						$('#modal-reviews-popup #preview-photo').append(
								'<input type="hidden" name="photos[]" value="' + value + '" data-id="' + value+ '">'
							);
						});
				}
				//video
				if($('#modal-reviews-popup').find('input[name=video]').length>0)
					$('#modal-reviews-popup').find('input[name=video]').attr('value',Review['MULTIMEDIA']['VIDEO']);
				//presentation
				if($('#modal-reviews-popup').find('input[name=presentation]').length>0)
					$('#modal-reviews-popup').find('input[name=presentation]').attr('value',Review['MULTIMEDIA']['PRESENTATION']);
				}
				
				
				
				//Add fields

					$.each(Review['ADD_FIELDS'], function (index, value) {
						
						if($('#modal-reviews-popup').find('input[name=AddFields_'+index+']').length>0){
							if($('#modal-reviews-popup').find('input[name=AddFields_'+index+']').attr('type')=='hidden')
							{
								$('#modal-reviews-popup').find('input[name=AddFields_'+index+']').closest('.bx-html-editor').find('iframe').contents().find('body').html(value);
							}
						}
							else
							{
								if($('#modal-reviews-popup').find('textarea[name=AddFields_'+index+']').length>0)
								{
									$('#modal-reviews-popup').find('textarea[name=AddFields_'+index+']').html(value);
								}
							}
						});

				
				
				$('#reviews-personal-overlay').fadeIn(400, 
					 	function(){
								var html = document.documentElement;
								var w=$('#modal-reviews-popup').outerWidth(true);
								var h=parseInt($('#modal-reviews-popup').outerHeight(true));
					  			var left = (html.clientWidth/2)-(w/2);
					  			//var top = (html.clientHeight/2)-(h/2);
					  			
					  			var top = parseInt($(window).scrollTop());
					  			var htmlHeight=parseInt($('html').outerWidth(true));
					  			
					  			top = top + 10;
					  					
							$('#modal-reviews-popup') 
								.css('display', 'block') 
								.animate({opacity: 1, top: top, left:left}, 200); 
					});
				
				BX.closeWait();
			},
			error:  function (jqXHR, exception) {
			}
		});
		
		
		

	});
	
	
	$(document).on('submit','#add_review',
			function(){
				SiteDir = $(".table-personal-reviews").data("site-dir");
				var input = $(this).serialize();
				

				
				
				$.ajax({
					type: 'POST',
					url: SiteDir+'bitrix/components/sotbit/reviews.personalreviews/ajax/save.php',
					data: input,
					success: function(data){
						
						var CapId = $('#captcha-ids-reviews').html();
						if(CapId.trim()!=''){
							grecaptcha.reset(
									CapId
							);
							}
						
						
						if(data.trim()!='SUCCESS')
						{
							$('#modal-reviews-popup').find('.error').html(data).show();
							$('.sotbit_reviews_personal_reviews .success-delete').hide();
							$('.sotbit_reviews_personal_reviews .error-delete').hide();
							$('.sotbit_reviews_personal_reviews .success-edit').hide();
						}
						else
						{
							$('#modal-reviews-popup').find('.error').html('').hide();
							SiteDir = $(".table-personal-reviews").data("site-dir");
							var data=$('#PersonalReviewsParams').html();
							BX.showWait();
							$.ajax({
								type: 'POST',
								url: SiteDir+'bitrix/components/sotbit/reviews.personalreviews/ajax/reload.php',
								data: {data:data},
								success: function(data){
									$('#modal-reviews-popup').animate({opacity: 0, top: '45%'}, 200,  
											function(){ 
										$(this).css('display', 'none'); 
										$('#reviews-personal-overlay').css('display', 'none');
										$('#modal-reviews-popup').remove();
										$('#reviews-personal-overlay').remove();
										$('.sotbit_reviews_personal_reviews').wrap('<div></div>').parent().html(data);
										jQuery("#modal-reviews-popup").detach().appendTo('body');
										jQuery("#reviews-personal-overlay").detach().appendTo('body');
										$('.sotbit_reviews_personal_reviews .success-edit').show();
										$('.sotbit_reviews_personal_reviews .error-delete').hide();
										$('.sotbit_reviews_personal_reviews .success-delete').hide();
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

	$(document).on('click','.sotbit_reviews_personal_reviews .personal-delete',
			function(){
				SiteDir = $(this).closest(".table-personal-reviews").data("site-dir");
				var Id=$(this).data('id');
				//var data=$('#PersonalReviewsParams').html();
				BX.showWait();
				$.ajax({
					type: 'POST',
					url: SiteDir+'bitrix/components/sotbit/reviews.personalreviews/ajax/delete.php',
					data: {Id:Id},
					success: function(data){
						if(data.trim()!='SUCCESS')
						{
							$('.sotbit_reviews_personal_reviews .success-delete').hide();
							$('.sotbit_reviews_personal_reviews .success-edit').hide();
							$('.sotbit_reviews_personal_reviews .error-delete').html(data).show();
						}
						else
						{
							SiteDir = $(".table-personal-reviews").data("site-dir");
							var data=$('#PersonalReviewsParams').html();
							BX.showWait();
							$.ajax({
								type: 'POST',
								url: SiteDir+'bitrix/components/sotbit/reviews.personalreviews/ajax/reload.php',
								data: {data:data},
								success: function(data){
									$('#modal-reviews-popup').remove();
									$('#reviews-personal-overlay').remove();
									$('.sotbit_reviews_personal_reviews').wrap('<div></div>').parent().html(data);
									jQuery("#modal-reviews-popup").detach().appendTo('body');
									jQuery("#reviews-personal-overlay").detach().appendTo('body');
									$('.sotbit_reviews_personal_reviews .success-edit').hide();
									$('.sotbit_reviews_personal_reviews .error-delete').hide();
									$('.sotbit_reviews_personal_reviews .success-delete').show();
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
	
	//Нажатие на кнопку - замену input file
	$(document).on('click', '#add-photo-button', function() {
		$("input[type='file']").trigger('click');
	})

	var MaxCountImages=$('#preview-photo').data('max-count-images');
	var previewWidth = $('#preview-photo').data('thumb-width'), // ширина превью
	previewHeight = $('#preview-photo').data('thumb-height'), // высота превью
	maxFileSize = $('#preview-photo').data('max-size') * 1024 * 1024, // (байт) Максимальный размер файла (2мб)
	selectedFiles = {},// объект, в котором будут храниться выбранные файлы
	queue = [],
	image = new Image(),
	imgLoadHandler,
	isProcessing = false,
	errorMsg, // сообщение об ошибке при валидации файла
	previewPhotoContainer = document.querySelector('#preview-photo'); // контейнер, в котором будут отображаться превью
	// Когда пользователь выбрал файлы, обрабатываем их
	$('input[type=file][id=photo]').on('change', function() {

		var newFiles = $(this)[0].files; // массив с выбранными файлами
		//проверка на максимальное количество картинок
		if(($('#preview-photo > li').size()+newFiles.length)>MaxCountImages)
		{
			alert($('#preview-photo').data('error-max-count')+' - '+MaxCountImages);
			return;
		}
		for (var i = 0; i < newFiles.length; i++) {
			var file = newFiles[i];

			// В качестве "ключей" в объекте selectedFiles используем названия файлов
			// чтобы пользователь не мог добавлять один и тот же файл
			// Если файл с текущим названием уже существует в массиве, переходим к следующему файлу
			if (selectedFiles[file.name] != undefined) continue;

			// Валидация файлов (проверяем формат и размер)
			if ( errorMsg = validateFile(file) ) {
				alert(errorMsg);
				return;
			}

			// Добавляем файл в объект selectedFiles
			selectedFiles[file.name] = file;
			queue.push(file);
		}

		$(this).val('');
		processQueue(); // запускаем процесс создания миниатюр
	});

	// Валидация выбранного файла (формат, размер)
	var validateFile = function(file)
	{
		if ( !file.type.match(/image\/(jpeg|jpg|png)/) ) {
			return $('#preview-photo').data('error-type');
		}

		if ( file.size > maxFileSize ) {
			return $('#preview-photo').data('error-max-size')+' '+$('#preview-photo').data('max-size')+' Mb';
		}

	};

	var listen = function(element, event, fn) {
		return element.addEventListener(event, fn, false);
	};

	// Создание миниатюры
	var processQueue = function(Type)
	{
		// Миниатюры будут создаваться поочередно
		// чтобы в один момент времени не происходило создание нескольких миниатюр
		// проверяем запущен ли процесс
		if (isProcessing) { return; }

		// Если файлы в очереди закончились, завершаем процесс
		if (queue.length == 0) {
			isProcessing = false;
			return;
		}

		isProcessing = true;

		var file = queue.pop(); // Берем один файл из очереди

		var li = document.createElement('LI');
		var span = document.createElement('SPAN');
		var spanDel = document.createElement('SPAN');
		var canvas = document.createElement('CANVAS');
		canvas.setAttribute("width", previewWidth);
		canvas.setAttribute("height", previewHeight);
		var ctx = canvas.getContext('2d');

		span.setAttribute('class', 'img');
		spanDel.setAttribute('class', 'delete');
		spanDel.innerHTML = '<i class="fa fa-times"></i>';

		li.appendChild(span);
		li.appendChild(spanDel);
		li.setAttribute('data-id', file.name);

		image.removeEventListener('load', imgLoadHandler, false);

		// создаем миниатюру
		imgLoadHandler = function() {
			ctx.drawImage(image, 0, 0, previewWidth, previewHeight);
			URL.revokeObjectURL(image.src);
			span.appendChild(canvas);
			isProcessing = false;
			setTimeout(processQueue, 200); // запускаем процесс создания миниатюры для следующего изображения
		};

		// Выводим миниатюру в контейнере previewPhotoContainer
		previewPhotoContainer.appendChild(li);
		listen(image, 'load', imgLoadHandler);
		image.src = URL.createObjectURL(file);

		// Сохраняем содержимое оригинального файла в base64 в отдельном поле формы
		// чтобы при отправке формы файл был передан на сервер
		var fr = new FileReader();
		fr.readAsDataURL(file);
		fr.onload = (function (file) {
			return function (e) {
				$('#preview-photo').append(
					'<input type="hidden" name="photos[]" value="' + e.target.result + '" data-id="' + file.name+ '">'
				);
			}
		}) (file);
	};

	// Удаление фотографии
	$(document).on('click', '#preview-photo li span.delete', function() {
		var fileId = $(this).parents('li').attr('data-id');

		if (selectedFiles[fileId] != undefined) delete selectedFiles[fileId]; // Удаляем файл из объекта selectedFiles
		$(this).parents('li').remove(); // Удаляем превью
		$('input[name^=photo][data-id="' + fileId + '"]').remove(); // Удаляем поле с содержимым файла
	});
	
	
	
});