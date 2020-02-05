<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\QuestionsTable;
use Bitrix\Main\Localization\Loc;
global $APPLICATION;
global $USER;
global $CACHE_MANAGER;

if(!Loader::includeModule( 'sotbit.reviews' ) || !Loader::includeModule( 'iblock' ))
	return false;

if(!isset( $arParams['ID_USER'] ) || empty( $arParams['ID_USER'] ))
	return false;
	
// Set parameters if empty
if(!isset( $arParams["CACHE_TIME"] ))
	$arParams["CACHE_TIME"] = 36000000;
if(!isset( $arParams["CACHE_TYPE"] ))
	$arParams["CACHE_TYPE"] = "A";
if(!isset( $arParams["DATE_FORMAT"] ))
	$arParams["DATE_FORMAT"] = "d F Y, H:i";

$obCache = new CPHPCache();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_user_questions_' . $arParams['ID_USER'];
$cachePath = '/';

	if($obCache->InitCache( $life_time, $cache_id, $cachePath )) {
		$arResult = $obCache->GetVars();
	} else {
		$arResult["QUESTIONS_CNT"] = QuestionsTable::GetCount( array(
				'=ID_USER' => $arParams['ID_USER'],
				'=ACTIVE' => 'Y',
				'=MODERATED' => 'Y' 
		) );
		
		if($arResult["QUESTIONS_CNT"] > 0) {
			
			
			// User Info
			$rsUser = CUser::GetByID( $arParams['ID_USER'] );
			if($arUser = $rsUser->Fetch()) {
				$arResult['USER_NAME'] = trim( $arUser['NAME'] . ' ' . $arUser['LAST_NAME'] );
				if(isset( $arUser['PERSONAL_PHOTO'] ) && !empty( $arUser['PERSONAL_PHOTO'] ))
					$arResult['USER_PHOTO'] = CFile::GetPath( $arUser['PERSONAL_PHOTO'] );
					else
						$arResult['USER_PHOTO'] = COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_NO_USER_IMAGE_" . SITE_ID, "/bitrix/components/sotbit/reviews.user.questions/templates/bootstrap/images/no-photo.jpg" );
							
						$COUNTRIES = GetCountryArray( LANGUAGE_ID );
						if(isset( $arUser['PERSONAL_COUNTRY'] ) && !empty( $arUser['PERSONAL_COUNTRY'] ))
							$arResult['USER_COUNTRY'] = $COUNTRIES['reference'][array_search( $arUser['PERSONAL_COUNTRY'], $COUNTRIES['reference_id'] )];
								
							if(isset( $arUser['PERSONAL_BIRTHDAY'] ) && !empty( $arUser['PERSONAL_BIRTHDAY'] ))
								$arResult['USER_AGE'] = ( int ) ((date( 'Ymd' ) - date( 'Ymd', strtotime( $arResult['PERSONAL_BIRTHDAY'] ) )) / 10000);
			} else
				return false;
			

			// query
			$rsData = QuestionsTable::getList( 
					array(
							'select' => array(
									'ID',
									'ID_ELEMENT',
									'XML_ID_ELEMENT',
									'ID_USER',
									'DATE_CREATION',
									'QUESTION',
									'ANSWER'
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
			$ElemIds = array();
			$ElemXMLIds = array();
			while( $Question = $rsData->Fetch() ) {
				
				$ElemIds[$i] = $Question['ID_ELEMENT'];
				$ElemXMLIds[$i] = $Question['XML_ID_ELEMENT'];
				
				$arResult['QUESTIONS'][$i]['ID'] = $Question['ID'];
				$arResult['QUESTIONS'][$i]['DATE_CREATION'] = CIBlockFormatProperties::DateFormat( $arParams["DATE_FORMAT"], MakeTimeStamp( $Question['DATE_CREATION'], CSite::GetDateFormat() ) );
				$arResult['QUESTIONS'][$i]['QUESTION'] = CSotbitReviews::bb2html( $Question['QUESTION'] );
				$arResult['QUESTIONS'][$i]['ANSWER'] = CSotbitReviews::bb2html( $Question['ANSWER'] );
				++ $i;
			}
			
			// Site info
			$rsSites = CSite::GetByID( SITE_ID );
			$arSite = $rsSites->Fetch();
			$arResult['SITE_NAME'] = $arSite['SITE_NAME'];
				
				// Products name
			$IDx = COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
			
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
							$arResult['QUESTIONS'][$k]['ELEMENT_NAME'] = $Element['NAME'];
							$arResult['QUESTIONS'][$k]['ELEMENT_URL'] = $Element['DETAIL_PAGE_URL'];
						}
					}
				} else {
					foreach( $ElemXMLIds as $k => $val ) {
						if($Element['XML_ID'] == $val) {
							$arResult['QUESTIONS'][$k]['ELEMENT_NAME'] = $Element['NAME'];
							$arResult['QUESTIONS'][$k]['ELEMENT_URL'] = $Element['DETAIL_PAGE_URL'];
						}
					}
				}
			}
		}
		

		
		$CACHE_MANAGER->StartTagCache( $cachePath );
		$CACHE_MANAGER->RegisterTag( 'sotbit_reviews_user_questions_' . $arParams['ID_USER'] );
		$CACHE_MANAGER->EndTagCache();
		$obCache->EndDataCache( $arResult );
	}


$this->IncludeComponentTemplate();
?>