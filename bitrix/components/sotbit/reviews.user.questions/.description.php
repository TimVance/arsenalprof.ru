<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 
$iModuleID='sotbit_reviews';
$arComponentDescription = array(
	"NAME" => GetMessage($iModuleID."_EC_NAME"),
	"DESCRIPTION" => GetMessage($iModuleID."_EC_DESCRIPTION"),
	"ICON" => "/images/reviews.gif",
	"SORT" => 70,
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "reviews",
			"NAME" => GetMessage($iModuleID."_EC_COMMENTS"),
			"SORT" => 362,
		),
	),
);
?>