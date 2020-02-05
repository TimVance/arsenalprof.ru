<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;
if($REQUEST_METHOD == "POST")
{
	global $SotbitFilterRating;
	global $SotbitFilterImages;
	global $SotbitFilterSortOrder;
	global $SotbitFilterSortBy;
	global $SotbitFilterPage;
	global $SotbitUseFilter;
	global $URL;
	
	$URL=$Url;
	
	$SotbitUseFilter='Y';
	if(isset($FilterRating) && !empty($FilterRating))
		$SotbitFilterRating=$FilterRating;
	if(isset($FilterImages) && !empty($FilterImages))
		$SotbitFilterImages=$FilterImages;
	if(isset($FilterSortOrder) && !empty($FilterSortOrder))
		$SotbitFilterSortOrder=$FilterSortOrder;
	if(isset($FilterSortBy) && !empty($FilterSortBy))
		$SotbitFilterSortBy=$FilterSortBy;
	if(isset($FilterPage) && !empty($FilterPage))
		$SotbitFilterPage=$FilterPage;
	$APPLICATION->IncludeComponent(
		"sotbit:reviews.reviews.list",
		$TEMPLATE,
		array(
			'PRIMARY_COLOR'=>$PrimaryColor,
			'MAX_RATING'=>$MAX_RATING,
			'ID_ELEMENT'=>$IdElement,
			'DATE_FORMAT'=>$DateFormat,
			'AJAX'=>'N'
		),
		$component
	);
}
?> 