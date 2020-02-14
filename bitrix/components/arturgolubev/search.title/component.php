<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


if(!CModule::IncludeModule("arturgolubev.smartsearch"))
{
	global $USER; if($USER->IsAdmin()){
		ShowError(GetMessage("ARTURGOLUBEV_SMARTSEARCH_MODULE_UNAVAILABLE"));
	}
	return;
}

CModule::IncludeModule("search");
$isSearchInstalled = true;//CModule::IncludeModule("search");

if(!isset($arParams["PAGE"]) || strlen($arParams["PAGE"])<=0)
	$arParams["PAGE"] = "#SITE_DIR#search/index.php";



$arResult["CATEGORIES"] = array();
$query = ltrim($_POST["q"]);


$arResult["MODULE_SETTING"] = array();
$arResult["MODULE_SETTING"]["EXT_STANDART_SEARCH"] = COption::GetOptionString("arturgolubev.smartsearch", "use_with_standart");
$arResult["MODULE_SETTING"]["SHOW_PRELOADER"] = COption::GetOptionString("arturgolubev.smartsearch", "show_preloader");

$arResult["DEBUG"] = array();
$arResult["DEBUG"]["QUERY_COUNT"] = 0;
global $USER; if($USER->IsAdmin()){
	$arResult["DEBUG"]["SHOW"] = COption::GetOptionString("arturgolubev.smartsearch", 'debug');
}
$arResult["DEBUG"]["QUERY"] = $query;


