<?
use Sotbit\Reviews\QuestionsTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type;
require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if(!Loader::includeModule( 'sotbit.reviews' ))
	return false;

global $APPLICATION;
global $USER;
if($REQUEST_METHOD == "POST") {
	if($ID > 0) {
		$Recaptch2Return = "";
		$SecretKey = COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_RECAPTCHA2_SECRET_KEY_" . SITE_ID, "" );
		if(isset( $SecretKey ) && !empty( $SecretKey ) && isset( $_POST['g-recaptcha-response'] ))
			$Recaptch2Return = CSotbitReviews::Recaptha2( $_POST['g-recaptcha-response'], $SecretKey );
		
		if($Recaptch2Return != "") {
			echo $Recaptch2Return;
		} else {
			if(LANG_CHARSET == 'windows-1251')
				$text = iconv( "UTF-8", "WINDOWS-1251", $text );
			
			
			$SPAM = false;
			$AkismetKey = COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_AKISMET_API_KEY_" . SITE_ID, "" );
			if(isset( $AkismetKey ) && !empty( $AkismetKey )) {
				$AkismetAuthName = COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_AKISMET_API_LOGIN_" . SITE_ID, "" );
				$AkismetWebsiteUrl = SITE_SERVER_NAME;
				$oAkismet = new Akismet( $AkismetWebsiteUrl, $AkismetKey );
				if($oAkismet->isKeyValid()) {
					$oAkismet->setCommentAuthor( $AkismetAuthName );
					$oAkismet->setPermalink( $PAGE_URL );
					$oAkismet->setCommentContent( $text );
					if($oAkismet->isCommentSpam()) {
						$SPAM = true;
					}
					
				}
			}
			if(!$SPAM) {
				

					$arFields['ID_USER']=$USER->GetID();
					$arFields['QUESTION'] = $text;
					$arFields['DATE_CHANGE'] = new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' );
					$arFields['MODERATED'] = ($MODERATION == 'Y') ? 'N' : 'Y';
					$arFields['ACTIVE'] = 'Y';
					
					$Fields['NEW_FIELDS'] = $arFields;
					
					$Fields['NEW_FIELDS']['ID']=$ID;
					
					$result = QuestionsTable::GetById( $ID );
					if($Question=$result->Fetch())
					{
						$Fields['OLD_FIELDS']=$Question;
					}
					
					
					// Get site id
					$Fields['SITE'] = SITE_ID;
					$Fields['NOTICE_EMAIL'] = $NOTICE_EMAIL;
					
					
					// Send notice
					$Fields['ACTION'] = 'question';
					
					
					CSotbitReviews::SendNotice( $Fields );
					$rsEvents = GetModuleEvents( CSotbitReviews::iModuleID, "OnBeforeUpdateQuestion" );
					while( $arEvent = $rsEvents->Fetch() ) {
						if(ExecuteModuleEvent( $arEvent, $Fields )) {
						}
					}
					$result = QuestionsTable::update( $ID ,$arFields );
					if(!$result->isSuccess()) {
						$errors = $result->getErrorMessages();
						foreach( $errors as $error )
							echo $error . '<br>';
					} else {
						$rsEvents = GetModuleEvents( CSotbitReviews::iModuleID, "OnAfterUpdateQuestion" );
						while( $arEvent = $rsEvents->Fetch() ) {
							if(ExecuteModuleEvent( $arEvent, $Fields )) {
							}
						}
						
						echo "SUCCESS";
					}
				
			} else {
				echo $SPAM_ERROR;
			}
		}
	}
}

?> 