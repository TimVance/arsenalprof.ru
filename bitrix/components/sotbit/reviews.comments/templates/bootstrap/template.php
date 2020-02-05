<?
if(!defined( "B_PROLOG_INCLUDED" )||B_PROLOG_INCLUDED!==true)
	die();
global $USER;
global $APPLICATION;
$this->setFrameMode( true );
$APPLICATION->IncludeComponent( "sotbit:reviews.comments.add", "bootstrap", array(
		'PARENT' => 0,
		'TEXTBOX_MAXLENGTH' => $arParams['TEXTBOX_MAXLENGTH'],
		'ID_ELEMENT' => $arParams['ID_ELEMENT'],
		"PRIMARY_COLOR" => $arParams['PRIMARY_COLOR'],
		"BUTTON_BACKGROUND" => $arParams['BUTTON_BACKGROUND'],
		'AJAX' => $arParams["AJAX"],
		"NOTICE_EMAIL" => $arParams['NOTICE_EMAIL'],
		'CACHE_TIME' => $arParams["CACHE_TIME"],
		'CACHE_GROUPS' => $arParams["CACHE_GROUPS"]
), $component );
?>

	<div id="comments-list">
<?
$APPLICATION->IncludeComponent( "sotbit:reviews.comments.list", "bootstrap", array(
		'ID_ELEMENT' => $arParams['ID_ELEMENT'],
		'AJAX' => $arParams["AJAX"],
		'TEXTBOX_MAXLENGTH' => $arParams['TEXTBOX_MAXLENGTH'],
		"NOTICE_EMAIL" => $arParams['NOTICE_EMAIL'],
		"PRIMARY_COLOR" => $arParams['PRIMARY_COLOR'],
		"BUTTON_BACKGROUND" => $arParams['BUTTON_BACKGROUND'],
		"DATE_FORMAT" => $arParams['DATE_FORMAT'],
		'CACHE_TIME' => $arParams["CACHE_TIME"],
		'CACHE_GROUPS' => $arParams["CACHE_GROUPS"]
), $component );
?>
	</div>
