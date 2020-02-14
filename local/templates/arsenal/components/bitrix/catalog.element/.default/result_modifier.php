<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

foreach ($arResult["PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $photo_id) {
    $arResult["PROPERTIES"]["MORE_PHOTO"]["ARRAY"][] = CFile::GetFileArray($photo_id);
}

//Проверяем, есть ли данный товар в отложенных
$curProductId = $arResult['ID'];
$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "PRODUCT_ID" => $curProductId,
        "ORDER_ID" => "NULL",
        "DELAY" => "Y"
    ),
    false,
    false,
    array("PRODUCT_ID")
);
while ($arItems = $dbBasketItems->Fetch())
{
    $arResult["delay"] = $arItems['PRODUCT_ID'];
}

$arBasketItems = array();

$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID"   => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID"      => SITE_ID,
        "ORDER_ID" => "NULL"
    ),
    false,
    false,
    array("ID", "PRODUCT_ID")
);
while ($arItems = $dbBasketItems->Fetch()) {
    if (strlen($arItems["CALLBACK_FUNC"]) > 0) {
        CSaleBasket::UpdatePrice($arItems["ID"],
            $arItems["CALLBACK_FUNC"],
            $arItems["MODULE"],
            $arItems["PRODUCT_ID"],
            $arItems["QUANTITY"]);
        $arItems = CSaleBasket::GetByID($arItems["ID"]);
    }

    $arBasketItems[$arItems["PRODUCT_ID"]] = $arItems["ID"];
}

$arResult["arBasketItems"] = $arBasketItems;