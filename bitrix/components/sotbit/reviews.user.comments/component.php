<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\CommentsTable;
use Bitrix\Main\Localization\Loc;

if(!Loader::includeModule( 'sotbit.reviews' ) || !Loader::includeModule( 'iblock' ))
	return false;

if(!isset( $arParams['ID_USER'] ) || empty( $arParams['ID_USER'] ))
	return false;

global $APPLICATION;
global $USER;
global $CACHE_MANAGER;

// Set parameters if empty
if(!isset( $arParams["CACHE_TIME"] ))
	$arParams["CACHE_TIME"] = 36000000;
if(!isset( $arParams["CACHE_TYPE"] ))
	$arParams["CACHE_TYPE"] = "A";
if(!isset( $arParams["DATE_FORMAT"] ))
	$arParams["DATE_FORMAT"] = "d F Y, H:i";

$obCache = new CPHPCache();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_user_comments_' . $arParams['ID_USER'];
$cachePath = '/';
if($obCache->InitCache( $life_time, $cache_id, $cachePath )) {
	$arResult = $obCache->GetVars();
} else {
	$arResult["COMMENTS_CNT"] = CommentsTable::GetCount( array(
			'=ID_USER' => $arParams['ID_USER'],
			'=ACTIVE' => 'Y',
			'=MODERATED' => 'Y' 
	) );
	if($arResult["COMMENTS_CNT"] > 0) {
		
		// User Info
		$rsUser = CUser::GetByID( $arParams['ID_USER'] );
		if($arUser = $rsUser->Fetch()) {
			$arResult['USER_NAME'] = trim( $arUser['NAME'] . ' ' . $arUser['LAST_NAME'] );
			if(isset( $arUser['PERSONAL_PHOTO'] ) && !empty( $arUser['PERSONAL_PHOTO'] ))
				$arResult['USER_PHOTO'] = CFile::GetPath( $arUser['PERSONAL_PHOTO'] );
			else
				$arResult['USER_PHOTO'] = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_NO_USER_IMAGE_" . SITE_ID, "/bitrix/components/sotbit/reviews.user.comments/templates/bootstrap/images/no-photo.jpg" );
			
			$COUNTRIES = GetCountryArray( LANGUAGE_ID );
			if(isset( $arUser['PERSONAL_COUNTRY'] ) && !empty( $arUser['PERSONAL_COUNTRY'] ))
				$arResult['USER_COUNTRY'] = $COUNTRIES['reference'][array_search( $arUser['PERSONAL_COUNTRY'], $COUNTRIES['reference_id'] )];
			
			if(isset( $arUser['PERSONAL_BIRTHDAY'] ) && !empty( $arUser['PERSONAL_BIRTHDAY'] ))
				$arResult['USER_AGE'] = ( int ) ((date( 'Ymd' ) - date( 'Ymd', strtotime( $arResult['PERSONAL_BIRTHDAY'] ) )) / 10000);
		} else
			return false;
			
			// Get comments
		$rsData = CommentsTable::getList( 
				array(
						'select' => array(
								'ID',
								'ID_ELEMENT',
								'XML_ID_ELEMENT',
								'ID_USER',
								'DATE_CREATION',
								'TEXT' 
						),
						'filter' => array(
								'=ID_USER' => $arParams['ID_USER'],
								'=ACTIVE' => 'Y',
								'=MODERATED' => 'Y' 
						),
						'order' => array(
								'DATE_CREATION' => 'desc' 
						) 
				) );
		
		$i = 0;
		
		$arResult['COMMENTS'] = array();
		$ElemIds = array();
		$ElemXMLIds = array();
		
		while( $Comment = $rsData->Fetch() ) {
			
			$ElemIds[$i] = $Comment['ID_ELEMENT'];
			$ElemXMLIds[$i] = $Comment['XML_ID_ELEMENT'];
			
			$arResult['COMMENTS'][$i]['ID'] = $Comment['ID'];
			$arResult['COMMENTS'][$i]['DATE_CREATION'] = CIBlockFormatProperties::DateFormat( $arParams["DATE_FORMAT"], MakeTimeStamp( $Comment['DATE_CREATION'], CSite::GetDateFormat() ) );
			$arResult['COMMENTS'][$i]['TEXT'] = CSotbitReviews::bb2html( $Comment['TEXT'] );
			++ $i;
		}
		unset( $rsData );
		unset( $Comment );
		unset( $i );
		
		// Products name
		$IDx = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
		
		if($IDx == 'ID_ELEMENT') {
			$arElemValues = array_values( $ElemIds );
			$FilterEl = array(
					'=ID' => $arElemValues 
			);
		} else {
			$arXMLElemValues = array_values( $ElemXMLIds );
			$FilterEl = array(
					'=XML_ID' => $arXMLElemValues 
			);
		}
		$Elements = CIBlockElement::GetList( array(
				"SORT" => "ASC" 
		), $FilterEl, false, false, array(
				'NAME',
				'ID',
				'XML_ID',
				'DETAIL_PAGE_URL' 
		) );
		while( $Element = $Elements->GetNext() ) {
			if($IDx == 'ID_ELEMENT') {
				foreach( $ElemIds as $k => $val ) {
					if($Element['ID'] == $val) {
						$arResult['COMMENTS'][$k]['ELEMENT_NAME'] = $Element['NAME'];
						$arResult['COMMENTS'][$k]['ELEMENT_URL'] = $Element['DETAIL_PAGE_URL'];
					}
				}
			} else {
				foreach( $ElemXMLIds as $k => $val ) {
					if($Element['XML_ID'] == $val) {
						$arResult['COMMENTS'][$k]['ELEMENT_NAME'] = $Element['NAME'];
						$arResult['COMMENTS'][$k]['ELEMENT_URL'] = $Element['DETAIL_PAGE_URL'];
					}
				}
			}
		}
	}
	
	$CACHE_MANAGER->StartTagCache( $cachePath );
	$CACHE_MANAGER->RegisterTag( 'sotbit_reviews_user_comments_' . $arParams['ID_USER'] );
	$CACHE_MANAGER->EndTagCache();
	$obCache->EndDataCache( $arResult );
}
$this->IncludeComponentTemplate();
?>