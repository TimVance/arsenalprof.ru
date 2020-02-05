<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

use Sotbit\Reviews\BansTable;
use Bitrix\Main\Loader;
use Sotbit\Reviews\CommentsTable;
if (! Loader::includeModule( 'sotbit.reviews' ))
	return false;

IncludeTemplateLangFile( __FILE__ );

global $CACHE_MANAGER;

if (! isset( $arParams["PARENT"] ))
	$arParams["PARENT"] = 0;
if (! isset( $arParams["TEXTBOX_MAXLENGTH"] ))
	$arParams["TEXTBOX_MAXLENGTH"] = 100;
if (! isset( $arParams["PRIMARY_COLOR"] ))
	$arParams["PRIMARY_COLOR"] = "#a76e6e";
if (! isset( $arParams["BUTTON_BACKGROUND"] ))
	$arParams["BUTTON_BACKGROUND"] = "#dbbfb9";
if (! isset( $arParams["AJAX"] ))
	$arParams["AJAX"] = "N";
if (! isset( $arParams["CACHE_TIME"] ))
	$arParams["CACHE_TIME"] = 36000000;
if (! isset( $arParams["CACHE_TYPE"] ))
	$arParams["CACHE_TYPE"] = "A";

if ($arParams["AJAX"] != 'Y')
{
	$obCache = Bitrix\Main\Data\Cache::createInstance();
	$life_time = $arParams["CACHE_TIME"];
	$cache_id = 'sotbit_reviews_' . $arParams['ID_ELEMENT'];
	$cachePath = '/SotbitReviews';
	
	if (isset( $CacheValues['SOTBIT_REVIEWS_COMMENTS_ADD'] ))
	{
		$arResult = $CacheValues['SOTBIT_REVIEWS_COMMENTS_ADD'];
	}
	
	if (! isset( $arResult ) || ! is_array( $arResult ) || sizeof( $arResult ) == 0)
	{
		$arResult['RECAPTCHA2_SITE_KEY'] = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_RECAPTCHA2_SITE_KEY_" . SITE_ID, "" );
		
		$arResult['BAN'] = "N";
		$rsBan = BansTable::getList( 
				array (
						'select' => array (
								'ID',
								'REASON' 
						),
						'filter' => array (
								'=ACTIVE' => 'Y',
								'>=DATE_TO' => new \Bitrix\Main\Type\DateTime( date( 'd.m.Y H:i:s' ) ),
								array (
										"LOGIC" => "OR",
										array (
												"=IP" => $_SERVER["REMOTE_ADDR"] 
										),
										array (
												"=ID_USER" => $USER->GetID() 
										) 
								) 
						),
						'order' => array (
								'ID' => 'asc' 
						) 
				) );
		if ($Ban = $rsBan->Fetch())
		{
			$arResult['BAN'] = "Y";
			$arResult['REASON'] = $Ban['REASON'];
		}
		
		if ($arResult['BAN'] != "Y")
		{
			
			$arResult['REPEAT'] = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_REPEAT_" . SITE_ID, "-1" );
			
			$arResult['CAN_REPEAT'] = true;
			
			if ($arResult['REPEAT'] >= 0)
			{
				if ($USER->IsAuthorized())
				{
					
					$arResult['SHOP_ADMIN'] = unserialize( COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_SHOP_ADMINS_" . SITE_ID, array () ) );
					
					if (! in_array( $USER->GetID(), $arResult['SHOP_ADMIN'] ))
					{
						
						$IDx = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
						if ($IDx == 'ID_ELEMENT')
							$Filter = array (
									'=ID_ELEMENT' => $arParams['ID_ELEMENT'] 
							);
						else
							$Filter = array (
									'=XML_ID' => $arParams['ID_ELEMENT'] 
							);
						
						$Filter = array_merge( $Filter, array (
								'=ID_USER' => $USER->GetID() 
						) );
						
						// find last
						$rsFields = CommentsTable::getList( 
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
						if ($Fields = $rsFields->Fetch())
						{
							
							if (isset( $Fields['ID'] ) && $Fields['ID'] > 0)
							{
								if ($arResult['REPEAT'] == 0)
								{
									$arResult['CAN_REPEAT'] = false;
								}
								elseif ($arResult['REPEAT'] > 0)
								{
									$LastTime = CIBlockFormatProperties::DateFormat( 'd.m.Y H:i:s', MakeTimeStamp( $Fields['DATE_CREATION'], CSite::GetDateFormat() ) );
									
									$ReadyTime = date( 'd.m.Y H:i:s', strtotime( "+" . $arResult['REPEAT'] . " hours", strtotime( $LastTime ) ) );
									
									if (strtotime( $ReadyTime ) > strtotime( date( 'd.m.Y H:i:s' ) ))
									{
										$arResult['CAN_REPEAT'] = $ReadyTime;
									}
								}
							}
						}
					}
				}
			}
		}
		CSotbitReviews::SetCacheValues( 'SOTBIT_REVIEWS_COMMENTS_ADD', $arResult );
	}
}
$this->IncludeComponentTemplate();

?>