if(
	!empty($query)
	&& $_REQUEST["ajax_call"] === "y"
	&& (
		!isset($_REQUEST["INPUT_ID"])
		|| $_REQUEST["INPUT_ID"] == $arParams["INPUT_ID"]
	)
)
{
	CUtil::decodeURIComponent($query);
	if (!$isSearchInstalled)
	{
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/search/tools/language.php");
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/search/tools/stemming.php");
	}

	$arResult["alt_query"] = "";
	if($arParams["USE_LANGUAGE_GUESS"] !== "N")
	{
		$arLang = CSearchLanguage::GuessLanguage($query);
		if(is_array($arLang) && $arLang["from"] != $arLang["to"])
			$arResult["alt_query"] = CSearchLanguage::ConvertKeyboardLayout($query, $arLang["from"], $arLang["to"]);
	}

	$arResult["query"] = $query;
	$arResult["phrase"] = stemming_split($query, LANGUAGE_ID);

	$arParams["NUM_CATEGORIES"] = intval($arParams["NUM_CATEGORIES"]);
	if($arParams["NUM_CATEGORIES"] <= 0)
		$arParams["NUM_CATEGORIES"] = 1;

	$arParams["TOP_COUNT"] = intval($arParams["TOP_COUNT"]);
	if($arParams["TOP_COUNT"] <= 0)
		$arParams["TOP_COUNT"] = 5;
	
	$moduleTopCount = IntVal(COption::GetOptionString("arturgolubev.smartsearch", "title_max_count"));
	if($moduleTopCount) $arParams["TOP_COUNT"] = $moduleTopCount;

	$arResult["DEBUG"]["TOP_COUNT"] = $arParams["TOP_COUNT"]; 

	$arOthersFilter = array("LOGIC"=>"OR");
	
	$alreadyFinded = array();
	for($i = 0; $i < $arParams["NUM_CATEGORIES"]; $i++)
	{
		$bCustom = true;
		if(is_array($arParams["CATEGORY_".$i]))
		{
			foreach($arParams["CATEGORY_".$i] as $categoryCode)
			{
				if ((strpos($categoryCode, 'custom_') !== 0))
				{
					$bCustom = false;
					break;
				}
			}
		}
		else
		{
			$bCustom = (strpos($arParams["CATEGORY_".$i], 'custom_') === 0);
		}

		if ($bCustom)
			continue;

		$category_title = trim($arParams["CATEGORY_".$i."_TITLE"]);
		if(empty($category_title))
		{
			if(is_array($arParams["CATEGORY_".$i]))
				$category_title = implode(", ", $arParams["CATEGORY_".$i]);
			else
				$category_title = trim($arParams["CATEGORY_".$i]);
		}
		if(empty($category_title))
			continue;

		$arResult["CATEGORIES"][$i] = array(
			"TITLE" => htmlspecialcharsbx($category_title),
			"ITEMS" => array()
		);

		if ($isSearchInstalled)
		{
			$arResult["DEBUG"]["TYPE"] = 'standart'; 
			$exFILTER = array(
				0 => CSearchParameters::ConvertParamsToFilter($arParams, "CATEGORY_".$i),
			);
			$exFILTER[0]["LOGIC"] = "OR";

			if($arParams["CHECK_DATES"] === "Y")
				$exFILTER["CHECK_DATES"] = "Y";

			$arOthersFilter[] = $exFILTER;

			$j = 0;
			$obTitle = new CSearchTitle;
			$obTitle->setMinWordLength($_REQUEST["l"]);
			
			$arResult["MAIN_QUERY"] = $arResult["alt_query"]? $arResult["alt_query"]: $arResult["query"];
			$arResult["DEBUG"]["REQUESTS"][] = $arResult["MAIN_QUERY"];

			$arResult["DEBUG"]["QUERY_COUNT"]++;
			if($obTitle->Search(
				$arResult["MAIN_QUERY"]
				,$arParams["TOP_COUNT"]
				,$exFILTER
				,false
				,$arParams["ORDER"]
			))
			{
				while($ar = $obTitle->Fetch())
				{
					$j++;
					if($j > $arParams["TOP_COUNT"])
					{
						/*
						$params = array("q" => $arResult["alt_query"]? $arResult["alt_query"]: $arResult["query"]);

						$url = CHTTP::urlAddParams(
								str_replace("#SITE_DIR#", SITE_DIR, $arParams["PAGE"])
								,$params
								,array("encode"=>true)
							).CSearchTitle::MakeFilterUrl("f", $exFILTER);
							
						$arResult["CATEGORIES"][$i]["ITEMS"][] = array(
							"NAME" => GetMessage("CC_BST_MORE"),
							"URL" => htmlspecialcharsex($url),
							"TYPE" => "all"
						);
						*/
						
						break;
					}
					else
					{
						$arResult["CATEGORIES"][$i]["ITEMS"][] = array(
							"NAME" => $ar["NAME"],
							"URL" => htmlspecialcharsbx($ar["URL"]),
							"MODULE_ID" => $ar["MODULE_ID"],
							"PARAM1" => $ar["PARAM1"],
							"PARAM2" => $ar["PARAM2"],
							"ITEM_ID" => $ar["ITEM_ID"],
						);
						
						$alreadyFinded[] = $ar["ITEM_ID"];
					}
				}
			}
			
			/// тапочки любимый - дубляж результатов
			if (!$obTitle->selectedRowsCount() || ($arResult["MODULE_SETTING"]["EXT_STANDART_SEARCH"] == 'Y' && $j < $arParams["TOP_COUNT"])){
				$arResult["DEBUG"]["TYPE"] = 'extended'; 
				
				$time_start = microtime(true); 
				
				$arSmartParams = array();
				$arSmartParams["query_words"] = $arResult["DEBUG"]["query_words"] = explode(' ', strtoupper($arResult['query']));
				$arSmartParams["SETTINGS"]["WORDS"] = CArturgolubevSmartsearch::prepareQueryWords($arSmartParams["query_words"]);
				
				if(!empty($arSmartParams["SETTINGS"]["WORDS"]))
				{
					$arLavelsWords = CArturgolubevSmartsearch::getSimilarWordsList($arSmartParams["SETTINGS"]["WORDS"]);
					
					$arResult["DEBUG"]["RESULT_WORDS"] = $arLavelsWords;
					//$arResult["DEBUG"]["TEST"] = $exFILTER;
					
					if(!empty($arLavelsWords))
					{
						foreach($arLavelsWords as $level=>$searchArray)
						{
							foreach($searchArray as $sWord)
							{
								if(strtolower($sWord) == strtolower($arResult["MAIN_QUERY"]))
									continue;
								
								$arResult["DEBUG"]["QUERY_COUNT"]++;
								
								$exFILTER["!ITEM_ID"] = $alreadyFinded;
								$arResult["DEBUG"]["REQUESTS"][] = $sWord;
								if ($obTitle->Search(
									  $sWord
									  , $arParams["TOP_COUNT"]
									  , $exFILTER
									  , false
									  , $arParams["ORDER"]
								   )) {
									while ($ar = $obTitle->Fetch()) {
										$j++;
										if ($j > $arParams["TOP_COUNT"]){
											/*
											$params = array("q" => $sWord);

											$url = CHTTP::urlAddParams(
												  str_replace("#SITE_DIR#", SITE_DIR, $arParams["PAGE"])
												  , $params
												  , array("encode" => true)
											   ) . CSearchTitle::MakeFilterUrl("f", $exFILTER);

											$arResult["CATEGORIES"][$i]["ITEMS"][] = array(
												"NAME" => GetMessage("CC_BST_MORE"),
												"URL" => htmlspecialcharsex($url),
											);
											*/
											
											break;
										}
										else
										{
											$arResult["CATEGORIES"][$i]["ITEMS"][] = array(
												"NAME" => $ar["NAME"],
												"URL" => htmlspecialcharsbx($ar["URL"]),
												"MODULE_ID" => $ar["MODULE_ID"],
												"PARAM1" => $ar["PARAM1"],
												"PARAM2" => $ar["PARAM2"],
												"ITEM_ID" => $ar["ITEM_ID"],
											);
											
											$alreadyFinded[] = $ar["ITEM_ID"];
										}
									}
								}
								
								if ($j > $arParams["TOP_COUNT"]) {
									break(2);
								}
							}
						}
					}
				}
				
				$arResult["DEBUG"]["TIME"] = round((microtime(true) - $time_start), 2);
			}
			
			if(!$j)
			{
				unset($arResult["CATEGORIES"][$i]);
			}
		}
		
		
		
		/* get dop information */
		if(count($alreadyFinded)>0)
		{
			$arResult["INFORMATION"] = CArturgolubevSmartsearch::getRealElementsName($alreadyFinded);
			if(!empty($arResult["INFORMATION"])){
				foreach($arResult["CATEGORIES"] as $category_id => $arCategory)
				{
					foreach($arCategory["ITEMS"] as $i => $arItem)
					{
						if(isset($arItem["ITEM_ID"]))
						{
							$newTitle = $arResult["INFORMATION"][$arItem["ITEM_ID"]]["NAME"];
							if($newTitle)
							{
								$arResult["CATEGORIES"][$category_id]["ITEMS"][$i]["NAME_S"] = $arResult["CATEGORIES"][$category_id]["ITEMS"][$i]["NAME"];
								
								$arResult["CATEGORIES"][$category_id]["ITEMS"][$i]["NAME"] = CArturgolubevSmartsearch::formatElementName($arResult["CATEGORIES"][$category_id]["ITEMS"][$i]["NAME"], $newTitle);
							}
						}
					}
				}
			}
		}
		/* end get dop information */
	}


	if($arParams["SHOW_OTHERS"] === "Y" && $isSearchInstalled)
	{
		$arResult["CATEGORIES"]["others"] = array(
			"TITLE" => htmlspecialcharsbx($arParams["CATEGORY_OTHERS_TITLE"]),
			"ITEMS" => array(),
		);

		$j = 0;
		$obTitle = new CSearchTitle;
		$obTitle->setMinWordLength($_REQUEST["l"]);
		$arResult["DEBUG"]["QUERY_COUNT"]++;
		if($obTitle->Search(
			$arResult["alt_query"]? $arResult["alt_query"]: $arResult["query"]
			,$arParams["TOP_COUNT"]
			,$arOthersFilter
			,true
			,$arParams["ORDER"]
		))
		{
			while($ar = $obTitle->Fetch())
			{
				$j++;
				if($j > $arParams["TOP_COUNT"])
				{
					//it's really hard to make it working
					break;
				}
				else
				{
					$arResult["CATEGORIES"]["others"]["ITEMS"][] = array(
						"NAME" => $ar["NAME"],
						"URL" => htmlspecialcharsbx($ar["URL"]),
						"MODULE_ID" => $ar["MODULE_ID"],
						"PARAM1" => $ar["PARAM1"],
						"PARAM2" => $ar["PARAM2"],
						"ITEM_ID" => $ar["ITEM_ID"],
					);
				}
			}
		}

		if(!$j)
		{
			unset($arResult["CATEGORIES"]["others"]);
		}
	}

	if(!empty($arResult["CATEGORIES"]) && $isSearchInstalled)
	{
		$arResult["CATEGORIES"]["all"] = array(
			"TITLE" => "",
			"ITEMS" => array()
		);

		$params = array(
			"q" => $arResult["alt_query"]? $arResult["alt_query"]: $arResult["query"],
		);
		$url = CHTTP::urlAddParams(
			str_replace("#SITE_DIR#", SITE_DIR, $arParams["PAGE"])
			,$params
			,array("encode"=>true)
		);
		$arResult["CATEGORIES"]["all"]["ITEMS"][] = array(
			"NAME" => GetMessage("CC_BST_ALL_RESULTS"),
			"URL" => $url,
		);
		/*
		if($arResult["alt_query"] != "")
		{
			$params = array(
				"q" => $arResult["query"],
				"spell" => 1,
			);

			$url = CHTTP::urlAddParams(
				str_replace("#SITE_DIR#", SITE_DIR, $arParams["PAGE"])
				,$params
				,array("encode"=>true)
			);

			$arResult["CATEGORIES"]["all"]["ITEMS"][] = array(
				"NAME" => GetMessage("CC_BST_ALL_QUERY_PROMPT", array("#query#"=>$arResult["query"])),
				"URL" => htmlspecialcharsex($url),
			);
		}
		*/
	}
}

$arResult["FORM_ACTION"] = htmlspecialcharsbx(str_replace("#SITE_DIR#", SITE_DIR, $arParams["PAGE"]));

if (
	$_REQUEST["ajax_call"] === "y"
	&& (
		!isset($_REQUEST["INPUT_ID"])
		|| $_REQUEST["INPUT_ID"] == $arParams["INPUT_ID"]
	)
)
{
	$APPLICATION->RestartBuffer();

	if(!empty($query))
		$this->IncludeComponentTemplate('ajax');
	CMain::FinalActions();
	die();
}
else
{
	$APPLICATION->AddHeadScript($this->GetPath().'/script.js');
	CUtil::InitJSCore(array('ajax'));
	$this->IncludeComponentTemplate();
}
?>
