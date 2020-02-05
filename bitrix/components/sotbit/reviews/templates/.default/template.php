<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();  
global $USER;
global $APPLICATION;?>

 <?
if(isset($arResult['REVIEWS_RECAPTCHA2_SITE_KEY']) && !empty($arResult['REVIEWS_RECAPTCHA2_SITE_KEY']))
	$APPLICATION->AddHeadString("<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
	<script>
		function onloadCallback() {
			if($('#add_review [data-captcha-review=\"Y\"]').attr('id')!==undefined)
			{
				var CapId = grecaptcha.render($('#add_review [data-captcha-review=\"Y\"]').attr('id'), {
					'sitekey' : '".$arResult['REVIEWS_RECAPTCHA2_SITE_KEY']."'
				});
	
				$('#captcha-ids-reviews').html(CapId);
			}
					
		}	
	</script>
");

if($arParams["SHOW_COMMENTS"]=='Y' && $arParams["FIRST_ACTIVE"]==2 && isset($arResult['COMMENTS_RECAPTCHA2_SITE_KEY']) && !empty($arResult['COMMENTS_RECAPTCHA2_SITE_KEY']))
	$APPLICATION->AddHeadString("<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
	<script>
		function onloadCallback() {
			
		if($('#add_review [data-captcha-review=\"Y\"]').attr('id')!==undefined)
		{
			var CapId = grecaptcha.render($('#add_review [data-captcha-review=\"Y\"]').attr('id'), {
					'sitekey' : '".$arResult['REVIEWS_RECAPTCHA2_SITE_KEY']."'
				});
		
			$('#captcha-ids-reviews').html(CapId);
		}
			if($('#recaptcha-comment-0').length>0){
				var CapId = grecaptcha.render('recaptcha-comment-0', {
					'sitekey' : '".$arResult['COMMENTS_RECAPTCHA2_SITE_KEY']."'
				});
				
				$('#captcha-ids-comments').html(CapId);
			}
			$.each($('#comments-body [data-captcha-comment=\"Y\"]'), function (index, value) {
				var CapId = grecaptcha.render($(this).attr('id'), {
					 'sitekey' : '".$arResult['COMMENTS_RECAPTCHA2_SITE_KEY']."'
				});
				$('#captcha-ids-comments').append('|'+CapId);
			});
		}
	</script>
");

if($arParams["SHOW_QUESTIONS"]=='Y' && $arParams["FIRST_ACTIVE"]==3 && isset($arResult['QUESTIONS_RECAPTCHA2_SITE_KEY']) && !empty($arResult['QUESTIONS_RECAPTCHA2_SITE_KEY']))
	$APPLICATION->AddHeadString("<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
	<script>
		function onloadCallback() {

		if($('#add_review [data-captcha-review=\"Y\"]').attr('id')!==undefined)
		{
		var CapId = grecaptcha.render($('#add_review [data-captcha-review=\"Y\"]').attr('id'), {
				'sitekey' : '".$arResult['REVIEWS_RECAPTCHA2_SITE_KEY']."'
			});

			$('#captcha-ids-reviews').html(CapId);
		}
			if($('#captcha-question-0').length>0){
				var CapId = grecaptcha.render('captcha-question-0', {
					'sitekey' : '".$arResult['QUESTIONS_RECAPTCHA2_SITE_KEY']."'
				});
	
				$('#captcha-ids-questions').html(CapId);
			}

		}
	</script>
");	

?> 


<?if ($arParams["SHOW_REVIEWS"]=='Y'):?>
	<div id="reviews-statistics">
		<?
		$APPLICATION->IncludeComponent(
			"sotbit:reviews.statistics",
			"",
			array(
				'MAX_RATING'=>$arParams['MAX_RATING'],
				'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
				"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
				'CACHE_TIME'=>$arParams["CACHE_TIME"],
				'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
			),
			$component
		);
		?>
		<?if($arParams['ADD_REVIEW_PLACE']==1):

			$APPLICATION->IncludeComponent(
				"sotbit:reviews.reviews.add",
				"",
				array(
					'MAX_RATING'=>$arParams['MAX_RATING'],
					'DEFAULT_RATING_ACTIVE'=>$arParams['DEFAULT_RATING_ACTIVE'],
					'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
					"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
					"BUTTON_BACKGROUND"=>$arParams['BUTTON_BACKGROUND'],
					'ADD_REVIEW_PLACE'=>$arParams['ADD_REVIEW_PLACE'],
					'CACHE_TIME'=>$arParams["CACHE_TIME"],
					'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
					'TEXTBOX_MAXLENGTH'=>$arParams['REVIEWS_TEXTBOX_MAXLENGTH'],
					"NOTICE_EMAIL"=>$arParams['NOTICE_EMAIL']
				),
				$component
			);

			endif;?>
	</div>
	<?endif;?>
<?$frame=$this->createFrame()->begin();?>
<div class="tabs">
	<ul class="tabs-caption" data-id-element="<?=$arParams['ID_ELEMENT']?>" data-site-dir="<?=SITE_DIR?>">
		<?if ($arParams["SHOW_REVIEWS"]=='Y'):?>
			<li  id="reviews" data-ajax="<?=($arParams["FIRST_ACTIVE"]==1)?'N':'Y'?>"data-ajax="<?=($arParams["FIRST_ACTIVE"]==1)?'N':'Y'?>" <?=($arParams["FIRST_ACTIVE"]==1)?'class="active"':''?>><i class="fa  fa-star"></i> <?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_TAB_TITLE")?> (<?=$arResult["SUM_CNT_REVIEWS"]?>) </li>
			<?endif;?>
		<?if ($arParams["SHOW_COMMENTS"]=='Y'):?>
			<li id="comments" data-ajax="<?=($arParams["FIRST_ACTIVE"]==2)?'N':'Y'?>" <?=($arParams["FIRST_ACTIVE"]==2)?'class="active"':''?>><i class="fa fa-wechat"></i> <?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_TAB_TITLE")?> (<?=$arResult["COMMENTS_CNT"]?>)</li>
			<?endif;?>
		<?if ($arParams["SHOW_QUESTIONS"]=='Y'):?>
			<li id="questions" data-ajax="<?=($arParams["FIRST_ACTIVE"]==3)?'N':'Y'?>" <?=($arParams["FIRST_ACTIVE"]==3)?'class="active"':''?>><i class="fa  fa-question-circle"></i> <?=GetMessage(CSotbitReviews::iModuleID."_QUESTIONS_TAB_TITLE")?> (<?=$arResult["QUESTIONS_CNT"]?>)</li>
			<?endif;?>
	</ul>
</div>
<?$frame->end();?>
<?if ($arParams["SHOW_REVIEWS"]=='Y'):?>
	<div class="tabs-content <?=($arParams["FIRST_ACTIVE"]==1)?'active':''?>" id="reviews-body">
		<?
		$APPLICATION->IncludeComponent(
			"sotbit:reviews.reviews",
			"",
			array(
				'DEFAULT_RATING_ACTIVE'=>$arParams['DEFAULT_RATING_ACTIVE'],
				'TEXTBOX_MAXLENGTH'=>$arParams['REVIEWS_TEXTBOX_MAXLENGTH'],
				'MAX_RATING'=>$arParams['MAX_RATING'],
				'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
				"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
				"BUTTON_BACKGROUND"=>$arParams['BUTTON_BACKGROUND'],
				"ADD_REVIEW_PLACE"=>$arParams['ADD_REVIEW_PLACE'],
				'CACHE_TIME'=>$arParams["CACHE_TIME"],
				'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
				"NOTICE_EMAIL"=>$arParams['NOTICE_EMAIL'],
				"DATE_FORMAT"=>$arParams['DATE_FORMAT'],
				'AJAX'=>($arParams["FIRST_ACTIVE"]==1)?'N':'Y'
			),
			$component
		);
		?>
	</div>
	<?endif;?>
<?if ($arParams["SHOW_COMMENTS"]=='Y'):?>
	<div class="tabs-content <?=($arParams["FIRST_ACTIVE"]==2)?'active':''?>" id="comments-body">
		<?
		$APPLICATION->IncludeComponent(
			"sotbit:reviews.comments",
			"",
			array(
				'TEXTBOX_MAXLENGTH'=>$arParams['COMMENTS_TEXTBOX_MAXLENGTH'],
				'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
				"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
				"BUTTON_BACKGROUND"=>$arParams['BUTTON_BACKGROUND'],
				'CACHE_TIME'=>$arParams["CACHE_TIME"],
				'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
				"NOTICE_EMAIL"=>$arParams['NOTICE_EMAIL'],
				"DATE_FORMAT"=>$arParams['DATE_FORMAT'],
				'AJAX'=>($arParams["FIRST_ACTIVE"]==2)?'N':'Y'
			),
			$component
		);
		?>
	</div>
<?endif;?>
<?if ($arParams["SHOW_QUESTIONS"]=='Y'):?>
	<div class="tabs-content <?=($arParams["FIRST_ACTIVE"]==3)?'active':''?>" id="questions-body">
		<?
		$APPLICATION->IncludeComponent(
			"sotbit:reviews.questions",
			"",
			array(
				'TEXTBOX_MAXLENGTH'=>$arParams['QUESTIONS_TEXTBOX_MAXLENGTH'],
				'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
				"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
				"BUTTON_BACKGROUND"=>$arParams['BUTTON_BACKGROUND'],
				'CACHE_TIME'=>$arParams["CACHE_TIME"],
				'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
				"NOTICE_EMAIL"=>$arParams['NOTICE_EMAIL'],
				"DATE_FORMAT"=>$arParams['DATE_FORMAT'],
				'AJAX'=>($arParams["FIRST_ACTIVE"]==3)?'N':'Y'
			),
			$component
		);
		?>
	</div>
	<?endif;?>
<div id="ReviewsParams" style="display:none;"><?=serialize(array_merge($arParams,array('TEMPLATE'=>$templateName)))?></div>
<script>
	//���������� ��� ���������� ��� �������� ��������
	(function($) {
		$(function() {
			var Active = $("ul.tabs-caption").find( $("li.active") )
			Active=Active.attr('id');
			if(Active=='reviews')
			{
				$("#comments-body").empty();
				$("#questions-body").empty();
			}
			if(Active=='comments')
			{
				$("#reviews-body").empty();
				$("#questions-body").empty();
			}
			if(Active=='questions')
			{
				$("#reviews-body").empty();
				$("#comments-body").empty();
			}
		});
	})(jQuery);
</script>
<div id="ReviewsParams" style="display:none;"><?=serialize(array_merge($arParams,array('TEMPLATE'=>$templateName)))?></div>
<div id="captcha-reviews" style="display:none;"><?=$arResult['REVIEWS_RECAPTCHA2_SITE_KEY'] ?></div>
<div id="captcha-ids-reviews" style="display:none;"></div>
<div id="captcha-comments" style="display:none;"><?=$arResult['COMMENTS_RECAPTCHA2_SITE_KEY'] ?></div>
<div id="captcha-ids-comments" style="display:none;"></div>
<div id="captcha-questions" style="display:none;"><?=$arResult['QUESTIONS_RECAPTCHA2_SITE_KEY'] ?></div>
<div id="captcha-ids-questions" style="display:none;"></div>