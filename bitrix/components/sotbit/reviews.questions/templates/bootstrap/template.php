<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();  ?>
<?$this->setFrameMode(true);?>
<?
	global $USER;
	if(!is_object($USER)) $USER=new CUser;
	global $APPLICATION;
$APPLICATION->IncludeComponent(
	"sotbit:reviews.questions.add",
	"bootstrap",
	array(
		'TEXTBOX_MAXLENGTH'=>$arParams['TEXTBOX_MAXLENGTH'],
		'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
		"PRIMARY_COLOR"=>$arParams['PRIMARY_COLOR'],
		"BUTTON_BACKGROUND"=>$arParams['BUTTON_BACKGROUND'],
		'AJAX'=>$arParams["AJAX"],
		"NOTICE_EMAIL"=>$arParams['NOTICE_EMAIL'],
		'CACHE_TIME'=>$arParams["CACHE_TIME"],
		'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
	),
	$component
);
?>
<div id="questions-list">
<?
$APPLICATION->IncludeComponent(
	"sotbit:reviews.questions.list",
	"bootstrap",
	array(
		'ID_ELEMENT'=>$arParams['ID_ELEMENT'],
		'AJAX'=>$arParams["AJAX"],
		"DATE_FORMAT"=>$arParams['DATE_FORMAT'],
		'CACHE_TIME'=>$arParams["CACHE_TIME"],
		'CACHE_GROUPS'=>$arParams["CACHE_GROUPS"],
	),
	$component
);
?>
</div>
