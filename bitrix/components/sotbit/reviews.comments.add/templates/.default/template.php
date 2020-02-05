<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
  
$frame = $this->createFrame()->begin();
global $USER;
global $APPLICATION;

?>
<div class="success" style="display: none;"><?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_SUCCESS_TEXT")?></div>
<div class="add-comments">
	<div class="spoiler">
		<div class="spoiler-input">
				<?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_SPOILER_TITLE")?>
			</div>
	</div>
	<div class="spoiler-comments-body">
			<?

if($USER->IsAuthorized() || COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_REGISTER_USERS_" . SITE_ID, "" ) != 'Y') :
				if($arResult['BAN'] != "Y") :

					?>
					<?if($arResult['CAN_REPEAT']==1):?>
				<div class="comments-add-block">
			<p class="add-check-error" style="display: none;"></p>
			<form class="comment" id="add_comment"
				action="javascript:void(null);">
				<input type="hidden" name="ID_ELEMENT"
					value="<?=$arParams['ID_ELEMENT']?>" /> <input type="hidden"
					name="MODERATION"
					value="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "COMMENTS_MODERATION_".SITE_ID, "")?>" />
				<input type="hidden" name="SITE_DIR" value="<?=SITE_DIR?>" />
				<input type="hidden" name="PAGE_URL" value="<?=$APPLICATION->GetCurPage()?>" />
				<input type="hidden" name="SPAM_ERROR" value="<?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_SPAM_ERROR")?>" />
				<input
					type="hidden" name="PARENT" value="<?=$arParams['PARENT']?>" /> <input
					type="hidden" name="TEMPLATE" value="<?=$templateName?>" /> <input
					type="hidden" name="PRIMARY_COLOR"
					value="<?=$arParams['PRIMARY_COLOR']?>" /> <input type="hidden"
					name="TEXTBOX_MAXLENGTH"
					value="<?=$arParams['TEXTBOX_MAXLENGTH']?>" /> <input type="hidden"
					name="BUTTON_BACKGROUND"
					value="<?=$arParams['BUTTON_BACKGROUND']?>" /> <input type="hidden"
					name="NOTICE_EMAIL" value="<?=$arParams['NOTICE_EMAIL']?>" />
				<p class="text"><?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_TEXT")?></p>
				<span id="comments-editor">
						<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "COMMENTS_EDITOR_".SITE_ID, "")=="Y"): ?>
						<?

$APPLICATION->IncludeComponent( "bitrix:main.post.form", "", Array(
								'BUTTONS' => array(),
								'PARSER' => array(),
								'PIN_EDITOR_PANEL' => 'N',
								'TEXT' => array(
										'SHOW' => 'Y',
										'VALUE' => "",
										'NAME' => 'text'
								)
						) );
						?>

						<?else: ?>
				<textarea name="text" id="contentbox"
						maxlength="<?=$arParams["TEXTBOX_MAXLENGTH"]?>"></textarea>
					<p class="count"><?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_ADD_COUNT")?> <span
							class="count-now">0</span> / <?=$arParams["TEXTBOX_MAXLENGTH"]?></p>
						<?endif; ?>
						</span>

<?if(isset($arResult['RECAPTCHA2_SITE_KEY']) && !empty($arResult['RECAPTCHA2_SITE_KEY'])): ?>
								<div id="recaptcha-comment-0" class="captcha-block"></div>
<?endif; ?>


				<input type="submit" name="submit" id="comment_submit"
					value="<?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_SUBMIT_VALUE")?>" />
				<input type="button"
					value="<?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_ADD_CANCEL")?>"
					id="reset-form">
			</form>
		</div>


											<?else: ?>

					<?if($arResult['CAN_REPEAT']==0): ?>
						<p class="not-error"><?=GetMessage(CSotbitReviews::iModuleID."_REPEAT")?></p>
					<?else: ?>
						<p class="not-error"><?=GetMessage(CSotbitReviews::iModuleID."_REPEAT_TIME").' '.$arResult['CAN_REPEAT']?></p>
					<?endif; ?>

		<?endif; ?>
		<?else:?>
	<p class="not-error not-ban-error"><?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_USER_BAN_TITLE")?></p>
	<?if(isset($arResult['REASON']) && !empty($arResult['REASON'])): ?>
		<p class="reason-title"><?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_USER_BAN_REASON_TITLE")?></p>
		<p class="reason-text"><?=$arResult['REASON']?></p>
	<?endif; ?>
<?endif;?>
				<?else:?>
				<div class="auth-error">
						<?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_NO_AUTH")?>
				</div>
		<div class="forms">
			<div class="form-auth">
				<p id="auth-title"><?=GetMessage(CSotbitReviews::iModuleID."_AUTH_TITLE").SITE_SERVER_NAME?></p>
				<p id="auth_comment-check-error" style="display: none;"></p>
						<?

				$APPLICATION->IncludeComponent( "bitrix:system.auth.form", "", array(
						"REGISTER_URL" => SITE_DIR . "login/",
						"FORGOT_PASSWORD_URL" => SITE_DIR . "login/?forgot_password=yes",
						"PROFILE_URL" => SITE_DIR . "personal/",
						"SHOW_ERRORS" => "Y"
				), $component );
						
				?>
					</div>
			<div class="form-reg">
				<p id="register-title"><?=GetMessage(CSotbitReviews::iModuleID."_REGISTER_TITLE")?></p>
				<p id="registration_comment-check-error" style="display: none;"></p>
					<?if( COption::GetOptionString(CSotbitReviews::iModuleID, "COMMENTS_ANONYM_REG_".SITE_ID, "")=='Y'): ?>
						<?$APPLICATION->IncludeComponent("sotbit:reviews.anonymregister","",Array(
							"USER_GROUP" => "",
						),
						$component
					);?>
					<?else: ?>
						<?
						if($arParams['AJAX'] != 'Y')
						{
				$APPLICATION->IncludeComponent( "bitrix:main.register", "", Array(
						"USER_PROPERTY_NAME" => "",
						"SEF_MODE" => "Y",
						"SHOW_FIELDS" => array(
								'NAME',
								'LAST_NAME'
						),
						"REQUIRED_FIELDS" => Array(),
						"AUTH" => "Y",
						"USE_BACKURL" => "N",
						"SUCCESS_PAGE" => "",
						"SET_TITLE" => "N",
						"USER_PROPERTY" => Array(),
						"SEF_FOLDER" => "/",
						"VARIABLE_ALIASES" => Array()
				), $component );
						}
				?>
				<?endif; ?>
					</div>
		</div>
				<?endif;?>
		</div>
</div>
<style>
.add-comments .spoiler-input {
	background: <?=$arParams['BUTTON_BACKGROUND']?>;
}

#comments-body .add-check-error {
	color: <?=$arParams['PRIMARY_COLOR']?>;
}
</style>
<?$frame->end();?>