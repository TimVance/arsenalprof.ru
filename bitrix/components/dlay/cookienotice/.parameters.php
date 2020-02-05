<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentParameters = array(
    "GROUPS"     => array(),
    "PARAMETERS" => array(
        "NOTICE_TEXT"      => array(
            "PARENT"   => "BASE",
            "NAME"     => GetMessage("DLAY_COOKIENOTICE_PARAM_TEXT_NAME"),
            "TYPE"     => "TEXT",
            "MULTIPLE" => "N",
            "DEFAULT"  => GetMessage("DLAY_COOKIENOTICE_PARAM_TEXT_DEFAULT"),
        ),
        "NOTICE_BUTTON"    => array(
            "PARENT"   => "BASE",
            "NAME"     => GetMessage("DLAY_COOKIENOTICE_PARAM_BUTTON_NAME"),
            "TYPE"     => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT"  => GetMessage("DLAY_COOKIENOTICE_PARAM_BUTTON_DEFAULT"),
        ),
        "NOTICE_LINK"      => array(
            "PARENT"   => "BASE",
            "NAME"     => GetMessage("DLAY_COOKIENOTICE_PARAM_LINK_NAME"),
            "TYPE"     => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT"  => "/privacy/",
        ),
        "NOTICE_LINK_TEXT" => array(
            "PARENT"   => "BASE",
            "NAME"     => GetMessage("DLAY_COOKIENOTICE_PARAM_LINK_TEXT_NAME"),
            "TYPE"     => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT"  => GetMessage("DLAY_COOKIENOTICE_PARAM_LINK_TEXT_DEFAULT"),
        ),
    ),
);
?>