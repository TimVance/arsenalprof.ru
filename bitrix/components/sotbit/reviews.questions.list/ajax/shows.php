<?
use Sotbit\Reviews\QuestionsTable;
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
	
	print_r($arIds);
	
	
			$rsData = QuestionsTable::getList( array(
				'select' => array(
						'SHOWS',
						'ID',
				),
				'filter' => array('ID'=>$arIds),
		) );
		while ( $Question = $rsData->Fetch() )
		{
			QuestionsTable::update($Question['ID'],array('SHOWS'=>++$Question['SHOWS']));
		}
}
?>