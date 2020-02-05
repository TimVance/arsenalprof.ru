<?
if(!defined( "B_PROLOG_INCLUDED" )||B_PROLOG_INCLUDED!==true)
	die();
use Bitrix\Main\Localization\Loc;
global $APPLICATION;
global $USER;
?>
<?$frame=$this->createFrame()->begin();?>
<div class="sotbit_reviews_user_questions">
	<div class="row user-row">
		<div class="col-sm-24 user">
			<div class="avatar">
				<div class="avatar-inner">
					<img src="<?=$arResult['USER_PHOTO']?>" title="<?=$arResult['USER_NAME']?>" alt="<?=$arResult['USER_NAME']?>" />
				</div>
			</div>
			<div class="user-info">
				<p class="user-param-row name"><?=$arResult['USER_NAME']?></p>
				<?if(isset($arResult['USER_AGE']) && !empty($arResult['USER_AGE'])): ?>
					<p class="user-param-row age"><span><?= Loc::getMessage(CSotbitReviews::iModuleID."_USER_AGE") ?></span><?=$arResult['USER_AGE']?></p>
				<?endif; ?>
				<?if(isset($arResult['USER_COUNTRY']) && !empty($arResult['USER_COUNTRY'])): ?>
					<p class="user-param-row country"><span><?= Loc::getMessage(CSotbitReviews::iModuleID."_USER_COUNTRY") ?></span><?=$arResult['USER_COUNTRY']?></p>
				<?endif; ?>
			</div>
		</div>
	</div>
		<div class="row">
		<div class="col-sm-24">
			<p class="cnt"><?= Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_CNT") ?>&nbsp;<?=$arResult["QUESTIONS_CNT"] ?></p>
		</div>
	</div>
	<div class="row questions-user-list" id="questions-user-list" >
		<div class="col-sm-24">
			<?
			if(isset( $arResult['QUESTIONS'] ) && is_array( $arResult['QUESTIONS'] ) && $arResult["QUESTIONS_CNT"]>0 )
			{
				foreach( $arResult['QUESTIONS'] as $Question )
				{
			?>
				<div class="item row">			
					<div class="col-sm-24 item-col">
					<div class="border">	
						<div class="row">
							<div class="col-sm-24">
								<div class="element">
									<a href="<?=$Question['ELEMENT_URL'] ?>"><?=$Question['ELEMENT_NAME'] ?></a>
								</div>
							</div>
						</div>
					<div class="row">
						<div class="date-creation col-sm-24">
							<?=$Question['DATE_CREATION'];?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-24 text">
							<?=$Question['QUESTION']?>
						</div>
					</div>
				<?if(isset($Question['ANSWER']) && !empty($Question['ANSWER'])):?>
					<div class="row shopanswer">
						<div class="col-sm-24 col-shopanswer">
							<div class="avatar">
								<div class="avatar-inner">
									<img class="img-responsive" alt="<?=$arResult['SITE_NAME']?>" title="<?=$arResult['SITE_NAME']?>" src="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "QUESTIONS_ANSWER_IMAGE_".SITE_ID, "")?>">
								</div>
							</div>
							<div class="shopanswer-text">
								<p class="username"><?=$arResult['SITE_NAME']?></p>
								<p class="text"><?=$Question['ANSWER']?></p>
							</div>
						</div>
					</div>
				<?endif;?>
				</div>
</div>
			</div>
					<?
				}
			}
			else
			{
				?>
				<p><?=Loc::getMessage(CSotbitReviews::iModuleID."_QUESTIONS_NO_RESULTS")?></p>
				<?}?>
		</div>
</div>
</div>
	<style>
.questions-user-list .element a {
	color: <?=$arParams['PRIMARY_COLOR']?>
}
</style>
	<?$frame->end();?>
