<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\CommentsTable;
if (! Loader::includeModule ( 'sotbit.reviews' ) || ! Loader::includeModule ( 'iblock' ))
	return false;
global $APPLICATION;
global $USER;
global $CACHE_MANAGER;

global $SotbitFilterPageComments;

// Set parameters if empty
if (! isset ( $arParams["TEXTBOX_MAXLENGTH"] ))
	$arParams["TEXTBOX_MAXLENGTH"] = 100;
if (! isset ( $arParams["AJAX"] ))
	$arParams["AJAX"] = "N";
if (! isset ( $arParams["CACHE_TIME"] ))
	$arParams["CACHE_TIME"] = 36000000;
if (! isset ( $arParams["CACHE_TYPE"] ))
	$arParams["CACHE_TYPE"] = "A";
if (! isset ( $arParams["DATE_FORMAT"] ))
	$arParams["DATE_FORMAT"] = "d F Y, H:i";
global $SotbitCommentsList;
$IDx = COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
$ShopAdmins = unserialize ( COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_SHOP_ADMINS_" . SITE_ID, "a:0:{}" ) );
if (! isset ( $ShopAdmins ) || empty ( $ShopAdmins ))
	$ShopAdmins = array ();

$obCache = Bitrix\Main\Data\Cache::createInstance();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_' . $arParams['ID_ELEMENT'];
$cachePath = '/SotbitReviews';
$CacheValues = CSotbitReviews::GetCacheValues('');
if ($arParams['AJAX'] != 'Y')
{
	if(isset($CacheValues['SOTBIT_REVIEWS_COMMENTS_LIST']))
	{
		
		$arResult = $CacheValues['SOTBIT_REVIEWS_COMMENTS_LIST'];
	}
	if(!isset($arResult) || !is_array($arResult) || sizeof($arResult)==0)
	{
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



		if (isset ( $SotbitFilterPageComments ) && ! empty ( $SotbitFilterPageComments ))
		{
			$arResult['CURRENT_PAGE'] = $SotbitFilterPageComments;
		}
		elseif (isset ( $_COOKIE['sotbit_comments_filter_page'] ))
		{
			$arResult['CURRENT_PAGE'] = $_COOKIE['sotbit_comments_filter_page'];
		}
		else
		{
			$arResult['CURRENT_PAGE'] = 1;
		}


		if (isset ( $arResult['CURRENT_PAGE'] ))
		{
			$limit = COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_COUNT_PAGE_" . SITE_ID, "10" );
			$offset = ($arResult['CURRENT_PAGE'] - 1) * COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_COUNT_PAGE_" . SITE_ID, "10" );
		}
		else
		{
			$limit = COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_COUNT_PAGE_" . SITE_ID, "10" );
			$offset = 0;
		}


		// Get comments
		$rsData = CommentsTable::getList ( 
				array (
						'select' => array (
								'ID',
								'ID_PARENT',
								'ID_USER',
								'DATE_CREATION',
								'TEXT',
								'SHOWS' 
						),
						'filter' => array (
								'=' . $IDx => $arParams['ID_ELEMENT'],
								'=ACTIVE' => 'Y',
								'=MODERATED' => 'Y' 
						),
						'order' => array (
								'DATE_CREATION' => 'desc' 
						) 
				) );
		$i = 0;
		$COUNTRIES = GetCountryArray ( LANGUAGE_ID ); // list of countries
		$arResult['COMMENTS'] = array ();
		$arResult["CNT_COMMENTS"] = 0;
		$UsersIds = array ();
		$CommentsIds = array ();
		while ( $Comment = $rsData->Fetch () )
		{
			$CommentsIds[] = $Comment['ID'];
			
			++ $arResult["CNT_COMMENTS"];
			if ($Comment['ID_USER'] > 0)
			{
				
				$UsersIds[] = $Comment['ID_USER'];
				if (count ( $ShopAdmins ) > 0 && in_array ( $Comment['ID_USER'], $ShopAdmins ))
				{
					$rsSites = CSite::GetByID ( SITE_ID );
					$arSite = $rsSites->Fetch ();
					$User['NAME'] = $arSite['SITE_NAME'];
					$User['LAST_NAME'] = '';
					unset ( $rsSites );
					unset ( $arSite );
				}
				else
				{
					$Users = CUser::GetByID ( $Comment['ID_USER'] );
					if ($User = $Users->Fetch ())
					{
						unset ( $Users );
					}
					else
						continue;
				}
			}
			elseif ($Comment['ID_USER'] == 0)
			{
				$User['NAME'] = GetMessage ( CSotbitReviews::iModuleID . '_COMMENTS_GUEST' );
				$User['LAST_NAME'] = "";
			}
			if (! isset ( $User['NAME'] ))
				$User['NAME'] = '';
			if (! isset ( $User['LAST_NAME'] ))
				$User['LAST_NAME'] = '';
			$UserName = trim ( $User['NAME'] . ' ' . $User['LAST_NAME'] );
			if (isset ( $User['PERSONAL_PHOTO'] ) && ! empty ( $User['PERSONAL_PHOTO'] ))
				$UserPhoto = CFile::GetPath ( $User['PERSONAL_PHOTO'] );
			else
				$UserPhoto = COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_NO_USER_IMAGE_" . SITE_ID, "/bitrix/components/sotbit/reviews.comments.list/templates/.default/images/no-photo.jpg" );
			$Comments[$i]['ID'] = $Comment['ID'];
			$Comments[$i]['ID_PARENT'] = $Comment['ID_PARENT'];
			$Comments[$i]['ID_USER'] = $Comment['ID_USER'];
			$Comments[$i]['PERSONAL_PHOTO'] = $UserPhoto;
			$Comments[$i]['NAME'] = $UserName;
			$Comments[$i]['COUNTRY'] = (isset ( $User['PERSONAL_COUNTRY'] ) && ! empty ( $User['PERSONAL_COUNTRY'] )) ? $COUNTRIES['reference'][array_search ( $User['PERSONAL_COUNTRY'], $COUNTRIES['reference_id'] )] : '';
			$Comments[$i]['AGE'] = (isset ( $User['PERSONAL_BIRTHDAY'] ) && ! empty ( $User['PERSONAL_BIRTHDAY'] )) ? ( int ) ((date ( 'Ymd' ) - date ( 'Ymd', strtotime ( $User['PERSONAL_BIRTHDAY'] ) )) / 10000) : '';
			$Comments[$i]['DATE_CREATION'] = CIBlockFormatProperties::DateFormat ( $arParams["DATE_FORMAT"], MakeTimeStamp ( $Comment['DATE_CREATION'], CSite::GetDateFormat () ) );
			$Comments[$i]['TEXT'] = CSotbitReviews::bb2html ( $Comment['TEXT'] );
			$Comments[$i]['SHOP_ADMIN'] = (in_array ( $Comment['ID_USER'], $ShopAdmins )) ? "Y" : "N";
			$Comments[$i]['ID_USER'] = $Comment['ID_USER'];
			++ $i;
		}
		unset ( $rsData );
		unset ( $Comment );
		unset ( $Users );
		unset ( $User );
		unset ( $i );
		// Find level
		if (isset ( $Comments ) && is_array ( $Comments ))
		{
			foreach ( $Comments as $i => $Comment )
			{
				$Parent = $Comments[$i]['ID_PARENT'];
				$Id = $Comments[$i]['ID'];
				$level = 0;
				$IEl = $i;
				while ( $Parent != 0 )
				{
					$Parent = $Comments[$IEl]['ID_PARENT'];
					foreach ( $Comments as $k => $Commentk )
					{
						if ($Commentk['ID'] == $Parent)
						{
							$IEl = $k;
						}
					}
					if ($Parent > 0)
						++ $level;
					else
						break;
					if ($level > 9) // 10 levels max
						break;
				}
				$Comments[$i]['LEVEL'] = $level;
			}
		}
		$Sorting = array ();
		// Sort
		for($i = count ( $Comments ) - 1; $i >= 0; -- $i)
		{
			$Id = $Comments[$i]['ID'];
			$Parent = $Comments[$i]['ID_PARENT'];
			if ($Parent == 0)
			{
				$Childs = CSotbitReviews::SortComments ( $Id, $Comments, array () );
				if (isset ( $Childs ) && is_array ( $Childs ))
				{
					$Childs = array_reverse ( $Childs, true );
					foreach ( $Childs as $Child )
					{
						$Sorting[] = $Child;
					}
				}
				$Sorting[] = $Comments[$i];
			}
		}
		
		$arResult['COMMENTS'] = array_reverse ( $Sorting, true );



		$l = 0;

		foreach($arResult['COMMENTS'] as $i=> $comment)
		{
			if($comment['LEVEL'] == 0)
			{
				++$l;
			}
			if($l > $limit + $offset || $l <= $offset)
			{
				unset($arResult['COMMENTS'][$i]);
			}
		}


		$CommentsPerPage = COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_COUNT_PAGE_" . SITE_ID,
			"10" );
		$AllCount = $l;
		if ($AllCount % $CommentsPerPage == 0)
			$arResult['CNT_PAGES'] = $AllCount / $CommentsPerPage;
		else
			$arResult['CNT_PAGES'] = floor ( $AllCount / $CommentsPerPage ) + 1;


		$POST_RIGHT = $APPLICATION->GetGroupRight ( 'sotbit.reviews' );
		if ($POST_RIGHT == "W")
			$arResult['MODERATOR'] = "Y";
		else
			$arResult['MODERATOR'] = "N";
		
		unset ( $Comment );
		
		$rsData = CommentsTable::getList ( array (
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
		
		$LinkToUser = COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_USER_PAGE_" . SITE_ID, "" );
		
		$arResult['USER_COMMENTS_CNT'] = array ();
		$arResult['LINK_TO_USER'] = array ();
		while ( $Comment = $rsData->Fetch () )
		{
			if (! empty ( $LinkToUser ) && ! isset ( $arResult['LINK_TO_USER'][$Comment['ID_USER']] ))
			{
				$arResult['LINK_TO_USER'][$Comment['ID_USER']] = $LinkToUser . '?user=' . $Comment['ID_USER'];
			}
			
			if (! isset ( $arResult['USER_COMMENTS_CNT'][$Comment['ID_USER']] ))
			{
				$arResult['USER_COMMENTS_CNT'][$Comment['ID_USER']] = 1;
			}
			else
			{
				++ $arResult['USER_COMMENTS_CNT'][$Comment['ID_USER']];
			}
		}
		
		$arResult['SHARE_SERVICES'] = unserialize ( COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_SHARE_" . SITE_ID, "" ) );
		$arResult['SHARE_LINK'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_SHARE_LINK_" . SITE_ID, "Y" );
		
		$arResult['FACEBOOK_APP_ID'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "COMMENTS_FACEBOOK_ID_" . SITE_ID, "" );
		
		$arResult['COMMENTS_IDS'] = serialize ( $CommentsIds );
		
		CSotbitReviews::SetCacheValues('SOTBIT_REVIEWS_COMMENTS_LIST',$arResult);

		$arResult["CNT_LEFT_PGN"] = 3;
		$arResult["CNT_RIGHT_PGN"] = 3;





	}
}

$this->IncludeComponentTemplate ();
?>