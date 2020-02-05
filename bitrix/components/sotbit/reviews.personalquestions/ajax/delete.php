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
		if($Id > 0) {
			$arParams = unserialize( $data );
			
			$arFields = QuestionsTable::getById( $Id );
			$Fields['OLD_FIELDS'] = $arFields->fetch();
			
			$rsEvents = GetModuleEvents('sotbit.reviews', "OnBeforeDeleteQuestion" );
			while( $arEvent = $rsEvents->Fetch() )
			{
				
				if(ExecuteModuleEvent( $arEvent, $Fields ))
				{
					
				}
			}
			
			$result = QuestionsTable::delete( $Id );
			if(!$result->isSuccess()) {
				$errors = $result->getErrorMessages();
				echo $errors;
			} else {
				echo 'SUCCESS';
				$rsEvents = GetModuleEvents( 'sotbit.reviews', "OnAfterDeleteQuestion" );
				while( $arEvent = $rsEvents->Fetch() )
				{
					
					if(ExecuteModuleEvent( $arEvent, $Fields )) {
						
					}
				}
			}
		}
		
		
	}
	?> 