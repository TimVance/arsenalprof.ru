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
			if($('#add_comment [data-captcha-comment=\"Y\"]').attr('id')!==undefined)
			{
			
				var CapId = grecaptcha.render($('#add_comment [data-captcha-comment=\"Y\"]').attr('id'), {
					'sitekey' : '".$arResult['RECAPTCHA2_SITE_KEY']."'
				});
	
				$('#captcha-ids-comments').html(CapId);
			}
					
		}	
	</script>
");
}
?>
<div class="sotbit_comments_personal_comments">
<p class="error-delete"></p>
<p class="success-edit"><?=Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_PERSONAL_SUCCESS_EDIT") ?></p>
<p class="success-delete"><?=Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_PERSONAL_SUCCESS_DELETE") ?></p>
	<table class="table-personal-comments" data-site-dir="<?=SITE_DIR?>" data-sort="" data-by="">
		<thead>
			<tr>
				<th data-sort="date"><?=Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_PERSONAL_DATE") ?></th>
				<th data-sort="comment"><?=Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_PERSONAL_TITLE") ?></th>
				<th data-sort="status"><?=Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_PERSONAL_STATUS") ?></th>
				<th data-sort="options"><?=Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_PERSONAL_OPTIONS") ?></th>
				<th data-sort="shows"><?=Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_PERSONAL_SHOWS") ?></th>
				<th data-sort="bill"><?=Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_PERSONAL_BILLS") ?> (<?=$arResult['CURRENCY'] ?>)</th>
			</tr>
		</thead>
		<tbody>
 		<?
		if(isset( $arResult['COMMENTS'] ) && is_array( $arResult['COMMENTS'] ) && $arResult["COMMENTS_CNT"] > 0) {
				foreach( $arResult['COMMENTS'] as $k=>$Comment ) {
		?>
		<tr>
			<td class="date" data-name="date" data-value="<?=$k?>"><?=$Comment['DATE_CREATION'] ?></td>
			<td class="comment" data-name="comment" data-value="<?=$Comment['ELEMENT_NAME']?> <?=$Comment['TEXT'] ?>"><a href="<?=$Comment['ELEMENT_URL'] ?>"><?=$Comment['ELEMENT_NAME'] ?></a>
			<div class="text">
				<?=$Comment['TEXT']?>
			</div>
			</td>
			<td class="status status-<?=$Comment['STATUS']?>" data-name="status" data-value="<?=$Comment['STATUS']?>">
				<?=Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_PERSONAL_STATUS".$Comment['STATUS']) ?>
			</td>
			<td class="options" data-name="options" data-value="<?=$k?>">
				<div class="wrap-options">
					<i class="fa fa-pencil-square-o personal-edit" aria-hidden="true" data-id="<?=$Comment['ID'] ?>"></i>
					<i class="fa fa-times personal-delete" aria-hidden="true" data-id="<?=$Comment['ID'] ?>" ></i>
				</div>
			</td>
			<td class="shows" data-name="shows" data-value="<?=$Comment['SHOWS']?>">
				<?=$Comment['SHOWS']?>
			</td>
			<td class="bill" data-name="bill" data-value="<?=$Comment['BILL']?>">
				<?=$Comment['BILL']?>
			</td>
		</tr>
		<?}?>
	<?} ?>
	</tbody>
	</table>




<div class="comments-popup" id="modal-comments-popup">
<p class="error"></p>
	<span id="modal_close"><i class="fa fa-times" aria-hidden="true"></i></span>
				<div class="comment-add-block">
				<p class="add-check-error" style="display:none;"></p>
				<form  class="comment" id="add_comment" action="javascript:void(null);" enctype="multipart/form-data">
				<div class='rating_selection'>
							<?for($i=1;$i<=$arParams['MAX_RATING'];++$i):?>
								<input id="star-<?=$i?>" type="radio" name="rating" value="<?=$i?>" <?=($i==$arParams['DEFAULT_RATING_ACTIVE'])?'checked':'';?>/>
								<label title="" for="star-<?=$i?>"></label>
								<?endfor;?>
						</div>
						<input type="hidden" name="ID" value="" />
						<input type="hidden" name="MODERATION" value="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "COMMENTS_MODERATION_".SITE_ID, "")?>" />
						<input type="hidden" name="SPAM_ERROR" value="<?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_SPAM_ERROR")?>" />
						<input type="hidden" name="NOTICE_EMAIL" value="<?=$arParams['NOTICE_EMAIL']?>" />
						<input type="hidden" name="TEMPLATE" value="<?=$templateName?>" />
						<p class="text"><?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_TEXT")?></p>

						<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "COMMENTS_EDITOR_".SITE_ID, "")=="Y"): ?>
							<span id="comment-editor">
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
						<p class="count"><?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_ADD_COUNT")?> <span class="count-now">0</span> / <?=$arParams["TEXTBOX_MAXLENGTH"]?></p>	
						<?endif; ?>
						<div style="clear:both"></div>
						
							<?if(isset($arResult['RECAPTCHA2_SITE_KEY']) && !empty($arResult['RECAPTCHA2_SITE_KEY'])): ?>
								<div data-captcha-comment="Y" id="recaptcha-comment-<?=$arResult["COMMENTS_CNT"]?>" class="captcha-block"></div>
							<?endif; ?>
						<input type="submit" name="submit" id="comment_submit" value="<?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_SUBMIT_VALUE")?>" />
					</form>
</div>

	</div>
	<div id="comments-personal-overlay"></div>
</div>

<div id="PersonalCommentsParams" style="display:none;"><?=serialize(array_merge($arParams,array('TEMPLATE'=>$templateName)))?></div>


<div id="captcha-comments" style="display:none;"><?=$arResult['COMMENTS_RECAPTCHA2_SITE_KEY'] ?></div>
<div id="captcha-ids-comments" style="display:none;"></div>

<?$frame->end();?>


