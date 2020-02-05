<?
if(!defined( "B_PROLOG_INCLUDED" )||B_PROLOG_INCLUDED!==true)
	die();
use Bitrix\Main\Localization\Loc;
global $APPLICATION;
global $USER;
?>
<?$frame=$this->createFrame()->begin();?>
<div class="sotbit_reviews_user_reviews">
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
			<p class="cnt"><?= Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_CNT") ?>&nbsp;<?=$arResult["REVIEWS_CNT"] ?></p>
		</div>
	</div>
		<?
$APPLICATION->IncludeComponent(
	"sotbit:reviews.userreviews.filter",
	"",
	array(
		'MAX_RATING'=>$arParams['MAX_RATING'],
		'ID_USER'=>$arParams['ID_USER'],
		"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
		'CACHE_TIME'=>$arParams["CACHE_TIME"],
		'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
	),
	$component
);
?>
<?$APPLICATION->IncludeComponent(

		"sotbit:reviews.userreviews.list",
		"",
		array(
				'MAX_RATING'=>$arParams['MAX_RATING'],
				'ID_USER'=>$arParams['ID_USER'],
				"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
				'CACHE_TIME'=>"36000000",
				"DATE_FORMAT"=>$arParams['DATE_FORMAT'],
		),
		$component
		);

?>
</div>