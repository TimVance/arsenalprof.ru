<?
use Bitrix\Main\Loader;
use Bitrix\Main\Type;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->RestartBuffer();

if(!Loader::includeModule('sotbit.reviews'))
	return false;
	
global $USER;
global $APPLICATION;
if($REQUEST_METHOD == "POST")
{
	$arParams=unserialize($data);
	
	if($active=='reviews')
	{
		$APPLICATION->IncludeComponent(
				"sotbit:reviews.reviews",
				$arParams['TEMPLATE'],
				array(
						'DEFAULT_RATING_ACTIVE'=>$arParams['DEFAULT_RATING_ACTIVE'],
						'TEXTBOX_MAXLENGTH'=>$arParams['REVIEWS_TEXTBOX_MAXLENGTH'],
						'MAX_RATING'=>$arParams['MAX_RATING'],
						'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
						"DATE_FORMAT" => $arParams['DATE_FORMAT'],
						"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
						"BUTTON_BACKGROUND"=>$arParams['BUTTON_BACKGROUND'],
						"ADD_REVIEW_PLACE"=>$arParams['ADD_REVIEW_PLACE'],
						'CACHE_TIME'=>$arParams["CACHE_TIME"],
						'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
						"NOTICE_EMAIL"=>$arParams['NOTICE_EMAIL'],
						'AJAX'=>'N',
				),
				$component
				);
	}
	if($active=='comments')
	{
		$APPLICATION->IncludeComponent(
				"sotbit:reviews.comments",
				$arParams['TEMPLATE'],
				array(
						'TEXTBOX_MAXLENGTH'=>$arParams['COMMENTS_TEXTBOX_MAXLENGTH'],
						'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
						"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
						"DATE_FORMAT" => $arParams['DATE_FORMAT'],
						"BUTTON_BACKGROUND"=>$arParams['BUTTON_BACKGROUND'],
						"NOTICE_EMAIL"=>$arParams['NOTICE_EMAIL'],
						'CACHE_TIME'=>$arParams["CACHE_TIME"],
						'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
						'AJAX'=>'N',
				),
				$component
				);
	}
	if($active=='questions')
	{
		$APPLICATION->IncludeComponent(
				"sotbit:reviews.questions",
				$arParams['TEMPLATE'],
				array(
						'TEXTBOX_MAXLENGTH'=>$arParams['QUESTIONS_TEXTBOX_MAXLENGTH'],
						'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
						"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
						"NOTICE_EMAIL"=>$arParams['NOTICE_EMAIL'],
						"DATE_FORMAT" => $arParams['DATE_FORMAT'],
						"BUTTON_BACKGROUND"=>$arParams['BUTTON_BACKGROUND'],
						'CACHE_TIME'=>$arParams["CACHE_TIME"],
						'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
						'AJAX'=>'N',
				),
				$component
				);
	}
	
	
}
?>