<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 *
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @global CUserTypeManager $USER_FIELD_MANAGER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponent $this
 */
if(!defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true)
	die();

global $USER_FIELD_MANAGER;

// if user registration blocked - return auth form
if(COption::GetOptionString( "main", "new_user_registration", "N" ) == "N")
	$APPLICATION->AuthForm( array() );

$arResult["USE_EMAIL_CONFIRMATION"] = (COption::GetOptionString( "main", "new_user_registration_email_confirmation", "N" ) == "Y" && $arResult["EMAIL_REQUIRED"] ? "Y" : "N");

$arDefaultFields = array(
		'EMAIL',
		'NAME'
);

if(isset( $arParams['USER_GROUP'] ) && $arParams['USER_GROUP'] > 0) {
	$def_group = $arParams['USER_GROUP'];
} else {
	$def_group = COption::GetOptionString( "main", "new_user_registration_def_group", "" );
}

if($def_group != "")
	$arResult["GROUP_POLICY"] = CUser::GetGroupPolicy( explode( ",", $def_group ) );
else
	$arResult["GROUP_POLICY"] = CUser::GetGroupPolicy( array() );

$arResult["SHOW_FIELDS"] = $arDefaultFields;

// use captcha?
$arResult["USE_CAPTCHA"] = COption::GetOptionString( "main", "captcha_registration", "N" ) == "Y" ? "Y" : "N";

// start values
$arResult["VALUES"] = array();
$arResult["ERRORS"] = array();
$register_done = false;

// register user
if($_SERVER["REQUEST_METHOD"] == "POST" && !empty( $_REQUEST["register_submit_button"] ) && !$USER->IsAuthorized()) {

	// check emptiness of required fields
	foreach( $arResult["SHOW_FIELDS"] as $key ) {
		$arResult["VALUES"][$key] = $_REQUEST["REGISTER"][$key];
		
		if(trim( $arResult["VALUES"][$key] ) == '')
		{
			if($key == 'NAME')
			{
				$arResult["ERRORS"][$key] = GetMessage( "REGISTER_FIELD_REQUIRED_NAME");
			}
			elseif($key == 'EMAIL')
			{
				$arResult["ERRORS"][$key] = GetMessage( "REGISTER_FIELD_REQUIRED_EMAIL");
			}
			else 
			{
				$arResult["ERRORS"][$key] = GetMessage( "REGISTER_FIELD_REQUIRED");
			}
		}
	}
	
	$USER_FIELD_MANAGER->EditFormAddFields( "USER", $arResult["VALUES"] );

	// this is a part of CheckFields() to show errors about user defined fields
	if(!$USER_FIELD_MANAGER->CheckFields( "USER", 0, $arResult["VALUES"] )) {
		$e = $APPLICATION->GetException();
		$arResult["ERRORS"][] = substr( $e->GetString(), 0, -4 ); // cutting "<br>"
		$APPLICATION->ResetException();
	}

	// check captcha
	if($arResult["USE_CAPTCHA"] == "Y") {
		if(!$APPLICATION->CaptchaCheckCode( $_REQUEST["captcha_word"], $_REQUEST["captcha_sid"] ))
			$arResult["ERRORS"][] = GetMessage( "REGISTER_WRONG_CAPTCHA" );
	}

	if(count( $arResult["ERRORS"] ) > 0) {
		if(COption::GetOptionString( "main", "event_log_register_fail", "N" ) === "Y") {
			$arError = $arResult["ERRORS"];
			foreach( $arError as $key => $error )
				if(intval( $key ) == 0 && $key !== 0)
					$arError[$key] = str_replace( "#FIELD_NAME#", '"' . $key . '"', $error );
			CEventLog::Log( "SECURITY", "USER_REGISTER_FAIL", "main", false, implode( "<br>", $arError ) );
		}
	} else // if there;s no any errors - create user
{
		$bConfirmReq = (COption::GetOptionString( "main", "new_user_registration_email_confirmation", "N" ) == "Y" && $arResult["EMAIL_REQUIRED"]);

		$arr = array(
				'a',
				'b',
				'c',
				'd',
				'e',
				'f',
				'g',
				'h',
				'i',
				'j',
				'k',
				'l',
				'm',
				'n',
				'o',
				'p',
				'r',
				's',
				't',
				'u',
				'v',
				'x',
				'y',
				'z',
				'A',
				'B',
				'C',
				'D',
				'E',
				'F',
				'G',
				'H',
				'I',
				'J',
				'K',
				'L',
				'M',
				'N',
				'O',
				'P',
				'R',
				'S',
				'T',
				'U',
				'V',
				'X',
				'Y',
				'Z',
				'1',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7',
				'8',
				'9',
				'0',
				'.',
				',',
				'(',
				')',
				'[',
				']',
				'!',
				'?',
				'&',
				'^',
				'%',
				'@',
				'*',
				'$',
				'<',
				'>',
				'/',
				'|',
				'+',
				'-',
				'{',
				'}',
				'`',
				'~'
		);
		$pass = "";
		for($i = 0; $i < 12; ++ $i) {
			$index = rand( 0, count( $arr ) - 1 );
			$pass .= $arr[$index];
		}

		$arResult['VALUES']["LOGIN"] = Cutil::translit( $_REQUEST["REGISTER"]['NAME'], 'ru', array(
				"replace_space" => "-",
				"replace_other" => "-"
		) );
		$arResult['VALUES']["PASSWORD"] = $pass;
		$arResult['VALUES']["CHECKWORD"] = md5( CMain::GetServerUniqID() . uniqid() );
		$arResult['VALUES']["~CHECKWORD_TIME"] = $DB->CurrentTimeFunction();
		$arResult['VALUES']["ACTIVE"] = $bConfirmReq ? "N" : "Y";
		$arResult['VALUES']["CONFIRM_CODE"] = $bConfirmReq ? randString( 8 ) : "";
		$arResult['VALUES']["LID"] = SITE_ID;

		$arResult['VALUES']["USER_IP"] = $_SERVER["REMOTE_ADDR"];
		$arResult['VALUES']["USER_HOST"] = @gethostbyaddr( $_SERVER["REMOTE_ADDR"] );

		if($arResult["VALUES"]["AUTO_TIME_ZONE"] != "Y" && $arResult["VALUES"]["AUTO_TIME_ZONE"] != "N")
			$arResult["VALUES"]["AUTO_TIME_ZONE"] = "";

		if(isset( $arParams['USER_GROUP'] ) && $arParams['USER_GROUP'] > 0) {
			$def_group = $arParams['USER_GROUP'];
		} else {
			$def_group = COption::GetOptionString( "main", "new_user_registration_def_group", "" );
		}
		if($def_group != "")
			$arResult['VALUES']["GROUP_ID"] = explode( ",", $def_group );

		$bOk = true;

		$events = GetModuleEvents( "main", "OnBeforeUserRegister", true );
		foreach( $events as $arEvent ) {
			if(ExecuteModuleEventEx( $arEvent, array(
					&$arResult['VALUES']
			) ) === false) {
				if($err = $APPLICATION->GetException())
					$arResult['ERRORS'][] = $err->GetString();

				$bOk = false;
				break;
			}
		}

		$ID = 0;
		$user = new CUser();
		if($bOk) {
			$ID = $user->Add( $arResult["VALUES"] );
		}

		if(intval( $ID ) > 0) {
			$register_done = true;

			// authorize user
			if($arResult["VALUES"]["ACTIVE"] == "Y") {
				if(!$arAuthResult = $USER->Login( $arResult["VALUES"]["LOGIN"], $arResult["VALUES"]["PASSWORD"] ))
					$arResult["ERRORS"][] = $arAuthResult;
			}

			$arResult['VALUES']["USER_ID"] = $ID;

			$arEventFields = $arResult['VALUES'];
			unset( $arEventFields["PASSWORD"] );
			unset( $arEventFields["CONFIRM_PASSWORD"] );

			$event = new CEvent();
			$event->SendImmediate( "NEW_USER", SITE_ID, $arEventFields );
			if($bConfirmReq)
				$event->SendImmediate( "NEW_USER_CONFIRM", SITE_ID, $arEventFields );
		} else {
			$arResult["ERRORS"][] = $user->LAST_ERROR;
		}

		if(count( $arResult["ERRORS"] ) <= 0) {
			if(COption::GetOptionString( "main", "event_log_register", "N" ) === "Y")
				CEventLog::Log( "SECURITY", "USER_REGISTER", "main", $ID );
		} else {
			if(COption::GetOptionString( "main", "event_log_register_fail", "N" ) === "Y")
				CEventLog::Log( "SECURITY", "USER_REGISTER_FAIL", "main", $ID, implode( "<br>", $arResult["ERRORS"] ) );
		}

		$events = GetModuleEvents( "main", "OnAfterUserRegister", true );
		foreach( $events as $arEvent )
			ExecuteModuleEventEx( $arEvent, array(
					&$arResult['VALUES']
			) );
	}
}

