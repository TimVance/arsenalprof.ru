<?
use \Bitrix\Main\Localization\Loc;
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
	die();
Loc::loadMessages( __FILE__ );
$arComponentDescription = array(
		"NAME" => Loc::getMessage( "SOTBIT_CRM_COMPONENT_NAME" ),
		"DESCRIPTION" => Loc::getMessage( "SOTBIT_CRM_COMPONENT_DESCRIPTION" ),
		"ICON" => "/images/iblock_filter.gif",
		"CACHE_PATH" => "Y",
		"SORT" => 70,
		"PATH" => array(
				"ID" => "crm",
				"NAME" => Loc::getMessage( "SOTBIT_CRM_COMPONENT_PATH_NAME" ) 
		) 
);
?>