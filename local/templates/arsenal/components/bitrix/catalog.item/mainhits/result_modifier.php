<?php

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