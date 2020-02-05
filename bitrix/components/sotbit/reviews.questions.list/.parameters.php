<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!CModule::IncludeModule("iblock"))
	return;
$IdModule='sotbit_reviews';
$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
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
		"DATE_FORMAT" => CIBlockParameters::GetDateFormat(GetMessage($IdModule."_DATE_FORMAT"), "BASE"),
		"CACHE_TIME" => array(
			"DEFAULT" => 36000000,
		), 
	),
);
?>