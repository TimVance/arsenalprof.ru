<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\ReviewsTable;
use Sotbit\Reviews\ReviewsfieldsTable;
use Sotbit\Reviews\AnaliticTable;
use Bitrix\Main\Localization\Loc;
global $APPLICATION;
global $USER;
global $CACHE_MANAGER;

if(!Loader::includeModule( 'sotbit.reviews' ) || !Loader::includeModule( 'iblock' ))
	return false;

if(!isset( $arParams['ID_USER'] ) || empty( $arParams['ID_USER'] ))
	return false;


if(!isset( $arParams["MAX_RATING"] ))
	$arParams["MAX_RATING"] = 5;
if(!isset( $arParams["DATE_FORMAT"] ))
	$arParams["DATE_FORMAT"] = "d F Y, H:i";

$obCache = Bitrix\Main\Data\Cache::createInstance();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_user_personalreviews_' . $arParams['ID_USER'];
$cachePath = '/';
if($obCache->InitCache( $life_time, $cache_id, $cachePath )) {
	$arResult = $obCache->GetVars();
} elseif ($obCache->StartDataCache ()) {
	$arResult["REVIEWS_CNT"] = ReviewsTable::GetCount( array(
			'=ID_USER' => $arParams['ID_USER']
	) );

	if($arResult["REVIEWS_CNT"] > 0) {

		$arResult['USE_TITLE'] = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_TITLE_" . SITE_ID, "" );

		$arResult['VIDEO_ALLOW']=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_MULTIMEDIA_VIDEO_ALLOW_".SITE_ID, "");
		$arResult['PRESENTATION_ALLOW']=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_MULTIMEDIA_PRESENTATION_ALLOW_".SITE_ID, "");

		$arResult['RECAPTCHA2_SITE_KEY']=COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_RECAPTCHA2_SITE_KEY_".SITE_ID, "" );

		//Add fields
		$rsFields = ReviewsfieldsTable::getList( array(
				'select' => array(
						'NAME',
						'TYPE',
						'TITLE'
				),
				'filter' => array(
						'=ACTIVE' => 'Y'
				),
				'order' => array(
						'SORT' => 'asc'
				)
		) );
		while ( $Fields = $rsFields->Fetch() )
		{
			$arResult['ADD_FIELDS'][$Fields['NAME']]['TYPE'] = $Fields['TYPE'];
			$arResult['ADD_FIELDS'][$Fields['NAME']]['TITLE'] = (isset( $Fields['TITLE'] ) && !empty( $Fields['TITLE'] )) ? $Fields['TITLE'] : GetMessage( "REVIEWS_ADD_FIELD_TITLE_" . $Fields['NAME'] );
		}

		unset( $rsFields );
		unset( $Fields );





		// query
		$rsData = ReviewsTable::getList( array(
				'select' => array(
						'*'
				),
				// 'filter' => array(),
				'filter' => array('=ID_USER' => $arParams['ID_USER']),
				'order' => array(
						'DATE_CREATION' => 'desc'
				)
		) );

		$i = 0;
		$ElemIds = array();
		$Ids = array();

		$rsSites = CSite::GetByID( SITE_ID );
		$arSite = $rsSites->Fetch();
		$arResult['SITE_NAME'] = $arSite['SITE_NAME'];


		while( $Review = $rsData->Fetch() ) {
			$Ids[] = $Review['ID'];
			$ElemIds[$i] = $Review['ID_ELEMENT'];
			$ElemXMLIds[$i] = $Review['XML_ID_ELEMENT'];

			$arResult['REVIEWS'][$i]['ID'] = $Review['ID'];
			$arResult['REVIEWS'][$i]['RATING'] = $Review['RATING'];
			$arResult['REVIEWS'][$i]['DATE_CREATION'] = CIBlockFormatProperties::DateFormat( $arParams["DATE_FORMAT"], MakeTimeStamp( $Review['DATE_CREATION'], CSite::GetDateFormat() ) );
			$arResult['REVIEWS'][$i]['DATE_CREATION_ORIG'] = $Review['DATE_CREATION'];
			$arResult['REVIEWS'][$i]['TITLE'] = $Review['TITLE'];
			$arResult['REVIEWS'][$i]['ANSWER'] = CSotbitReviews::bb2html($Review['ANSWER']);
			$arResult['REVIEWS'][$i]['TEXT'] = CSotbitReviews::bb2html( $Review['TEXT'] );
			$arResult['REVIEWS'][$i]['LIKES'] = $Review['LIKES'];
			$arResult['REVIEWS'][$i]['DISLIKES'] = $Review['DISLIKES'];
			$arResult['REVIEWS'][$i]['ADD_FIELDS'] = unserialize( $Review['ADD_FIELDS'] );
			$arResult['REVIEWS'][$i]['RECOMMENDATED'] = $Review['RECOMMENDATED'];
			$arResult['REVIEWS'][$i]['MULTIMEDIA'] = $Review['MULTIMEDIA'];
			$arResult['REVIEWS'][$i]['SHOWS'] = $Review['SHOWS'];

			if($Review['MODERATED'] == 'N' && $Review['ACTIVE'] == 'Y') {
				$arResult['REVIEWS'][$i]['STATUS'] = 1;
			} elseif($Review['ACTIVE'] == 'N') {
				$arResult['REVIEWS'][$i]['STATUS'] = 2;
			} elseif($Review['MODERATED'] == 'Y') {
				$arResult['REVIEWS'][$i]['STATUS'] = 3;
			}


			++ $i;
		}

		$BillAdd = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_BILL_ADD_REVIEW_" . SITE_ID, "" );
		$BillLike = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_BILL_LIKE_REVIEW_" . SITE_ID, "" );
		$BillDislike = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_BILL_DISLIKE_REVIEW_" . SITE_ID, "" );


		if((isset( $BillAdd ) && $BillAdd > 0) || (isset( $BillLike ) && $BillLike > 0) || (isset( $BillDislike ) && $BillDislike > 0)) {
			$arBills = array();
			$rsAnalitic = AnaliticTable::getList(
					array(
							'select' => array(
									'ID',
									'ID_RCQ',
									'ID_USER',
									'ACTION',
									'DATE_CREATION',
									'VALUE'
							),
							'filter' => array(
									'ID_RCQ' => $Ids,
									"=ACTION" => array(
											3,
											4,
											5
									)
							),
							'order' => array(
									'ID' => 'desc'
							)
					) );
			while( $arAnalitic = $rsAnalitic->Fetch() ) {

				if(!isset( $arBills[$arAnalitic['ID_RCQ']] )) {
					$arBills[$arAnalitic['ID_RCQ']] = $arAnalitic['VALUE'];
				} else {
					$arBills[$arAnalitic['ID_RCQ']] += $arAnalitic['VALUE'];
				}
			}

			foreach( $arResult['REVIEWS'] as $k => $Review ) {
				if(isset( $arBills[$Review['ID']] )) {
					$arResult['REVIEWS'][$k]['BILL'] = $arBills[$Review['ID']];
				} else {
					$arResult['REVIEWS'][$k]['BILL'] = 0;
				}
			}
			$arReviews['USE_BILL']='Y';
		}
		else
		{
			$arReviews['USE_BILL']='N';
		}

		// Products name
		$IDx = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );

		if($IDx == 'ID_ELEMENT') {
			$arElemValues = array_values( $ElemIds );
			$FilterEl = array(
					'=ID' => $arElemValues,
					'=ACTIVE' => 'Y'
			);
		} else {
			$arXMLElemValues = array_values( $ElemXMLIds );
			$FilterEl = array(
					'=XML_ID' => $arXMLElemValues,
					'=ACTIVE' => 'Y'
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
						$arResult['REVIEWS'][$k]['ELEMENT_NAME'] = $Element['NAME'];
						$arResult['REVIEWS'][$k]['ELEMENT_URL'] = $Element['DETAIL_PAGE_URL'];
					}
				}
			} else {
				foreach( $ElemXMLIds as $k => $val ) {
					if($Element['XML_ID'] == $val) {
						$arResult['REVIEWS'][$k]['ELEMENT_NAME'] = $Element['NAME'];
						$arResult['REVIEWS'][$k]['ELEMENT_URL'] = $Element['DETAIL_PAGE_URL'];
					}
				}
			}
		}
	}
	$CurrencyId = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_BILL_CURRENCY_REVIEW_" . SITE_ID, "RUB" );
	$arResult['CURRENCY'] = $CurrencyId;

	$CACHE_MANAGER->StartTagCache( $cachePath );
	$CACHE_MANAGER->RegisterTag( 'sotbit_reviews_user_personalreviews_' . $arParams['ID_USER'] );
	$CACHE_MANAGER->EndTagCache();
	$obCache->EndDataCache( $arResult );
}
$this->IncludeComponentTemplate();
?>