<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!CModule::IncludeModule("iblock"))
	return;
$IdModule='sotbit.reviews';
$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"MAX_RATING" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_MAX_RATING"),
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
		"CACHE_TIME" => array(
			"DEFAULT" => 36000000,
		), 
	),
);
?>