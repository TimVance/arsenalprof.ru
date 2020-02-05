<?
use Sotbit\Reviews\ReviewsTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type;
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;
if(!Loader::includeModule('sotbit.reviews'))
	return false;

if($REQUEST_METHOD == "POST")
{
	$arIds=unserialize($Ids);
			$rsData = ReviewsTable::getList( array(
				'select' => array(
						'SHOWS',
						'ID',
				),
				'filter' => array('ID'=>$arIds),
		) );
		while ( $Review = $rsData->Fetch() )
		{
			ReviewsTable::update($Review['ID'],array('SHOWS'=>++$Review['SHOWS']));
		}
}
?>