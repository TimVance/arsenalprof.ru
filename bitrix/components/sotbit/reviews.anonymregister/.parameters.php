<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$IdModule='sotbit.reviews';
	$rsGroups = CGroup::GetList ($by = "c_sort", $order = "asc", Array ("ACTIVE" => 'Y'));
	while($arGroup=$rsGroups->Fetch())
	{
		$Groups[$arGroup['ID']]='['.$arGroup['ID'].'] '.$arGroup['NAME'];
	}

$arComponentParameters = array(
		"GROUPS" => array(),
	"PARAMETERS" => array(
		"USER_GROUP" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_USER_GROUP"),
			"TYPE" => "LIST",
            "VALUES" => $Groups,
				)
		)
);
?>