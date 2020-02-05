<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

$arParams['ID'] = intval($arParams['ID']);

if(isset( $_SERVER['HTTPS'] ))
	$arResult['HTTP'] = 'https://';
else
	$arResult['HTTP'] = 'http://';

if (substr($arParams['URL'], 0, 1) == '/') {
	if (defined('SITE_SERVER_NAME') && strlen(SITE_SERVER_NAME) > 0)
		$arParams['URL'] = $arResult['HTTP'] . SITE_SERVER_NAME . $arParams['URL'];
	else
		$arParams['URL'] = $arResult['HTTP'] . COption::GetOptionString('main', 'server_name', $GLOBALS['SERVER_NAME']) . $arParams['URL'];
}

if (substr($arParams['PICTURE'], 0, 1) == '/') {
	$arParams['PICTURE_REL'] = $arParams['PICTURE'];
	if (defined('SITE_SERVER_NAME') && strlen(SITE_SERVER_NAME) > 0)
		$arParams['PICTURE'] = $arResult['HTTP'] . SITE_SERVER_NAME . $arParams['PICTURE'];
	else
		$arParams['PICTURE'] = $arResult['HTTP'] . COption::GetOptionString('main', 'server_name', $GLOBALS['SERVER_NAME']) . $arParams['PICTURE'];
}

$arResult['URL'] = urlencode($arParams['URL']);
$arResult['URL_NOT_ENCODE'] = $arParams['URL'];
$arResult['TITLE'] = urlencode($arParams['TITLE']);
$arResult['TITLE_NOT_ENCODE'] = $arParams['TITLE'];
$arResult['PICTURE'] = urlencode($arParams['PICTURE']);
$arResult['PICTURE_NOT_ENCODE'] = $arParams['PICTURE'];
$arResult['TEXT_NOT_ENCODE'] = TruncateText(strip_tags($arParams['TEXT']), 250);
$arResult['TEXT'] = urlencode($arResult['TEXT_NOT_ENCODE']);
$arResult['HTML'] = urlencode((trim($arParams['PICTURE']) == '' ? '' : '<img vspace="5" hspace="5" align="left" alt="" src="' . $arParams['PICTURE'] . '" />') .
		'<a href="' . $arParams['URL'] . '" target="_blank">' . $arParams['TITLE'] . '</a><br/><br/>' .
		$arParams['TEXT']);


$this->IncludeComponentTemplate();

?>