<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!CModule::IncludeModule("iblock"))
{
	ShowError(GetMessage("CC_BCF_MODULE_NOT_INSTALLED"));
	return;
}
if(!CModule::IncludeModule("catalog"))
{
	ShowError(GetMessage("CC_CATALOG_MODULE_NOT_INSTALLED"));
	return;
}
if(!CModule::IncludeModule("shs.onlinebuyers"))
{
	ShowError(GetMessage("CC_SHS_ONLINE_NOT_INSTALLED"));
	return;
}
if($arParams["JQUERY"]=="Y")$APPLICATION->AddHeadScript("https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js");

//print_r($this);
if($this->StartResultCache(false, ($arParams["CACHE_GROUPS"]? $USER->GetGroups(): false)))
{
    $arResult = CShsOnlinebuyers::ShsGetOnline($arParams);
    $this->IncludeComponentTemplate();
}
?>
