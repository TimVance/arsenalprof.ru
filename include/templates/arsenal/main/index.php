
    <section class="promo__slider">
        <div class="container">
            <?$APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "main_slider",
                array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "DISPLAY_TOP_PAGER" => "N",
                    "FIELD_CODE" => array(
                        0 => "",
                        1 => "",
                    ),
                    "FILTER_NAME" => "",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "IBLOCK_ID" => 6,
                    "IBLOCK_TYPE" => "b2bs_content",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "MESSAGE_404" => "",
                    "NEWS_COUNT" => "20",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "PAGER_TITLE" => "Новости",
                    "PARENT_SECTION" => "33",
                    "PARENT_SECTION_CODE" => "",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "PROPERTY_CODE" => array("","LINK",""),
                    "SET_BROWSER_TITLE" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_404" => "N",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER1" => "DESC",
                    "SORT_ORDER2" => "ASC",
                    "STRICT_SECTION_CHECK" => "N",
                    "COMPONENT_TEMPLATE" => "official",
                    "TEMPLATE_THEME" => "blue",
                    "MEDIA_PROPERTY" => "",
                    "SLIDER_PROPERTY" => "",
                    "SEARCH_PAGE" => "/search/",
                    "USE_RATING" => "N",
                    "USE_SHARE" => "N"
                ),
                false
            );?>
        </div>
    </section>


    <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"brands",
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "b2bs_content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "499",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "brands"
	),
	false
);?>


    <section class="products__section">
        <div class="container">
            <div class="section-top">
                <h2 class="title">Хиты продаж</h2>
                <?
                $hits = CIBlockElement::GetList(
                    array(),
                    array('IBLOCK_ID' => 4, 'PROPERTY_KHIT' => 1),
                    array(),
                    false,
                    array('ID', 'NAME')
                );
                ?>
                <a href="/hits/" class="watch-more watch-more__product">Все хиты (<?=$hits?>)</a>
            </div>

            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.top",
                "hits",
                array(
                    "ACTION_VARIABLE" => "action",
                    "ADD_PICT_PROP" => "MORE_PHOTO",
                    "ADD_PROPERTIES_TO_BASKET" => "Y",
                    "ADD_TO_BASKET_ACTION" => "ADD",
                    "BASKET_URL" => COption::GetOptionString("sotbit.b2bshop","URL_CART",""),
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
                    "COMPATIBLE_MODE" => "Y",
                    "CONVERT_CURRENCY" => "N",
                    "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:4:370\",\"DATA\":{\"logic\":\"Equal\",\"value\":1}}]}",
                    "DETAIL_URL" => "",
                    "DISPLAY_COMPARE" => "Y",
                    "ELEMENT_COUNT" => "6",
                    "ELEMENT_SORT_FIELD" => "sort",
                    "ELEMENT_SORT_FIELD2" => "id",
                    "ELEMENT_SORT_ORDER" => "asc",
                    "ELEMENT_SORT_ORDER2" => "desc",
                    "ENLARGE_PRODUCT" => "STRICT",
                    "FILTER_NAME" => "",
                    "HIDE_NOT_AVAILABLE" => "N",
                    "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                    "IBLOCK_ID" => "4",
                    "IBLOCK_TYPE" => "b2bs_catalog",
                    "LABEL_PROP" => array(
                    ),
                    "LINE_ELEMENT_COUNT" => "3",
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                    "MESS_BTN_BUY" => "Купить",
                    "MESS_BTN_COMPARE" => "Сравнить",
                    "MESS_BTN_DETAIL" => "Подробнее",
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",
                    "OFFERS_FIELD_CODE" => array(
                        0 => "",
                        1 => "",
                    ),
                    "OFFERS_LIMIT" => "5",
                    "OFFERS_SORT_FIELD" => "sort",
                    "OFFERS_SORT_FIELD2" => "id",
                    "OFFERS_SORT_ORDER" => "asc",
                    "OFFERS_SORT_ORDER2" => "desc",
                    "PARTIAL_PRODUCT_PROPERTIES" => "Y",
                    "PRICE_CODE" => array(
                        0 => "Розничная цена",
                    ),
                    "PRICE_VAT_INCLUDE" => "Y",
                    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
                    "PRODUCT_DISPLAY_MODE" => "N",
                    "PRODUCT_ID_VARIABLE" => "id",
                    "PRODUCT_PROPS_VARIABLE" => "prop",
                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'6','BIG_DATA':false}]",
                    "PRODUCT_SUBSCRIPTION" => "Y",
                    "ROTATE_TIMER" => "30",
                    "SECTION_URL" => "",
                    "SEF_MODE" => "N",
                    "SHOW_CLOSE_POPUP" => "Y",
                    "SHOW_DISCOUNT_PERCENT" => "Y",
                    "SHOW_MAX_QUANTITY" => "N",
                    "SHOW_OLD_PRICE" => "Y",
                    "SHOW_PAGINATION" => "Y",
                    "SHOW_PRICE_COUNT" => "1",
                    "SHOW_SLIDER" => "N",
                    "SLIDER_INTERVAL" => "3000",
                    "SLIDER_PROGRESS" => "N",
                    "TEMPLATE_THEME" => "blue",
                    "USE_ENHANCED_ECOMMERCE" => "N",
                    "USE_PRICE_COUNT" => "N",
                    "USE_PRODUCT_QUANTITY" => "N",
                    "VIEW_MODE" => "SECTION",
                    "COMPONENT_TEMPLATE" => "hits",
                    "PROPERTY_CODE_MOBILE" => array(
                        0 => "KHIT",
                    ),
                    "DISCOUNT_PERCENT_POSITION" => "bottom-right"
                ),
                false
            );?>

            <div class="section-top">
                <h2 class="title">Новинки</h2>
                <?
                    $novinki = CIBlockElement::GetList(
                        array(),
                        array('IBLOCK_ID' => 4, 'PROPERTY_NOVINKA' => 1),
                        array(),
                        false,
                        array('ID', 'NAME')
                    );
                ?>
                <a href="/novinki/" class="watch-more watch-more__product">Все новинки (<?=$novinki?>)</a>
            </div>


            <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top",
	"hits",
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => COption::GetOptionString("sotbit.b2bshop","URL_CART",""),
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPATIBLE_MODE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:4:371\",\"DATA\":{\"logic\":\"Equal\",\"value\":1}}]}",
		"DETAIL_URL" => "",
		"DISPLAY_COMPARE" => "Y",
		"ELEMENT_COUNT" => "6",
		"ELEMENT_SORT_FIELD" => "id",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "4",
		"IBLOCK_TYPE" => "b2bs_catalog",
		"LABEL_PROP" => array(
		),
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_LIMIT" => "5",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_ORDER2" => "desc",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"PRICE_CODE" => array(
			0 => "Розничная цена",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
		"PRODUCT_DISPLAY_MODE" => "N",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'6','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"ROTATE_TIMER" => "30",
		"SECTION_URL" => "",
		"SEF_MODE" => "N",
		"SHOW_CLOSE_POPUP" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PAGINATION" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "N",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "blue",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"VIEW_MODE" => "SECTION",
		"COMPONENT_TEMPLATE" => "hits",
		"PROPERTY_CODE_MOBILE" => array(
			0 => "KHIT",
		),
		"DISCOUNT_PERCENT_POSITION" => "bottom-right"
	),
	false
);?>

        </div>
    </section>

    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include/templates/arsenal/main/news.php",
            "COMPONENT_TEMPLATE" => ".default"
        ),
        false
    );?>

    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        ".default",
        array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include/templates/arsenal/main/articles.php",
            "COMPONENT_TEMPLATE" => ".default"
        ),
        false
    );?>

    <section class="advantages">
        <div class="container">

            <h2 class="title">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/include/templates/arsenal/main/about_title.php"
                    )
                );?>
            </h2>
            <span class="advantages-subtitle">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/include/templates/arsenal/main/about_text.php"
                    )
                );?>
            </span>

            <?$APPLICATION->IncludeComponent("bitrix:news.list", "preimushestva", Array(
                "ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
                    "ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
                    "AJAX_MODE" => "N",	// Включить режим AJAX
                    "AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
                    "AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
                    "AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
                    "AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
                    "CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
                    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
                    "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
                    "CACHE_TYPE" => "A",	// Тип кеширования
                    "CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
                    "COMPONENT_TEMPLATE" => ".default",
                    "DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
                    "DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
                    "DISPLAY_DATE" => "N",	// Выводить дату элемента
                    "DISPLAY_NAME" => "Y",	// Выводить название элемента
                    "DISPLAY_PICTURE" => "Y",	// Выводить изображение для анонса
                    "DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
                    "DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
                    "FIELD_CODE" => array(	// Поля
                        0 => "",
                        1 => "",
                    ),
                    "FILTER_NAME" => "",	// Фильтр
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
                    "IBLOCK_ID" => "6",	// Код информационного блока
                    "IBLOCK_TYPE" => "b2bs_content",	// Тип информационного блока (используется только для проверки)
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
                    "INCLUDE_SUBSECTIONS" => "N",	// Показывать элементы подразделов раздела
                    "MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
                    "NEWS_COUNT" => "20",	// Количество новостей на странице
                    "PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
                    "PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
                    "PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
                    "PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
                    "PAGER_TEMPLATE" => ".default",	// Шаблон постраничной навигации
                    "PAGER_TITLE" => "Новости",	// Название категорий
                    "PARENT_SECTION" => "501",	// ID раздела
                    "PARENT_SECTION_CODE" => "",	// Код раздела
                    "PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
                    "PROPERTY_CODE" => array(	// Свойства
                        0 => "",
                        1 => "",
                    ),
                    "SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
                    "SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
                    "SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
                    "SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
                    "SET_STATUS_404" => "N",	// Устанавливать статус 404
                    "SET_TITLE" => "N",	// Устанавливать заголовок страницы
                    "SHOW_404" => "N",	// Показ специальной страницы
                    "SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
                    "SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
                    "SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
                    "SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
                    "STRICT_SECTION_CHECK" => "N",	// Строгая проверка раздела для показа списка
                ),
                false
            );?>

        </div>
    </section>