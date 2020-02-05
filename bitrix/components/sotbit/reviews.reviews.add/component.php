<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Bitrix\Main\Loader;
use Sotbit\Reviews\ReviewsTable;
use Sotbit\Reviews\BansTable;
use Sotbit\Reviews\ReviewsfieldsTable;
if (! Loader::includeModule ( 'sotbit.reviews' ))
	return false;
global $APPLICATION;
global $USER;
global $CACHE_MANAGER;
if (! isset ( $arParams["MAX_RATING"] ))
	$arParams["MAX_RATING"] = 5;
if (! isset ( $arParams["DEFAULT_RATING_ACTIVE"] ))
	$arParams["DEFAULT_RATING_ACTIVE"] = 3;
if (! isset ( $arParams["TEXTBOX_MAXLENGTH"] ))
	$arParams["TEXTBOX_MAXLENGTH"] = 100;
if (! isset ( $arParams["PRIMARY_COLOR"] ))
	$arParams["PRIMARY_COLOR"] = "#a76e6e";
if (! isset ( $arParams["BUTTON_BACKGROUND"] ))
	$arParams["BUTTON_BACKGROUND"] = "#dbbfb9";
if (! isset ( $arParams["ADD_REVIEW_PLACE"] ))
	$arParams["ADD_REVIEW_PLACE"] = 1;
if (! isset ( $arParams["AJAX"] ))
	$arParams["AJAX"] = "N";
$obCache = Bitrix\Main\Data\Cache::createInstance ();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_' . $arParams['ID_ELEMENT'];
$cachePath = '/SotbitReviews';
if ($arParams['AJAX'] != 'Y')
{
	$CacheValues = CSotbitReviews::GetCacheValues('');
	
	if(isset($CacheValues['SOTBIT_REVIEWS_REVIEWS_ADD']))
	{
		$arResult = $CacheValues['SOTBIT_REVIEWS_REVIEWS_ADD'];
	}
	if(!isset($arResult) || !is_array($arResult) || sizeof($arResult)==0)
	{
		$arResult['BAN'] = "N";
		$rsBan = BansTable::getList (
				array (
						'select' => array (
								'ID',
								'REASON'
						),
						'filter' => array (
								'=ACTIVE' => 'Y',
								'>=DATE_TO' => new \Bitrix\Main\Type\DateTime ( date ( 'd.m.Y H:i:s' ) ),
								array (
										"LOGIC" => "OR",
										array (
												"=IP" => $_SERVER["REMOTE_ADDR"]
										),
										array (
												"=ID_USER" => $USER->GetID ()
										)
								)
						),
						'order' => array (
								'ID' => 'asc'
						)
				) );
		if ($Ban = $rsBan->Fetch ())
		{
			$arResult['BAN'] = "Y";
			$arResult['REASON'] = $Ban['REASON'];
		}
		if ($arResult['BAN'] != "Y")
		{
				
			$arResult['REPEAT'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_REPEAT_" . SITE_ID, "-1" );
				
			$arResult['CAN_REPEAT'] = true;
				
			if ($arResult['REPEAT'] >= 0)
			{
				if ($USER->IsAuthorized ())
				{
						
					$IDx = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
					if ($IDx == 'ID_ELEMENT')
						$Filter = array (
								'=ID_ELEMENT' => $arParams['ID_ELEMENT']
						);
						else
							$Filter = array (
									'=XML_ID' => $arParams['ID_ELEMENT']
							);
								
							$Filter = array_merge ( $Filter, array (
									'=ID_USER' => $USER->GetID ()
							) );
								
							// find last
							$rsFields = ReviewsTable::getList (
									array (
											'select' => array (
													'ID',
													'DATE_CREATION'
											),
											'filter' => $Filter,
											'order' => array (
													'DATE_CREATION' => 'desc'
											),
											'limit' => 1
									) );
							if ($Fields = $rsFields->Fetch ())
							{
		
								if (isset ( $Fields['ID'] ) && $Fields['ID'] > 0)
								{
									if ($arResult['REPEAT'] == 0)
									{
										$arResult['CAN_REPEAT'] = false;
									}
									elseif ($arResult['REPEAT'] > 0)
									{
										$LastTime = CIBlockFormatProperties::DateFormat ( 'd.m.Y H:i:s', MakeTimeStamp ( $Fields['DATE_CREATION'], CSite::GetDateFormat () ) );
		
										$ReadyTime = date ( 'd.m.Y H:i:s', strtotime ( "+" . $arResult['REPEAT'] . " hours", strtotime ( $LastTime ) ) );
		
										if (strtotime ( $ReadyTime ) > strtotime ( date ( 'd.m.Y H:i:s' ) ))
										{
											$arResult['CAN_REPEAT'] = $ReadyTime;
										}
									}
								}
							}
				}
			}
				
			$rsFields = ReviewsfieldsTable::getList (
					array (
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
				$arResult['ADD_FIELDS'][$Fields['NAME']]['NAME'] = (isset ( $Fields['TITLE'] ) && ! empty ( $Fields['TITLE'] )) ? $Fields['TITLE'] : GetMessage ( "REVIEWS_ADD_FIELD_TITLE_" . $Fields['NAME'] );
			}
				
			$arResult['RECAPTCHA2_SITE_KEY'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_RECAPTCHA2_SITE_KEY_" . SITE_ID, "" );
				
			$IDx = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
			$arResult["REVIEWS_CNT"] = ReviewsTable::GetCount ( array (
					'=' . $IDx => $arParams['ID_ELEMENT'],
					'=ACTIVE' => 'Y',
					'=MODERATED' => 'Y'
			) );
			$arResult['VIDEO_ALLOW'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_MULTIMEDIA_VIDEO_ALLOW_" . SITE_ID, "" );
			$arResult['PRESENTATION_ALLOW'] = COption::GetOptionString ( CSotbitReviews::iModuleID, "REVIEWS_MULTIMEDIA_PRESENTATION_ALLOW_" . SITE_ID, "" );
		}
		unset ( $rsFields );
		unset ( $Fields );
		CSotbitReviews::SetCacheValues('SOTBIT_REVIEWS_REVIEWS_ADD',$arResult);
	}
}

// Out of cache because after each change order need clear cache
$arResult['REVIEWS_BUY'] = CSotbitReviews::IfUserBuy ( SITE_ID, $arParams['ID_ELEMENT'], $USER->GetID () );
$this->IncludeComponentTemplate ();
?>