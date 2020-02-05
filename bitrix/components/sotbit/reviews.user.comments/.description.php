<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$IdModule='sotbit.reviews';
$arComponentDescription = array(
	"NAME" => GetMessage($IdModule."_EC_NAME"),
	"DESCRIPTION" => GetMessage($IdModule."_EC_DESCRIPTION"),
	"ICON" => "/images/reviews.gif",
	"SORT" => 70,
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "reviews",
			"NAME" => GetMessage($IdModule."_EC_COMMENTS"),
			"SORT" => 362,
		),
	),
);
?>