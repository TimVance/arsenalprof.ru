<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

if (!CModule::IncludeModule("iblock"))
	return;

if (!CModule::IncludeModule("sale"))
	return;

if (!CModule::IncludeModule("catalog"))
	return;

$db_vars = CSaleLocation::GetList(
        array(
                "SORT" => "ASC",
                "COUNTRY_NAME_LANG" => "ASC",
                "CITY_NAME_LANG" => "ASC",
                "!CITY_NAME" => false
            ),
        array("LID" => LANGUAGE_ID),
        false,
        false,
        array()
    );

$rsSites = CSite::GetList($by="sort", $order="desc", Array());
while ($arSites = $rsSites->Fetch())
{
  $arSite[$arSites["ID"]] = $arSites["NAME"]."[".$arSites["ID"]."]";
}
while ($vars = $db_vars->Fetch())
{
    if(!empty($vars["CITY_NAME"]))$arCity[$vars["CITY_NAME"]] = $vars["CITY_NAME"];
}
if(CModule::IncludeModule("catalog"))
{
	$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
	while($arr=$rsPrice->Fetch()) $arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
}

$boolCatalog = \Bitrix\Main\Loader::includeModule("catalog");

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];

$arProperty = array();
$arProperty_N = array();
$arProperty_X = array();
if (0 < intval($arCurrentValues["IBLOCK_ID"]))
{
	$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"], "ACTIVE"=>"Y"));
	while ($arr=$rsProp->Fetch())
	{
		$code = $arr["CODE"];
		$label = "[".$arr["CODE"]."] ".$arr["NAME"];

		if($arr["PROPERTY_TYPE"] != "F")
			$arProperty[$code] = $label;

		if($arr["PROPERTY_TYPE"]=="N")
			$arProperty_N[$code] = $label;

		if($arr["PROPERTY_TYPE"]!="F")
		{
			if($arr["MULTIPLE"] == "Y")
				$arProperty_X[$code] = $label;
			elseif($arr["PROPERTY_TYPE"] == "L")
				$arProperty_X[$code] = $label;
			elseif($arr["PROPERTY_TYPE"] == "E" && $arr["LINK_IBLOCK_ID"] > 0)
				$arProperty_X[$code] = $label;
		}
	}
}

$arOffers = CIBlockPriceTools::GetOffersIBlock($arCurrentValues["IBLOCK_ID"]);
$OFFERS_IBLOCK_ID = is_array($arOffers)? $arOffers["OFFERS_IBLOCK_ID"]: 0;
$arProperty_Offers = array();
$arProperty_OffersWithoutFile = array();
if($OFFERS_IBLOCK_ID)
{
	$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$OFFERS_IBLOCK_ID, "ACTIVE"=>"Y"));
	while($arr=$rsProp->Fetch())
	{
		$arr['ID'] = intval($arr['ID']);
		if ($arOffers['OFFERS_PROPERTY_ID'] == $arr['ID'])
			continue;
		$strPropName = '['.$arr['ID'].']'.('' != $arr['CODE'] ? '['.$arr['CODE'].']' : '').' '.$arr['NAME'];
		if ('' == $arr['CODE'])
			$arr['CODE'] = $arr['ID'];
		$arProperty_Offers[$arr["CODE"]] = $strPropName;
		if ('F' != $arr['PROPERTY_TYPE'])
			$arProperty_OffersWithoutFile[$arr["CODE"]] = $strPropName;
	}
}

$arSort = CIBlockParameters::GetElementSortFields(
	array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
	array('KEY_LOWERCASE' => 'Y')
);

$arPrice = array();
if ($boolCatalog)
{
	$arSort = array_merge($arSort, CCatalogIBlockParameters::GetCatalogSortFields());
	$rsPrice = CCatalogGroup::GetList($v1="sort", $v2="asc");
	while($arr=$rsPrice->Fetch()) $arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
}
else
{
	$arPrice = $arProperty_N;
}

$arAscDesc = array(
	"asc" => GetMessage("IBLOCK_SORT_ASC"),
	"desc" => GetMessage("IBLOCK_SORT_DESC"),
);


