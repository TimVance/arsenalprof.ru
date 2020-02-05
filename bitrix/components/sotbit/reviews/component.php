<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?	
use Bitrix\Main\Loader;
use Sotbit\Reviews\ReviewsTable;
use Sotbit\Reviews\ReviewsfieldsTable;
use Sotbit\Reviews\CommentsTable;
use Sotbit\Reviews\QuestionsTable;

if(!Loader::includeModule('sotbit.reviews'))
	return false;

global $APPLICATION;
global $USER;
global $CACHE_MANAGER;


if (!isset($arParams["SHOW_REVIEWS"]))
	$arParams["SHOW_REVIEWS"] = 'Y';
if (!isset($arParams["SHOW_COMMENTS"]))
	$arParams["SHOW_COMMENTS"] = 'Y';
if (!isset($arParams["SHOW_QUESTIONS"]))
	$arParams["SHOW_QUESTIONS"] = 'Y';
if (!isset($arParams["FIRST_ACTIVE"]))
	$arParams["FIRST_ACTIVE"] = 1;
if (!isset($arParams["MAX_RATING"]))
	$arParams["MAX_RATING"] = 5;
if (!isset($arParams["DEFAULT_RATING_ACTIVE"]))
	$arParams["DEFAULT_RATING_ACTIVE"] = 3;
if (!isset($arParams["PRIMARY_COLOR"]))
	$arParams["PRIMARY_COLOR"] = "#a76e6e";
if (!isset($arParams["BUTTON_BACKGROUND"]))
	$arParams["BUTTON_BACKGROUND"] = "#dbbfb9";
if (!isset($arParams["ADD_REVIEW_PLACE"]))
	$arParams["ADD_REVIEW_PLACE"] = 1;
if (!isset($arParams["REVIEWS_TEXTBOX_MAXLENGTH"]))
	$arParams["REVIEWS_TEXTBOX_MAXLENGTH"] = 100;
if (!isset($arParams["COMMENTS_TEXTBOX_MAXLENGTH"]))
	$arParams["COMMENTS_TEXTBOX_MAXLENGTH"] = 100;
if (!isset($arParams["QUESTIONS_TEXTBOX_MAXLENGTH"]))
	$arParams["QUESTIONS_TEXTBOX_MAXLENGTH"] = 100;
if (!isset($arParams["INIT_JQUERY"]))
	$arParams["INIT_JQUERY"] = "Y";
if (!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;
if (!isset($arParams["CACHE_TYPE"]))
	$arParams["CACHE_TYPE"] = "A";
if (!isset($arParams["DATE_FORMAT"]))
	$arParams["DATE_FORMAT"] = "d F Y, H:i";
	
if($arParams["INIT_JQUERY"]=="Y")
	CJSCore::Init(array("jquery"));
	
$IDx=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_ID_ELEMENT_".SITE_ID, "ID_ELEMENT");

 
$obCache = Bitrix\Main\Data\Cache::createInstance();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_'.$arParams['ID_ELEMENT'];
$cachePath = '/SotbitReviews/'.$arParams['ID_ELEMENT'];
if($obCache->InitCache($life_time, $cache_id, $cachePath)) 
{
	$cache = $obCache->GetVars();
	if(isset($cache['SOTBIT_REVIEWS']))
	{
		CSotbitReviews::SetCacheValues('',$cache);
		$arResult=$cache['SOTBIT_REVIEWS'];
	}
}
else
{
	if($arParams["SHOW_REVIEWS"]=='Y')
	{
		CSotbitReviews::setReviewsCnt($arParams['ID_ELEMENT'],$arParams["MAX_RATING"],SITE_ID);
		$arResult=CSotbitReviews::getReviewsCnt();

	}
	if($arParams["SHOW_COMMENTS"]=='Y')
	{
		$IDx=COption::GetOptionString(CSotbitReviews::iModuleID, "COMMENTS_ID_ELEMENT_".SITE_ID, "ID_ELEMENT");
		$arResult["COMMENTS_CNT"]=CommentsTable::GetCount(array('='.$IDx => $arParams['ID_ELEMENT'],'=ACTIVE'=>'Y','=MODERATED'=>'Y'));
	}
	if($arParams["SHOW_QUESTIONS"]=='Y')
	{
		$IDx=COption::GetOptionString(CSotbitReviews::iModuleID, "QUESTIONS_ID_ELEMENT_".SITE_ID, "ID_ELEMENT");
		$arResult["QUESTIONS_CNT"]=QuestionsTable::GetCount(array('='.$IDx => $arParams['ID_ELEMENT'],'=ACTIVE'=>'Y','=MODERATED'=>'Y'));
	}
	
	$arResult['REVIEWS_RECAPTCHA2_SITE_KEY']=COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_RECAPTCHA2_SITE_KEY_".SITE_ID, "" );
	$arResult['COMMENTS_RECAPTCHA2_SITE_KEY']=COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_RECAPTCHA2_SITE_KEY_".SITE_ID, "" );
	$arResult['QUESTIONS_RECAPTCHA2_SITE_KEY']=COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_RECAPTCHA2_SITE_KEY_".SITE_ID, "" );

	CSotbitReviews::SetCacheValues('SOTBIT_REVIEWS',$arResult);
}
$this->IncludeComponentTemplate();
$CacheValues = CSotbitReviews::GetCacheValues('');

if ($obCache->StartDataCache () && is_array($CacheValues) && sizeof($CacheValues)>0 && $arParams["CACHE_TIME"]>0)
{
	$CACHE_MANAGER->ClearByTag( "sotbit_reviews_".$arParams['ID_ELEMENT']);
	$CACHE_MANAGER->StartTagCache($cachePath);
	$CACHE_MANAGER->RegisterTag('sotbit_reviews_'.$arParams['ID_ELEMENT']);
	$CACHE_MANAGER->EndTagCache();
	$obCache->EndDataCache($CacheValues);
}


?>