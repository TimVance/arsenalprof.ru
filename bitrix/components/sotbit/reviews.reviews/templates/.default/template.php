<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();  ?>
<?$this->setFrameMode(true);?>
<?global $APPLICATION;
	global $USER;
	if(!is_object($USER)) $USER=new CUser;
if($arParams['ADD_REVIEW_PLACE']==2):

	$APPLICATION->IncludeComponent(
		"sotbit:reviews.reviews.add",
		"",
		array(
			'DEFAULT_RATING_ACTIVE'=>$arParams['DEFAULT_RATING_ACTIVE'],
		'TEXTBOX_MAXLENGTH'=>$arParams['TEXTBOX_MAXLENGTH'],
			'MAX_RATING'=>$arParams['MAX_RATING'],
			'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
			"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
			"BUTTON_BACKGROUND"=>$arParams['BUTTON_BACKGROUND'],
			'CACHE_TIME'=>$arParams["CACHE_TIME"],
			'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
			'ADD_REVIEW_PLACE'=>$arParams['ADD_REVIEW_PLACE'],
			"NOTICE_EMAIL"=>$arParams['NOTICE_EMAIL'],
			'AJAX'=>$arParams["AJAX"],
		),
		$component
	);

	endif;?>
<?
$APPLICATION->IncludeComponent(
	"sotbit:reviews.reviews.filter",
	"",
	array(
		'MAX_RATING'=>$arParams['MAX_RATING'],
		'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
		"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
		'CACHE_TIME'=>$arParams["CACHE_TIME"],
		'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
		'AJAX'=>$arParams["AJAX"],
	),
	$component
);
?>
<?
$APPLICATION->IncludeComponent(
	"sotbit:reviews.reviews.list",
	"",
	array(
		'MAX_RATING'=>$arParams['MAX_RATING'],
		'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
		"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
		"DATE_FORMAT"=>$arParams['DATE_FORMAT'],
		'CACHE_TIME'=>$arParams["CACHE_TIME"],
		'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
		'AJAX'=>$arParams["AJAX"],
	),
	$component
);
?>