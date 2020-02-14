<?php
use Bitrix\Main\Page\Asset;
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)    die();
$asset = Asset::getInstance();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?$APPLICATION->ShowTitle();?></title>
    <? CJSCore::Init(array("jquery")); ?>
    <? $APPLICATION->ShowHead(); ?>
    <? if(CSite::InDir('/personal/'))  {
        $asset->addCss(SITE_TEMPLATE_PATH."/personal.css");
        Asset::getInstance()->addString('<script>var SITE_DIR = "'.SITE_DIR.'";</script>');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/jquery/jquery.1.11.1.min.js");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/plugins/bootstrap/css/bootstrap.min.css");?>
        <!--[if lt IE 9]>
        <script src="<?=SITE_TEMPLATE_PATH?>/site_files/plugins/bootstrap/js/respond.min.js"></script>
        <![endif]-->
        <?
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/plugins/owl-carousel/owl.carousel.min.css");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/owl-carousel/owl.carousel.js");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/plugins/jquery.mmenu/4.3.7/jquery.mmenu.all.css");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/jquery.mmenu/4.3.7/jquery.mmenu.min.all.js");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/plugins/nouislider/jquery.nouislider.min.css");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/nouislider/jquery.nouislider.min.js");
        ?>
        <script type="text/javascript">var Link = $.noUiSlider.Link;</script>
        <?
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/niceScroll/jquery.nicescroll.changed.min.js");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/plugins/ZoomIt/zoomIt.min.css");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/ZoomIt/zoomit.jquery.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/ikSelect/jquery.ikSelect.min.js");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/plugins/idangerous.swiper/idangerous.swiper.min.css");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/idangerous.swiper/idangerous.swiper.min.js");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/fonts/FontAwesome/css/font-awesome.min.css");
        //Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/css/style.css");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/css/style_quick_view.css");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/js/script.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/fancybox2/lib/jquery.mousewheel-3.0.6.pack.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/fancybox2/source/jquery.fancybox.pack.js?v=2.1.5");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/site_files/plugins/fancybox2/source/jquery.fancybox.min.css?v=2.1.5");

        if(COption::GetOptionString( "sotbit.b2bshop", "SHOW_BRICKS", "N" ) == 'Y')
        {
            Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/masonry/masonry.min.js");
        }

        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/site_files/plugins/mask/jquery.maskedinput.min.js");
        ?>
        <!--[if lt IE 9]>
        <script src="<?=SITE_TEMPLATE_PATH?>/site_files/plugins/pie/PIE.js"></script>
        <link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/site_files/plugins/pie/PIE.css"/>
        <link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/site_files/css/ie.css"/>
        <![endif]-->
        <!--[if lt IE 10]>
        <link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/site_files/css/ie9.css"/>
        <![endif]-->
    <? } ?>
