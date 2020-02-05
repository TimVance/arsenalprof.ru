<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$IdModule='sotbit.reviews';
$arComponentDescription = array(
	"NAME" => GetMessage($IdModule."_NAME"),
	"DESCRIPTION" => GetMessage($IdModule."_DESCRIPTION"),
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