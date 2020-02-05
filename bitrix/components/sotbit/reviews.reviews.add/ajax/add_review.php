<?
use Sotbit\Reviews\ReviewsTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type;
require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if(!Loader::includeModule( 'sotbit.reviews' ) || !Loader::includeModule( 'iblock' ))
	return false;
global $APPLICATION;
global $USER;

if($REQUEST_METHOD == "POST") {
	$Recaptch2Return = "";
	$SecretKey = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_RECAPTCHA2_SECRET_KEY_" . SITE_ID, "" );
	
	if(isset( $SecretKey ) && !empty( $SecretKey ) && isset( $_POST['g-recaptcha-response'] ))
		$Recaptch2Return = CSotbitReviews::Recaptha2( $_POST['g-recaptcha-response'], $SecretKey );
	
	if($Recaptch2Return != "") {
		echo $Recaptch2Return;
	} else {
		
		if(LANG_CHARSET == 'windows-1251')
			$text = iconv( "UTF-8", "WINDOWS-1251", $text );
		
		$AddFields = array();
		foreach( $_POST as $key => $val ) {
			$pos = strpos( $key, 'AddFields_' );
			if($pos !== false) {
				$key = substr( $key, 10 );
				$AddFields[$key] = (LANG_CHARSET == 'windows-1251') ? iconv( "UTF-8", "WINDOWS-1251", $val ) : $val;
			}
		}
		
		$SPAM = false;
		$AkismetKey = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_AKISMET_API_KEY_" . SITE_ID, "" );
		if(isset( $AkismetKey ) && !empty( $AkismetKey )) {
			$AkismetAuthName = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_AKISMET_API_LOGIN_" . SITE_ID, "" );
			$AkismetWebsiteUrl = SITE_SERVER_NAME;
			$oAkismet = new Akismet( $AkismetWebsiteUrl, $AkismetKey );
			if($oAkismet->isKeyValid()) {
				$oAkismet->setCommentAuthor( $AkismetAuthName );
				$oAkismet->setPermalink( $PAGE_URL );
				$oAkismet->setCommentContent( $text );
				if($oAkismet->isCommentSpam()) {
					$SPAM = true;
				}
				foreach( $AddFields as $AddField ) {
					$oAkismet->setCommentContent( $AddField );
					if($oAkismet->isCommentSpam()) {
						$SPAM = true;
					}
				}
			}
		}
		
		if(!$SPAM) {
			$IDx = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
			$title_conv = (LANG_CHARSET == 'windows-1251') ? iconv( "UTF-8", "WINDOWS-1251", $title ) : $title;
			
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
			
			if(!isset( $video ))
				$video = "";
			if(!isset( $presentation ))
				$presentation = "";
			
			$Multimedia = CSotbitReviews::GetMultimedia( $video, $presentation );
			
			if(isset( $video ) && !empty( $video ) && $Multimedia['VIDEO'] == "") {
				echo $VIDEO_ERROR;
			} elseif(isset( $presentation ) && !empty( $presentation ) && $Multimedia['PRESENTATION'] == "") {
				echo $PRESENTATION_ERROR;
			} else {
				
				$IdUser = $USER->GetID();
				$arFields['ID_USER'] = (COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_REGISTER_USERS_" . SITE_ID, "" ) != 'Y' && empty( $IdUser )) ? 0 : $IdUser; // $USER->GetID();
				$arFields['RATING'] = $rating;
				$arFields['TITLE'] = (COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_TITLE_" . SITE_ID, "" ) == 'Y') ? $title_conv : NULL;
				$arFields['TEXT'] = $text;
				$arFields['ADD_FIELDS'] = serialize( $AddFields );
				$arFields['LIKES'] = 0;
				$arFields['DISLIKES'] = 0;
				$arFields['DATE_CREATION'] = new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' );
				$arFields['DATE_CHANGE'] = NULL;
				$arFields['MODERATED'] = ($MODERATION == 'Y') ? 'N' : 'Y';
				$arFields['RECOMMENDATED'] = ($RECOMMENDATED != 'Y') ? 'N' : 'Y';
				$arFields['MODERATED_BY'] = NULL;
				$arFields['ACTIVE'] = 'Y';
				$arFields['IP_USER'] = $_SERVER['REMOTE_ADDR'];
				$arFields['MULTIMEDIA'] = $Multimedia;
				// Get old fields
				$Fields['NEW_FIELDS'] = $arFields;
				// Get site id
				$Fields['SITE'] = SITE_ID;
				$Fields['NOTICE_EMAIL'] = $NOTICE_EMAIL;
				$rsEvents = GetModuleEvents( CSotbitReviews::iModuleID, "OnBeforeAddReview" );
				while( $arEvent = $rsEvents->Fetch() ) {
					if(ExecuteModuleEvent( $arEvent, $Fields )) {
					}
				}
				$result = ReviewsTable::add( $arFields );
				if(!$result->isSuccess()) {
					$errors = $result->getErrorMessages();
					foreach( $errors as $error )
						echo $error . '<br>';
				} else {
					$ID = $result->getId();
					$Fields['NEW_FIELDS']['ID'] = $ID;
					$Fields['NEW_FIELDS']['IMAGES'] = $photos;
					$rsEvents = GetModuleEvents( CSotbitReviews::iModuleID, "OnAfterAddReview" );
					while( $arEvent = $rsEvents->Fetch() ) {
						if(ExecuteModuleEvent( $arEvent, $Fields )) {
						}
					}
					
					echo "SUCCESS";
				}
			}
		} else {
			echo $SPAM_ERROR;
		}
	}
}
?>