$arComponentParameters = array(
	"GROUPS" => array(
        "BASKET" => array(
			"SORT" => 100,
			"NAME" => GetMessage("SHS_ONLINE_BASKET"),
		),
        "USERS" => array(
			"SORT" => 200,
			"NAME" => GetMessage("SHS_ONLINE_USERS"),
		),
        "PRICES" => array(
			"SORT" => 300,
			"NAME" => GetMessage("SHS_ONLINE_PRICES"),
		),
	),


	"PARAMETERS" => array(
        "TYPE_MODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SHS_ONLINE_TYPE_MODE"),
			"TYPE" => "CHECKBOX",
			"REFRESH" => "Y",
            "DEFAULT" => "N"
		),

        "ONLINE_TYPE" => array(
			"PARENT" => "BASKET",
			"NAME" => GetMessage("SHS_ONLINE_TYPE"),
			"TYPE" => "LIST",
            "VALUES" => array(0=>GetMessage("SHS_ONLINE_TYPE1"), 1=>GetMessage("SHS_ONLINE_TYPE2"), 2=>GetMessage("SHS_ONLINE_TYPE3")),
            "DEFAULT" => 0
		),
		"SHOW_COUNT_BUYERS" => array(
			"PARENT" => "USERS",
			"NAME" => GetMessage("SHS_ONLINE_SHOW_COUNT_BUYERS"),
			"TYPE" => "CHECKBOX",
			"REFRESH" => "Y",
            "DEFAULT" => "N"
		),
        /*"TYPE_COUNT_BUYERS" => array(
			"PARENT" => "BASE",
		    "NAME" => GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS"),
		    "TYPE" => "LIST",
		    "VALUES" => array(0=>GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS1"), 1=>GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS2")),
            "DEFAULT" => 0,
		    "REFRESH" => "Y",
		),*/
        "IMAGE_WIDTH" => array(
			"PARENT" => "BASKET",
			"NAME" => GetMessage("SHS_ONLINE_IMAGE_WIDTH"),
			"TYPE" => "STRING",
            "DEFAULT" => "100"
		),
        "IMAGE_HEIGHT" => array(
			"PARENT" => "BASKET",
			"NAME" => GetMessage("SHS_ONLINE_IMAGE_HEIGHT"),
			"TYPE" => "STRING",
            "DEFAULT" => "200"
		),
        "SHOW_NAME" => array(
			"PARENT" => "BASKET",
			"NAME" => GetMessage("SHS_ONLINE_SHOW_NAME"),
			"TYPE" => "LIST",
            "VALUES" => array(0=>GetMessage("SHS_ONLINE_PRODUCT_NAME"), 1=>GetMessage("SHS_ONLINE_OFFER_NAME")),
            "DEFAULT" => 0
		),
        "SHOW_CITY" => array(
			"PARENT" => "USERS",
			"NAME" => GetMessage("SHS_ONLINE_SHOW_CITY"),
			"TYPE" => "CHECKBOX",
			"REFRESH" => "Y",
            "DEFAULT" => "N"
		),

        "PRICE_CODE" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("SHS_ONLINE_PRICE_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $arPrice,
		),
        "SITE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("SHS_ONLINE_SITE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $arSite,
		),
        "ELEMENT_COUNT" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("SHS_ONLINE_ELEMENT_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "20",
		),
        "JQUERY" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("SHS_ONLINE_JQUERY"),
			"TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
		),
        "CACHE_TIME" => array(
			"DEFAULT" => 36000000,
		),
	),
);

