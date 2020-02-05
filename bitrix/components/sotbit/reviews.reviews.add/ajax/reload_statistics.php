<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
	global $USER;
	if(!is_object($USER)) $USER=new CUser;
$APPLICATION->IncludeComponent(
	"sotbit:reviews.statistics",
	$TEMPLATE,
	array(
		'MAX_RATING'=>$MAX_RATING,
		'ID_ELEMENT'=>$IdElement,
		"PRIMARY_COLOR"=>$PrimaryColor,
	),
	$component
);
?>
<?if($ADD_REVIEW_PLACE==1):

	$APPLICATION->IncludeComponent(
		"sotbit:reviews.reviews.add",
		$TEMPLATE,
		array(
		'MAX_RATING'=>$MAX_RATING,
		'ID_ELEMENT'=>$IdElement,
		"PRIMARY_COLOR"=>$PrimaryColor,
		"BUTTON_BACKGROUND"=>$BUTTON_BACKGROUND,
		'TEXTBOX_MAXLENGTH'=>$TextLength,
		),
		$component
	);

	endif;?>