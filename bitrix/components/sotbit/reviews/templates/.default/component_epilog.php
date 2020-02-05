<?
global $USER;
if(!is_object($USER)) $USER=new CUser;
	global $APPLICATION;
$APPLICATION->SetAdditionalCSS($templateFolder."/css/font-awesome.min.css");?>