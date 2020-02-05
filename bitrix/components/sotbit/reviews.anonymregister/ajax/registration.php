<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;
$APPLICATION->IncludeComponent("sotbit:reviews.anonymregister","error_register",
		unserialize($arparams),
	$component
);?>