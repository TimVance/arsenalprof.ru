<?
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;
if ($REQUEST_METHOD == "POST") {

    $arParams = unserialize($data);

    if (isset($FilterRating) && ! empty($FilterRating))
         $_SESSION['sotbit_reviews_userreviews_rating_' . $arParams['ID_USER']] = $FilterRating;
    if (isset($FilterImages) && ! empty($FilterImages))
         $_SESSION['sotbit_reviews_userreviews_images_' . $arParams['ID_USER']] = $FilterImages;
    if (isset($FilterSortOrder) && ! empty($FilterSortOrder))
         $_SESSION['sotbit_reviews_userreviews_order_' . $arParams['ID_USER']] = $FilterSortOrder;
    if (isset($FilterSortBy) && ! empty($FilterSortBy))
         $_SESSION['sotbit_reviews_userreviews_by_' . $arParams['ID_USER']] = $FilterSortBy;


    $_SESSION['sotbit_reviews_userreviews_page_' . $arParams['ID_USER']] = $FilterPage;
    $_SESSION['sotbit_reviews_user_' . $arParams['ID_USER']] = $Url;

    $APPLICATION->IncludeComponent("sotbit:reviews.userreviews.list", $arParams['TEMPLATE'], array(
        'MAX_RATING' => $arParams['MAX_RATING'],
        'ID_USER' => $arParams['ID_USER'],
        "PRIMARY_COLOR" => $arParams['PRIMARY_COLOR'],
        'CACHE_TIME' => $arParams["CACHE_TIME"],
        'CACHE_GROUPS' => $arParams["CACHE_GROUPS"],
        "DATE_FORMAT" => $arParams['DATE_FORMAT']
    ), $component);
}
?>