<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @global CDatabase $DB */
global $DB;
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;
//CModule::IncludeModule("sotbit.analog");
if (!CModule::IncludeModule("iblock") || !CModule::IncludeModule("sale") || !CModule::IncludeModule("sotbit.analog") || !CSotbitAnalog::getDemo())
{
	//ShowError(GetMessage("SAP_MODULE_NOT_INSTALL"));
	return;
}

$arParams["ID"] = IntVal($arParams["ID"]);
if($arParams["ID"] <= 0 && !$arParams["CODE"] && $arParams["MODE"]=="PRODUCT")
	return;
$arParams["ELEMENT_COUNT"] = IntVal($arParams["ELEMENT_COUNT"]);
if($arParams["ELEMENT_COUNT"] <= 0)
	$arParams["ELEMENT_COUNT"] = 5;

$arParams['CONVERT_CURRENCY'] = (isset($arParams['CONVERT_CURRENCY']) && 'Y' == $arParams['CONVERT_CURRENCY'] ? 'Y' : 'N');
$arParams['CURRENCY_ID'] = trim(strval($arParams['CURRENCY_ID']));
if ('' == $arParams['CURRENCY_ID'])
{
	$arParams['CONVERT_CURRENCY'] = 'N';
}
elseif ('N' == $arParams['CONVERT_CURRENCY'])
{
	$arParams['CURRENCY_ID'] = '';
}
$arrFilter = array();
$arAnalogFilter = array();
if($arParams["MODE"]=="BASKET")
{
    $arParams["CACHE_TYPE"] = "N";
    $arParams["~CACHE_TYPE"] = "N";
}
if($this->StartResultCache(false, array($arrFilter, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))))
{
    if($arParams["MODE"]=="PRODUCT")
    {
        if($arParams["CODE"])
        {
            $arParams["ID"] = CIBlockFindTools::GetElementID(
            $arParams["ID"],
            $arParams["CODE"],
            false,
            false,
            array(
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "IBLOCK_LID" => SITE_ID,
                "IBLOCK_ACTIVE" => "Y",
                "ACTIVE_DATE" => "Y",
                "ACTIVE" => "Y",
                "CHECK_PERMISSIONS" => "Y",
            )
            );
        }
        $arFilter["=ID"] = $arParams["ID"];
        $arSelect = $arParams["DETAIL_PROPS_ANALOG"];
        $arSelect[] = "IBLOCK_ID";
        if(in_array("SECTION_ID", $arSelect))$arSelect[] = "IBLOCK_SECTION_ID";
        $rsElement = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while($arElement = $rsElement->Fetch())
        {
            foreach($arSelect as $select)
            {
                if(isset($arElement[$select."_ENUM_ID"]) && $arElement[$select."_ENUM_ID"])
                {
                    $arAnalogFilter[$select][] = $arElement[$select."_ENUM_ID"];
                }elseif(isset($arElement[$select."_VALUE"]) && $arElement[$select."_VALUE"])
                {
                    $arAnalogFilter[$select][] = $arElement[$select."_VALUE"];    
                }elseif($select=="IBLOCK_ID")
                {
                    $arAnalogFilter[$select] = $arElement[$select];    
                }elseif($select=="IBLOCK_SECTION_ID"){
                    $arAnalogFilter["SECTION_ID"] = $arElement[$select];    
                }
            }
        }
        if(!empty($arAnalogFilter))
        {
            $arAnalogFilter["!ID"] = $arParams["ID"];    
        } 
           
    }elseif($arParams["MODE"]=="BASKET")
    {
        $rsBaskets = CSaleBasket::GetList(
            array("ID" => "ASC"),
            array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"),
            false,
            false,
            array(
                "PRODUCT_ID"
            )
        );
        
        while($arBaskets = $rsBaskets->Fetch())
        {
            $arID[] = $arBaskets["PRODUCT_ID"];
        }
        if(!isset($arID)) return;
        $arFilter["=ID"] = $arID;
        $arSelect = $arParams["DETAIL_PROPS_ANALOG"];
        $arSelect[] = "IBLOCK_ID";
        $arSelect[] = "ID"; 
        if(in_array("SECTION_ID", $arSelect))$arSelect[] = "IBLOCK_SECTION_ID";
        $arOffersIBlock = CIBlockPriceTools::GetOffersIBlock($arParams["IBLOCK_ID"]);
        if(is_array($arOffersIBlock))
        {
            $productProp = "PROPERTY_".$arOffersIBlock["OFFERS_PROPERTY_ID"];
            $arSelect[] = $productProp;   
        } 
        $rsElement = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        
        while($arElement = $rsElement->Fetch())
        {   
            if(isset($arElement[$productProp."_VALUE"]) && $arElement[$productProp."_VALUE"])
            {
                $arProductID[] = $arElement[$productProp."_VALUE"];
            }else{
                foreach($arSelect as $select)
                {
                    if(isset($arElement[$select."_ENUM_ID"]) && $arElement[$select."_ENUM_ID"])
                    {
                        $arAnalogFilter[$select][] = $arElement[$select."_ENUM_ID"];
                    }elseif(isset($arElement[$select."_VALUE"]) && $arElement[$select."_VALUE"])
                    {
                        $arAnalogFilter[$select][] = $arElement[$select."_VALUE"];    
                    }elseif($select=="IBLOCK_ID")
                    {
                        $arAnalogFilter[$select][$arElement[$select]] = $arElement[$select];    
                    }elseif($select=="IBLOCK_SECTION_ID"){
                        $arAnalogFilter["SECTION_ID"][$arElement[$select]] = $arElement[$select];    
                    }
                }        
            }
        }
        
        if(!empty($arAnalogFilter))
        {
            $arAnalogFilter["!ID"] = $arID;    
        }
        
        if(isset($arProductID))
        {
            $arFilter["=ID"] = $arProductID;
            $rsElement = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            
            while($arElement = $rsElement->Fetch())
            {
                foreach($arSelect as $select)
                {
                    if(isset($arElement[$select."_ENUM_ID"]) && $arElement[$select."_ENUM_ID"])
                    {
                        $arAnalogFilter[$select][] = $arElement[$select."_ENUM_ID"];
                    }elseif(isset($arElement[$select."_VALUE"]) && $arElement[$select."_VALUE"])
                    {
                        $arAnalogFilter[$select][] = $arElement[$select."_VALUE"];    
                    }elseif($select=="IBLOCK_ID")
                    {
                        $arAnalogFilter[$select][$arElement[$select]] = $arElement[$select];    
                    }elseif($select=="IBLOCK_SECTION_ID"){
                        $arAnalogFilter["SECTION_ID"][$arElement[$select]] = $arElement[$select];    
                    }
                }        
            }
            
            if(!empty($arAnalogFilter))
            {
                if(!isset($arAnalogFilter["!ID"]))$arAnalogFilter["!ID"] = $arProductID; 
                else $arAnalogFilter["!ID"] = array_merge($arProductID, $arAnalogFilter["!ID"]);   
            }   
        }    
    }
    $arResult = CSotbitAnalog::GetAnalogElements($arAnalogFilter);
    $this->EndResultCache();
} 
$this->IncludeComponentTemplate();
?>