<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\CommentsTable;
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
$cache_id = 'sotbit_reviews_user_personalcomments_' . $arParams['ID_USER'];
$cachePath = '/';
if($obCache->InitCache( $life_time, $cache_id, $cachePath )) {
	$arResult = $obCache->GetVars();
} elseif ($obCache->StartDataCache ()) {
	$arResult["COMMENTS_CNT"] = CommentsTable::GetCount( array(
			'=ID_USER' => $arParams['ID_USER'],
	) );

	if($arResult["COMMENTS_CNT"] > 0) {


		$arResult['RECAPTCHA2_SITE_KEY']=COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_RECAPTCHA2_SITE_KEY_".SITE_ID, "" );


		// query
		$rsData = CommentsTable::getList( array(
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

		while( $Comment = $rsData->Fetch() ) {
			$Ids[] = $Comment['ID'];
			$ElemIds[$i] = $Comment['ID_ELEMENT'];
			$ElemXMLIds[$i] = $Comment['XML_ID_ELEMENT'];

			$arResult['COMMENTS'][$i]['ID'] = $Comment['ID'];
			$arResult['COMMENTS'][$i]['DATE_CREATION'] = CIBlockFormatProperties::DateFormat( $arParams["DATE_FORMAT"], MakeTimeStamp( $Comment['DATE_CREATION'], CSite::GetDateFormat() ) );
			$arResult['COMMENTS'][$i]['DATE_CREATION_ORIG'] = $Comment['DATE_CREATION'];
			$arResult['COMMENTS'][$i]['TEXT'] = CSotbitReviews::bb2html( $Comment['TEXT'] );
			$arResult['COMMENTS'][$i]['SHOWS'] = $Comment['SHOWS'];

			if($Comment['MODERATED'] == 'N' && $Comment['ACTIVE'] == 'Y') {
				$arResult['COMMENTS'][$i]['STATUS'] = 1;
			} elseif($Comment['ACTIVE'] == 'N') {
				$arResult['COMMENTS'][$i]['STATUS'] = 2;
			} elseif($Comment['MODERATED'] == 'Y') {
				$arResult['COMMENTS'][$i]['STATUS'] = 3;
			}


			++ $i;
		}

		$BillAdd = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_BILL_ADD_COMMENT_" . SITE_ID, "" );

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
											'6'
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

			foreach( $arResult['COMMENTS'] as $k => $Comment ) {
				if(isset( $arBills[$Comment['ID']] )) {
					$arResult['COMMENTS'][$k]['BILL'] = $arBills[$Comment['ID']];
				} else {
					$arResult['COMMENTS'][$k]['BILL'] = 0;
				}
			}
			$arComments['USE_BILL']='Y';
		}
		else
		{
			$arComments['USE_BILL']='N';
		}

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

	$CurrencyId = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_BILL_CURRENCY_" . SITE_ID, "RUB" );
	$arResult['CURRENCY'] = $CurrencyId;

	$CACHE_MANAGER->StartTagCache( $cachePath );
	$CACHE_MANAGER->RegisterTag( 'sotbit_reviews_user_personalcomments_' . $arParams['ID_USER'] );
	$CACHE_MANAGER->EndTagCache();
	$obCache->EndDataCache( $arResult );
}
$this->IncludeComponentTemplate();
?>