// if user is registered - redirect him to backurl or to success_page; currently added users too
if($register_done) {
	if($arParams["USE_BACKURL"] == "Y" && $_REQUEST["backurl"] != '')
		LocalRedirect( $_REQUEST["backurl"] );
	elseif($arParams["SUCCESS_PAGE"] != '')
		LocalRedirect( $arParams["SUCCESS_PAGE"] );
}



$arResult["VALUES"] = htmlspecialcharsEx( $arResult["VALUES"] );


$arResult["USER_PROPERTIES"] = array("SHOW" => "N");
$arUserFields = $USER_FIELD_MANAGER->GetUserFields("USER", 0, LANGUAGE_ID);
if (is_array($arUserFields) && count($arUserFields) > 0)
{
	if (!is_array($arParams["USER_PROPERTY"]))
		$arParams["USER_PROPERTY"] = array($arParams["USER_PROPERTY"]);

		foreach ($arUserFields as $FIELD_NAME => $arUserField)
		{
			if (!in_array($FIELD_NAME, $arParams["USER_PROPERTY"]) && $arUserField["MANDATORY"] != "Y")
				continue;

				$arUserField["EDIT_FORM_LABEL"] = strLen($arUserField["EDIT_FORM_LABEL"]) > 0 ? $arUserField["EDIT_FORM_LABEL"] : $arUserField["FIELD_NAME"];
				$arUserField["EDIT_FORM_LABEL"] = htmlspecialcharsEx($arUserField["EDIT_FORM_LABEL"]);
				$arUserField["~EDIT_FORM_LABEL"] = $arUserField["EDIT_FORM_LABEL"];
				$arResult["USER_PROPERTIES"]["DATA"][$FIELD_NAME] = $arUserField;
		}
}
if (!empty($arResult["USER_PROPERTIES"]["DATA"]))
{
	$arResult["USER_PROPERTIES"]["SHOW"] = "Y";
	$arResult["bVarsFromForm"] = (count($arResult['ERRORS']) <= 0) ? false : true;
}

// initialize captcha
if($arResult["USE_CAPTCHA"] == "Y")
	$arResult["CAPTCHA_CODE"] = htmlspecialcharsbx( $APPLICATION->CaptchaGetCode() );

	// all done
$this->IncludeComponentTemplate();
