<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SHS_ONLINE_NAME"),
	"DESCRIPTION" => GetMessage("SHS_ONLINE_DESCRIPTION"),
	"ICON" => "/images/shs_online.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 70,
	"PATH" => array(
		"ID" => "e-store",
		"CHILD" => array(
			"ID" => "onlinebuyers",
			"NAME" => GetMessage("SHS_ONLINE_NAME"),
			"SORT" => 30,
		),
	),
);
?>