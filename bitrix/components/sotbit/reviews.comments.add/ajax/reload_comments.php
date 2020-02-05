<?
require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $USER;
global $APPLICATION;
$APPLICATION->IncludeComponent( "sotbit:reviews.comments.list", $TEMPLATE, array(
		'AJAX' => 'N',
		'ID_ELEMENT' => $IdElement,
		'PRIMARY_COLOR' => $PRIMARY_COLOR,
		'BUTTON_BACKGROUND' => $BUTTON_BACKGROUND,
		'TEXTBOX_MAXLENGTH' => $TEXTBOX_MAXLENGTH 
), $component );
?>