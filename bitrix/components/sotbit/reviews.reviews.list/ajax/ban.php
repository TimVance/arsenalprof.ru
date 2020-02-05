<?
use Sotbit\Reviews\BansTable;
use Sotbit\Reviews\ReviewsTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type;

global $USER;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if(!Loader::includeModule('sotbit.reviews'))
	return false;

	
	
if ($ID > 0)
{
	$rsReview = ReviewsTable::GetByID( $ID );
	if ( $Review = $rsReview->Fetch() )
	{
		$arFields = Array(
				"DATE_CREATION" => new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' ),
				"DATE_CHANGE" => new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' ),
				"DATE_TO" => new Type\DateTime( date('d.m.Y H:i:s',strtotime('+1 year')) ),
				"ACTIVE" => 'Y',
				"ID_MODERATOR" => $USER->GetID()
				);
		if(isset($Review['ID_USER']) && $Review['ID_USER']>0)
		{
			$arFields['ID_USER']=$Review['ID_USER'];
		}
		if(isset($Review['IP_USER']) && !empty($Review['IP_USER']))
		{
			$arFields['IP']=$Review['IP_USER'];
		}
		if(isset($arFields['ID_USER']) || isset($arFields['IP_USER']))
		{
			
			BansTable::Add($arFields);
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_" . $Review['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_reviews_add_" . $Review['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_reviews_filter_" . $Review['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_reviews_list_" . $Review['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_statistics_" . $Review['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_" . $Review['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_comments_list_" . $Review['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_" . $Review['ID_ELEMENT'] );
			$GLOBALS['CACHE_MANAGER']->ClearByTag( "sotbit_reviews_questions_list_" . $Review['ID_ELEMENT'] );
			echo "SUCCESS";
		}
		
	}
}

?>