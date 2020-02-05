<?

if(!defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true)
	die();
if(!CModule::IncludeModule( "iblock" ))
	return;
$iModuleID = 'sotbit_reviews';
$arComponentParameters = array(
		"GROUPS" => array(),
		"PARAMETERS" => array(
				"MAX_RATING" => array(
						"PARENT" => "BASE",
						"NAME" => GetMessage( $iModuleID . "_MAX_RATING" ),
						"TYPE" => "STRING",
						"DEFAULT" => 5 
				),
				"PRIMARY_COLOR" => array(
						"PARENT" => "BASE",
						"NAME" => GetMessage( $iModuleID . "_PRIMARY_COLOR" ),
						"TYPE" => "STRING",
						"DEFAULT" => "#a76e6e" 
				),
				"NOTICE_EMAIL" => array(
						"PARENT" => "BASE",
						"NAME" => GetMessage($iModuleID."_NOTICE_EMAIL"),
						"TYPE" => "STRING",
				),
				"DATE_FORMAT" => CIBlockParameters::GetDateFormat( GetMessage( $iModuleID . "_DATE_FORMAT" ), "BASE" ),
				"ID_USER" => array(
						"PARENT" => "BASE",
						"NAME" => GetMessage( $iModuleID . "_ID_USER" ),
						"TYPE" => "STRING",
						"DEFAULT" => '={$USER->GetID()}' 
				),
				"CACHE_TIME" => array(
						"DEFAULT" => 36000000 
				) 
		) 
);
?>