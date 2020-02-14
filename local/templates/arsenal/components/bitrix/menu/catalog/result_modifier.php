<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult as $i => $item) {
    $code = str_replace("/catalog/", "", $item["LINK"]);
    $code = str_replace("/", "", $code);
    $arResult[$i]["novinki"] = '/novinki/'.$code.'/';
    $arResult[$i]["hits"] = '/hits/'.$code.'/';
    $arResult[$i]["skidki"] = '/skidki/'.$code.'/';
}