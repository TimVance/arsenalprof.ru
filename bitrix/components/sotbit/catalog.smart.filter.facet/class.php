<?
use Bitrix\Main\Loader;
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/*DEMO CODE for component inheritance
 CBitrixComponent::includeComponentClass("bitrix::news.base");
 class CBitrixCatalogSmartFilter extends CBitrixNewsBase
 */
class CBitrixCatalogSmartFilter extends CBitrixComponent
{
	var $IBLOCK_ID = 0;
	var $SKU_IBLOCK_ID = 0;
	var $SKU_PROPERTY_ID = 0;
	var $SECTION_ID = 0;
	var $FILTER_NAME = "";
	var $SAFE_FILTER_NAME = '';
	protected $currencyCache = array();
	protected static $catalogIncluded = null;
	protected static $iblockIncluded = null;
	/** \Bitrix\Iblock\PropertyIndex\Facet **/
	protected $facet = null;

	public function onPrepareComponentParams($arParams)
	{
		$arParams["CACHE_TIME"] = isset($arParams["CACHE_TIME"]) ? $arParams["CACHE_TIME"]: 36000000;
		$arParams["IBLOCK_ID"] = (int)$arParams["IBLOCK_ID"];
		if(!is_array($arParams["SECTION_ID"]))
		{
			$arParams["SECTION_ID"] = (int)$arParams["SECTION_ID"];
		}
		if (!is_array($arParams["SECTION_ID"]) && $arParams["SECTION_ID"] <= 0 && Loader::includeModule('iblock'))
		{
			$arParams["SECTION_ID"] = CIBlockFindTools::GetSectionID(
					$arParams["SECTION_ID"],
					$arParams["SECTION_CODE"],
					array(
							"GLOBAL_ACTIVE" => "Y",
							"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					)
					);
		}

		$arParams["PRICE_CODE"] = is_array($arParams["PRICE_CODE"])? $arParams["PRICE_CODE"]: array();
		foreach ($arParams["PRICE_CODE"] as $k=>$v)
		{
			if ($v===null || $v==='' || $v===false)
				unset($arParams["PRICE_CODE"][$k]);
		}

		$arParams["SAVE_IN_SESSION"] = $arParams["SAVE_IN_SESSION"] == "Y";
		$arParams["CACHE_GROUPS"] = $arParams["CACHE_GROUPS"] !== "N";
		$arParams["INSTANT_RELOAD"] = $arParams["INSTANT_RELOAD"] === "Y";
		$arParams["SECTION_TITLE"] = trim($arParams["SECTION_TITLE"]);
		$arParams["SECTION_DESCRIPTION"] = trim($arParams["SECTION_DESCRIPTION"]);

		$arParams["FILTER_NAME"] = (isset($arParams["FILTER_NAME"]) ? (string)$arParams["FILTER_NAME"] : '');
		if(
				$arParams["FILTER_NAME"] == ''
				|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])
				)
		{
			$arParams["FILTER_NAME"] = "arrFilter";
		}
		return $arParams;
	}

	public function executeComponent()
	{
		$this->IBLOCK_ID = $this->arParams["IBLOCK_ID"];
		$this->SECTION_ID = $this->arParams["SECTION_ID"];
		$this->FILTER_NAME = $this->arParams["FILTER_NAME"];
		$this->SAFE_FILTER_NAME = htmlspecialcharsbx($this->FILTER_NAME);

		if (self::$iblockIncluded === null)
			self::$iblockIncluded = Loader::includeModule('iblock');
			if (!self::$iblockIncluded)
				return '';

				if (self::$catalogIncluded === null)
					self::$catalogIncluded = Loader::includeModule('catalog');
					if (self::$catalogIncluded)
					{
						$arCatalog = CCatalogSKU::GetInfoByProductIBlock($this->IBLOCK_ID);
						if (!empty($arCatalog))
						{
							$this->SKU_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
							$this->SKU_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
						}
					}

					$this->facet = new \Bitrix\Iblock\PropertyIndex\Facet($this->IBLOCK_ID);

					return parent::executeComponent();
	}

	public function getIBlockItems($IBLOCK_ID)
	{
		$items = array();
		if($this->arParams["SECTIONS"]=="Y")$items["SECTION_ID"] = array();
		foreach(CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $this->SECTION_ID) as $PID => $arLink)
		{
			if($arLink["SMART_FILTER"] !== "Y")
				continue;

				$rsProperty = CIBlockProperty::GetByID($PID);
				$arProperty = $rsProperty->Fetch();
				if($arProperty)
				{
					$items[$arProperty["ID"]] = array(
							"ID" => $arProperty["ID"],
							"IBLOCK_ID" => $arProperty["IBLOCK_ID"],
							"CODE" => $arProperty["CODE"],
							"CODE_SEF" => $arProperty["CODE"]?ToLower($arProperty["CODE"]):ToLower(CUtil::translit($arProperty["CODE"], "ru", array("replace_space"=>"_","replace_other"=>"_"))),
							"NAME" => $arProperty["NAME"],
							"PROPERTY_TYPE" => $arProperty["PROPERTY_TYPE"],
							"USER_TYPE" => $arProperty["USER_TYPE"],
							"USER_TYPE_SETTINGS" => $arProperty["USER_TYPE_SETTINGS"],
							"DISPLAY_TYPE" => $arLink["DISPLAY_TYPE"],
							"DISPLAY_EXPANDED" => $arLink["DISPLAY_EXPANDED"],
							"VALUES" => array(),
					);

					if($arProperty["PROPERTY_TYPE"] == "N")
					{
						$minID = $this->SAFE_FILTER_NAME.'_'.$arProperty['ID'].'_MIN';
						$maxID = $this->SAFE_FILTER_NAME.'_'.$arProperty['ID'].'_MAX';
						$items[$arProperty["ID"]]["VALUES"] = array(
								"MIN" => array(
										"CONTROL_ID" => $minID,
										"CONTROL_NAME" => $minID,
								),
								"MAX" => array(
										"CONTROL_ID" => $maxID,
										"CONTROL_NAME" => $maxID,
								),
						);
					}
				}
		}
		return $items;
	}

	function getItemSections()
	{

		if(isset($this->arResult["SECTIONS"]) && !empty($this->arResult["SECTIONS"]))
		{
			$this->arResult["ITEMS"]["SECTION_ID"] = array(
					"ID" => "SECTION_ID",
					"IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
					"CODE" => "SECTION_ID",
					"CODE_SEF" => "section",
					"NAME" => GetMessage("MS_FILTER_CATALOG"),
					"PROPERTY_TYPE" => "SECTION_ID",
					"VALUES" => array(),
			);
			$PROPERTY_ID = "SECTION_ID";
			$name_sef = "section";
			foreach($this->arResult["SECTIONS"] as $sectID=>$arSect)
			{
				$this->arResult["ITEMS"]["SECTION_ID"]["VALUES"][$sectID] = array(
						"CONTROL_ID" => htmlspecialcharsbx($this->FILTER_NAME."_".$PROPERTY_ID."_".abs(crc32($sectID))),
						"CONTROL_NAME" => htmlspecialcharsbx($this->FILTER_NAME."_".$PROPERTY_ID."_".abs(crc32($sectID))),
						"CONTROL_NAME_ALT" => htmlspecialcharsbx($this->FILTER_NAME."_".$PROPERTY_ID),
						"CONTROL_NAME_SEF" => $sectID,
						"HTML_VALUE_ALT" => abs(crc32($sectID)),
						"HTML_VALUE" => "Y",
						"VALUE" => $arSect["NAME"],
						"SORT" => "",
						"UPPER" => ToUpper($arSect["NAME"]),
						"DEPTH_LEVEL" => $arSect["DEPTH_LEVEL"],
						"IS_PARENT" => $arSect["IS_PARENT"]
				);
			}
			unset($this->arResult["SECTIONS"]);
		}
	}

	public function getSefParams($SefMode)
	{
		if(class_exists('B2BSSotbitParent'))
		{
			if(!B2BSSotbitParent::$filterPath) return false;

			$arFilterPath = explode("/", B2BSSotbitParent::$filterPath);
		}elseif(class_exists('B2BSSotbitParent'))
		{
			if(!B2BSSotbitParent::$filterPath) return false;

			$arFilterPath = explode("/", B2BSSotbitParent::$filterPath);
		}

		$arRes = array();
		$_CHECK = array();
		if(!empty($arFilterPath))
		{
			foreach($arFilterPath as $path0)
			{
				$path0 = trim($path0);
				if(empty($path0)) continue;


				$prop = preg_replace("/-(.)*/", "", $path0);
				$propSect = preg_replace("/^".$prop."-/", "", $path0);
				$arPath = explode("-or-", $propSect);
				foreach($arPath as $path1)
				{
					if(empty($path1)) continue 1;
					if(strpos($path1, "from-")!==false || strpos($path1, "to-")!==false)
					{
						$from = str_replace("from-", "", preg_replace("/-to-(.)*/", "", $path1));
						if($SefMode == 'Y')
						{
							$from = preg_replace("/[^0-9]/", '', $from);
						}
						$to = preg_replace("/(.)*to-/", "", $path1);
						$arRes[$prop]["from"] = (int)$from;
						$arRes[$prop]["to"] = (int)$to;
					}
					else $arRes[$prop][$path1] = ($SefMode == 'Y')?urlencode($path1):$path1;

				}
			}
		}
		$_CHECK['PARSE_CHECK'] = $arRes;
		if(!empty($arRes))
		{
			foreach($this->arResult["ITEMS"] as $arItem)
			{

				$prop = $arItem["CODE_SEF"];

				if($SefMode == 'Y')
				{
					if($_REQUEST['bxajaxid'])
					{

					}
					else
					{
						if($arItem["PRICE"])
						{
							$prop = 'price';
						}

					}

				}
				if(isset($arItem["PRICE"]) || isset($arItem["VALUES"]["MAX"]) || isset($arItem["VALUES"]["MIN"]))
				{
					if(isset($arItem["VALUES"]) && !empty($arItem["VALUES"]))
					{
						if(isset($arRes[$prop]["from"]) && isset($arRes[$prop]["to"]))
						{
							if($arRes[$prop]["from"])$_CHECK[$arItem["VALUES"]["MIN"]["CONTROL_NAME"]] = $arRes[$prop]["from"];
							if($arRes[$prop]["to"])$_CHECK[$arItem["VALUES"]["MAX"]["CONTROL_NAME"]] = $arRes[$prop]["to"];
						}
					}
				}
				elseif(isset($arItem["VALUES"]) && !empty($arItem["VALUES"]))
				{

					foreach($arItem["VALUES"] as $value)
					{
						if($SefMode == 'N')
						{
							$val = $value["CONTROL_NAME_SEF"];
						}
						else
						{
							if($_REQUEST['bxajaxid'])
							{
								$val = $value["CONTROL_NAME_SEF"];
							}
							else
							{
								$val = urlencode($value["URL_ID"]);
							}
						}
						if(isset($arRes[$prop][$val]) || ($SefMode == 'Y' && isset($arRes[$prop]['is-'.$val])))
						{
							$_CHECK[$value["CONTROL_NAME"]] = "Y";
						}
					}
				}

			}
		}

		return $_CHECK;
	}

	public function getPriceItems()
	{
		$items = array();
		if (!empty($this->arParams["PRICE_CODE"]))
		{
			if (self::$catalogIncluded === null)
				self::$catalogIncluded = Loader::includeModule('catalog');
				if (self::$catalogIncluded)
				{
					$rsPrice = CCatalogGroup::GetList(
							array('SORT' => 'ASC', 'ID' => 'ASC'),
							array('=NAME' => $this->arParams["PRICE_CODE"]),
							false,
							false,
							array('ID', 'NAME', 'NAME_LANG', 'CAN_ACCESS', 'CAN_BUY')
							);
					while($arPrice = $rsPrice->Fetch())
					{
						if($arPrice["CAN_ACCESS"] == "Y" || $arPrice["CAN_BUY"] == "Y")
						{
							$arPrice["NAME_LANG"] = (string)$arPrice["NAME_LANG"];
							if ($arPrice["NAME_LANG"] === '')
								$arPrice["NAME_LANG"] = $arPrice["NAME"];
								$minID = $this->SAFE_FILTER_NAME.'_P'.$arPrice['ID'].'_MIN';
								$maxID = $this->SAFE_FILTER_NAME.'_P'.$arPrice['ID'].'_MAX';
								$items[$arPrice["NAME"]] = array(
										"ID" => $arPrice["ID"],
										"CODE" => $arPrice["NAME"],
										"CODE_SEF" => "price".$arPrice["ID"],
										"NAME" => $arPrice["NAME_LANG"],
										"PRICE" => true,
										"VALUES" => array(
												"MIN" => array(
														"CONTROL_ID" => $minID,
														"CONTROL_NAME" => $minID,
												),
												"MAX" => array(
														"CONTROL_ID" => $maxID,
														"CONTROL_NAME" => $maxID,
												),
										),
								);
						}
					}
				}
		}
		return $items;
	}

	public function getResultItems()
	{
		$items = $this->getIBlockItems($this->IBLOCK_ID);
		$this->arResult["PROPERTY_COUNT"] = count($items);
		$this->arResult["PROPERTY_ID_LIST"] = array_keys($items);

		if($this->SKU_IBLOCK_ID)
		{
			$this->arResult["SKU_PROPERTY_ID_LIST"] = array($this->SKU_PROPERTY_ID);
			foreach($this->getIBlockItems($this->SKU_IBLOCK_ID) as $PID => $arItem)
			{
				$items[$PID] = $arItem;
				$this->arResult["SKU_PROPERTY_COUNT"]++;
				$this->arResult["SKU_PROPERTY_ID_LIST"][] = $PID;
			}
		}

		if (!empty($this->arParams["PRICE_CODE"]))
		{
			foreach($this->getPriceItems() as $PID => $arItem)
			{
				$arItem["ENCODED_ID"] = md5($arItem["ID"]);
				$items[$PID] = $arItem;
			}
		}

		return $items;
	}

	public function fillItemPrices(&$resultItem, $arElement)
	{
		if (isset($arElement["MIN_VALUE_NUM"]) && isset($arElement["MAX_VALUE_NUM"]))
		{
			$currency = $arElement["VALUE"];
			$existCurrency = strlen($currency) > 0;

			$resultItem["VALUES"]["MIN"]["VALUE"] = $arElement["MIN_VALUE_NUM"];
			if ($existCurrency)
				$resultItem["VALUES"]["MIN"]["CURRENCY"] = $this->facet->lookupDictionaryValue($currency);


				$resultItem["VALUES"]["MAX"]["VALUE"] = $arElement["MAX_VALUE_NUM"];
				if ($existCurrency)
					$resultItem["VALUES"]["MAX"]["CURRENCY"] = $this->facet->lookupDictionaryValue($currency);
		}
		else
		{
			$currency = $arElement["CATALOG_CURRENCY_".$resultItem["ID"]];
			$existCurrency = strlen($currency) > 0;
			$price = $arElement["CATALOG_PRICE_".$resultItem["ID"]];
			if(strlen($price))
			{
				$convertPrice = (float)$price;
				if(
						!isset($resultItem["VALUES"]["MIN"])
						|| !array_key_exists("VALUE", $resultItem["VALUES"]["MIN"])
						|| doubleval($resultItem["VALUES"]["MIN"]["VALUE"]) > $convertPrice
						)
				{
					$resultItem["VALUES"]["MIN"]["VALUE"] = floor($price);
					if ($existCurrency)
						$resultItem["VALUES"]["MIN"]["CURRENCY"] = $currency;
				}

				if(
						!isset($resultItem["VALUES"]["MAX"])
						|| !array_key_exists("VALUE", $resultItem["VALUES"]["MAX"])
						|| doubleval($resultItem["VALUES"]["MAX"]["VALUE"]) < $convertPrice
						)
				{
					$resultItem["VALUES"]["MAX"]["VALUE"] = ceil($price);
					if ($existCurrency)
						$resultItem["VALUES"]["MAX"]["CURRENCY"] = $currency;
				}
			}
		}

		if ($existCurrency)
		{
			$resultItem["CURRENCIES"][$currency] = (
					isset($this->currencyCache[$currency])
					? $this->currencyCache[$currency]
					: $this->getCurrencyFullName($currency)
					);
		}
	}

	public function fillItemValues(&$resultItem, $arProperty, $flag = null)
	{
		static $cache = array();

		if(is_array($arProperty))
		{
			if(isset($arProperty["PRICE"]))
			{
				return null;
			}
			$key = $arProperty["VALUE"];
			$PROPERTY_TYPE = $arProperty["PROPERTY_TYPE"];
			$PROPERTY_USER_TYPE = $arProperty["USER_TYPE"];
			$PROPERTY_ID = $arProperty["ID"];
		}
		else
		{
			$key = $arProperty;
			$PROPERTY_TYPE = $resultItem["PROPERTY_TYPE"];
			$PROPERTY_USER_TYPE = $resultItem["USER_TYPE"];
			$PROPERTY_ID = $resultItem["ID"];
			$arProperty = $resultItem;
		}

		if($PROPERTY_TYPE == "F")
		{
			return null;
		}
		elseif($PROPERTY_TYPE == "N")
		{
			$convertKey = (float)$key;
			if (strlen($key) <= 0)
			{
				return null;
			}
			if(!isset($resultItem["VALUES"]["MIN"]) || !array_key_exists("VALUE", $resultItem["VALUES"]["MIN"]) || doubleval($resultItem["VALUES"]["MIN"]["VALUE"]) > $convertKey)
				$resultItem["VALUES"]["MIN"]["VALUE"] = preg_replace("/\\.0+\$/", "", $key);

				if(!isset($resultItem["VALUES"]["MAX"]) || !array_key_exists("VALUE", $resultItem["VALUES"]["MAX"]) || doubleval($resultItem["VALUES"]["MAX"]["VALUE"]) < $convertKey)
					$resultItem["VALUES"]["MAX"]["VALUE"] = preg_replace("/\\.0+\$/", "", $key);

					return null;
		}
		elseif($PROPERTY_TYPE == "E" && $key <= 0)
		{
			return null;
		}
		elseif($PROPERTY_TYPE == "G" && $key <= 0)
		{
			return null;
		}
		elseif(strlen($key) <= 0)
		{
			return null;
		}

		$htmlKey = htmlspecialcharsbx($key);
		if (isset($resultItem["VALUES"][$htmlKey]))
		{
			return $htmlKey;
		}

		$arUserType = array();
		if($PROPERTY_USER_TYPE != "")
		{
			$arUserType = CIBlockProperty::GetUserType($PROPERTY_USER_TYPE);
			if(isset($arUserType["GetExtendedValue"]))
				$PROPERTY_TYPE = "Ux";
				elseif(isset($arUserType["GetPublicViewHTML"]))
				$PROPERTY_TYPE = "U";
		}

		$file_id = null;

		$url_id = null;

		switch($PROPERTY_TYPE)
		{
			case "L":
				$enum = CIBlockPropertyEnum::GetByID($key);
				if ($enum)
				{
					$value = $enum["VALUE"];
					$sort  = $enum["SORT"];
					$url_id = toLower($enum["XML_ID"]);
				}
				else
				{
					return null;
				}
				break;
			case "E":
				if(!isset($cache[$PROPERTY_TYPE][$key]))
				{
					$arLinkFilter = array (
							"ID" => $key,
							"ACTIVE" => "Y",
							"ACTIVE_DATE" => "Y",
							"CHECK_PERMISSIONS" => "Y",
					);
					$rsLink = CIBlockElement::GetList(array(), $arLinkFilter, false, false, array("ID","IBLOCK_ID","NAME","SORT"));

					$cache[$PROPERTY_TYPE][$key] = $rsLink->Fetch();

					if ($cache[$PROPERTY_TYPE][$key]["CODE"])
						$url_id = toLower($cache[$PROPERTY_TYPE][$key]["CODE"]);
						else
							$url_id = toLower($value);
				}

				$value = $cache[$PROPERTY_TYPE][$key]["NAME"];
				$sort = $cache[$PROPERTY_TYPE][$key]["SORT"];
				break;
			case "G":
				if(!isset($cache[$PROPERTY_TYPE][$key]))
				{
					$arLinkFilter = array (
							"ID" => $key,
							"GLOBAL_ACTIVE" => "Y",
							"CHECK_PERMISSIONS" => "Y",
					);
					$rsLink = CIBlockSection::GetList(array(), $arLinkFilter, false, array("ID","IBLOCK_ID","NAME","LEFT_MARGIN","DEPTH_LEVEL"));
					$cache[$PROPERTY_TYPE][$key] = $rsLink->Fetch();
					$cache[$PROPERTY_TYPE][$key]['DEPTH_NAME'] = str_repeat(".", $cache[$PROPERTY_TYPE][$key]["DEPTH_LEVEL"]).$cache[$PROPERTY_TYPE][$key]["NAME"];
				}

				$value = $cache[$PROPERTY_TYPE][$key]['DEPTH_NAME'];
				$sort = $cache[$PROPERTY_TYPE][$key]["LEFT_MARGIN"];

				if ($cache[$PROPERTY_TYPE][$key]["CODE"])
					$url_id = toLower($cache[$PROPERTY_TYPE][$key]["CODE"]);
					else
						$url_id = toLower($value);
						break;
			case "U":
				if(!isset($cache[$PROPERTY_ID]))
					$cache[$PROPERTY_ID] = array();

					if(!isset($cache[$PROPERTY_ID][$key]))
					{
						$cache[$PROPERTY_ID][$key] = call_user_func_array(
								$arUserType["GetPublicViewHTML"],
								array(
										$arProperty,
										array("VALUE" => $key),
										array("MODE" => "SIMPLE_TEXT"),
								)
								);
					}

					$value = $cache[$PROPERTY_ID][$key];
					$sort = 0;
					$url_id = toLower($value);
					break;
			case "Ux":
				if(!isset($cache[$PROPERTY_ID]))
					$cache[$PROPERTY_ID] = array();

					if(!isset($cache[$PROPERTY_ID][$key]))
					{
						$cache[$PROPERTY_ID][$key] = call_user_func_array(
								$arUserType["GetExtendedValue"],
								array(
										$arProperty,
										array("VALUE" => $key),
								)
								);
					}

					$value = $cache[$PROPERTY_ID][$key]['VALUE'];
					$file_id = $cache[$PROPERTY_ID][$key]['FILE_ID'];
					$sort = (isset($cache[$PROPERTY_ID][$key]['SORT']) ? $cache[$PROPERTY_ID][$key]['SORT'] : 0);
					$url_id = toLower($cache[$PROPERTY_ID][$key]['UF_XML_ID']);
					break;
			default:
				$value = $key;
				$sort = 0;
				$url_id = toLower($value);
				break;
		}

		$keyCrc = abs(crc32($htmlKey));
		$value = htmlspecialcharsex($value);
		$sort = (int)$sort;


		$name_sef = CUtil::translit($value, "ru", array("replace_space"=>"_","replace_other"=>"_"));

		$filterPropertyID = $this->SAFE_FILTER_NAME.'_'.$PROPERTY_ID;
		$filterPropertyIDKey = $filterPropertyID.'_'.$keyCrc;
		$resultItem["VALUES"][$htmlKey] = array(
				"CONTROL_ID" => $filterPropertyIDKey,
				"CONTROL_NAME" => $filterPropertyIDKey,
				"CONTROL_NAME_ALT" => $filterPropertyID,
				"CONTROL_NAME_SEF" => ToLower($name_sef),
				"HTML_VALUE_ALT" => $keyCrc,
				"HTML_VALUE" => "Y",
				"VALUE" => $value,
				"SORT" => $sort,
				"UPPER" => ToUpper($value),
				"FLAG" => $flag,
		);

		if ($file_id)
		{
			$resultItem["VALUES"][$htmlKey]['FILE'] = CFile::GetFileArray($file_id);
		}

		if (strlen($url_id))
		{
			$resultItem["VALUES"][$htmlKey]['URL_ID'] = urlencode($url_id);
		}

		return $htmlKey;
	}

	function combineCombinations(&$arCombinations)
	{
		$result = array();
		foreach($arCombinations as $arCombination)
		{
			foreach($arCombination as $PID => $value)
			{
				if(!isset($result[$PID]))
					$result[$PID] = array();
					if(strlen($value))
						$result[$PID][] = $value;
			}
		}
		return $result;
	}

	function filterCombinations(&$arCombinations, $arItems, $currentPID)
	{
		foreach($arCombinations as $key => $arCombination)
		{
			if(!$this->combinationMatch($arCombination, $arItems, $currentPID))
				unset($arCombinations[$key]);
		}
	}

	function combinationMatch($combination, $arItems, $currentPID)
	{
		foreach($arItems as $PID => $arItem)
		{
			if ($PID != $currentPID)
			{
				if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"]))
				{
					//TODO
				}
				else
				{
					if(!$this->matchProperty($combination[$PID], $arItem["VALUES"]))
						return false;
				}
			}
		}
		return true;
	}

	function matchProperty($value, $arValues)
	{
		$match = true;
		foreach($arValues as $formControl)
		{
			if($formControl["CHECKED"])
			{
				if($formControl["VALUE"] == $value)
					return true;
					else
						$match = false;
			}
		}
		return $match;
	}

	public function _sort($v1, $v2)
	{
		if ($v1["SORT"] > $v2["SORT"])
			return 1;
			elseif ($v1["SORT"] < $v2["SORT"])
			return -1;
			elseif ($v1["UPPER"] > $v2["UPPER"])
			return 1;
			elseif ($v1["UPPER"] < $v2["UPPER"])
			return -1;
			else
				return 0;
	}

	/*
	 This function takes an array (arTuple) which is mix of scalar values and arrays
	 and return "rectangular" array of arrays.
	 For example:
	 array(1, array(1, 2), 3, arrays(4, 5))
	 will be transformed as
	 array(
	 array(1, 1, 3, 4),
	 array(1, 1, 3, 5),
	 array(1, 2, 3, 4),
	 array(1, 2, 3, 5),
	 )
	 */
	function ArrayMultiply(&$arResult, $arTuple, $arTemp = array())
	{
		if($arTuple)
		{
			reset($arTuple);
			list($key, $head) = each($arTuple);
			unset($arTuple[$key]);
			$arTemp[$key] = false;
			if(is_array($head))
			{
				if(empty($head))
				{
					if(empty($arTuple))
						$arResult[] = $arTemp;
						else
							$this->ArrayMultiply($arResult, $arTuple, $arTemp);
				}
				else
				{
					foreach($head as $value)
					{
						$arTemp[$key] = $value;
						if(empty($arTuple))
							$arResult[] = $arTemp;
							else
								$this->ArrayMultiply($arResult, $arTuple, $arTemp);
					}
				}
			}
			else
			{
				$arTemp[$key] = $head;
				if(empty($arTuple))
					$arResult[] = $arTemp;
					else
						$this->ArrayMultiply($arResult, $arTuple, $arTemp);
			}
		}
		else
		{
			$arResult[] = $arTemp;
		}
	}

	function makeFilter($FILTER_NAME)
	{
		$bOffersIBlockExist = false;
		if (self::$catalogIncluded === null)
			self::$catalogIncluded = Loader::includeModule('catalog');
			if (self::$catalogIncluded)
			{
				$arCatalog = CCatalogSKU::GetInfoByProductIBlock($this->IBLOCK_ID);
				if (!empty($arCatalog))
				{
					$bOffersIBlockExist = true;
				}
			}

			$gFilter = $GLOBALS[$FILTER_NAME];

			$arFilter = array(
					"IBLOCK_ID" => $this->IBLOCK_ID,
					"IBLOCK_LID" => SITE_ID,
					"IBLOCK_ACTIVE" => "Y",
					"ACTIVE_DATE" => "Y",
					"ACTIVE" => "Y",
					"CHECK_PERMISSIONS" => "Y",
					"MIN_PERMISSION" => "R",
					"INCLUDE_SUBSECTIONS" => ($this->arParams["INCLUDE_SUBSECTIONS"] != 'N' ? 'Y' : 'N'),
			);
			if (($this->SECTION_ID > 0) || ($this->arParams["SHOW_ALL_WO_SECTION"] !== "Y"))
			{
				$arFilter["SECTION_ID"] = $this->SECTION_ID;
			}

			if ($this->arParams['HIDE_NOT_AVAILABLE'] == 'Y')
				$arFilter['CATALOG_AVAILABLE'] = 'Y';

				if(self::$catalogIncluded && $bOffersIBlockExist)
				{
					$arPriceFilter = array();
					foreach($gFilter as $key => $value)
					{
						if(preg_match('/^(>=|<=|><)CATALOG_PRICE_/', $key))
						{
							$arPriceFilter[$key] = $value;
							unset($gFilter[$key]);
						}
					}

					if(!empty($gFilter["OFFERS"]))
					{
						if (empty($arPriceFilter))
							$arSubFilter = $gFilter["OFFERS"];
							else
								$arSubFilter = array_merge($gFilter["OFFERS"], $arPriceFilter);

								$arSubFilter["IBLOCK_ID"] = $this->SKU_IBLOCK_ID;
								$arSubFilter["ACTIVE_DATE"] = "Y";
								$arSubFilter["ACTIVE"] = "Y";
								if ('Y' == $this->arParams['HIDE_NOT_AVAILABLE'])
									$arSubFilter['CATALOG_AVAILABLE'] = 'Y';
									$arFilter["=ID"] = CIBlockElement::SubQuery("PROPERTY_".$this->SKU_PROPERTY_ID, $arSubFilter);
					}
					elseif(!empty($arPriceFilter))
					{
						$arSubFilter = $arPriceFilter;

						$arSubFilter["IBLOCK_ID"] = $this->SKU_IBLOCK_ID;
						$arSubFilter["ACTIVE_DATE"] = "Y";
						$arSubFilter["ACTIVE"] = "Y";
						$arFilter[] = array(
								"LOGIC" => "OR",
								array($arPriceFilter),
								"=ID" => CIBlockElement::SubQuery("PROPERTY_".$this->SKU_PROPERTY_ID, $arSubFilter),
						);
					}

					unset($gFilter["OFFERS"]);
				}

				return array_merge($gFilter, $arFilter);
	}

	public function getCurrencyFullName($currencyId)
	{
		if (!isset($this->currencyCache[$currencyId]))
		{
			$currencyInfo = CCurrencyLang::GetById($currencyId, LANGUAGE_ID);
			if ($currencyInfo["FULL_NAME"] != "")
				$this->currencyCache[$currencyId] = $currencyInfo["FULL_NAME"];
				else
					$this->currencyCache[$currencyId] = $currencyId;
		}
		return $this->currencyCache[$currencyId];
	}

	public function makeSmartUrl($url, $apply, $checkedControlId = false)
	{
		$smartParts = array();
		if ($apply)
		{
			foreach($this->arResult["ITEMS"] as $PID => $arItem)
			{
				$smartPart = array();
				//Prices
				if ($arItem["PRICE"])
				{
					if (strlen($arItem["VALUES"]["MIN"]["HTML_VALUE"]) > 0)
						$smartPart["from"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
						if (strlen($arItem["VALUES"]["MAX"]["HTML_VALUE"]) > 0)
							$smartPart["to"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
				}

				if ($smartPart)
				{
					array_unshift($smartPart, toLower("price-".$arItem["CODE"]));

					$smartParts[] = $smartPart;
				}
			}

			foreach($this->arResult["ITEMS"] as $PID => $arItem)
			{
				$smartPart = array();
				if ($arItem["PRICE"])
					continue;

					//Numbers && calendar == ranges
					if (
							$arItem["PROPERTY_TYPE"] == "N"
							|| $arItem["DISPLAY_TYPE"] == "U"
							)
					{
						if (strlen($arItem["VALUES"]["MIN"]["HTML_VALUE"]) > 0)
							$smartPart["from"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
							if (strlen($arItem["VALUES"]["MAX"]["HTML_VALUE"]) > 0)
								$smartPart["to"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
					}
					else
					{
						foreach($arItem["VALUES"] as $key => $ar)
						{
							if (
									(
											$ar["CHECKED"]
											|| $ar["CONTROL_ID"] === $checkedControlId
											)
									&& strlen($ar["URL_ID"])
									)
							{
								$smartPart[] = $ar["URL_ID"];
							}
						}
					}

					if ($smartPart)
					{
						if ($arItem["CODE"])
							array_unshift($smartPart, toLower($arItem["CODE"]));
							else
								array_unshift($smartPart, $arItem["ID"]);

								$smartParts[] = $smartPart;
					}
			}
		}

		if (!$smartParts)
			$smartParts[] = array("clear");
			return str_replace("#SMART_FILTER_PATH#", implode("/", $this->encodeSmartParts($smartParts)), $url);
	}
	public function encodeSmartParts($smartParts)
	{
		foreach ($smartParts as &$smartPart)
		{
			$urlPart = "";
			foreach ($smartPart as $i => $smartElement)
			{
				if (!$urlPart)
					$urlPart .= urlencode($smartElement);
					elseif ($i == 'from' || $i == 'to')
					$urlPart .= urlencode('-'.$i.'-'.$smartElement);
					elseif ($i == 1)
					$urlPart .= urlencode('-is-'.$smartElement);
					else
						$urlPart .= urlencode('-or-'.$smartElement);
			}
			$smartPart = $urlPart;
		}
		unset($smartPart);
		return $smartParts;
	}

}