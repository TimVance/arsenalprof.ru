<?
use Sotbit\Reviews\CommentsTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type;
require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if(!Loader::includeModule( 'sotbit.reviews' ) || !Loader::includeModule( 'iblock' ))
	return false;
global $USER;
global $APPLICATION;
if($REQUEST_METHOD == "POST") {
	
	$Recaptch2Return = "";
	$SecretKey = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_RECAPTCHA2_SECRET_KEY_" . SITE_ID, "" );
	
	if(isset( $SecretKey ) && !empty( $SecretKey ) && isset( $_POST['g-recaptcha-response'] ))
		$Recaptch2Return = CSotbitReviews::Recaptha2( $_POST['g-recaptcha-response'], $SecretKey );
	
	if($Recaptch2Return != "") {
		echo $Recaptch2Return;
	} else {
		if(LANG_CHARSET == 'windows-1251')
			$text = iconv( "UTF-8", "WINDOWS-1251", $text );
		
		$SPAM = false;
		$AkismetKey = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_AKISMET_API_KEY_" . SITE_ID, "" );
		if(isset( $AkismetKey ) && !empty( $AkismetKey )) {
			$AkismetAuthName = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_AKISMET_API_LOGIN_" . SITE_ID, "" );
			$AkismetWebsiteUrl = SITE_SERVER_NAME;
			$oAkismet = new Akismet( $AkismetWebsiteUrl, $AkismetKey );
			if($oAkismet->isKeyValid()) {
				$oAkismet->setCommentAuthor( $AkismetAuthName );
				//$oAkismet->setPermalink( $PAGE_URL );
				$oAkismet->setCommentContent( $text );
				if($oAkismet->isCommentSpam()) {
					$SPAM = true;
				}
			}
		}
		
		if(!$SPAM) {
			$IDx = COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
			if($IDx == 'XML_ID_ELEMENT')
				$IDx = 'XML_ID';
			else
				$IDx = 'ID';
			$el_res = CIBlockElement::GetList( array(), array(
					$IDx => $ID_ELEMENT 
			), array(
					'ID',
					'XML_ID' 
			) );
			if($el_arr = $el_res->GetNext()) {
				$arFields['ID_ELEMENT'] = $el_arr['ID'];
				$arFields['XML_ID_ELEMENT'] = $el_arr['XML_ID'];
			}
			$arFields['ID_PARENT'] = $PARENT;
			$IdUser = $USER->GetID();
			$arFields['ID_USER'] = (COption::GetOptionString( CSotbitReviews::iModuleID, "COMMENTS_REGISTER_USERS_" . SITE_ID, "" ) != 'Y' && empty( $IdUser )) ? 0 : $IdUser;
			$arFields['TEXT'] = $text;
			$arFields['DATE_CREATION'] = new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' );
			$arFields['DATE_CHANGE'] = NULL;
			$arFields['MODERATED'] = ($MODERATION == 'Y') ? 'N' : 'Y';
			$arFields['MODERATED_BY'] = NULL;
			$arFields['ACTIVE'] = 'Y';
			$arFields['IP_USER'] = $_SERVER['REMOTE_ADDR'];
			// Get old fields
			$Fields['NEW_FIELDS'] = $arFields;
			// Get site id
			$Fields['SITE'] = SITE_ID;
			$Fields['NOTICE_EMAIL'] = $NOTICE_EMAIL;
			$rsEvents = GetModuleEvents( CSotbitReviews::iModuleID, "OnBeforeAddComment" );
			while( $arEvent = $rsEvents->Fetch() ) {
				if(ExecuteModuleEvent( $arEvent, $Fields )) {
				}
			}
			$result = CommentsTable::add( $arFields );
			if(!$result->isSuccess()) {
				$errors = $result->getErrorMessages();
				foreach( $errors as $error )
					echo $error . ' ';
			} else {
				$ID = $result->getId();
				$Fields['NEW_FIELDS']['ID'] = $ID;
				$rsEvents = GetModuleEvents( CSotbitReviews::iModuleID, "OnAfterAddComment" );
				while( $arEvent = $rsEvents->Fetch() ) {
					if(ExecuteModuleEvent( $arEvent, $Fields )) {
					}
				}
				echo "SUCCESS";
			}
		}
	}
}
?>