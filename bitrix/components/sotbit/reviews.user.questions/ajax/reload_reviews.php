<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;
if($REQUEST_METHOD == "POST")
{
	$arParams=unserialize($data);
	
	$_SESSION['sotbib_reviews_user_reviews_page_' . $arParams['ID_USER']]=$FilterPage;
	$_SESSION['sotbib_reviews_user_' . $arParams['ID_USER']]=$Url;
	
		$APPLICATION->IncludeComponent(
				"sotbit:reviews.user.reviews",
				$TEMPLATE,
				array(
						'MAX_RATING'=>$arParams['MAX_RATING'],
						'ID_USER'=>$arParams['ID_USER'],
						"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
						'CACHE_TIME'=>$arParams["CACHE_TIME"],
						'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
						"DATE_FORMAT"=>$arParams['DATE_FORMAT'],
						'AJAX'=>($arParams["FIRST_ACTIVE"]==1)?'N':'Y'
				),
				$component
				);
}
?> 