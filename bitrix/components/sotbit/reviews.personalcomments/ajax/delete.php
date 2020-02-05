<?
use Sotbit\Reviews\CommentsTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type;
require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if(!Loader::includeModule( 'sotbit.reviews' ))
	return false;
	
	global $APPLICATION;
	global $USER;
	if($REQUEST_METHOD == "POST") {
		if($Id > 0) {
			$arParams = unserialize( $data );
			
			$arFields = CommentsTable::getById( $Id );
			$Fields['OLD_FIELDS'] = $arFields->fetch();
			
			$rsEvents = GetModuleEvents('sotbit.reviews', "OnBeforeDeleteComment" );
			while( $arEvent = $rsEvents->Fetch() )
			{
				
				if(ExecuteModuleEvent( $arEvent, $Fields ))
				{
					
				}
			}
			
			$result = CommentsTable::delete( $Id );
			if(!$result->isSuccess()) {
				$errors = $result->getErrorMessages();
				echo $errors;
			} else
			{
				echo 'SUCCESS';
				$rsEvents = GetModuleEvents( 'sotbit.reviews', "OnAfterDeleteComment" );
				while( $arEvent = $rsEvents->Fetch() )
				{
					
					if(ExecuteModuleEvent( $arEvent, $Fields )) {
						
					}
				}
			}
		}
		
		
	}
	?> 