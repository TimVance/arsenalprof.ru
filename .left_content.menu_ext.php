<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if (!function_exists("GetTreeRecursive")) //Include from main.map component
{
	$aMenuLinksExt = $APPLICATION->IncludeComponent("bitrix:menu.sections","",Array(
			"IS_SEF" => "Y",
			"SEF_BASE_URL" => "/catalog/",
			"SECTION_PAGE_URL" => "#SECTION_CODE#/",
			"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
			"IBLOCK_TYPE" => COption::GetOptionString("sotbit.b2bshop", "IBLOCK_TYPE", ""),
			"IBLOCK_ID" => COption::GetOptionString("sotbit.b2bshop", "IBLOCK_ID", ""),
			"DEPTH_LEVEL" => "3",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600"
		)
	);


	$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
}
?>