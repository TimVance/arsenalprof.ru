<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	global $USER;
	if(!is_object($USER)) $USER=new CUser;
global $APPLICATION;
$APPLICATION->IncludeComponent(
	"sotbit:reviews.questions.list",
	$TEMPLATE,
	array(
		'AJAX'=>'N',
		'ID_ELEMENT'=>$IdElement,
	),
	$component
);
?> 