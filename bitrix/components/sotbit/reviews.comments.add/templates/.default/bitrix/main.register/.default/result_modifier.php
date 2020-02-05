<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
	global $USER;
	if(!is_object($USER)) $USER=new CUser;
	global $APPLICATION;
$arResult['SHOW_FIELDS']=array('EMAIL','NAME','PASSWORD','LAST_NAME','CONFIRM_PASSWORD','LOGIN');
?>