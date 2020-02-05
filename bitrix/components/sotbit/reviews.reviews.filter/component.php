<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Loader;
use Sotbit\Reviews\ReviewsTable;
use Sotbit\Reviews\ReviewsfieldsTable;
global $APPLICATION;
global $USER;
global $CACHE_MANAGER;
if (!Loader::includeModule( 'sotbit.reviews' ))
	return false;
 
global $SotbitUseFilter;
$SotbitUseFilter = 'Y';
if (!isset( $arParams["MAX_RATING"] ))
	$arParams["MAX_RATING"] = 5;
if (!isset( $arParams["PRIMARY_COLOR"] ))
	$arParams["PRIMARY_COLOR"] = "#a76e6e";
if (!isset( $arParams["AJAX"] ))
	$arParams["AJAX"] = "N";

$obCache = Bitrix\Main\Data\Cache::createInstance ();
$life_time = $arParams["CACHE_TIME"];
$cache_id = 'sotbit_reviews_' . $arParams['ID_ELEMENT'];
$cachePath = '/SotbitReviews';

$CacheValues = CSotbitReviews::GetCacheValues('');

if ($arParams["AJAX"] != 'Y')
{  
	if(isset($CacheValues['SOTBIT_REVIEWS_REVIEWS_FILTER']))
	{
		$arResult = $CacheValues['SOTBIT_REVIEWS_REVIEWS_FILTER'];
	}
	if(!isset($arResult) || !is_array($arResult) || sizeof($arResult)==0)
	{
		if (!is_array( CSotbitReviews::getReviewsCnt()) && count( CSotbitReviews::getReviewsCnt() < 1 ))
		{
			CSotbitReviews::setReviewsCnt( $arParams['ID_ELEMENT'], $arParams["MAX_RATING"], SITE_ID );
		}
		$arResult = CSotbitReviews::getReviewsCnt();
		
		$IDx = COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_ID_ELEMENT_" . SITE_ID, "ID_ELEMENT" );
		$Filter = array(
				'=' . $IDx => $arParams['ID_ELEMENT'],
				'=ACTIVE' => 'Y',
				'=MODERATED' => 'Y' 
		);
		
		$rsDataPic = ReviewsTable::getList( array(
				'select' => array(
						'ID' 
				),
				'filter' => $Filter,
				'order' => array() 
		) );
		
		$arResult['CNT_PIC'] = 0;
		$ReviewsIds = array();
		while ( $Review = $rsDataPic->Fetch() )
		{
			$ReviewsIds[]=$Review['ID'];
		}
		
		if (Loader::includeModule( 'fileman' ) && COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_" . SITE_ID, "" ) == 'Y')
		{
			CMedialib::Init();
			
			//parent collection
			$arCollections = CMedialibCollection::GetList( array(
					'arOrder' => Array(
							'NAME' => 'ASC'
							),
					'arFilter' => array(
							'ACTIVE' => 'Y',
							'NAME' => 'sotbit.reviews'
					)
			) );
			if(!$arCollections[0]['ID'])
			{
				$arResult['CNT_PIC']=0;
			}
			else 
			{
				$ParentId = $arCollections[0]['ID'];
				$arCollections = CMedialibCollection::GetList( array(
						'arOrder' => Array(
								'NAME' => 'ASC'
								),
						'arFilter' => array(
								'ACTIVE' => 'Y',
								'PARENT_ID' => $ParentId
						)
				) );
				if(in_array($arCollections[0]['NAME'],$ReviewsIds))
				{
					$ID_COLLECTION = $arCollections[0]['ID'];
					$arItems = CMedialibItem::GetList( array(
							'arCollections' => array(
									"0" => $ID_COLLECTION
							)
					) );
					if (isset( $arItems ) && is_array( $arItems ))
					{
						foreach ( $arItems as $arItem )
						{
							if ($arItem['TYPE'] == 'image')
							{
								++$arResult['CNT_PIC'];
								break;
							}
						}
					}
				}
			}
		}
		
		
		
		if(isset($_COOKIE['sotbit_reviews_filter_sort_images']) && $_COOKIE['sotbit_reviews_filter_sort_images']=="Y")
		{
			$arResult['SORT_IMAGES']="Y";
		}
		
		
		$arResult['TITLE']='NEW';
		$arResult['BY']='DATE_CREATION';
		$arResult['ORDER']='desc';
		if(isset($_COOKIE['sotbit_reviews_filter_sort_by']) && $_COOKIE['sotbit_reviews_filter_sort_by']=='DATE_CREATION')
		{
			if(isset($_COOKIE['sotbit_reviews_filter_sort_order']) && $_COOKIE['sotbit_reviews_filter_sort_order']=="asc")
			{
				$arResult['TITLE']='OLD';
				$arResult['BY']="DATE_CREATION";
				$arResult['ORDER']='asc';
			}
			elseif(isset($_COOKIE['sotbit_reviews_filter_sort_order']) && $_COOKIE['sotbit_reviews_filter_sort_order']=="desc")
			{
				$arResult['TITLE']='NEW';
				$arResult['BY']="DATE_CREATION";
				$arResult['ORDER']='desc';
			}
		}
		if(isset($_COOKIE['sotbit_reviews_filter_sort_by']) && $_COOKIE['sotbit_reviews_filter_sort_by']=='RATING')
		{
			if(isset($_COOKIE['sotbit_reviews_filter_sort_order']) && $_COOKIE['sotbit_reviews_filter_sort_order']=="asc")
			{
				$arResult['TITLE']='LOW_RATING';
				$arResult['BY']="RATING";
				$arResult['ORDER']='asc';
			}
			elseif(isset($_COOKIE['sotbit_reviews_filter_sort_order']) && $_COOKIE['sotbit_reviews_filter_sort_order']=="desc")
			{
				$arResult['TITLE']='HIGH_RATING';
				$arResult['BY']="RATING";
				$arResult['ORDER']='desc';
			}
		}
		if(isset($_COOKIE['sotbit_reviews_filter_sort_by']) && $_COOKIE['sotbit_reviews_filter_sort_by']=='LIKES')
		{
			if(isset($_COOKIE['sotbit_reviews_filter_sort_order']) && $_COOKIE['sotbit_reviews_filter_sort_order']=="desc")
			{
				$arResult['TITLE']='LIKES';
				$arResult['BY']="LIKES";
				$arResult['ORDER']='desc';
			}
		}

		$arResult['FILTER_REVIEWS_TITLE']="";
		$arResult['FILTER_REVIEWS_VALUE']=-1;
		if(isset($_COOKIE['sotbit_reviews_filter_sort_rating']) && $_COOKIE['sotbit_reviews_filter_sort_rating']>0)
		{
			for($k=1;$k<=$_COOKIE['sotbit_reviews_filter_sort_rating'];++$k){
				$arResult['FILTER_REVIEWS_TITLE'].='
					<span class="uni-stars">
						<i class="fa fa-star" aria-hidden="true"></i>
					</span>';		
			}
			$arResult['FILTER_REVIEWS_TITLE'].='('.$arResult['CNT_REVIEW'][$_COOKIE['sotbit_reviews_filter_sort_rating']].')';
			$arResult['FILTER_REVIEWS_VALUE']=$_COOKIE['sotbit_reviews_filter_sort_rating'];
		}
		if(!isset($arResult['FILTER_REVIEWS_TITLE']) || $arResult['FILTER_REVIEWS_TITLE']=="")
		{
			$arResult['FILTER_REVIEWS_TITLE']=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_FILTER_GENERAL_RATING")." (".$arResult['SUM_CNT_REVIEWS'].")";
		}
		CSotbitReviews::SetCacheValues('SOTBIT_REVIEWS_REVIEWS_FILTER',$arResult);
	}
}
$this->IncludeComponentTemplate();

?>