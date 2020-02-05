<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;
if($REQUEST_METHOD == "POST")
{
	global $SotbitFilterPageComments;
	global $URL;
	
	$URL=$Url;

	if(isset($FilterPage) && !empty($FilterPage))
		$SotbitFilterPageComments=$FilterPage;

	$APPLICATION->IncludeComponent(
		"sotbit:reviews.comments.list",
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