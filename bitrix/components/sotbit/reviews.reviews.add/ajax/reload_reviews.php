<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
	global $USER;
	if(!is_object($USER)) $USER=new CUser;
$APPLICATION->IncludeComponent(
	"sotbit:reviews.reviews",
	$TEMPLATE,
	array(
		'MAX_RATING'=>$MAX_RATING,
		'ID_ELEMENT'=>$IdElement,
		"PRIMARY_COLOR"=>$PrimaryColor,
		"BUTTON_BACKGROUND"=>$BUTTON_BACKGROUND,
		"ADD_REVIEW_PLACE"=>$ADD_REVIEW_PLACE,
		'TEXTBOX_MAXLENGTH'=>$TextLength,
		'AJAX'=>'N'
	),
	$component
);
/*
$APPLICATION->IncludeComponent(
"sotbit:reviews.reviews.filter",
"",
array(
'MAX_RATING'=>$arParams['MAX_RATING'],
'ID_ELEMENT'=>$IdElement,
'CACHE_TIME'=>$arParams["CACHE_TIME"],
'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
),
$component
);

$APPLICATION->IncludeComponent(
"sotbit:reviews.reviews.list",
"",
array(
'ID_ELEMENT'=>$IdElement,
),
$component
);*/
?> 