<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"TITLE" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TITLE"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["title"]}',
		),
		"URL" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("URL"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["url"]}',
		),
		"PICTURE" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PICTURE"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["picture"]}',
		),
		"TEXT" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TEXT"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["text"]}',
		),
			"SERVICES" => Array(
					"PARENT" => "BASE",
					"NAME" => GetMessage("SERVICES"),
					"TYPE" => "LIST",
					"MULTIPLE" => "Y",
					"REFRESH" => "N",
					"VALUES" => array(
							"" => "-",
							"VK" => GetMessage("VK"),
							"FACEBOOK" => GetMessage("FACEBOOK"),
							"GOOGLE" => GetMessage("GOOGLE"),
							"OK" => GetMessage("OK"),
							"TWITTER" => GetMessage("TWITTER"),
							"MAIL" => GetMessage("MAIL"),

					)
					),

			"SHARE_LINK" => Array(
					"PARENT" => "BASE",
					"NAME" => GetMessage("SHARE_LINK"),
					"TYPE" => "LIST",
					"MULTIPLE" => "N",
					"REFRESH" => "N",
					"VALUES" => array(
							"Y" => GetMessage("Y"),
							"N" => GetMessage("N"),
					)
					),


			"LINK_TITLE" => Array(
					"PARENT" => "BASE",
					"NAME" => GetMessage("LINK_TITLE"),
					"TYPE" => "STRING",
					"DEFAULT" => "",
					),
			"FACEBOOK_APP_ID" => Array(
					"PARENT" => "BASE",
					"NAME" => GetMessage("FACEBOOK_APP_ID"),
					"TYPE" => "STRING",
					"DEFAULT" => "",
					),
	),
);
?>