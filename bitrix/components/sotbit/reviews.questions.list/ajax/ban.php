<?
use Sotbit\Reviews\BansTable;
use Sotbit\Reviews\QuestionsTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type;

global $USER;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if(!Loader::includeModule('sotbit.reviews'))
	return false;

if ($ID > 0)
{
	$rsQuestion = QuestionsTable::GetByID( $ID );
	if ( $Question = $rsQuestion->Fetch() )
	{
		$arFields = Array(
				"DATE_CREATION" => new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' ),
				"DATE_CHANGE" => new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' ),
				"DATE_TO" => new Type\DateTime( date('d.m.Y H:i:s',strtotime('+1 year')) ),
				"ACTIVE" => 'Y',
				"ID_MODERATOR" => $USER->GetID()
				);
		if(isset($Question['ID_USER']) && $Question['ID_USER']>0)
		{
			$arFields['ID_USER']=$Question['ID_USER'];
		}
		if(isset($Question['IP_USER']) && !empty($Question['IP_USER']))
		{
			$arFields['IP']=$Question['IP_USER'];
		}
		if(isset($arFields['ID_USER']) || isset($arFields['IP_USER']))
		{
			BansTable::Add($arFields);
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_" . $Question['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_reviews_add_" . $Question['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_reviews_filter_" . $Question['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_reviews_list_" . $Question['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_statistics_" . $Question['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_" . $Question['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_comments_list_" . $Question['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_" . $Question['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_questions_list_" . $Question['ID_ELEMENT'] );
			echo "SUCCESS";
		}
		
	}
}

?>