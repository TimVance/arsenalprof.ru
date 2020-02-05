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
			if($('#add_question [data-captcha-question=\"Y\"]').attr('id')!==undefined)
			{
			
				var CapId = grecaptcha.render($('#add_question [data-captcha-question=\"Y\"]').attr('id'), {
					'sitekey' : '".$arResult['RECAPTCHA2_SITE_KEY']."'
				});
	
				$('#captcha-ids-questions').html(CapId);
			}
					
		}	
	</script>
");
}
?>
<div class="sotbit_questions_personal_questions">
<p class="error-delete"></p>
<p class="success-edit"><?=Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_PERSONAL_SUCCESS_EDIT") ?></p>
<p class="success-delete"><?=Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_PERSONAL_SUCCESS_DELETE") ?></p>
	<table class="table-personal-questions" data-site-dir="<?=SITE_DIR?>" data-sort="" data-by="">
		<thead>
			<tr>
				<th data-sort="date"><?=Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_PERSONAL_DATE") ?></th>
				<th data-sort="question"><?=Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_PERSONAL_TITLE") ?></th>
				<th data-sort="status"><?=Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_PERSONAL_STATUS") ?></th>
				<th data-sort="options"><?=Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_PERSONAL_OPTIONS") ?></th>
				<th data-sort="shows"><?=Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_PERSONAL_SHOWS") ?></th>
				<th data-sort="bill"><?=Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_PERSONAL_BILLS") ?> (<?=$arResult['CURRENCY'] ?>)</th>
			</tr>
		</thead>
		<tbody>
 		<?
		if(isset( $arResult['QUESTIONS'] ) && is_array( $arResult['QUESTIONS'] ) && $arResult["QUESTIONS_CNT"] > 0) {
				foreach( $arResult['QUESTIONS'] as $k=>$Question ) {
		?>
		<tr>
			<td class="date" data-name="date" data-value="<?=$k?>"><?=$Question['DATE_CREATION'] ?></td>
			<td class="question" data-name="question" data-value="<?=$Question['ELEMENT_NAME']?> <?=$Question['QUESTION'] ?>"><a href="<?=$Question['ELEMENT_URL'] ?>"><?=$Question['ELEMENT_NAME'] ?></a>
			<div class="text">
				<?=$Question['QUESTION']?>
			</div>
			<?if(isset($Question['ANSWER']) && !empty($Question['ANSWER'])): ?>
				<div class="answer">
					<p class="answer-name"><?=$arResult['SITE_NAME'] ?></p>
					<div class="answer-text"><?=$Question['ANSWER'] ?></div>
				</div>
			<?endif; ?>
			</td>
			<td class="status status-<?=$Question['STATUS']?>" data-name="status" data-value="<?=$Question['STATUS']?>">
				<?=Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_PERSONAL_STATUS".$Question['STATUS']) ?>
			</td>
			<td class="options" data-name="options" data-value="<?=$k?>">
				<div class="wrap-options">
					<i class="fa fa-pencil-square-o personal-edit" aria-hidden="true" data-id="<?=$Question['ID'] ?>"></i>
					<i class="fa fa-times personal-delete" aria-hidden="true" data-id="<?=$Question['ID'] ?>" ></i>
				</div>
			</td>
			<td class="shows" data-name="shows" data-value="<?=$Question['SHOWS']?>">
				<?=$Question['SHOWS']?>
			</td>
			<td class="bill" data-name="bill" data-value="<?=$Question['BILL']?>">
				<?=$Question['BILL']?>
			</td>
		</tr>
		<?}?>
	<?} ?>
	</tbody>
	</table>




<div class="questions-popup" id="modal-questions-popup">
<p class="error"></p>
	<span id="modal_close"><i class="fa fa-times" aria-hidden="true"></i></span>
				<div class="question-add-block">
				<p class="add-check-error" style="display:none;"></p>
				<form  class="question" id="add_question" action="javascript:void(null);" enctype="multipart/form-data">
				<div class='rating_selection'>
							<?for($i=1;$i<=$arParams['MAX_RATING'];++$i):?>
								<input id="star-<?=$i?>" type="radio" name="rating" value="<?=$i?>" <?=($i==$arParams['DEFAULT_RATING_ACTIVE'])?'checked':'';?>/>
								<label title="" for="star-<?=$i?>"></label>
								<?endfor;?>
						</div>
						<input type="hidden" name="ID" value="" />
						<input type="hidden" name="MODERATION" value="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "QUESTIONS_MODERATION_".SITE_ID, "")?>" />
						<input type="hidden" name="SPAM_ERROR" value="<?=GetMessage(CSotbitReviews::iModuleID."_QUESTIONS_SPAM_ERROR")?>" />
						<input type="hidden" name="NOTICE_EMAIL" value="<?=$arParams['NOTICE_EMAIL']?>" />
						<input type="hidden" name="TEMPLATE" value="<?=$templateName?>" />
						<p class="text"><?=GetMessage(CSotbitReviews::iModuleID."_QUESTIONS_TEXT")?></p>

						<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "QUESTIONS_EDITOR_".SITE_ID, "")=="Y"): ?>
							<span id="question-editor">
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
						<p class="count"><?=GetMessage(CSotbitReviews::iModuleID."_QUESTIONS_ADD_COUNT")?> <span class="count-now">0</span> / <?=$arParams["TEXTBOX_MAXLENGTH"]?></p>	
						<?endif; ?>
						<div style="clear:both"></div>
						
							<?if(isset($arResult['RECAPTCHA2_SITE_KEY']) && !empty($arResult['RECAPTCHA2_SITE_KEY'])): ?>
								<div data-captcha-question="Y" id="recaptcha-question-<?=$arResult["QUESTIONS_CNT"]?>" class="captcha-block"></div>
							<?endif; ?>
						<input type="submit" name="submit" id="question_submit" value="<?=GetMessage(CSotbitReviews::iModuleID."_QUESTIONS_SUBMIT_VALUE")?>" />
					</form>
</div>

	</div>
	<div id="questions-personal-overlay"></div>
</div>

<div id="PersonalQuestionsParams" style="display:none;"><?=serialize(array_merge($arParams,array('TEMPLATE'=>$templateName)))?></div>


<div id="captcha-questions" style="display:none;"><?=$arResult['QUESTIONS_RECAPTCHA2_SITE_KEY'] ?></div>
<div id="captcha-ids-questions" style="display:none;"></div>

<?$frame->end();?>


