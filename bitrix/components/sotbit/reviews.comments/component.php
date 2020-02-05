<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Loader;
 
if(!Loader::includeModule('sotbit.reviews'))
	return false;
global $USER;
global $APPLICATION;
if (!isset($arParams["TEXTBOX_MAXLENGTH"]))
	$arParams["TEXTBOX_MAXLENGTH"] = 100;
if (!isset($arParams["PRIMARY_COLOR"]))
	$arParams["PRIMARY_COLOR"] = "#a76e6e";
if (!isset($arParams["BUTTON_BACKGROUND"]))
	$arParams["BUTTON_BACKGROUND"] = "#dbbfb9";
if (!isset($arParams["AJAX"]))
	$arParams["AJAX"] = "N";
if (!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;
if (!isset($arParams["CACHE_TYPE"]))
	$arParams["CACHE_TYPE"] = "A";
if (!isset($arParams["DATE_FORMAT"]))
	$arParams["DATE_FORMAT"] = "d F Y, H:i";
$this->IncludeComponentTemplate();
?>