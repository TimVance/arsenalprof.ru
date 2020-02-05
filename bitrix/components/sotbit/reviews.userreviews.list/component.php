<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\ReviewsTable;
use Sotbit\Reviews\ReviewsfieldsTable;
use Bitrix\Main\Localization\Loc;
global $APPLICATION;
global $USER;
global $CACHE_MANAGER;

if (! Loader::includeModule ( 'sotbit.reviews' ) || ! Loader::includeModule ( 'iblock' ))
	return false;

if (! isset ( $arParams['ID_USER'] ) || empty ( $arParams['ID_USER'] ))
	return false;

if (isset ( $_SESSION['sotbit_reviews_user_' . $arParams['ID_USER']] ) && ! empty ( $_SESSION['sotbit_reviews_user_' . $arParams['ID_USER']] ))
{
	$Url = $_SESSION['sotbit_reviews_user_' . $arParams['ID_USER']];
}
else
{
	$Url = $APPLICATION->GetCurPage ();
}

if (! isset ( $arParams["MAX_RATING"] ))
	$arParams["MAX_RATING"] = 5;
if (! isset ( $arParams["DATE_FORMAT"] ))
	$arParams["DATE_FORMAT"] = "d F Y, H:i";

$obCache = Bitrix\Main\Data\Cache::createInstance ();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_userreviews_list_' . $arParams['ID_USER'];
$cachePath = '/';
if ($obCache->InitCache ( $life_time, $cache_id, $cachePath ))
{
	$arResult = $obCache->GetVars ();
}
elseif ($obCache->StartDataCache ())
{
	$arResult["REVIEWS_CNT"] = ReviewsTable::GetCount ( array (
			'=ID_USER' => $arParams['ID_USER'],
			'=ACTIVE' => 'Y',
			'=MODERATED' => 'Y' 
	) );
	
	if ($arResult["REVIEWS_CNT"] > 0)
	{
		
		$arResult['USE_TITLE'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_TITLE_" . SITE_ID, "" );
		
		$arResult['USE_IMAGES'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_" . SITE_ID, "" );
		
		$arResult['USE_VIDEO'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_MULTIMEDIA_VIDEO_ALLOW_" . SITE_ID, "" );
		
		$arResult['USE_PRESENTATION'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_MULTIMEDIA_PRESENTATION_ALLOW_" . SITE_ID, "" );
		
		// Nav page
		if (isset ( $_SESSION['sotbit_reviews_userreviews_page_' . $arParams['ID_USER']] ) && ! empty ( $_SESSION['sotbit_reviews_userreviews_page_' . $arParams['ID_USER']] ))
		{
			SetCookie ( 'sotbit_reviews_userreviews_page_' . $arParams['ID_USER'], $_SESSION['sotbit_reviews_userreviews_page_' . $arParams['ID_USER']], time () + 3600 * 24 * 3, $Url );
		}
		else
		{
			SetCookie ( 'sotbit_reviews_userreviews_page_' . $arParams['ID_USER'], '1', time () + 3600 * 24 * 3, $Url );
		}
		
		if (isset ( $_SESSION['sotbit_reviews_userreviews_page_' . $arParams['ID_USER']] ) && ! empty ( $_SESSION['sotbit_reviews_userreviews_page_' . $arParams['ID_USER']] ))
		{
			$arResult['CURRENT_PAGE'] = $_SESSION['sotbit_reviews_userreviews_page_' . $arParams['ID_USER']];
		}
		elseif (isset ( $_COOKIE['sotbit_reviews_userreviews_page_' . $arParams['ID_USER']] ))
		{
			$arResult['CURRENT_PAGE'] = $_COOKIE['sotbit_reviews_userreviews_page_' . $arParams['ID_USER']];
		}
		else
		{
			$arResult['CURRENT_PAGE'] = 1;
		}
		
		// Add fields
		$rsFields = ReviewsfieldsTable::getList ( array (
				'select' => array (
						'NAME',
						'TYPE',
						'TITLE' 
				),
				'filter' => array (
						'=ACTIVE' => 'Y' 
				),
				'order' => array (
						'SORT' => 'asc' 
				) 
		) );
		while ( $Fields = $rsFields->Fetch () )
		{
			$arResult['ADD_FIELDS'][$Fields['NAME']]['TYPE'] = $Fields['TYPE'];
			$arResult['ADD_FIELDS'][$Fields['NAME']]['TITLE'] = (isset ( $Fields['TITLE'] ) && ! empty ( $Fields['TITLE'] )) ? $Fields['TITLE'] : Loc::getMessage ( "REVIEWS_ADD_FIELD_TITLE_" . $Fields['NAME'] );
		}
		unset ( $rsFields );
		unset ( $Fields );
		
		// medialibrary
		if (Loader::includeModule ( 'fileman' ) && $arResult['USE_IMAGES'] == 'Y')
		{
			CMedialib::Init ();
			$IDs = array ();
			$arCollections = CMedialibCollection::GetList ( array (
					'arOrder' => Array (
							'NAME' => 'ASC' 
					),
					'arFilter' => array (
							'ACTIVE' => 'Y',
							'NAME' => 'sotbit.reviews' 
					) 
			) );
			$arResult['SOTBIT_MEDIACOLLECTION_ID'] = $arCollections[0]['ID'];
		}
		
		// limit and offset
		$arResult['PER_PAGE'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_COUNT_PAGE_" . SITE_ID, "10" );
		
		if (isset ( $arResult['CURRENT_PAGE'] ))
		{
			$limit = $arResult['PER_PAGE'];
			if ($limit > $arResult["REVIEWS_CNT"])
			{
				$limit = $arResult["REVIEWS_CNT"];
			}
			$offset = ($arResult['CURRENT_PAGE'] - 1) * $arResult['PER_PAGE'];
		}
		else
		{
			$limit = $arResult["REVIEWS_CNT"];
			$offset = 0;
		}
		
		$Filter = array (
				'=ID_USER' => $arParams['ID_USER'],
				'=ACTIVE' => 'Y',
				'=MODERATED' => 'Y' 
		);
		
		if (isset ( $_SESSION['sotbit_reviews_userreviews_rating_' . $arParams['ID_USER']] ) && ! empty ( $_SESSION['sotbit_reviews_userreviews_rating_' . $arParams['ID_USER']] ) && $_SESSION['sotbit_reviews_userreviews_rating_' . $arParams['ID_USER']] > 0)
		{
			$arResult['FILTER_RATING'] = $_SESSION['sotbit_reviews_userreviews_rating_' . $arParams['ID_USER']];
			SetCookie ( "sotbit_reviews_userreviews_rating_" . $arParams['ID_USER'], $_SESSION['sotbit_reviews_userreviews_rating_' . $arParams['ID_USER']], time () + 3600 * 24 * 3, $Url );
		}
		elseif (isset ( $_SESSION['sotbit_reviews_userreviews_rating_' . $arParams['ID_USER']] ) && $_SESSION['sotbit_reviews_userreviews_rating_' . $arParams['ID_USER']] == - 1)
		{
			$arResult['FILTER_RATING'] = "";
			SetCookie ( "sotbit_reviews_userreviews_rating_" . $arParams['ID_USER'], '', time () - 1000, $Url );
		}
		elseif (! isset ( $_SESSION['sotbit_reviews_userreviews_rating_' . $arParams['ID_USER']] ) && isset ( $_COOKIE["sotbit_reviews_userreviews_rating_" . $arParams['ID_USER']] ))
		{
			$arResult['FILTER_RATING'] = $_COOKIE["sotbit_reviews_userreviews_rating_" . $arParams['ID_USER']];
		}
		else
		{
			$arResult['FILTER_RATING'] = "";
		}
		if (isset ( $arResult['FILTER_RATING'] ) && $arResult['FILTER_RATING'] != "")
		{
			$Filter = array_merge ( $Filter, array (
					'=RATING' => $arResult['FILTER_RATING'] 
			) );
		}
		
		if (Loader::includeModule ( 'fileman' ) && COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_" . SITE_ID, "" ) == 'Y' && isset ( $_SESSION['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']] ) && $_SESSION['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']] == 'Y')
		{
			$arResult['FILTER_IMAGES'] = $_SESSION['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']];
			SetCookie ( 'sotbit_reviews_userreviews_images_' . $arParams['ID_USER'], $_SESSION['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']], time () + 3600 * 24 * 3, $Url );
		}
		elseif (isset ( $_SESSION['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']] ) && $_SESSION['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']] == 'N')
		{
			$arResult['FILTER_IMAGES'] = $_SESSION['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']];
			SetCookie ( 'sotbit_reviews_userreviews_images_' . $arParams['ID_USER'], '', time () + 3600 * 24 * 3, $Url );
		}
		elseif (isset ( $_COOKIE['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']] ))
		{
			$arResult['FILTER_IMAGES'] = $_COOKIE['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']];
		}
		else
		{
			$arResult['FILTER_IMAGES'] = "N";
		}
		
		if (Loader::includeModule ( 'fileman' ) && COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_" . SITE_ID, "" ) == 'Y' && isset ( $arResult['FILTER_IMAGES'] ) && $arResult['FILTER_IMAGES'] == 'Y')
		{
			CMedialib::Init ();
			$IDs = array ();
			$arCollections = CMedialibCollection::GetList ( array (
					'arOrder' => Array (
							'NAME' => 'ASC' 
					),
					'arFilter' => array (
							'ACTIVE' => 'Y',
							'NAME' => 'sotbit.reviews' 
					) 
			) );
			$IdMainCollection = $arCollections[0]['ID'];
			$arCollections = CMedialibCollection::GetList ( 
					array (
							'arOrder' => Array (
									'NAME' => 'ASC' 
							),
							'arFilter' => array (
									'ACTIVE' => 'Y',
									'ML_TYPE' => 1,
									'PARENT_ID' => $IdMainCollection 
							) 
					) );
			foreach ( $arCollections as $Collection )
			{
				$arItems = CMedialibItem::GetList ( array (
						'arCollections' => array (
								"0" => $Collection['ID'] 
						) 
				) );
				if (is_array ( $arItems ) && count ( $arItems ) > 0)
					$IDs[] = $Collection['NAME'];
			}
			$Filter = array_merge ( $Filter, array (
					'ID' => $IDs 
			) );
		}
		
		if (isset ( $_SESSION['sotbit_reviews_userreviews_by_' . $arParams['ID_USER']] ) && ! empty ( $_SESSION['sotbit_reviews_userreviews_by_' . $arParams['ID_USER']] ))
		{
			$arResult['FILTER_SORT_BY'] = $_SESSION['sotbit_reviews_userreviews_by_' . $arParams['ID_USER']];
			SetCookie ( "sotbit_reviews_userreviews_by_" . $arParams['ID_USER'], $SotbitFilterSortBy, time () + 3600 * 24 * 3, $Url );
		}
		elseif (isset ( $_COOKIE["sotbit_reviews_userreviews_by_" . $arParams['ID_USER']] ))
		{
			$arResult['FILTER_SORT_BY'] = $_COOKIE["sotbit_reviews_userreviews_by_" . $arParams['ID_USER']];
		}
		else
		{
			$arResult['FILTER_SORT_BY'] = 'DATE_CREATION';
		}
		
		if (isset ( $_SESSION['sotbit_reviews_userreviews_order_' . $arParams['ID_USER']] ) && ! empty ( $_SESSION['sotbit_reviews_userreviews_order_' . $arParams['ID_USER']] ))
		{
			$arResult['FILTER_SORT_ORDER'] = $_SESSION['sotbit_reviews_userreviews_order_' . $arParams['ID_USER']];
			SetCookie ( "sotbit_reviews_userreviews_order_" . $arParams['ID_USER'], $_SESSION['sotbit_reviews_userreviews_order_' . $arParams['ID_USER']], time () + 3600 * 24 * 3, $Url );
		}
		elseif (isset ( $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']] ))
		{
			$arResult['FILTER_SORT_ORDER'] = $_COOKIE["sotbit_reviews_userreviews_order_" . $arParams['ID_USER']];
		}
		else
		{
			$arResult['FILTER_SORT_ORDER'] = 'desc';
		}
		
		if (isset ( $arResult['FILTER_SORT_BY'] ) && ! empty ( $arResult['FILTER_SORT_BY'] ) && isset ( $arResult['FILTER_SORT_ORDER'] ) && ! empty ( $arResult['FILTER_SORT_ORDER'] ))
			$sort = array (
					$arResult['FILTER_SORT_BY'] => $arResult['FILTER_SORT_ORDER'] 
			);
		else
			$sort = array (
					'DATE_CREATION' => 'desc' 
			);
			
			// query
		$rsData = ReviewsTable::getList ( 
				array (
						'select' => array (
								'ID',
								'ID_USER',
								'ID_ELEMENT',
								'XML_ID_ELEMENT',
								'RATING',
								'DATE_CREATION',
								'TITLE',
								'ANSWER',
								'TEXT',
								'ADD_FIELDS',
								'LIKES',
								'DISLIKES',
								'RECOMMENDATED',
								'MULTIMEDIA' 
						),
						'filter' => $Filter,
						'order' => $sort 
				) );
		$arResult["REVIEWS_CNT"] = 0;
		while ( $Review = $rsData->Fetch () )
		{
			++ $arResult["REVIEWS_CNT"];
		}
		
		// query
		$rsData = ReviewsTable::getList ( 
				array (
						'select' => array (
								'ID',
								'ID_USER',
								'ID_ELEMENT',
								'XML_ID_ELEMENT',
								'RATING',
								'DATE_CREATION',
								'TITLE',
								'ANSWER',
								'TEXT',
								'ADD_FIELDS',
								'LIKES',
								'DISLIKES',
								'RECOMMENDATED',
								'MULTIMEDIA' 
						),
						'filter' => $Filter,
						'order' => $sort,
						'limit' => $limit,
						'offset' => $offset 
				) );
		
		$i = 0;
		$ElemIds = array ();
		
		while ( $Review = $rsData->Fetch () )
		{
			
			$ElemIds[$i] = $Review['ID_ELEMENT'];
			$ElemXMLIds[$i] = $Review['XML_ID_ELEMENT'];
			
			$arResult['REVIEWS'][$i]['ID'] = $Review['ID'];
			$arResult['REVIEWS'][$i]['RATING'] = $Review['RATING'];
			$arResult['REVIEWS'][$i]['DATE_CREATION'] = CIBlockFormatProperties::DateFormat ( $arParams["DATE_FORMAT"], MakeTimeStamp ( $Review['DATE_CREATION'], CSite::GetDateFormat () ) );
			$arResult['REVIEWS'][$i]['TITLE'] = $Review['TITLE'];
			$arResult['REVIEWS'][$i]['TEXT'] = CSotbitReviews::bb2html ( $Review['TEXT'] );
			$arResult['REVIEWS'][$i]['ANSWER'] = CSotbitReviews::bb2html ( $Review['ANSWER'] );
			$arResult['REVIEWS'][$i]['ADD_FIELDS'] = unserialize ( $Review['ADD_FIELDS'] );
			$arResult['REVIEWS'][$i]['LIKES'] = $Review['LIKES'];
			$arResult['REVIEWS'][$i]['DISLIKES'] = $Review['DISLIKES'];
			$arResult['REVIEWS'][$i]['RECOMMENDATED'] = $Review['RECOMMENDATED'];
			$arResult['REVIEWS'][$i]['MULTIMEDIA'] = $Review['MULTIMEDIA'];
			
			if (Loader::includeModule ( 'fileman' ) && $arResult['USE_IMAGES'] == 'Y')
			{
				CMedialib::Init ();
				$arCollections = CMedialibCollection::GetList ( 
						array (
								'arOrder' => Array (
										'NAME' => 'ASC' 
								),
								'arFilter' => array (
										'ACTIVE' => 'Y',
										'PARENT_ID' => $arResult['SOTBIT_MEDIACOLLECTION_ID'],
										'NAME' => $Review['ID'] 
								) 
						) );
				
				if (isset ( $arCollections ) && is_array ( $arCollections ) && count ( $arCollections ) != 0 && $arCollections[0]['PARENT_ID'] == $arResult['SOTBIT_MEDIACOLLECTION_ID'] && $arCollections[0]['NAME'] == $Review['ID'])
				{
					$ID_COLLECTION = $arCollections[0]['ID'];
					$arItems = CMedialibItem::GetList ( array (
							'arCollections' => array (
									"0" => $ID_COLLECTION 
							) 
					) );
					if (isset ( $arItems ) && is_array ( $arItems ))
					{
						$k = 0;
						foreach ( $arItems as $arItem )
						{
							if ($arItem['TYPE'] == 'image')
							{
								$file = CFile::ResizeImageGet ( $arItem['SOURCE_ID'], 
										array (
												'width' => COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_THUMB_WIDTH_" . SITE_ID, "" ),
												'height' => COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_THUMB_HEIGHT_" . SITE_ID, "" ) 
										), BX_RESIZE_IMAGE_PROPORTIONAL, true );
								$arResult['REVIEWS'][$i]['THUMB_IMAGE'][$k] = $file['src'];
								$arResult['REVIEWS'][$i]['BIG_IMAGE'][$k] = $arItem['PATH'];
							}
							++ $k;
						}
					}
				}
			}
			++ $i;
		}
		
		// Site info
		$rsSites = CSite::GetByID ( SITE_ID );
		$arSite = $rsSites->Fetch ();
		$arResult['SITE_NAME'] = $arSite['SITE_NAME'];
		
		// Count of pages
		
		if ($arResult["REVIEWS_CNT"] % $arResult['PER_PAGE'] == 0)
			$arResult['CNT_PAGES'] = $arResult["REVIEWS_CNT"] / $arResult['PER_PAGE'];
		else
			$arResult['CNT_PAGES'] = floor ( $arResult["REVIEWS_CNT"] / $arResult['PER_PAGE'] ) + 1;
			
			// Products name
		$IDx = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
		
		if ($IDx == 'ID_ELEMENT')
		{
			$arElemValues = array_values ( $ElemIds );
			$FilterEl = array (
					'=ID' => $arElemValues 
			);
		}
		else
		{
			$arXMLElemValues = array_values ( $ElemXMLIds );
			$FilterEl = array (
					'=XML_ID' => $arXMLElemValues 
			);
		}
		$Elements = CIBlockElement::GetList ( array (
				"SORT" => "ASC" 
		), $FilterEl, false, false, array (
				'NAME',
				'ID',
				'XML_ID',
				'DETAIL_PAGE_URL',
				'ACTIVE'
		) );
		while ( $Element = $Elements->GetNext () )
		{
			if ($IDx == 'ID_ELEMENT')
			{
				foreach ( $ElemIds as $k => $val )
				{
					if ($Element['ID'] == $val)
					{
						$arResult['REVIEWS'][$k]['ELEMENT_NAME'] = $Element['NAME'];
						if ($Element['ACTIVE'] == 'N')
						{
							$arResult['REVIEWS'][$k]['ELEMENT_URL'] = '';
						}
						else
						{
							$arResult['REVIEWS'][$k]['ELEMENT_URL'] = $Element['DETAIL_PAGE_URL'];
						}
					}
				}
			}
			else
			{
				foreach ( $ElemXMLIds as $k => $val )
				{
					if ($Element['XML_ID'] == $val)
					{
						if ($Element['ACTIVE'] == 'N')
						{
							$arResult['REVIEWS'][$k]['ELEMENT_URL'] = '';
						}
						else 
						{
							$arResult['REVIEWS'][$k]['ELEMENT_NAME'] = $Element['NAME'];
						}
						$arResult['REVIEWS'][$k]['ELEMENT_URL'] = $Element['DETAIL_PAGE_URL'];
					}
				}
			}
		}
	}
	
	$arResult["CNT_LEFT_PGN"] = 3;
	$arResult["CNT_RIGHT_PGN"] = 3;
	
	$CACHE_MANAGER->StartTagCache ( $cachePath );
	$CACHE_MANAGER->RegisterTag ( 'sotbit_reviews_userreviews_list_' . $arParams['ID_USER'] );
	$CACHE_MANAGER->EndTagCache ();
	$obCache->EndDataCache ( $arResult );
}

$this->IncludeComponentTemplate ();
?>