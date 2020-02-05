<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	global $USER;
	if(!is_object($USER)) $USER=new CUser;
	global $APPLICATION;
$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"error_auth",
	Array(
		"REGISTER_URL" => "",
		"FORGOT_PASSWORD_URL" => "",
		"PROFILE_URL" => "/personal/profile/",
		"SHOW_ERRORS" => "Y"
	)
);
?>