if (CModule::IncludeModule("statistic"))
{
  $arStat = array(0=>GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS1"), 1=>GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS2"));
}else{
    $arStat = array(1=>GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS2"));
}
if($arCurrentValues["SHOW_COUNT_BUYERS"]=="Y")
{
    $arComponentParameters["PARAMETERS"]["TYPE_COUNT_BUYERS"] = array(
        "PARENT" => "USERS",
		"NAME" => GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS"),
		"TYPE" => "LIST",
		"VALUES" => $arStat,
        "DEFAULT" => 0,
		"REFRESH" => "Y",
    );
}

if($arCurrentValues["TYPE_MODE"]=="Y")
{
    $arComponentParameters["PARAMETERS"]["IBLOCK_TYPE"] = array(
        "PARENT" => "BASE",
		"NAME" => GetMessage("IBLOCK_TYPE"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["IBLOCK_ID"] = array(
        "PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_IBLOCK"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["ELEMENT_SORT_FIELD"] = array(
        "PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_ELEMENT_SORT_FIELD"),
			"TYPE" => "LIST",
			"VALUES" => $arSort,
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "sort",
    );
    $arComponentParameters["PARAMETERS"]["ELEMENT_SORT_ORDER"] = array(
        "PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_ELEMENT_SORT_ORDER"),
			"TYPE" => "LIST",
			"VALUES" => $arAscDesc,
			"DEFAULT" => "asc",
			"ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["ELEMENT_SORT_FIELD2"] = array(
        "PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_ELEMENT_SORT_FIELD2"),
			"TYPE" => "LIST",
			"VALUES" => $arSort,
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "id",
    );
    $arComponentParameters["PARAMETERS"]["ELEMENT_SORT_ORDER2"] = array(
        "PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_ELEMENT_SORT_ORDER2"),
			"TYPE" => "LIST",
			"VALUES" => $arAscDesc,
			"DEFAULT" => "desc",
			"ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["FILTER_NAME"] = array(
        "PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_FILTER_NAME_IN"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
    );
    $arComponentParameters["PARAMETERS"]["SECTION_URL"] = CIBlockParameters::GetPathTemplateParam(
        "SECTION",
			"SECTION_URL",
			GetMessage("IBLOCK_SECTION_URL"),
			"",
			"URL_TEMPLATES"
    );
    $arComponentParameters["PARAMETERS"]["DETAIL_URL"] = CIBlockParameters::GetPathTemplateParam(
        "DETAIL",
			"DETAIL_URL",
			GetMessage("IBLOCK_DETAIL_URL"),
			"",
			"URL_TEMPLATES"
    );
    $arComponentParameters["PARAMETERS"]["SECTION_ID_VARIABLE"] = array(
        "PARENT" => "URL_TEMPLATES",
			"NAME"		=> GetMessage("IBLOCK_SECTION_ID_VARIABLE"),
			"TYPE"		=> "STRING",
			"DEFAULT"	=> "SECTION_ID"
    );
    $arComponentParameters["PARAMETERS"]["DISPLAY_COMPARE"] = array(
        "PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_DISPLAY_COMPARE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
    );
    $arComponentParameters["PARAMETERS"]["LINE_ELEMENT_COUNT"] = array(
        "PARENT" => "VISUAL",
			"NAME" => GetMessage("IBLOCK_LINE_ELEMENT_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "3",
    );
    $arComponentParameters["PARAMETERS"]["PROPERTY_CODE"] = array(
        "PARENT" => "VISUAL",
			"NAME" => GetMessage("IBLOCK_PROPERTY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty,
			"ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["OFFERS_FIELD_CODE"] = CIBlockParameters::GetFieldCode(GetMessage("CP_BCT_OFFERS_FIELD_CODE"), "VISUAL");
    $arComponentParameters["PARAMETERS"]["OFFERS_PROPERTY_CODE"] = array(
        "PARENT" => "VISUAL",
			"NAME" => GetMessage("CP_BCT_OFFERS_PROPERTY_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty_Offers,
			"ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["OFFERS_SORT_FIELD"] = array(
        "PARENT" => "VISUAL",
			"NAME" => GetMessage("CP_BCT_OFFERS_SORT_FIELD"),
			"TYPE" => "LIST",
			"VALUES" => $arSort,
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "sort",
    );
    $arComponentParameters["PARAMETERS"]["OFFERS_SORT_ORDER"] = array(
        "PARENT" => "VISUAL",
			"NAME" => GetMessage("CP_BCT_OFFERS_SORT_ORDER"),
			"TYPE" => "LIST",
			"VALUES" => $arAscDesc,
			"DEFAULT" => "asc",
			"ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["OFFERS_SORT_FIELD2"] = array(
        "PARENT" => "VISUAL",
			"NAME" => GetMessage("CP_BCT_OFFERS_SORT_FIELD2"),
			"TYPE" => "LIST",
			"VALUES" => $arSort,
			"ADDITIONAL_VALUES" => "Y",
			"DEFAULT" => "id",
    );
    $arComponentParameters["PARAMETERS"]["OFFERS_SORT_ORDER2"] = array(
        "PARENT" => "VISUAL",
			"NAME" => GetMessage("CP_BCT_OFFERS_SORT_ORDER2"),
			"TYPE" => "LIST",
			"VALUES" => $arAscDesc,
			"DEFAULT" => "desc",
			"ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["OFFERS_LIMIT"] = array(
        "PARENT" => "VISUAL",
			"NAME" => GetMessage('CP_BCT_OFFERS_LIMIT'),
			"TYPE" => "STRING",
			"DEFAULT" => 5,
    );
    $arComponentParameters["PARAMETERS"]["PRICE_CODE"] = array(
        "PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arPrice,
    );
    $arComponentParameters["PARAMETERS"]["USE_PRICE_COUNT"] = array(
        "PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_USE_PRICE_COUNT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
    );
    $arComponentParameters["PARAMETERS"]["SHOW_PRICE_COUNT"] = array(
        "PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_SHOW_PRICE_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "1"
    );
    $arComponentParameters["PARAMETERS"]["PRICE_VAT_INCLUDE"] = array(
        "PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_VAT_INCLUDE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["BASKET_URL"] = array(
        "PARENT" => "BASKET",
			"NAME" => GetMessage("IBLOCK_BASKET_URL"),
			"TYPE" => "STRING",
			"DEFAULT" => "/personal/basket.php",
    );
    $arComponentParameters["PARAMETERS"]["ACTION_VARIABLE"] = array(
        "PARENT" => "BASKET",
			"NAME"		=> GetMessage("IBLOCK_ACTION_VARIABLE"),
			"TYPE"		=> "STRING",
			"DEFAULT"	=> "action"
    );
    $arComponentParameters["PARAMETERS"]["PRODUCT_ID_VARIABLE"] = array(
        "PARENT" => "BASKET",
			"NAME"		=> GetMessage("IBLOCK_PRODUCT_ID_VARIABLE"),
			"TYPE"		=> "STRING",
			"DEFAULT"	=> "id"
    );
    $arComponentParameters["PARAMETERS"]["USE_PRODUCT_QUANTITY"] = array(
        "PARENT" => "BASKET",
			"NAME" => GetMessage("CP_BCT_USE_PRODUCT_QUANTITY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["PRODUCT_QUANTITY_VARIABLE"] = array(
        "PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("CP_BCT_PRODUCT_QUANTITY_VARIABLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "quantity",
			"HIDDEN" => (isset($arCurrentValues['USE_PRODUCT_QUANTITY']) && $arCurrentValues['USE_PRODUCT_QUANTITY'] == 'Y' ? 'N' : 'Y')
    );
    $arComponentParameters["PARAMETERS"]["ADD_PROPERTIES_TO_BASKET"] = array(
        "PARENT" => "BASKET",
			"NAME" => GetMessage("CP_BCT_ADD_PROPERTIES_TO_BASKET"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y"
    );
    $arComponentParameters["PARAMETERS"]["PRODUCT_PROPS_VARIABLE"] = array(
        "PARENT" => "BASKET",
			"NAME" => GetMessage("CP_BCT_PRODUCT_PROPS_VARIABLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "prop",
			"HIDDEN" => (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && $arCurrentValues['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'Y' : 'N')
    );
    $arComponentParameters["PARAMETERS"]["PARTIAL_PRODUCT_PROPERTIES"] = array(
        "PARENT" => "BASKET",
			"NAME" => GetMessage("CP_BCT_PARTIAL_PRODUCT_PROPERTIES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"HIDDEN" => (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && $arCurrentValues['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'Y' : 'N')
    );
    $arComponentParameters["PARAMETERS"]["PRODUCT_PROPERTIES"] = array(
        "PARENT" => "BASKET",
			"NAME" => GetMessage("CP_BCT_PRODUCT_PROPERTIES"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty_X,
			"HIDDEN" => (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && $arCurrentValues['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'Y' : 'N')
    );
    $arComponentParameters["PARAMETERS"]["CACHE_FILTER"] = array(
        "PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("IBLOCK_CACHE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
    );
    $arComponentParameters["PARAMETERS"]["CACHE_GROUPS"] = array(
        "PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BCT_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
    );

}

if($arCurrentValues["TYPE_COUNT_BUYERS"]==0)
{
    unset($arComponentParameters["PARAMETERS"]["TYPE_COUNT_BUYERS2_1"]);
    unset($arComponentParameters["PARAMETERS"]["TYPE_COUNT_BUYERS2_2"]);


}elseif($arCurrentValues["TYPE_COUNT_BUYERS"]==1){
    $arComponentParameters["PARAMETERS"]["TYPE_COUNT_BUYERS_MIN"] = array(
        "PARENT" => "USERS",
		"NAME" => GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS_MIN"),
		"TYPE" => "STRING",
        "DEFAULT" => "1",
    );
    $arComponentParameters["PARAMETERS"]["TYPE_COUNT_BUYERS_MAX"] = array(
        "PARENT" => "USERS",
		"NAME" => GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS_MAX"),
		"TYPE" => "STRING",
        "DEFAULT" => "10",
    );

}

if($arCurrentValues["SHOW_CITY"]=="Y")
{
    $arComponentParameters["PARAMETERS"]["CITY_DEFAULT"] = array(
        "PARENT" => "USERS",
		"NAME" => GetMessage("SHS_ONLINE_CITY_DEFAULT"),
		"TYPE" => "LIST",
        "MULTIPLE" => "Y",
        /*"DEFAULT" => GetMessage("SHS_ONLINE_CITY_DEFAULT_MOSCOW"),*/
        "VALUES" => $arCity
    );
}

if($arCurrentValues["SHOW_COUNT_BUYERS"]=="Y")
{
    if (!CModule::IncludeModule("statistic"))
    {
        $arComponentParameters["PARAMETERS"]["TYPE_COUNT_BUYERS2_1"] = array(
            "PARENT" => "USERS",
		    "NAME" => GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS2_1"),
		    "TYPE" => "STRING",
            "DEFAULT" => "1",
        );
        $arComponentParameters["PARAMETERS"]["TYPE_COUNT_BUYERS2_2"] = array(
            "PARENT" => "USERS",
		    "NAME" => GetMessage("SHS_ONLINE_TYPE_COUNT_BUYERS2_2"),
		    "TYPE" => "STRING",
            "DEFAULT" => "10",
        );
    }
}

if (CModule::IncludeModule('catalog') && CModule::IncludeModule('currency'))
{
	$arComponentParameters["PARAMETERS"]['CONVERT_CURRENCY'] = array(
		'PARENT' => 'PRICES',
		'NAME' => GetMessage('SHS_ONLINE_CONVERT_CURRENCY'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	);

	if (isset($arCurrentValues['CONVERT_CURRENCY']) && 'Y' == $arCurrentValues['CONVERT_CURRENCY'])
	{
		$arCurrencyList = array();
		$rsCurrencies = CCurrency::GetList(($by = 'SORT'), ($order = 'ASC'));
		while ($arCurrency = $rsCurrencies->Fetch())
		{
			$arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];
		}
		$arComponentParameters['PARAMETERS']['CURRENCY_ID'] = array(
			'PARENT' => 'PRICES',
			'NAME' => GetMessage('SHS_ONLINE_CURRENCY_ID'),
			'TYPE' => 'LIST',
			'VALUES' => $arCurrencyList,
			'DEFAULT' => CCurrency::GetBaseCurrency(),
			"ADDITIONAL_VALUES" => "Y",
		);
	}
}

if ($boolCatalog)
{
	$arComponentParameters["PARAMETERS"]['HIDE_NOT_AVAILABLE'] = array(
		'PARENT' => 'DATA_SOURCE',
		'NAME' => GetMessage('CP_BCT_HIDE_NOT_AVAILABLE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	);

	$arComponentParameters["PARAMETERS"]['CONVERT_CURRENCY'] = array(
		'PARENT' => 'PRICES',
		'NAME' => GetMessage('CP_BCT_CONVERT_CURRENCY'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	);

	if (isset($arCurrentValues['CONVERT_CURRENCY']) && 'Y' == $arCurrentValues['CONVERT_CURRENCY'])
	{
		$arCurrencyList = array();
		$by = 'SORT';
		$order = 'ASC';
		$rsCurrencies = CCurrency::GetList($by, $order);
		while ($arCurrency = $rsCurrencies->Fetch())
		{
			$arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];
		}
		$arComponentParameters['PARAMETERS']['CURRENCY_ID'] = array(
			'PARENT' => 'PRICES',
			'NAME' => GetMessage('CP_BCT_CURRENCY_ID'),
			'TYPE' => 'LIST',
			'VALUES' => $arCurrencyList,
			'DEFAULT' => CCurrency::GetBaseCurrency(),
			"ADDITIONAL_VALUES" => "Y",
		);
	}
}

if(!$OFFERS_IBLOCK_ID)
{
	unset($arComponentParameters["PARAMETERS"]["OFFERS_FIELD_CODE"]);
	unset($arComponentParameters["PARAMETERS"]["OFFERS_PROPERTY_CODE"]);
	unset($arComponentParameters["PARAMETERS"]["OFFERS_SORT_FIELD"]);
	unset($arComponentParameters["PARAMETERS"]["OFFERS_SORT_ORDER"]);
	unset($arComponentParameters["PARAMETERS"]["OFFERS_SORT_FIELD2"]);
	unset($arComponentParameters["PARAMETERS"]["OFFERS_SORT_ORDER2"]);
}
else
{
	$arComponentParameters["PARAMETERS"]["OFFERS_CART_PROPERTIES"] = array(
		"PARENT" => "BASKET",
		"NAME" => GetMessage("CP_BCT_OFFERS_CART_PROPERTIES"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arProperty_OffersWithoutFile,
		"HIDDEN" => (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && $arCurrentValues['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'Y' : 'N')
	);
}
?>