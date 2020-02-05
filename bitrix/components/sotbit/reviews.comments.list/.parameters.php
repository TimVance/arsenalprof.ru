<?
if(!defined( "B_PROLOG_INCLUDED" )||B_PROLOG_INCLUDED!==true)
	die();
if(!CModule::IncludeModule( "iblock" ))
	return;
$IdModule = 'sotbit.reviews';
$arComponentParameters = array(
		"GROUPS" => array(),
		"PARAMETERS" => array(
				"ID_ELEMENT" => array(
						"PARENT" => "BASE",
						"NAME" => GetMessage( $IdModule."_ID_ELEMENT" ),
						"TYPE" => "STRING",
						"DEFAULT" => '={$ElementID}' 
				),
				"NOTICE_EMAIL" => array(
						"PARENT" => "BASE",
						"NAME" => GetMessage( $IdModule."_NOTICE_EMAIL" ),
						"TYPE" => "STRING" 
				),
				"PRIMARY_COLOR" => array(
						"PARENT" => "BASE",
						"NAME" => GetMessage( $IdModule."_PRIMARY_COLOR" ),
						"TYPE" => "STRING",
						"DEFAULT" => "#a76e6e" 
				),
				"BUTTON_BACKGROUND" => array(
						"PARENT" => "BASE",
						"NAME" => GetMessage( $IdModule."_BUTTON_BACKGROUND" ),
						"TYPE" => "STRING",
						"DEFAULT" => "#dbbfb9" 
				),
				"DATE_FORMAT" => CIBlockParameters::GetDateFormat( GetMessage( $IdModule."_DATE_FORMAT" ), "BASE" ),
				"AJAX" => array(
						"PARENT" => "BASE",
						"NAME" => GetMessage( $IdModule."_AJAX" ),
						"TYPE" => "LIST",
						"VALUES" => array(
								'N' => GetMessage( $IdModule."_OFF" ),
								'Y' => GetMessage( $IdModule."_ON" ) 
						) 
				),
				"TEXTBOX_MAXLENGTH" => array(
						"PARENT" => "BASE",
						"NAME" => GetMessage( $IdModule."_TEXTBOX_MAXLENGTH" ),
						"TYPE" => "STRING",
						"DEFAULT" => 200 
				),
				"CACHE_TIME" => array(
						"DEFAULT" => 36000000 
				) 
		) 
);
?>