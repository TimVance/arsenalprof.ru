<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!CModule::IncludeModule("iblock"))
	return;
$IdModule='sotbit.reviews';
$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"SHOW_REVIEWS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_SHOW_REVIEWS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"SHOW_COMMENTS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_SHOW_COMMENTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"SHOW_QUESTIONS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_SHOW_QUESTIONS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"FIRST_ACTIVE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_FIRST_ACTIVE"),
			"TYPE" => "LIST",
			"VALUES" => array('1'=>GetMessage($IdModule."_REVIEWS"),'2'=>GetMessage($IdModule."_COMMENTS"),'3'=>GetMessage($IdModule."_QUESTIONS")),
		),
		"MAX_RATING" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_MAX_RATING"),
			"TYPE" => "STRING",
			"DEFAULT" => 5,
		),
		"DEFAULT_RATING_ACTIVE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_DEFAULT_RATING_ACTIVE"),
			"TYPE" => "STRING",
			"DEFAULT" => 3,
		),
		"PRIMARY_COLOR" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_PRIMARY_COLOR"),
			"TYPE" => "STRING",
			"DEFAULT" => "#a76e6e",
		),
		"BUTTON_BACKGROUND" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_BUTTON_BACKGROUND"),
			"TYPE" => "STRING",
			"DEFAULT" => "#dbbfb9",
		),
		"ADD_REVIEW_PLACE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_ADD_REVIEW_PLACE"),
			"TYPE" => "LIST",
			"VALUES"=>array('1'=>GetMessage($IdModule."_IN_STATISTICS"),'2'=>GetMessage($IdModule."_IN_REVIEWS"))
		),
		"REVIEWS_TEXTBOX_MAXLENGTH" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_REVIEWS_TEXTBOX_MAXLENGTH"),
			"TYPE" => "STRING",
			"DEFAULT" => 200,
		),
		"COMMENTS_TEXTBOX_MAXLENGTH" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_COMMENTS_TEXTBOX_MAXLENGTH"),
			"TYPE" => "STRING",
			"DEFAULT" => 200,
		),
		"QUESTIONS_TEXTBOX_MAXLENGTH" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_QUESTIONS_TEXTBOX_MAXLENGTH"),
			"TYPE" => "STRING",
			"DEFAULT" => 200,
		),
		"NOTICE_EMAIL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_NOTICE_EMAIL"),
			"TYPE" => "STRING",
		),
		"INIT_JQUERY" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_INIT_JQUERY"),
			"TYPE" => "CHECKBOX",
		),
		"DATE_FORMAT" => CIBlockParameters::GetDateFormat(GetMessage($IdModule."_DATE_FORMAT"), "BASE"),
		"ID_ELEMENT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage($IdModule."_ID_ELEMENT"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$ElementID}',
		),
		"CACHE_TIME" => array(
			"DEFAULT" => 36000000,
		), 
	),
);
?>