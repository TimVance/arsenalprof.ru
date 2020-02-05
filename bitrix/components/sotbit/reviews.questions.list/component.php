<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Loader;
use Sotbit\Reviews\QuestionsTable;

if(!Loader::includeModule('sotbit.reviews') || !Loader::includeModule('iblock'))
	return false;

global $APPLICATION;
global $USER;
global $CACHE_MANAGER;

global $SotbitFilterPageQuestions;

if (!isset($arParams["TEXTBOX_MAXLENGTH"]))
	$arParams["REVIEWS_TEXTBOX_MAXLENGTH"] = 100;
if (!isset($arParams["AJAX"]))
	$arParams["AJAX"] = "N";
if (!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;
if (!isset($arParams["CACHE_TYPE"]))
	$arParams["CACHE_TYPE"] = "A";
if (!isset($arParams["DATE_FORMAT"]))
	$arParams["DATE_FORMAT"] = "d F Y, H:i";


$IDx=COption::GetOptionString(CSotbitReviews::iModuleID, "QUESTIONS_ID_ELEMENT_".SITE_ID, "ID_ELEMENT");
$obCache = Bitrix\Main\Data\Cache::createInstance();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_'.$arParams['ID_ELEMENT'];
$cachePath = '/SotbitReviews';
if($arParams['AJAX']!='Y')
{
	$CacheValues = CSotbitReviews::GetCacheValues('');
	
	if(isset($CacheValues['SOTBIT_REVIEWS_QUESTIONS_LIST']))
	{
		$arResult = $CacheValues['SOTBIT_REVIEWS_QUESTIONS_LIST'];
	}
	if(!isset($arResult) || !is_array($arResult) || sizeof($arResult)==0)
	{

	if ($IDx == 'ID_ELEMENT')
		$FilterEl = array(
				'=ID' => $arParams['ID_ELEMENT']
		);
		else
			$FilterEl = array(
					'=XML_ID' => $arParams['ID_ELEMENT']
			);
			$Elem = CIBlockElement::GetList( array(
					"SORT" => "ASC"
			), $FilterEl, false, false, array(
					'NAME','DETAIL_PAGE_URL','DETAIL_PICTURE','PREVIEW_PICTURE'
			) );
			$arResult['ELEMENT'] = $Elem->GetNext();


		if (isset ( $SotbitFilterPageQuestions ) && ! empty ( $SotbitFilterPageQuestions ))
		{
			$arResult['CURRENT_PAGE'] = $SotbitFilterPageQuestions;
		}
		elseif (isset ( $_COOKIE['sotbit_questions_filter_page'] ))
		{
			$arResult['CURRENT_PAGE'] = $_COOKIE['sotbit_questions_filter_page'];
		}
		else
		{
			$arResult['CURRENT_PAGE'] = 1;
		}



		$Filter = array('='.$IDx => $arParams['ID_ELEMENT'],'=ACTIVE'=>'Y','=MODERATED'=>'Y');


		if (isset ( $arResult['CURRENT_PAGE'] ))
		{
			$limit = COption::GetOptionString ( CSotbitReviews::iModuleID, "QUESTIONS_COUNT_PAGE_" . SITE_ID, "10" );
			$offset = ($arResult['CURRENT_PAGE'] - 1) * COption::GetOptionString ( CSotbitReviews::iModuleID, "QUESTIONS_COUNT_PAGE_" . SITE_ID, "10" );
		}
		else
		{
			$limit = COption::GetOptionString ( CSotbitReviews::iModuleID, "QUESTIONS_COUNT_PAGE_" . SITE_ID, "10" );
			$offset = 0;
		}



		$rsData=QuestionsTable::getList(array(
			'select' => array('ID','ID_USER','DATE_CREATION','QUESTION','ANSWER'),
			'filter' =>$Filter,
			'order' => array('DATE_CREATION'=>'desc'),
			'limit' => $limit,
			'offset' => $offset
		));

		$rsSites = CSite::GetByID(SITE_ID);
		$arSite = $rsSites->Fetch();
		$arResult['SITE_NAME']=$arSite['SITE_NAME'];
		$i=0;
		$COUNTRIES=GetCountryArray(LANGUAGE_ID);
		$UsersIds=array();

		$QuestionsPerPage = COption::GetOptionString ( CSotbitReviews::iModuleID, "QUESTIONS_COUNT_PAGE_" . SITE_ID,
			"10" );
		$AllCount = QuestionsTable::GetCount ( $Filter );
		if ($AllCount % $QuestionsPerPage == 0)
			$arResult['CNT_PAGES'] = $AllCount / $QuestionsPerPage;
		else
			$arResult['CNT_PAGES'] = floor ( $AllCount / $QuestionsPerPage ) + 1;



		$QuestionsIds=array();
		while($Question = $rsData->Fetch()){

			$QuestionsIds[]=$Question['ID'];

			if($Question['ID_USER']>0)
			{
				$UsersIds[]=$Question['ID_USER'];
				$Users = CUser::GetByID($Question['ID_USER']);
				if($User = $Users->Fetch()){unset($Users);} else continue;
			}
			elseif($Question['ID_USER']==0)
			{
				$User['NAME']=GetMessage(CSotbitReviews::iModuleID.'_QUESTIONS_GUEST');
				$User['LAST_NAME']="";
			}
			$arResult['QUESTIONS'][$i]['ID']=$Question['ID'];
			$arResult['QUESTIONS'][$i]['ID_USER'] =$Question['ID_USER'];
			$arResult['QUESTIONS'][$i]['PERSONAL_PHOTO']=CFile::ShowImage($User['PERSONAL_PHOTO'], 50, 50,'border="0" title="'.$User['NAME'].' '.$User['LAST_NAME'].'" alt="'.$User['NAME'].' '.$User['LAST_NAME'].'"');
			$arResult['QUESTIONS'][$i]['NAME']=$User['NAME'];
			$arResult['QUESTIONS'][$i]['LAST_NAME']=$User['LAST_NAME'];
			$arResult['QUESTIONS'][$i]['COUNTRY']=(isset($User['PERSONAL_COUNTRY']) && !empty($User['PERSONAL_COUNTRY']))?$COUNTRIES['reference'][array_search($User['PERSONAL_COUNTRY'],$COUNTRIES['reference_id'])]:'';
			$arResult['QUESTIONS'][$i]['AGE']=(isset($User['PERSONAL_BIRTHDAY']) && !empty($User['PERSONAL_BIRTHDAY']))?(int)((date('Ymd') - date('Ymd', strtotime($User['PERSONAL_BIRTHDAY']))) / 10000):'';
			$arResult['QUESTIONS'][$i]['DATE_CREATION']=CIBlockFormatProperties::DateFormat($arParams["DATE_FORMAT"], MakeTimeStamp($Question['DATE_CREATION'], CSite::GetDateFormat()));
			$arResult['QUESTIONS'][$i]['QUESTION']=CSotbitReviews::bb2html($Question['QUESTION']);
			$arResult['QUESTIONS'][$i]['ANSWER']=CSotbitReviews::bb2html($Question['ANSWER']);
			++$i;
		}


		unset($Question);

		$rsData = QuestionsTable::getList( array(
				'select' => array(
						'ID_USER',
				),
				'filter' => array('=ID_USER' => $UsersIds,'=ACTIVE'=>'Y','=MODERATED'=>'Y'),

		) );

		$LinkToUser=COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_USER_PAGE_" . SITE_ID, "" );

		$arResult['USER_QUESTIONS_CNT']=array();
		$arResult['LINK_TO_USER']=array();
		while ( $Question = $rsData->Fetch() )
		{
			if(!empty($LinkToUser) && !isset($arResult['LINK_TO_USER'][$Question['ID_USER']]))
			{
				$arResult['LINK_TO_USER'][$Question['ID_USER']]=$LinkToUser.'?user='.$Question['ID_USER'];
			}


			if(!isset($arResult['USER_QUESTIONS_CNT'][$Question['ID_USER']]))
			{
				$arResult['USER_QUESTIONS_CNT'][$Question['ID_USER']]=1;
			}
			else
			{
				++$arResult['USER_QUESTIONS_CNT'][$Question['ID_USER']];
			}
		}


		$arResult['SHARE_SERVICES']=unserialize(COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_SHARE_" . SITE_ID, "" ));

		$arResult['SHARE_LINK']=COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_SHARE_LINK_" . SITE_ID, "Y" );

		$arResult['FACEBOOK_APP_ID']=COption::GetOptionString( CSotbitReviews::iModuleID, "QUESTIONS_FACEBOOK_ID_" . SITE_ID, "" );


		$POST_RIGHT = $APPLICATION->GetGroupRight('sotbit.reviews');
		if ($POST_RIGHT == "W")
			$arResult['MODERATOR']="Y";
		else
			$arResult['MODERATOR']="N";

		unset($rsData);
		unset($Question);
		unset($Users);
		unset($User);
		unset($i);

		$arResult["CNT_LEFT_PGN"] = 3;
		$arResult["CNT_RIGHT_PGN"] = 3;

		$arResult['QUESTIONS_IDS']=serialize($QuestionsIds);
		CSotbitReviews::SetCacheValues('SOTBIT_REVIEWS_QUESTIONS_LIST',$arResult);
	}

}

$this->IncludeComponentTemplate();


?>