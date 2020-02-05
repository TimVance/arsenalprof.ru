<?
use \Bitrix\Main\Localization\Loc;
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
	die();
Loc::loadMessages( __FILE__ );
$arComponentParameters = array(
		"PARAMETERS" => array(
				"EVENT" => array(
						"PARENT" => "DATA_SOURCE",
						"NAME" => Loc::getMessage( "SOTBIT_CRM_COMPONENT_EVENT" ),
						"TYPE" => "STRING",
						"DEFAULT" => '={$_POST["event"]}'
				),
				"DATA" => array(
						"PARENT" => "DATA_SOURCE",
						"NAME" => Loc::getMessage( "SOTBIT_CRM_COMPONENT_DATA" ),
						"TYPE" => "STRING",
						"DEFAULT" => '={$_POST["data"]}' 
				),
				"AUTH" => array(
						"PARENT" => "DATA_SOURCE",
						"NAME" => Loc::getMessage( "SOTBIT_CRM_COMPONENT_AUTH" ),
						"TYPE" => "STRING",
						"DEFAULT" => '={$_POST["auth"]}' 
				) 
		) 
);
?>