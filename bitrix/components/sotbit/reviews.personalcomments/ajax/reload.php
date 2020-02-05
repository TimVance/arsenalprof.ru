<?
use Sotbit\Reviews\CommentsTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type;
require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if(!Loader::includeModule( 'sotbit.reviews' ))
	return false;

global $APPLICATION;
global $USER;
if($REQUEST_METHOD == "POST") {

	
	$arParams = unserialize( $data );
			$APPLICATION->IncludeComponent( "sotbit:reviews.personalcomments", $arParams['TEMPLATE'], array(
					'ID_USER' => $arParams['ID_USER'],
					'NOTICE_EMAIL' => $arParams['NOTICE_EMAIL'],
					"PRIMARY_COLOR" => $arParams["PRIMARY_COLOR"],
					'CACHE_TIME' => $arParams['CACHE_TIME'],
					'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
					"DATE_FORMAT" => $arParams["DATE_FORMAT"] 
			), $component );
}
?> 