<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	global $USER;
	if(!is_object($USER)) $USER=new CUser;
	global $APPLICATION;
echo randString(32,array("abcdefghijklnmopqrstuvwxyz0123456789"));//htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
?>