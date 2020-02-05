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
			"NAME" => GetMessage($IdModule."_PRIMARY_COLOR"),
			"TYPE" => "STRING",
			"DEFAULT" => "#a76e6e",
		),
		"ID_ELEMENT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_ID_ELEMENT"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$ElementID}',
		),
		"AJAX" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_AJAX"),
			"TYPE" => "LIST",
			"VALUES"=>array('N'=>GetMessage($IdModule."_OFF"),'Y'=>GetMessage($IdModule."_ON"))
		),
		"CACHE_TIME" => array(
			"DEFAULT" => 36000000,
		), 
	),
);
?>