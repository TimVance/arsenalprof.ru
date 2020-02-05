<?
use Sotbit\Reviews\ReviewsTable;
use Sotbit\Reviews\AnaliticTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type;
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;
if(!Loader::includeModule('sotbit.reviews'))
	return false;

if($REQUEST_METHOD == "POST")
{
	$arFields[$action]=++$Likes;
	
	
	$Fields['OLD_FIELDS']['ID_ELEMENT']=$id;
	
	
	
	$rsEvents = GetModuleEvents( CSotbitReviews::iModuleID, "OnBeforeUpdateReview" );
	while ( $arEvent = $rsEvents->Fetch() )
	{
		if (ExecuteModuleEvent( $arEvent, $Fields ))
		{
		}
	}
	
	$result=ReviewsTable::GetById($id);
	$Review=$result->Fetch();
	
	
	$result=ReviewsTable::update($id,$arFields);
	
	
	
	
	if($action=='LIKES')
		$AnaliticAction=1;
	else 
		$AnaliticAction=2;
		
	$user=$Review['ID_USER'];	
		
	if(!isset($user) || empty($user))
		$user=0;
	
		if(!empty($user))
		{
			if($action=='LIKES')
				CSotbitReviews::UserMoney($user,'LIKE',SITE_ID,$id);
			elseif($action=='DISLIKES')
				CSotbitReviews::UserMoney($user,'DISLIKE',SITE_ID,$id);
		}
		
		
		

		
	$AnaliticFields=array(
			'ID_USER'=>$user,
			'IP_USER'=>$_SERVER['REMOTE_ADDR'],
			'ID_RCQ'=>$id,
			'ACTION'=>$AnaliticAction,
			'DATE_CREATION'=>new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' )	
	);
	
	AnaliticTable::add($AnaliticFields);
	
	
	$rsEvents = GetModuleEvents( CSotbitReviews::iModuleID, "OnAfterUpdateReview" );
	while ( $arEvent = $rsEvents->Fetch() )
	{
		if (ExecuteModuleEvent( $arEvent, $Fields ))
		{
		}
	}
	if(!$result->isSuccess())
	{
		$errors = $result->getErrorMessages();
		foreach($errors as $error)
			echo $error.' ';
	}
	SetCookie ( 'LIKE["'.$id.'"]', $id, time () + 3600 * 24 * 365, '/' );
}
?>