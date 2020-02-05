<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!CModule::IncludeModule("iblock"))
	return;
$iModuleID='sotbit_reviews';
$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"MAX_RATING" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($iModuleID."_MAX_RATING"),
			"TYPE" => "STRING",
			"DEFAULT" => 5,
		),
		"PRIMARY_COLOR" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($iModuleID."_PRIMARY_COLOR"),
			"TYPE" => "STRING",
			"DEFAULT" => "#a76e6e",
		),
		"AJAX" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($iModuleID."_AJAX"),
			"TYPE" => "LIST",
			"VALUES"=>array('N'=>GetMessage($iModuleID."_OFF"),'Y'=>GetMessage($iModuleID."_ON"))
		),
		"DATE_FORMAT" => CIBlockParameters::GetDateFormat(GetMessage($iModuleID."_DATE_FORMAT"), "BASE"),
		"ID_ELEMENT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($iModuleID."_ID_ELEMENT"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$ElementID}',
		),
		"CACHE_TIME" => array(
			"DEFAULT" => 36000000,
		), 
	),
);
?>