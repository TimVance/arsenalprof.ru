<?
global $APPLICATION;
	global $USER;
	if(!is_object($USER)) $USER=new CUser;
?>
<?$APPLICATION->SetAdditionalCSS($templateFolder."/css/colorbox.css");?>
<?$APPLICATION->AddHeadScript($templateFolder.'/js/jquery.colorbox-min.js');?>