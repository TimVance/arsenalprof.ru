<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\ReviewsTable;
use Sotbit\Reviews\ReviewsfieldsTable;
global $APPLICATION;
global $USER;
global $CACHE_MANAGER;
if (! Loader::includeModule ( 'sotbit.reviews' ) || ! Loader::includeModule ( 'iblock' ))
	return false;

global $SotbitFilterRating;
global $SotbitFilterImages;
global $SotbitFilterSortOrder;
global $SotbitFilterSortBy;
global $SotbitFilterPage;
global $SotbitUseFilter;
global $URL;

if (isset ( $URL ) && ! empty ( $URL ))
{
	$Url = $URL;
}
else
{
	$Url = $APPLICATION->GetCurPage ();
}

if (! isset ( $arParams["MAX_RATING"] ))
	$arParams["MAX_RATING"] = 5;
if (! isset ( $arParams["AJAX"] ))
	$arParams["AJAX"] = "N";
if (! isset ( $arParams["DATE_FORMAT"] ))
	$arParams["DATE_FORMAT"] = "d F Y, H:i";
$IDx = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
$obCache = Bitrix\Main\Data\Cache::createInstance ();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_' . $arParams['ID_ELEMENT'];
$cachePath = '/SotbitReviews';
if ($arParams['AJAX'] != 'Y')
{
	$CacheValues = CSotbitReviews::GetCacheValues('');
	
	if(isset($CacheValues['SOTBIT_REVIEWS_REVIEWS_LIST']))
	{
		$arResult = $CacheValues['SOTBIT_REVIEWS_REVIEWS_LIST'];
	}

	if(!isset($arResult) || !is_array($arResult) || sizeof($arResult)==0)
	{
		if (! is_array ( CSotbitReviews::getReviewsCnt ()) && count ( CSotbitReviews::getReviewsCnt ()  < 1 ))
		{
			CSotbitReviews::setReviewsCnt ( $arParams['ID_ELEMENT'], $arParams["MAX_RATING"], SITE_ID );
		}
		$arResult = CSotbitReviews::getReviewsCnt ();
		// Get element name for microdata
		if ($IDx == 'ID_ELEMENT')
			$FilterEl = array (
					'=ID' => $arParams['ID_ELEMENT'] 
			);
		else
			$FilterEl = array (
					'=XML_ID' => $arParams['ID_ELEMENT'] 
			);
		$Elem = CIBlockElement::GetList ( array (
				"SORT" => "ASC" 
		), $FilterEl, false, false, array (
				'NAME',
				'DETAIL_PAGE_URL',
				'DETAIL_PICTURE',
				'PREVIEW_PICTURE' 
		) );
		$arResult['ELEMENT'] = $Elem->GetNext ();
		
		if (isset ( $SotbitFilterPage ) && ! empty ( $SotbitFilterPage ))
		{
			SetCookie ( "sotbit_reviews_filter_page", $SotbitFilterPage, time () + 3600 * 24 * 3, $Url );
		}
		else
		{
			SetCookie ( "sotbit_reviews_filter_page", '1', time () + 3600 * 24 * 3, $Url );
		}
		
		if (isset ( $SotbitFilterPage ) && ! empty ( $SotbitFilterPage ))
		{
			$arResult['CURRENT_PAGE'] = $SotbitFilterPage;
		}
		elseif (isset ( $_COOKIE['sotbit_reviews_filter_page'] ))
		{
			$arResult['CURRENT_PAGE'] = $_COOKIE['sotbit_reviews_filter_page'];
		}
		else
		{
			$arResult['CURRENT_PAGE'] = 1;
		}
		
		if (isset ( $SotbitFilterSortBy ) && ! empty ( $SotbitFilterSortBy ))
		{
			$arResult['FILTER_SORT_BY'] = $SotbitFilterSortBy;
			SetCookie ( "sotbit_reviews_filter_sort_by", $SotbitFilterSortBy, time () + 3600 * 24 * 3, $Url );
		}
		elseif (isset ( $_COOKIE['sotbit_reviews_filter_sort_by'] ))
		{
			$arResult['FILTER_SORT_BY'] = $_COOKIE['sotbit_reviews_filter_sort_by'];
		}
		else
		{
			$arResult['FILTER_SORT_BY'] = 'DATE_CREATION';
		}
		
		if (isset ( $SotbitFilterSortOrder ) && ! empty ( $SotbitFilterSortOrder ))
		{
			$arResult['FILTER_SORT_ORDER'] = $SotbitFilterSortOrder;
			SetCookie ( "sotbit_reviews_filter_sort_order", $SotbitFilterSortOrder, time () + 3600 * 24 * 3, $Url );
		}
		elseif (isset ( $_COOKIE['sotbit_reviews_filter_sort_order'] ))
		{
			$arResult['FILTER_SORT_ORDER'] = $_COOKIE['sotbit_reviews_filter_sort_order'];
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
		unset ( $SotbitFilterSortBy );
		unset ( $SotbitFilterSortOrder );
		$Filter = array (
				'=' . $IDx => $arParams['ID_ELEMENT'],
				'=ACTIVE' => 'Y',
				'=MODERATED' => 'Y' 
		);
		
		if (isset ( $SotbitFilterRating ) && ! empty ( $SotbitFilterRating ) && $SotbitFilterRating > 0)
		{
			$arResult['FILTER_RATING'] = $SotbitFilterRating;
			SetCookie ( "sotbit_reviews_filter_sort_rating", $SotbitFilterRating, time () + 3600 * 24 * 3, $Url );
		}
		elseif (isset ( $SotbitFilterRating ) && $SotbitFilterRating == - 1)
		{
			$arResult['FILTER_RATING'] = "";
			SetCookie ( "sotbit_reviews_filter_sort_rating", '', time () - 1000, $Url );
		}
		elseif (! isset ( $SotbitFilterRating ) && isset ( $_COOKIE['sotbit_reviews_filter_sort_rating'] ))
		{
			$arResult['FILTER_RATING'] = $_COOKIE['sotbit_reviews_filter_sort_rating'];
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
			$arResult['ADD_FIELDS'][$Fields['NAME']]['TITLE'] = (isset ( $Fields['TITLE'] ) && ! empty ( $Fields['TITLE'] )) ? $Fields['TITLE'] : GetMessage ( "REVIEWS_ADD_FIELD_TITLE_" . $Fields['NAME'] );
		}
		unset ( $rsFields );
		unset ( $Fields );
		
		if (Loader::includeModule ( 'fileman' ) && COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_" . SITE_ID, "" ) == 'Y' && isset ( $SotbitFilterImages ) && $SotbitFilterImages == 'Y')
		{
			$arResult['FILTER_IMAGES'] = $SotbitFilterImages;
			SetCookie ( "sotbit_reviews_filter_sort_images", $SotbitFilterImages, time () + 3600 * 24 * 3, $Url );
		}
		elseif (isset ( $SotbitFilterImages ) && $SotbitFilterImages == 'N')
		{
			$arResult['FILTER_IMAGES'] = $SotbitFilterImages;
			SetCookie ( "sotbit_reviews_filter_sort_images", '', time () - 1000, $Url );
		}
		elseif (isset ( $_COOKIE['sotbit_reviews_filter_sort_images'] ))
		{
			$arResult['FILTER_IMAGES'] = $_COOKIE['sotbit_reviews_filter_sort_images'];
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
			$arCollections = CMedialibCollection::GetList ( array (
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
		
		if (isset ( $SotbitUseFilter ) && $SotbitUseFilter == 'Y')
		{
			$arResult['USE_FILTER'] = $SotbitUseFilter;
			SetCookie ( "sotbit_reviews_filter_use_filter", $SotbitUseFilter, time () + 3600 * 24 * 3, $Url );
		}
		elseif (isset ( $_COOKIE["sotbit_reviews_filter_use_filter"] ) && $_COOKIE["sotbit_reviews_filter_use_filter"] == 'Y')
		{
			$arResult['USE_FILTER'] = $_COOKIE["sotbit_reviews_filter_use_filter"];
		}
		
		if (isset ( $arResult['USE_FILTER'] ) && $arResult['USE_FILTER'] == 'Y')
		{
			if (isset ( $arResult['CURRENT_PAGE'] ))
			{
				$limit = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_COUNT_PAGE_" . SITE_ID, "10" );
				$offset = ($arResult['CURRENT_PAGE'] - 1) * COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_COUNT_PAGE_" . SITE_ID, "10" );
			}
			else
			{
				$limit = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_COUNT_PAGE_" . SITE_ID, "10" );
				$offset = 0;
			}
			$arResult["REVIEWS_FILTER_CNT"] = ReviewsTable::GetCount ( $Filter );
		}
		else
		{
			$limit = $arResult['SUM_CNT_REVIEWS'];
			$offset = 0;
		}
		
		$rsData = ReviewsTable::getList ( 
				array (
						'select' => array (
								'ID',
								'ID_USER',
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
		$rsSites = CSite::GetByID ( SITE_ID );
		$arSite = $rsSites->Fetch ();
		$arResult['SITE_NAME'] = $arSite['SITE_NAME'];
		$ReviewsPerPage = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_COUNT_PAGE_" . SITE_ID, "10" );
		$AllCount = ReviewsTable::GetCount ( $Filter );
		if ($AllCount % $ReviewsPerPage == 0)
			$arResult['CNT_PAGES'] = $AllCount / $ReviewsPerPage;
		else
			$arResult['CNT_PAGES'] = floor ( $AllCount / $ReviewsPerPage ) + 1;
		$i = 0;
		$COUNTRIES = GetCountryArray ( LANGUAGE_ID );
		
		$UsersIds = array ();
		
		$ReviewsIds = array ();
		
		
		if (Loader::includeModule( 'fileman' ) && COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_" . SITE_ID, "" ) == 'Y')
		{
			CMedialib::Init();
				
			//parent collection
			$arCollections = CMedialibCollection::GetList( array(
					'arOrder' => Array(
							'NAME' => 'ASC'
							),
					'arFilter' => array(
							'ACTIVE' => 'Y',
							'NAME' => 'sotbit.reviews'
					)
			) );
			if(!$arCollections[0]['ID'])
			{
				unset($arCollections);
			}
			else
			{
				$ParentId = $arCollections[0]['ID'];
				$arCollections = CMedialibCollection::GetList( array(
						'arOrder' => Array(
								'NAME' => 'ASC'
								),
						'arFilter' => array(
								'ACTIVE' => 'Y',
								'PARENT_ID' => $ParentId
						)
				) );
			}
		}

		
		$arUsers = array();
		while ( $Review = $rsData->Fetch () )
		{
			$ReviewsIds[] = $Review['ID'];
			
			if ($Review['ID_USER'] > 0)
			{
				$UsersIds[] = $Review['ID_USER'];
				
				if(!isset($arUsers[$Review['ID_USER']]))
				{
					$Users = CUser::GetByID ( $Review['ID_USER'] );
					if ($User = $Users->Fetch ())
					{
						$arUsers[$Review['ID_USER']] = $User;
						unset ( $Users );
					}
					else
					{
						continue;
					}
				}
			}
			elseif ($Review['ID_USER'] == 0)
			{
				$User['NAME'] = GetMessage ( CSotbitReviews::iModuleID . '_REVIEWS_GUEST' );
				$User['LAST_NAME'] = "";
			}
			if (! isset ( $arUsers[$Review['ID_USER']]['NAME'] ))
			{
				$arUsers[$Review['ID_USER']]['NAME'] = '';
			}
			if (! isset ( $arUsers[$Review['ID_USER']]['LAST_NAME'] ))
			{
				$arUsers[$Review['ID_USER']]['LAST_NAME'] = '';
			}
			$UserName = trim ( $arUsers[$Review['ID_USER']]['NAME'] . ' ' . $arUsers[$Review['ID_USER']]['LAST_NAME'] );
			if (isset ( $arUsers[$Review['ID_USER']]['PERSONAL_PHOTO'] ) && ! empty ( $arUsers[$Review['ID_USER']]['PERSONAL_PHOTO'] ))
			{
				if(!isset($arUsers[$Review['ID_USER']]['USER_PHOTO']))
				{
					$arUsers[$Review['ID_USER']]['USER_PHOTO'] = CFile::GetPath ( $arUsers[$Review['ID_USER']]['PERSONAL_PHOTO'] );
				}
			}
			else
			{
				if(!isset($arUsers[$Review['ID_USER']]['USER_PHOTO']))
				{
					$arUsers[$Review['ID_USER']]['USER_PHOTO'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_NO_USER_IMAGE_" . SITE_ID, "/bitrix/components/sotbit/reviews.reviews.list/templates/bootstrap/images/no-photo.jpg" );
				}
			}
			$arResult['REVIEWS'][$i]['ID'] = $Review['ID'];
			$arResult['REVIEWS'][$i]['ID_USER'] = $Review['ID_USER'];
			$arResult['REVIEWS'][$i]['NAME'] = $UserName;
			$arResult['REVIEWS'][$i]['PERSONAL_PHOTO'] = $arUsers[$Review['ID_USER']]['USER_PHOTO'];
			$arResult['REVIEWS'][$i]['COUNTRY'] = (isset ( $User['PERSONAL_COUNTRY'] ) && ! empty ( $User['PERSONAL_COUNTRY'] )) ? $COUNTRIES['reference'][array_search ( $User['PERSONAL_COUNTRY'], $COUNTRIES['reference_id'] )] : '';
			$arResult['REVIEWS'][$i]['AGE'] = (isset ( $User['PERSONAL_BIRTHDAY'] ) && ! empty ( $User['PERSONAL_BIRTHDAY'] )) ? ( int ) ((date ( 'Ymd' ) - date ( 'Ymd', strtotime ( $User['PERSONAL_BIRTHDAY'] ) )) / 10000) : '';
			$arResult['REVIEWS'][$i]['RATING'] = $Review['RATING'];
			$arResult['REVIEWS'][$i]['DATE_CREATION'] = CIBlockFormatProperties::DateFormat ( $arParams["DATE_FORMAT"], MakeTimeStamp ( $Review['DATE_CREATION'], CSite::GetDateFormat () ) );
			$arResult['REVIEWS'][$i]['DATE_CREATION_ORIG'] = $Review['DATE_CREATION'];
			$arResult['REVIEWS'][$i]['TITLE'] = $Review['TITLE'];
			$arResult['REVIEWS'][$i]['TEXT'] = CSotbitReviews::bb2html ( $Review['TEXT'] );
			$arResult['REVIEWS'][$i]['ANSWER'] = CSotbitReviews::bb2html ( $Review['ANSWER'] );
			$arResult['REVIEWS'][$i]['ADD_FIELDS'] = unserialize ( $Review['ADD_FIELDS'] );
			$arResult['REVIEWS'][$i]['LIKES'] = $Review['LIKES'];
			$arResult['REVIEWS'][$i]['DISLIKES'] = $Review['DISLIKES'];
			$arResult['REVIEWS'][$i]['RECOMMENDATED'] = $Review['RECOMMENDATED'];
			$arResult['REVIEWS'][$i]['MULTIMEDIA'] = $Review['MULTIMEDIA'];
			

			if(isset($arCollections))
			{
				foreach($arCollections as $Collection)
				{
					if($Collection['NAME'] == $Review['ID'])
					{
						$ID_COLLECTION = $Collection['ID'];
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
			}
			
			if (isset ( $arResult['REVIEWS'][$i]['BIG_IMAGE'][0] ) && ! empty ( $arResult['REVIEWS'][$i]['BIG_IMAGE'][0] ))
			{
				$arResult['REVIEWS'][$i]['SHARE_IMAGE'] = $arResult['REVIEWS'][$i]['BIG_IMAGE'][0];
			}
			elseif (isset ( $arResult['ELEMENT']['PREVIEW_PICTURE'] ) && ! empty ( $arResult['ELEMENT']['PREVIEW_PICTURE'] ))
			{
				$arFile = CFile::GetFileArray ( $arResult['ELEMENT']['PREVIEW_PICTURE'] );
				$arResult['REVIEWS'][$i]['SHARE_IMAGE'] = $arFile['SRC'];
			}
			elseif (isset ( $arResult['ELEMENT']['DETAIL_PICTURE'] ) && ! empty ( $arResult['ELEMENT']['DETAIL_PICTURE'] ))
			{
				$arFile = CFile::GetFileArray ( $arResult['ELEMENT']['DETAIL_PICTURE'] );
				$arResult['REVIEWS'][$i]['SHARE_IMAGE'] = $arFile['SRC'];
			}
			else
			{
				$arResult['REVIEWS'][$i]['SHARE_IMAGE'] = '';
			}
			
			++ $i;
		}
		
		unset ( $Review );
		
		$rsData = ReviewsTable::getList ( array (
				'select' => array (
						'ID_USER' 
				),
				'filter' => array (
						'=ID_USER' => $UsersIds,
						'=ACTIVE' => 'Y',
						'=MODERATED' => 'Y' 
				) 
		)
		 );
		
		$LinkToUser = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_USER_PAGE_" . SITE_ID, "" );
		
		$arResult['USER_REVIEWS_CNT'] = array ();
		$arResult['LINK_TO_USER'] = array ();
		while ( $Review = $rsData->Fetch () )
		{
			if (! empty ( $LinkToUser ) && ! isset ( $arResult['LINK_TO_USER'][$Review['ID_USER']] ))
			{
				$arResult['LINK_TO_USER'][$Review['ID_USER']] = $LinkToUser . '?user=' . $Review['ID_USER'];
			}
			
			if (! isset ( $arResult['USER_REVIEWS_CNT'][$Review['ID_USER']] ))
			{
				$arResult['USER_REVIEWS_CNT'][$Review['ID_USER']] = 1;
			}
			else
			{
				++ $arResult['USER_REVIEWS_CNT'][$Review['ID_USER']];
			}
		}
		
		$arResult['SHARE_SERVICES'] = unserialize ( COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_SHARE_" . SITE_ID, "" ) );
		$arResult['SHARE_LINK'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_SHARE_LINK_" . SITE_ID, "Y" );
		
		$arResult['FACEBOOK_APP_ID'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_FACEBOOK_ID_" . SITE_ID, "" );
		
		$POST_RIGHT = $APPLICATION->GetGroupRight ( 'sotbit.reviews' );
		if ($POST_RIGHT == "W")
			$arResult['MODERATOR'] = "Y";
		else
			$arResult['MODERATOR'] = "N";
		unset ( $rsData );
		unset ( $Review );
		unset ( $Users );
		unset ( $User );
		unset ( $ProductName );
		unset ( $i );
		$arResult["CNT_LEFT_PGN"] = 3;
		$arResult["CNT_RIGHT_PGN"] = 3;
		
		$arResult['REVIEWS_IDS'] = serialize ( $ReviewsIds );
		
		CSotbitReviews::SetCacheValues('SOTBIT_REVIEWS_REVIEWS_LIST',$arResult);
	}
}

$this->IncludeComponentTemplate ();
?>