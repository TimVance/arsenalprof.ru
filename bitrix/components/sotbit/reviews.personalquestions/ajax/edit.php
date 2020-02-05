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
		
		
		$return=array();
		
		
		$result = QuestionsTable::GetById( $Id );
		if($Question=$result->Fetch())
		{
			$return=$Question;
			$return['TEXT']=CSotbitReviews::bb2html($Question['TEXT']);
		}
		echo json_encode($return);
	}
}
?> 