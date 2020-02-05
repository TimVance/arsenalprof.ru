<?
if(!defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true)
	die();
use Bitrix\Main\Localization\Loc;
global $APPLICATION;
global $USER;
?>
<?$frame=$this->createFrame()->begin("");?>

<?

if(isset($arResult['RECAPTCHA2_SITE_KEY']) && !empty($arResult['RECAPTCHA2_SITE_KEY']))
{
	$APPLICATION->AddHeadString("<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
	<script>
		function onloadCallback() {
			if($('#add_review [data-captcha-review=\"Y\"]').attr('id')!==undefined)
			{
			
				var CapId = grecaptcha.render($('#add_review [data-captcha-review=\"Y\"]').attr('id'), {
					'sitekey' : '".$arResult['RECAPTCHA2_SITE_KEY']."'
				});
	
				$('#captcha-ids-reviews').html(CapId);
			}
					
		}	
	</script>
");
}
?>
<div class="sotbit_reviews_personal_reviews">





<p class="error-delete"></p>
<p class="success-edit"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PERSONAL_SUCCESS_EDIT") ?></p>
<p class="success-delete"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PERSONAL_SUCCESS_DELETE") ?></p>
	<table class="table-personal-reviews" data-site-dir="<?=SITE_DIR?>" data-sort="" data-by="">
		<thead>
			<tr>
				<th data-sort="date"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PERSONAL_DATE") ?></th>
				<th data-sort="review"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PERSONAL_TITLE") ?></th>
				<th data-sort="rating"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PERSONAL_RATING") ?></th>
				<th data-sort="status"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PERSONAL_STATUS") ?></th>
				<th data-sort="options"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PERSONAL_OPTIONS") ?></th>
				<th data-sort="shows"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PERSONAL_SHOWS") ?></th>
				<th data-sort="bill"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PERSONAL_BILLS") ?> (<?=$arResult['CURRENCY'] ?>)</th>
				<th class="align-center" data-sort="likes"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></th>
				<th class="align-center" data-sort="dislikes"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></th>
			</tr>
		</thead>
		<tbody>
 		<?
		if(isset( $arResult['REVIEWS'] ) && is_array( $arResult['REVIEWS'] ) && $arResult["REVIEWS_CNT"] > 0) {
				foreach( $arResult['REVIEWS'] as $k=>$Review ) {
		?>
		<tr>
			<td class="date" data-name="date" data-value="<?=$k?>"><?=$Review['DATE_CREATION'] ?></td>
			<td class="review" data-name="review" data-value="<?=$Review['ELEMENT_NAME']?> <?=($arResult['USE_TITLE'] && !empty( $Review['TITLE'] ))?$Review['TITLE']:"" ?> <?=$Review['TEXT'] ?>"><a href="<?=$Review['ELEMENT_URL'] ?>"><?=$Review['ELEMENT_NAME'] ?></a>
			<?if($arResult['USE_TITLE'] && !empty( $Review['TITLE'] )) {?>
				<p class="title"><?=$Review['TITLE']?></p>
			
				<?
			}?>
			<div class="text">
				<?=$Review['TEXT']?>
			</div>
			<?if(isset($Review['ANSWER']) && !empty($Review['ANSWER'])): ?>
				<div class="answer">
					<p class="answer-name"><?=$arResult['SITE_NAME'] ?></p>
					<div class="answer-text"><?=$Review['ANSWER'] ?></div>
				</div>
			<?endif; ?>
			</td>
			<td class="rating" data-name="rating" data-value="<?=$Review['RATING']?>">
				<div class="stars star<?=$Review['RATING']?>">
					<?for($i = 1; $i <= $arParams["MAX_RATING"]; ++ $i) {?>
						<i class="fa fa-star star <?=($i<=$Review['RATING'])?'full':'empty'?>"></i>
					<?}?>
				</div>
			</td>
			<td class="status status-<?=$Review['STATUS']?>" data-name="status" data-value="<?=$Review['STATUS']?>">
				<?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PERSONAL_STATUS".$Review['STATUS']) ?>
			</td>
			<td class="options" data-name="options" data-value="<?=$k?>">
				<div class="wrap-options">
					<i class="fa fa-pencil-square-o personal-edit" aria-hidden="true" data-id="<?=$Review['ID'] ?>"></i>
					<i class="fa fa-times personal-delete" aria-hidden="true" data-id="<?=$Review['ID'] ?>" ></i>
				</div>
			</td>
			<td class="shows" data-name="shows" data-value="<?=$Review['SHOWS']?>">
				<?=$Review['SHOWS']?>
			</td>
			<td class="bill" data-name="bill" data-value="<?=$Review['BILL']?>">
				<?=$Review['BILL']?>
			</td>
			<td class="likes" data-name="likes" data-value="<?=$Review['LIKES']?>">
				<?=$Review['LIKES']?>
			</td>
			<td class="dislikes" data-name="dislikes" data-value="<?=$Review['DISLIKES']?>">
				<?=$Review['DISLIKES']?>
			</td>
		</tr>
		<?}?>
	<?} ?>
	</tbody>
	</table>




<div class="reviews-popup" id="modal-reviews-popup">
<p class="error"></p>
	<span id="modal_close"><i class="fa fa-times" aria-hidden="true"></i></span>
				<div class="review-add-block">
				<p class="add-check-error" style="display:none;"></p>
				<form  class="review" id="add_review" action="javascript:void(null);" enctype="multipart/form-data">
				<div class='rating_selection'>
							<?for($i=1;$i<=$arParams['MAX_RATING'];++$i):?>
								<input id="star-<?=$i?>" type="radio" name="rating" value="<?=$i?>" <?=($i==$arParams['DEFAULT_RATING_ACTIVE'])?'checked':'';?>/>
								<label title="" for="star-<?=$i?>"></label>
								<?endfor;?>
						</div>
						<input type="hidden" name="ID" value="" />
						<input type="hidden" name="MODERATION" value="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_MODERATION_".SITE_ID, "")?>" />
						<input type="hidden" name="SPAM_ERROR" value="<?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_SPAM_ERROR")?>" />
						<input type="hidden" name="VIDEO_ERROR" value="<?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_VIDEO_ERROR")?>" />
						<input type="hidden" name="PRESENTATION_ERROR" value="<?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_PRESENTATION_ERROR")?>" />
						<input type="hidden" name="MAX_RATING" value="<?=$arParams['MAX_RATING']?>" />
						<input type="hidden" name="NOTICE_EMAIL" value="<?=$arParams['NOTICE_EMAIL']?>" />
						<input type="hidden" name="TEMPLATE" value="<?=$templateName?>" />
						<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_TITLE_".SITE_ID, "")=='Y'):?>
							<p class="title"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_TITLE")?></p>
							<p class="title-example"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_TITLE_EXAMPLE")?></p>
							
						<input type="text" name="title" value="" maxlength="255" class="title" />
						<?endif;?>
						<p class="text"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_TEXT")?></p>

						<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_EDITOR_".SITE_ID, "")=="Y"): ?>
							<span id="review-editor">
								<?$APPLICATION->IncludeComponent( "bitrix:main.post.form", "", Array(
									'BUTTONS' => array(),
									'PARSER' => array(),
									'PIN_EDITOR_PANEL'=>'N',
									'TEXT' => array(
										'SHOW'=>'Y',
										'VALUE'=>"",
										'NAME'=>'text'
									) 
								) );?>
							</span>
						<?else: ?>
						<textarea name="text" id="contentbox" maxlength="<?=$arParams["TEXTBOX_MAXLENGTH"]?>" ></textarea>
						<p class="count"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_ADD_COUNT")?> <span class="count-now">0</span> / <?=$arParams["TEXTBOX_MAXLENGTH"]?></p>	
						<?endif; ?>
						<p class="recommendated"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_RECOMMENDATED")?></p>
						<div class="radio">
							<input type="radio" name="RECOMMENDATED" value="Y" checked><span class="radio-label"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_RECOMMENDATED_YES")?></span>
						</div>
						<div class="radio">
							<input type="radio" name="RECOMMENDATED" value="N"><span class="radio-label"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_RECOMMENDATED_NO")?></span>
						</div>
						<?if(isset($arResult['ADD_FIELDS']) && is_array($arResult['ADD_FIELDS'])):?>
							<?foreach($arResult['ADD_FIELDS'] as $key=>$value):?>
								<p class="add-field-title"><?=$value['TITLE']?>:</p>
								<?if($value['TYPE']=='textbox'):?>
						<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_EDITOR_".SITE_ID, "")=="Y"): ?>
						<?$APPLICATION->IncludeComponent( "bitrix:main.post.form", "", Array(
		'BUTTONS' => array(),
		'PARSER' => array(),
		'PIN_EDITOR_PANEL'=>'N',
		'TEXT' => array(
				'SHOW'=>'Y',
				'VALUE'=>"",
				'NAME'=>'AddFields_'.$key
		) 
) );?>
						<?else: ?>
									<textarea name="AddFields_<?=$key?>" id="<?=$key?>"></textarea>
									<?endif;?>
									<?endif;?>
								<?endforeach;?>
							<?endif;?>
						<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_".SITE_ID, "")=='Y'):?>
							<div class="add-photo">
								<input type="file" multiple="" id="photo" accept="image/jpeg,image/png">
								<span id='add-photo-button'><i class="fa fa-plus"></i><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_ADD_IMAGES")?></span>
							</div>
							<ul id="preview-photo" data-max-size="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_MAX_IMAGE_SIZE_".SITE_ID, "2")?>" data-thumb-width="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_THUMB_WIDTH_".SITE_ID, "150")?>" data-thumb-height="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_THUMB_HEIGHT_".SITE_ID, "150")?>" data-max-count-images="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_MAX_COUNT_IMAGES_".SITE_ID, "5")?>" data-error-max-size="<?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_ERROR_IMAGE_MAX_SIZE")?>" data-error-type="<?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_ERROR_IMAGE_TYPE")?>" data-error-max-count="<?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_ERROR_MAX_COUNT_IMAGES")?>">
							</ul>
						<?endif;?>
						<div style="clear:both"></div>
						
						<?if($arResult['VIDEO_ALLOW']=="Y"): ?>
							<p class="title-video"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_VIDEO")?></p>
							<input type="text" name="video" value="" maxlength="255" class="video" />
						<?endif; ?>
						<?if($arResult['PRESENTATION_ALLOW']=="Y"): ?>
							<p class="title-presentation"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_PRESENTATION")?></p>
							<input type="text" name="presentation" value="" maxlength="255" class="presentation" />
						<?endif; ?>
						
							<?if(isset($arResult['RECAPTCHA2_SITE_KEY']) && !empty($arResult['RECAPTCHA2_SITE_KEY'])): ?>
								<div data-captcha-review="Y" id="recaptcha-review-<?=$arResult["REVIEWS_CNT"]?>" class="captcha-block"></div>
							<?endif; ?>
						<input type="submit" name="submit" id="review_submit" value="<?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_SUBMIT_VALUE")?>" />
					</form>
</div>

	</div>
	<div id="reviews-personal-overlay"></div>
</div>

<div id="PersonalReviewsParams" style="display:none;"><?=serialize(array_merge($arParams,array('TEMPLATE'=>$templateName)))?></div>


<div id="captcha-reviews" style="display:none;"><?=$arResult['REVIEWS_RECAPTCHA2_SITE_KEY'] ?></div>
<div id="captcha-ids-reviews" style="display:none;"></div>

<?$frame->end();?>


