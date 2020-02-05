<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\ReviewsTable;
use Sotbit\Reviews\ReviewsfieldsTable;
global $APPLICATION;
global $USER;
global $CACHE_MANAGER;
if (! Loader::includeModule ( 'sotbit.reviews' ))
	return false;

if ($arParams['ID_USER'] < 1)
	return false;

global $SotbitUseFilter;
$SotbitUseFilter = 'Y';
if (! isset ( $arParams["MAX_RATING"] ))
	$arParams["MAX_RATING"] = 5;
if (! isset ( $arParams["PRIMARY_COLOR"] ))
	$arParams["PRIMARY_COLOR"] = "#a76e6e";

$obCache = Bitrix\Main\Data\Cache::createInstance ();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_userreviews_filter_' . $arParams['ID_USER'];
$cachePath = '/';

if ($obCache->InitCache ( $life_time, $cache_id, $cachePath ))
{
	$arResult = $obCache->GetVars ();
}
elseif ($obCache->StartDataCache ())
{
	
	$arResult['CNT_PIC'] = 0;
	
	$Filter = array (
			'=ID_USER' => $arParams['ID_USER'],
			'=ACTIVE' => 'Y',
			'=MODERATED' => 'Y' 
	);
	
	$rsDataPic = ReviewsTable::getList ( array (
			'select' => array (
					'ID',
					'RATING' 
			),
			'filter' => $Filter 
	)
	 );
	
	$arResult['SUM_CNT_REVIEWS'] = 0;
	
	for($i = 1; $i <= $arParams['MAX_RATING']; ++ $i)
	{
		$arResult['CNT_REVIEW'][$i] = 0;
	}
	
	while ( $Review = $rsDataPic->Fetch () )
	{
		
		++ $arResult['CNT_REVIEW'][$Review['RATING']];
		
		if (Loader::includeModule ( 'fileman' ) && COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_" . SITE_ID, "" ) == 'Y')
		{
			CMedialib::Init ();
			$arCollections = CMedialibCollection::GetList ( array (
					'arOrder' => Array (
							'NAME' => 'ASC' 
					),
					'arFilter' => array (
							'ACTIVE' => 'Y',
							'NAME' => $Review['ID'] 
					) 
			) );
			if (isset ( $arCollections ) && is_array ( $arCollections ) && count ( $arCollections ) != 0)
			{
				$ID_COLLECTION = $arCollections[0]['ID'];
				$arItems = CMedialibItem::GetList ( array (
						'arCollections' => array (
								"0" => $ID_COLLECTION 
						) 
				) );
				if (isset ( $arItems ) && is_array ( $arItems ))
				{
					foreach ( $arItems as $arItem )
					{
						if ($arItem['TYPE'] == 'image')
						{
							++ $arResult['CNT_PIC'];
							break;
						}
					}
				}
			}
		}
		++ $arResult['SUM_CNT_REVIEWS'];
	}
	
	if (isset ( $_COOKIE['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']] ) && $_COOKIE['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']] == "Y")
	{
		$arResult['SORT_IMAGES'] = "Y";
	}
	
	$arResult['TITLE'] = 'NEW';
	$arResult['BY'] = 'DATE_CREATION';
	$arResult['ORDER'] = 'desc';
	if (isset ( $_COOKIE["sotbit_reviews_userreviews_by_" . $arParams['ID_USER']] ) && $_COOKIE["sotbit_reviews_userreviews_by_" . $arParams['ID_USER']] == 'DATE_CREATION')
	{
		if (isset ( $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] ) && $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] == "asc")
		{
			$arResult['TITLE'] = 'OLD';
			$arResult['BY'] = "DATE_CREATION";
			$arResult['ORDER'] = 'asc';
		}
		elseif (isset ( $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] ) && $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] == "desc")
		{
			$arResult['TITLE'] = 'NEW';
			$arResult['BY'] = "DATE_CREATION";
			$arResult['ORDER'] = 'desc';
		}
	}
	if (isset ( $_COOKIE["sotbit_reviews_userreviews_by_" . $arParams['ID_USER']] ) && $_COOKIE["sotbit_reviews_userreviews_by_" . $arParams['ID_USER']] == 'RATING')
	{
		if (isset ( $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] ) && $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] == "asc")
		{
			$arResult['TITLE'] = 'LOW_RATING';
			$arResult['BY'] = "RATING";
			$arResult['ORDER'] = 'asc';
		}
		elseif (isset ( $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] ) && $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] == "desc")
		{
			$arResult['TITLE'] = 'HIGH_RATING';
			$arResult['BY'] = "RATING";
			$arResult['ORDER'] = 'desc';
		}
	}
	if (isset ( $_COOKIE["sotbit_reviews_userreviews_by_" . $arParams['ID_USER']] ) && $_COOKIE["sotbit_reviews_userreviews_by_" . $arParams['ID_USER']] == 'LIKES')
	{
		if (isset ( $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] ) && $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] == "desc")
		{
			$arResult['TITLE'] = 'LIKES';
			$arResult['BY'] = "LIKES";
			$arResult['ORDER'] = 'desc';
		}
	}
	
	$arResult['FILTER_REVIEWS_TITLE'] = "";
	$arResult['FILTER_REVIEWS_VALUE'] = - 1;
	if (isset ( $_COOKIE["sotbit_reviews_userreviews_rating_" . $arParams['ID_USER']] ) && $_COOKIE["sotbit_reviews_userreviews_rating_" . $arParams['ID_USER']] > 0)
	{
		for($k = 1; $k <= $_COOKIE['sotbit_reviews_filter_sort_rating']; ++ $k)
		{
			$arResult['FILTER_REVIEWS_TITLE'] .= '
					<span class="uni-stars">
						<i class="fa fa-star" aria-hidden="true"></i>
					</span>';
		}
		$arResult['FILTER_REVIEWS_TITLE'] .= '(' . $arResult['CNT_REVIEW'][$_COOKIE['sotbit_reviews_filter_sort_rating']] . ')';
		$arResult['FILTER_REVIEWS_VALUE'] = $_COOKIE['sotbit_reviews_filter_sort_rating'];
	}
	if (! isset ( $arResult['FILTER_REVIEWS_TITLE'] ) || $arResult['FILTER_REVIEWS_TITLE'] == "")
	{
		$arResult['FILTER_REVIEWS_TITLE'] = GetMessage ( CSotbitReviews::iModuleID . "_REVIEWS_FILTER_GENERAL_RATING" ) . " (" . $arResult['SUM_CNT_REVIEWS'] . ")";
	}
	
	$CACHE_MANAGER->StartTagCache ( $cachePath );
	$CACHE_MANAGER->RegisterTag ( 'sotbit_reviews_userreviews_filter_' . $arParams['ID_USER'] );
	$CACHE_MANAGER->EndTagCache ();
	$obCache->EndDataCache ( $arResult );
}

$this->IncludeComponentTemplate ();

?>