<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;
if($REQUEST_METHOD == "POST")
{
	global $SotbitFilterPageQuestions;
	global $URL;
	
	$URL=$Url;

	if(isset($FilterPage) && !empty($FilterPage))
		$SotbitFilterPageQuestions=$FilterPage;

	$APPLICATION->IncludeComponent(
		"sotbit:reviews.questions.list",
		$TEMPLATE,
		array(
			'PRIMARY_COLOR'=>$PrimaryColor,
			'ID_ELEMENT'=>$IdElement,
			'DATE_FORMAT'=>$DateFormat,
			'AJAX'=>'N'
		),
		$component
	);
}
?> 