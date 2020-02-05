<?
use Sotbit\Reviews\ReviewsTable;
use Sotbit\Reviews\ReviewsfieldsTable;
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
				
				$title_conv = (LANG_CHARSET == 'windows-1251') ? iconv( "UTF-8", "WINDOWS-1251", $title ) : $title;
				
				
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
					$arFields['ID_USER']=$USER->GetID();
					$arFields['RATING'] = $rating;
					$arFields['TITLE'] = (COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_TITLE_" . SITE_ID, "" ) == 'Y') ? $title_conv : NULL;
					$arFields['TEXT'] = $text;
					$arFields['ADD_FIELDS'] = serialize( $AddFields );
					$arFields['DATE_CHANGE'] = new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' );
					$arFields['MODERATED'] = ($MODERATION == 'Y') ? 'N' : 'Y';
					$arFields['RECOMMENDATED'] = ($RECOMMENDATED != 'Y') ? 'N' : 'Y';
					$arFields['ACTIVE'] = 'Y';
					$arFields['MULTIMEDIA'] = $Multimedia;
					
					$Fields['NEW_FIELDS'] = $arFields;
					
					$Fields['NEW_FIELDS']['ID']=$ID;
					
					$result = ReviewsTable::GetById( $ID );
					if($Review=$result->Fetch())
					{
						$Fields['OLD_FIELDS']=$Review;
					}
					
					
					// Get site id
					$Fields['SITE'] = SITE_ID;
					$Fields['NOTICE_EMAIL'] = $NOTICE_EMAIL;
					
					
					// Send notice
					$Fields['ACTION'] = 'review';
					
					
					
					// Add images
					
					if(!isset($photos))
						$photos=array();
					
					if (COption::GetOptionString( 'sotbit.reviews', "REVIEWS_UPLOAD_IMAGE_" . $Fields['SITE'], "" ) == 'Y'  && Loader::includeModule( 'fileman' ))
					{
						CMedialib::Init();
						$arCollections = CMedialibCollection::GetList( array(
								'arOrder' => Array(
										'NAME' => 'ASC'
										),
								'arFilter' => array(
										'ACTIVE' => 'Y',
										'NAME' => 'sotbit.reviews'
								)
						) );
					
						if (isset( $arCollections ) && is_array( $arCollections ) && count( $arCollections ) != 0)
						{
							$PARENT_COLLECTION = $arCollections[0]['ID'];
						}
						else
						{
							$arCollectionFields = Array(
									"arFields" => Array(
											"ID" => 0,
											"NAME" => 'sotbit.reviews',
											"DESCRIPTION" => "",
											"PARENT_ID" => 0,
											"KEYWORDS" => "",
											"ACTIVE" => "Y",
											"ML_TYPE" => "1"
											)
									);
							$PARENT_COLLECTION = CMedialibCollection::Edit( $arCollectionFields );
					
						}
					

						$PostSrc=array();
						foreach ( $photos as $n => $fileBody )
						{
							$pos = strpos($fileBody, 'base64');
							if ($pos === false) {
								$arr=explode('/',$fileBody);
								if(copy($_SERVER['DOCUMENT_ROOT'].$fileBody, $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp/' . $arr[count($arr)-1]))
								{
									$PostSrc[]=$arr[count($arr)-1];
								}
							}
						}
						
						
						$arCollections = CMedialibCollection::GetList( array(
								'arOrder' => Array(
										'NAME' => 'ASC'
										),
								'arFilter' => array(
										'ACTIVE' => 'Y',
										"PARENT_ID" => $PARENT_COLLECTION,
										"NAME" => $ID,
								)
						) );
						if (isset( $arCollections ) && is_array( $arCollections ) && count( $arCollections ) != 0)
						{
							CMedialibCollection::delete($arCollections[0]['ID']);
						}

							$arCollectionFields = Array(
									"arFields" => Array(
											"ID" => 0,
											"NAME" => $ID,
											"DESCRIPTION" => "",
											"PARENT_ID" => $PARENT_COLLECTION,
											"KEYWORDS" => "",
											"ACTIVE" => "Y",
											"ML_TYPE" => "1"
											)
									);
							$ID_COLLECTION = CMedialibCollection::Edit( $arCollectionFields );

						
							
							foreach($PostSrc as $Src){
								CMedialib::Init();
								$arCollectionFields = array(
										"file" => CFile::MakeFileArray( '/upload/tmp/' . $Src ),
										"path" => false,
										"arFields" => Array(
												"ID" => 0,
												"NAME" => $Src,
												"DESCRIPTION" => "",
												"KEYWORDS" => ""
												),
										"arCollections" => Array(
												$ID_COLLECTION
												)
								);
								$arItem = CMedialibItem::Edit( $arCollectionFields );
								unlink( $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp/' . $Src );
							}
							
						
						
						foreach ( $photos as $n => $fileBody )
						{
							$pos = strpos($fileBody, 'base64');
							if ($pos === false) {
								continue;
							}
							else 
							{
								$fileName = ($n + 1) . md5( time() );
								preg_match( '#data:image\/(png|jpg|jpeg);#', $fileBody, $fileTypeMatch );
									
								
									
								$fileType = $fileTypeMatch[1];
								$fileBody = preg_replace( '#^data.*?base64,#', '', $fileBody );
								$fileBody = base64_decode( $fileBody );
								
							}

							if (file_put_contents( $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp/' . $fileName . '.' . $fileType, $fileBody ))
							{
								CMedialib::Init();
								$arCollectionFields = array(
										"file" => CFile::MakeFileArray( '/upload/tmp/' . $fileName . '.' . $fileType ),
										"path" => false,
										"arFields" => Array(
												"ID" => 0,
												"NAME" => $fileName . '.' . $fileType,
												"DESCRIPTION" => "",
												"KEYWORDS" => ""
												),
										"arCollections" => Array(
												$ID_COLLECTION
												)
								);
								$arItem = CMedialibItem::Edit( $arCollectionFields );
								unlink( $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp/' . $fileName . '.' . $fileType );
							}
						}
					}
					
					
					
					
					CSotbitReviews::SendNotice( $Fields );
					$rsEvents = GetModuleEvents( CSotbitReviews::iModuleID, "OnBeforeUpdateReview" );
					while( $arEvent = $rsEvents->Fetch() ) {
						if(ExecuteModuleEvent( $arEvent, $Fields )) {
						}
					}
					$result = ReviewsTable::update( $ID ,$arFields );
					if(!$result->isSuccess()) {
						$errors = $result->getErrorMessages();
						foreach( $errors as $error )
							echo $error . '<br>';
					} else {
						$rsEvents = GetModuleEvents( CSotbitReviews::iModuleID, "OnAfterUpdateReview" );
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
}

?> 