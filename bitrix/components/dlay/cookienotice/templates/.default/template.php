<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($_COOKIE["cookienotice"] != "Y"): ?>

    <div id="dlay-cookienotice-modal">
        <div class="dlay-cookienotice-text">
            <p class="dlay-cookienotice-p"><?=$arResult["NOTICE_TEXT"]?></p>
            <a class="dlay-cookienotice-link" href="<?=$arResult["NOTICE_LINK"]?>"><?=$arResult["NOTICE_LINK_TEXT"]?></a>
            <button class="dlay-cookienotice-button"><?=$arResult["NOTICE_BUTTON"]?></button>
        </div>
    </div>

<? endif; ?>