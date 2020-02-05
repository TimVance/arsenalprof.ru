<?
if(!defined( "B_PROLOG_INCLUDED" )||B_PROLOG_INCLUDED!==true)
	die();
use Bitrix\Main\Localization\Loc;
global $APPLICATION;
global $USER;
?>
<?$frame=$this->createFrame()->begin();?>
<div class="sotbit_reviews_user_comments">
	<div class="row-default user-row">
		<div class="col user">
			<div class="avatar">
				<div class="avatar-inner">
					<img src="<?=$arResult['USER_PHOTO']?>" title="<?=$arResult['USER_NAME']?>" alt="<?=$arResult['USER_NAME']?>" />
				</div>
			</div>
			<div class="user-info">
				<p class="user-param-row-default name"><?=$arResult['USER_NAME']?></p>
				<?if(isset($arResult['USER_AGE']) && !empty($arResult['USER_AGE'])): ?>
					<p class="user-param-row age"><span><?= Loc::getMessage(CSotbitReviews::iModuleID."_USER_AGE") ?></span><?=$arResult['USER_AGE']?></p>
				<?endif; ?>
				<?if(isset($arResult['USER_COUNTRY']) && !empty($arResult['USER_COUNTRY'])): ?>
					<p class="user-param-row country"><span><?= Loc::getMessage(CSotbitReviews::iModuleID."_USER_COUNTRY") ?></span><?=$arResult['USER_COUNTRY']?></p>
				<?endif; ?>
			</div>
		</div>
	</div>
		<div class="row-default">
		<div class="col">
			<p class="cnt"><?= Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_CNT") ?>&nbsp;<?=$arResult["COMMENTS_CNT"] ?></p>
		</div>
	</div>
	<div class="row-default comments-user-list" id="comments-user-list" >
		<div class="col">
			<?
			if(isset( $arResult['COMMENTS'] ) && is_array( $arResult['COMMENTS'] ) && $arResult["COMMENTS_CNT"]>0 )
			{
				foreach( $arResult['COMMENTS'] as $Comment )
				{
			?>
				<div class="item row-default">			
					<div class="col item-col">
						<div class="border">	
							<div class="row-default">
								<div class="col">
									<div class="element">
										<a href="<?=$Comment['ELEMENT_URL'] ?>"><?=$Comment['ELEMENT_NAME'] ?></a>
									</div>
								</div>
							</div>
						<div class="row-default">
							<div class="date-creation col">
								<?=$Comment['DATE_CREATION'];?>
							</div>
						</div>
						<div class="row-default">
							<div class="col text">
								<?=$Comment['TEXT']?>
							</div>
						</div>
					</div>
				</div>
			</div>
					<?
				}
			}
			else
			{
				?>
				<p><?=Loc::getMessage(CSotbitReviews::iModuleID."_COMMENTS_NO_RESULTS")?></p>
				<?}?>
		</div>
	<style>
.comments-user-list .element a {
	color: <?=$arParams['PRIMARY_COLOR']?>
}
</style>
	<?$frame->end();?>
</div>
</div>