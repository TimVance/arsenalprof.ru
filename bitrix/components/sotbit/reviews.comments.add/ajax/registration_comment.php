<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	global $USER;
	if(!is_object($USER)) $USER=new CUser;
	global $APPLICATION;
$APPLICATION->IncludeComponent("bitrix:main.register","error_register",
		unserialize($arparams)
/*Array(
	"USER_PROPERTY_NAME" => "", 
	"SEF_MODE" => "Y", 
	"SHOW_FIELDS" => Array(), 
	"REQUIRED_FIELDS" => Array(), 
	"AUTH" => "Y", 
	"USE_BACKURL" => "N", 
	"SUCCESS_PAGE" => "", 
	"SET_TITLE" => "Y", 
	"USER_PROPERTY" => Array(), 
	"SEF_FOLDER" => "/", 
	"VARIABLE_ALIASES" => Array(),
	)*/,
	$component
);?> 