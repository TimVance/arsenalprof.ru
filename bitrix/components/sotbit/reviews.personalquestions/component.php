<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\QuestionsTable;
use Sotbit\Reviews\AnaliticTable;
use Bitrix\Main\Localization\Loc;
global $APPLICATION;
global $USER;
global $CACHE_MANAGER;

if(!Loader::includeModule( 'sotbit.reviews' ) || !Loader::includeModule( 'iblock' ))
	return false;

if(!isset( $arParams['ID_USER'] ) || empty( $arParams['ID_USER'] ))
	return false;


if(!isset( $arParams["DATE_FORMAT"] ))
	$arParams["DATE_FORMAT"] = "d F Y, H:i";

$obCache = Bitrix\Main\Data\Cache::createInstance();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_user_personalquestions_' . $arParams['ID_USER'];
$cachePath = '/';
if($obCache->InitCache( $life_time, $cache_id, $cachePath )) {
	$arResult = $obCache->GetVars();
} elseif ($obCache->StartDataCache ()) {
	$arResult["QUESTIONS_CNT"] = QuestionsTable::GetCount( array(
			'=ID_USER' => $arParams['ID_USER'],
	) );

	if($arResult["QUESTIONS_CNT"] > 0) {

		$rsSites = CSite::GetByID( SITE_ID );
		$arSite = $rsSites->Fetch();
		$arResult['SITE_NAME'] = $arSite['SITE_NAME'];
		$arResult['RECAPTCHA2_SITE_KEY']=COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_RECAPTCHA2_SITE_KEY_".SITE_ID, "" );


		// query
		$rsData = QuestionsTable::getList( array(
				'select' => array(
						'*'
				),
				'filter' => array(),
				'order' => array(
						'DATE_CREATION' => 'desc'
				)
		) );

		$i = 0;
		$ElemIds = array();
		$Ids = array();

		while( $Question = $rsData->Fetch() ) {
			$Ids[] = $Question['ID'];
			$ElemIds[$i] = $Question['ID_ELEMENT'];
			$ElemXMLIds[$i] = $Question['XML_ID_ELEMENT'];

			$arResult['QUESTIONS'][$i]['ID'] = $Question['ID'];
			$arResult['QUESTIONS'][$i]['DATE_CREATION'] = CIBlockFormatProperties::DateFormat( $arParams["DATE_FORMAT"], MakeTimeStamp( $Question['DATE_CREATION'], CSite::GetDateFormat() ) );
			$arResult['QUESTIONS'][$i]['DATE_CREATION_ORIG'] = $Question['DATE_CREATION'];
			$arResult['QUESTIONS'][$i]['QUESTION'] = CSotbitReviews::bb2html( $Question['QUESTION'] );
			$arResult['QUESTIONS'][$i]['ANSWER'] = CSotbitReviews::bb2html( $Question['ANSWER'] );
			$arResult['QUESTIONS'][$i]['SHOWS'] = $Question['SHOWS'];

			if($Question['MODERATED'] == 'N' && $Question['ACTIVE'] == 'Y') {
				$arResult['QUESTIONS'][$i]['STATUS'] = 1;
			} elseif($Question['ACTIVE'] == 'N') {
				$arResult['QUESTIONS'][$i]['STATUS'] = 2;
			} elseif($Question['MODERATED'] == 'Y') {
				$arResult['QUESTIONS'][$i]['STATUS'] = 3;
			}


			++ $i;
		}

		$BillAdd = COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_BILL_ADD_QUESTION_" . SITE_ID, "" );

		if((isset( $BillAdd ) && $BillAdd > 0) ) {

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
											'7'
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

			foreach( $arResult['QUESTIONS'] as $k => $Question ) {
				if(isset( $arBills[$Question['ID']] )) {
					$arResult['QUESTIONS'][$k]['BILL'] = $arBills[$Question['ID']];
				} else {
					$arResult['QUESTIONS'][$k]['BILL'] = 0;
				}
			}
			$arQuestions['USE_BILL']='Y';
		}
		else
		{
			$arQuestions['USE_BILL']='N';
		}

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

	$CurrencyId = COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_BILL_CURRENCY_" . SITE_ID, "RUB" );
	$arResult['CURRENCY'] = $CurrencyId;

	$CACHE_MANAGER->StartTagCache( $cachePath );
	$CACHE_MANAGER->RegisterTag( 'sotbit_reviews_user_personalquestions_' . $arParams['ID_USER'] );
	$CACHE_MANAGER->EndTagCache();
	$obCache->EndDataCache( $arResult );
}
$this->IncludeComponentTemplate();
?>