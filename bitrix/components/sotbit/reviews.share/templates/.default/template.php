<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
	$this->setFrameMode(true);

	IncludeTemplateLangFile(__FILE__);
	?>

<?if((isset($arParams['SERVICES']) && is_array($arParams['SERVICES']) && count($arParams['SERVICES'])>0) || (isset($arParams['SHARE_LINK']) && !empty($arParams['SHARE_LINK']))): ?>
<div id="sotbit_reviews_share">
<!--noindex-->
<?if(isset($arParams['SERVICES']) && is_array($arParams['SERVICES']) && count($arParams['SERVICES'])>0 && in_array('VK',$arParams['SERVICES'])): ?>
<a class="vk_share share" href="#" title="<?=GetMessage("VK");?>"
	onclick="window.open('http://vkontakte.ru/share.php?url=<?=$arResult["URL"]?>&amp;title=<?= $arResult["TITLE"]?>&amp;image=<?= $arResult["PICTURE"]?>&amp;description=<?= $arResult["TEXT"]?>', '', 'scrollbars=yes,resizable=no,width=560,height=350,top='+Math.floor((screen.height - 350)/2-14)+',left='+Math.floor((screen.width - 560)/2-5)); return false;">
	<i class="fa fa-vk" aria-hidden="true"></i>
	</a>
<?endif; ?>
<?if(isset($arParams['SERVICES']) && is_array($arParams['SERVICES']) && count($arParams['SERVICES'])>0 && in_array('FACEBOOK',$arParams['SERVICES'])): ?>

	<?if(isset($arParams['FACEBOOK_APP_ID']) && !empty($arParams['FACEBOOK_APP_ID'])): ?>
	<a class="fb_share share" title="<?=GetMessage("FACEBOOK");?>"
		onClick="window.open('https://www.facebook.com/dialog/feed?app_id=<?=$arParams['FACEBOOK_APP_ID'] ?>&picture=<?= $arResult["PICTURE"]?>&redirect_uri=<?=$arResult["URL"]?>&link=<?=$arResult["URL"]?>&caption=<?= $arResult["TITLE"]?>&description=<?= $arResult["TEXT"]?>','Facebook',626,436);"
		href="javascript: void(0)"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
	<?else: ?>
		<a class="fb_share share" href="#" title="<?=GetMessage("FACEBOOK");?>" onclick="window.open('http://www.facebook.com/sharer.php?u=<?=$arResult["URL"]?>', '', 'scrollbars=yes,resizable=no,width=560,height=350,top='+Math.floor((screen.height - 350)/2-14)+',left='+Math.floor((screen.width - 560)/2-5)); return false;"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>

	<?endif; ?>
<?endif; ?>
<?if(isset($arParams['SERVICES']) && is_array($arParams['SERVICES']) && count($arParams['SERVICES'])>0 && in_array('GOOGLE',$arParams['SERVICES'])): ?>
<a class="gp_share share" href="#" title="<?=GetMessage("GOOGLE");?>"
	onclick="window.open('https://plus.google.com/share?url=<?=$arResult["URL"]?>', '', 'scrollbars=yes,resizable=no,width=560,height=350,top='+Math.floor((screen.height - 350)/2-14)+',left='+Math.floor((screen.width - 560)/2-5)); return false;">
	<i class="fa fa-google-plus" aria-hidden="true"></i></a>
<?endif; ?>
<?if(isset($arParams['SERVICES']) && is_array($arParams['SERVICES']) && count($arParams['SERVICES'])>0 && in_array('OK',$arParams['SERVICES'])): ?>
<a class="ok_share share" href="#" title="<?=GetMessage("OK");?>"
	onclick="window.open('http://www.odnoklassniki.ru/dk?st.cmd=addShare&amp;st._surl=<?=$arResult["URL"]?><?= substr($arResult["URL"], -3)=="%2F" ? "%3F" : ""?>', '', 'scrollbars=yes,resizable=no,width=620,height=450,top='+Math.floor((screen.height - 450)/2-14)+',left='+Math.floor((screen.width - 620)/2-5)); return false;">

	<i class="fa fa-odnoklassniki-square" aria-hidden="true"></i>
	</a>
<?endif; ?>
<?if(isset($arParams['SERVICES']) && is_array($arParams['SERVICES']) && count($arParams['SERVICES'])>0 && in_array('TWITTER',$arParams['SERVICES'])): ?>
<a class="tw_share share" href="#" title="<?=GetMessage("TWITTER");?>"
	onclick="window.open('http://twitter.com/share?text=<?= $arResult["TITLE"]?>&amp;url=<?= $arResult["URL"]?>', '', 'scrollbars=yes,resizable=no,width=560,height=350,top='+Math.floor((screen.height - 350)/2-14)+',left='+Math.floor((screen.width - 560)/2-5)); return false;">
	<i class="fa fa-twitter" aria-hidden="true"></i>
	</a>
<?endif; ?>
<?if(isset($arParams['SERVICES']) && is_array($arParams['SERVICES']) && count($arParams['SERVICES'])>0 && in_array('LIVEJOURNAL',$arParams['SERVICES'])): ?>
<a class="lj_share share" rel="nofollow"
	href="http://www.livejournal.com/update.bml?subject=<?= $arResult["TITLE"]?>&amp;event=<?= $arResult["HTML"]?>"
	target="_blank"></a>
	<?endif; ?>
	<?if(isset($arParams['SERVICES']) && is_array($arParams['SERVICES']) && count($arParams['SERVICES'])>0 && in_array('LIVEINTERNET',$arParams['SERVICES'])): ?>
<a class="li_share share" rel="nofollow"
	href="http://www.liveinternet.ru/journal_post.php?action=l_add&amp;cnurl=<?= $arResult["URL"]?>"
	target="_blank"></a>
	<?endif; ?>
	<?if(isset($arParams['SERVICES']) && is_array($arParams['SERVICES']) && count($arParams['SERVICES'])>0 && in_array('MAIL',$arParams['SERVICES'])): ?>
<a class="ma_share share" rel="nofollow" href="#" title="<?=GetMessage("MAIL");?>"
	onclick="window.open('http://connect.mail.ru/share?share_url=<?= $arResult["URL"]?>&title=<?= $arResult["TITLE"]?>&description=<?= $arResult["TEXT"]?>&imageurl=<?= $arResult["PICTURE"]?>', '', 'scrollbars=yes,resizable=no,width=560,height=350,top='+Math.floor((screen.height - 350)/2-14)+',left='+Math.floor((screen.width - 560)/2-5)); return false;">
	<i class="fa fa-at" aria-hidden="true"></i>
	</a>
<?endif; ?>

	<?if(isset($arParams['SHARE_LINK']) && !empty($arParams['SHARE_LINK'])): ?>
<a class="share_link share" rel="nofollow" href="javascript: void(0)" title="<?=$arParams['LINK_TITLE']?>"
	onclick="" data-url="<?=$arResult['HTTP'].SITE_SERVER_NAME.$arParams['SHARE_LINK']?>"><i class="fa fa-link" aria-hidden="true"></i></a>
<?endif; ?>


<!--/noindex-->
<div class="share-link-title"><?=$arParams['LINK_TITLE']?></div>
</div>
<?endif;?>
