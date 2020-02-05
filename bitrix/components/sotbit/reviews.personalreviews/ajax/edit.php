<?
use Sotbit\Reviews\ReviewsTable;
use Sotbit\Reviews\ReviewsfieldsTable;
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
		
		
		$result = ReviewsTable::GetById( $Id );
		if($Review=$result->Fetch())
		{
			$return=$Review;
			$return['TEXT']=CSotbitReviews::bb2html($Review['TEXT']);
			$return['THUMB_IMAGE']=array();
			
			$return['ADD_FIELDS'] = unserialize( $Review['ADD_FIELDS'] );
			
			if (Loader::includeModule( 'fileman' ) && COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_" . SITE_ID, "" ) == 'Y')
			{
				CMedialib::Init();
				$arCollections = CMedialibCollection::GetList( array(
						'arOrder' => Array(
								'NAME' => 'ASC'
								),
						'arFilter' => array(
								'ACTIVE' => 'Y',
								'NAME' => 'sotbit.reviews'
						)
				) );
					
					
				$ParentCollection = $arCollections[0]['ID'];
					
				$arCollections = CMedialibCollection::GetList( array(
						'arOrder' => Array(
								'NAME' => 'ASC'
								),
						'arFilter' => array(
								'ACTIVE' => 'Y',
								'PARENT_ID' => $ParentCollection,
								'NAME' => $Review['ID']
						)
				) );
					
				if (isset( $arCollections ) && is_array( $arCollections ) && count( $arCollections ) != 0 && $arCollections[0]['PARENT_ID'] == $ParentCollection && $arCollections[0]['NAME'] == $Review['ID'])
				{
					$ID_COLLECTION = $arCollections[0]['ID'];
					$arItems = CMedialibItem::GetList( array(
							'arCollections' => array(
									"0" => $ID_COLLECTION
							)
					) );
					if (isset( $arItems ) && is_array( $arItems ))
					{
						$k = 0;
						foreach ( $arItems as $arItem )
						{
							if ($arItem['TYPE'] == 'image')
							{
								$file = CFile::ResizeImageGet( $arItem['SOURCE_ID'], array(
										'width' => COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_THUMB_WIDTH_" . SITE_ID, "" ),
										'height' => COption::GetOptionString( CSotbitReviews::iModuleID, "REVIEWS_THUMB_HEIGHT_" . SITE_ID, "" )
								), BX_RESIZE_IMAGE_PROPORTIONAL, true );
								
								$return['THUMB_IMAGE'][$k] = $file['src'];
								$return['BIG_IMAGE'][$k] = $arItem['PATH'];
							}
							++$k;
						}
					}
				}
			}
		}
		echo json_encode($return);
	}
}
?> 