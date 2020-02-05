<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\ReviewsTable;

if (! Loader::includeModule ( 'sotbit.reviews' ) || ! Loader::includeModule ( 'iblock' ))
	return false;

global $APPLICATION;
global $USER;
global $CACHE_MANAGER;
if (! isset ( $arParams["MAX_RATING"] ))
	$arParams["MAX_RATING"] = 5;
if (! isset ( $arParams["PRIMARY_COLOR"] ))
	$arParams["PRIMARY_COLOR"] = "#a76e6e";
if (! isset ( $arParams["CACHE_TIME"] ))
	$arParams["CACHE_TIME"] = 36000000;
if (! isset ( $arParams["CACHE_TYPE"] ))
	$arParams["CACHE_TYPE"] = "A";

$IDx = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
$obCache = Bitrix\Main\Data\Cache::createInstance ();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_' . $arParams['ID_ELEMENT'];
$cachePath = '/SotbitReviews';

$CacheValues = CSotbitReviews::GetCacheValues('');

if(isset($CacheValues['SOTBIT_REVIEWS_STATISTICS']))
{
	$arResult = $CacheValues['SOTBIT_REVIEWS_STATISTICS'];
}

if(!isset($arResult) || !is_array($arResult) || sizeof($arResult)==0)
{
	$arResult = CSotbitReviews::getReviewsCnt ();
	if(!isset($arResult['SUM_CNT_REVIEWS']))
	{
		CSotbitReviews::setReviewsCnt ( $arParams['ID_ELEMENT'], $arParams["MAX_RATING"], SITE_ID );
		$arResult = CSotbitReviews::getReviewsCnt ();
	}

	if ($arResult['SUM_CNT_REVIEWS'] > 0)
		$arResult['RECOMMENDATED'] = round ( (ReviewsTable::GetCount ( array (
				'=RECOMMENDATED' => 'Y',
				'=ACTIVE' => 'Y',
				'=MODERATED' => 'Y',
				'=' . $IDx => $arParams["ID_ELEMENT"] 
		) ) / $arResult['SUM_CNT_REVIEWS']) * 100 );
	CSotbitReviews::SetCacheValues('SOTBIT_REVIEWS_STATISTICS',$arResult);
}

$this->IncludeComponentTemplate ();

?>