</head>
<body>

    <?$APPLICATION->ShowPanel();?>

    <div class="fixed-header-wrap">
        <div class="container  flexBetween">
            <div class="logo-container">
                <a href="/" class="logo"><img src="/local/templates/arsenal/images/logo.png" alt="Logo Mobile"></a>
            </div>
            <div>
                <div class="header__phone-number">
                    <svg>
                        <use xlink:href="#phone"></use>
                    </svg>
                    <span>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "/include/templates/arsenal/header/phone.php",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => "/include/templates/arsenal/header/phone.php"
                            )
                        );?>
                    </span>
                </div>
            </div>
            <div class="controls">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.compare.list",
                    ".default",
                    Array(
                        "ACTION_VARIABLE" => "action",
                        "AJAX_MODE" => "Y",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "COMPARE_URL" => "/catalog/compare.php",
                        "COMPONENT_TEMPLATE" => ".default",
                        "DETAIL_URL" => "",
                        "IBLOCK_ID" => COption::GetOptionString("sotbit.b2bshop","IBLOCK_ID",""),
                        "IBLOCK_TYPE" => COption::GetOptionString("sotbit.b2bshop","IBLOCK_TYPE",""),
                        "NAME" => "CATALOG_COMPARE_LIST",
                        "POSITION" => "top left",
                        "POSITION_FIXED" => "N",
                        "PRODUCT_ID_VARIABLE" => "id"
                    )
                );?>
                <a href="/personal/cart/?delay=1" class="c-favorite wishcount_id">
                    <?
                    use Bitrix\Main\Loader;
                    Loader::includeModule("sale");
                    $delaydBasketItems = CSaleBasket::GetList(
                        array(),
                        array(
                            "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                            "LID" => SITE_ID,
                            "ORDER_ID" => "NULL",
                            "DELAY" => "Y"
                        ),
                        array()
                    );
                    echo $delaydBasketItems;
                    ?>
                </a>
                <div class="card-wrap">
                    <a href="#" class="header__basket">
                        <svg>
                            <use xlink:href="#basket"></use>
                        </svg>
                        <div class="header__basket-price">
                            <?php

                            $dbBasketItems = CSaleBasket::GetList(
                                array(
                                    "NAME" => "ASC",
                                    "ID" => "ASC"
                                ),
                                array(
                                    "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                                    "LID" => SITE_ID,
                                    "ORDER_ID" => "NULL"
                                ),
                                false,
                                false,
                                array("ID", "CALLBACK_FUNC", "MODULE",
                                    "PRODUCT_ID", "QUANTITY", "DELAY",
                                    "CAN_BUY", "PRICE", "WEIGHT")
                            );
                            $sum_count_goods=0;
                            while ($arItems = $dbBasketItems->Fetch())
                            {
                                $sum_count_goods += $arItems["QUANTITY"];
                            }
                            echo $sum_count_goods;
                            ?>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="main">

        <div class="content">

            <header class="header">
                <div class="header__top">
                    <div class="container">
                        <div class="header__top-row header_tr1">
                            <div class="ht-item ht-item_l">
                                <a id="hamburger" href="#menu">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </a>
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    Array(
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "inc",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/include/templates/arsenal/header/arsenal_prof.php"
                                    )
                                );?>
                                <a href="/" class="logo m-logo">
                                    <svg>
                                        <use xlink:href="#logo"></use>
                                    </svg>
                                </a>
                                <div class="private-links">
                                    <?php
                                    global $USER;
                                    if ($USER->IsAuthorized()) {
                                        echo '<a class="p-link" href="/b2bcabinet/"><span class="personal-link"></span><span class="lk_text">Личный кабинет</span></a>';
                                    }
                                    else {
                                        echo '
                                            <a href="/auth/index.php?register=yes" class="p-link"><span class="personal-link"></span><span class="lk_text">Регистрация</span></a>
                                            <span class="lk_text">/</span> <a href="/auth/" class="p-link lk_text">Войти</a>
                                        ';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="ht-item controls">
                                <?$APPLICATION->IncludeComponent(
                                        "bitrix:catalog.compare.list",
                                        ".default",
                                        Array(
                                            "ACTION_VARIABLE" => "action",
                                            "AJAX_MODE" => "Y",
                                            "AJAX_OPTION_ADDITIONAL" => "",
                                            "AJAX_OPTION_HISTORY" => "N",
                                            "AJAX_OPTION_JUMP" => "N",
                                            "AJAX_OPTION_STYLE" => "Y",
                                            "COMPARE_URL" => "/catalog/compare.php",
                                            "COMPONENT_TEMPLATE" => ".default",
                                            "DETAIL_URL" => "",
                                            "IBLOCK_ID" => COption::GetOptionString("sotbit.b2bshop","IBLOCK_ID",""),
                                            "IBLOCK_TYPE" => COption::GetOptionString("sotbit.b2bshop","IBLOCK_TYPE",""),
                                            "NAME" => "CATALOG_COMPARE_LIST",
                                            "POSITION" => "top left",
                                            "POSITION_FIXED" => "N",
                                            "PRODUCT_ID_VARIABLE" => "id"
                                        )
                                );?>
                                <a href="/personal/cart/?delay=1" class="c-favorite wishcount_id">
                                        <?
                                        //use Bitrix\Main\Loader;
                                        Loader::includeModule("sale");
                                        $delaydBasketItems = CSaleBasket::GetList(
                                            array(),
                                            array(
                                                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                                                "LID" => SITE_ID,
                                                "ORDER_ID" => "NULL",
                                                "DELAY" => "Y"
                                            ),
                                            array()
                                        );
                                        echo $delaydBasketItems;
                                        ?>
                                </a>
                                <div class="card-wrap">
                                    <a href="#" class="header__basket">
                                        <svg>
                                            <use xlink:href="#basket"></use>
                                        </svg>
                                        <div class="header__basket-price">
                                            <?php echo $sum_count_goods; ?>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="header__top-row header__tr2">
                            <div class="search-wrap">
                                <?$APPLICATION->IncludeComponent(
                                	"arturgolubev:search.title", 
                                	"search", 
                                	array(
                                		"CATEGORY_0" => array(
                                			0 => "iblock_b2bs_catalog",
                                		),
                                		"CATEGORY_0_TITLE" => "",
                                		"CATEGORY_0_iblock_b2bs_catalog" => array(
                                			0 => "4",
                                		),
                                		"CATEGORY_0_iblock_b2bs_content" => array(
                                			0 => "all",
                                		),
                                		"CHECK_DATES" => "N",
                                		"COMPOSITE_FRAME_MODE" => "A",
                                		"COMPOSITE_FRAME_TYPE" => "AUTO",
                                		"CONTAINER_ID" => "smart-title-search",
                                		"CONVERT_CURRENCY" => "N",
                                		"INPUT_ID" => "smart-title-search-input",
                                		"NUM_CATEGORIES" => "1",
                                		"ORDER" => "date",
                                		"PAGE" => "#SITE_DIR#search/index.php",
                                		"PREVIEW_HEIGHT" => "75",
                                		"PREVIEW_TRUNCATE_LEN" => "",
                                		"PREVIEW_WIDTH" => "75",
                                		"PRICE_CODE" => array(
                                		),
                                		"PRICE_VAT_INCLUDE" => "Y",
                                		"SHOW_INPUT" => "Y",
                                		"SHOW_OTHERS" => "N",
                                		"SHOW_PREVIEW" => "Y",
                                		"TOP_COUNT" => "6",
                                		"USE_LANGUAGE_GUESS" => "Y",
                                		"COMPONENT_TEMPLATE" => "search"
                                	),
                                	false
                                );?>
                                <a class="brands-link" href="/brands/">Бренды</a>
                            </div>
                            <div class="logo-wrap">
                                <div class="logo-g">
                                    <a href="/" class="logo"><img src="/local/templates/arsenal/images/logo.png" alt="Logo"></a>
                                    <span class="header-subtitle">
                                        Заряжаем инструментом
                                    </span>
                                </div>

                            </div>
                            <div class="header__wrap_phone">
                                <div class="header__phone-number">
                                    <svg>
                                        <use xlink:href="#phone"></use>
                                    </svg>
                                    <span>
                                        <?$APPLICATION->IncludeComponent(
                                            "bitrix:main.include",
                                            "",
                                            Array(
                                                "AREA_FILE_SHOW" => "file",
                                                "AREA_FILE_SUFFIX" => "/include/templates/arsenal/header/phone.php",
                                                "EDIT_TEMPLATE" => "",
                                                "PATH" => "/include/templates/arsenal/header/phone.php"
                                            )
                                        );?>
                                    </span>
                                </div>
                                <div class="header__order-wrap">
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "",
                                        Array(
                                            "AREA_FILE_SHOW" => "file",
                                            "AREA_FILE_SUFFIX" => "/include/templates/arsenal/header/email.php",
                                            "EDIT_TEMPLATE" => "",
                                            "PATH" => "/include/templates/arsenal/header/email.php"
                                        )
                                    );?>
                                    <span class="header__order-call">заказать звонок</span>
                                </div>
                            </div>
                        </div>
                        <div class="header__top-row header-tr3">
                            <ul class="menu-big menu-big_left">
                                <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"header_left", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"COMPONENT_TEMPLATE" => "header_left",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "36000",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "header_left",
		"USE_EXT" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
                            </ul>
                            <ul class="menu-big menu-big_right">
                                <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"header_right", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"COMPONENT_TEMPLATE" => "header_right",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "36000",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "header_right",
		"USE_EXT" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
                            </ul>
                        </div>
                    </div>

                </div>

            </header>

            <section class="menu__section">
                <div class="container">
                    <?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"catalog", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => "2",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "36000",
		"MENU_CACHE_TYPE" => "Y",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "left",
		"USE_EXT" => "Y",
		"COMPONENT_TEMPLATE" => "catalog",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
                </div>
            </section>

            <? if ($APPLICATION->GetCurPage(false) !== '/'): ?>
                <section class="page__section<? if(CSite::InDir('/personal/')) echo ' personal_section'; ?>">
                    <div class="container">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:breadcrumb",
                                "breadcrumb",
                                array(
                                    "START_FROM" => "0",
                                    "PATH" => "",
                                    "SITE_ID" => "s1",
                                    "COMPONENT_TEMPLATE" => "breadcrumb"
                                ),
                                false
                            );?>
                            <? if(!CSite::InDir('/personal/')) { ?>
                                <h1 class="page-title"><?$APPLICATION->ShowTitle(false);?></h1>
                            <? } ?>
            <? endif; ?>
