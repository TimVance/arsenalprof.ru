<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div id="shs-online-vertical">
    <h2 class="with_tr_line"><?=GetMessage("SHS_ONLINE_NOW_BUY")?></h2>
    <?if($arParams["SHOW_COUNT_BUYERS"]=="Y"):?>
    <span class="shs_online"><?=$arResult["USERS_COUNT"]?> <?=CShsOnlinebuyers::GetStringCount($arResult["USERS_COUNT"], GetMessage("SHS_ONLINE_CLIENT"))?> <?=GetMessage("SHS_ONLINE_ONLINE")?></span>
    <?endif;?>
    <div class="shs_vertical">
            <ul>
            <?foreach($arResult["PRODUCTS"] as $arItem):
            ?>
                <li>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                        <img src="<?=$arItem["PREVIEW_PICTURE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["height"]?>" alt="">
                    </a>
                    <div><?=$arItem["NAME"]?></div>
                    <?foreach ($arItem["PRICE_MATRIX"]["ROWS"] as $ind => $arQuantity):?>
                        <?foreach($arItem["PRICE_MATRIX"]["COLS"] as $typeID => $arType):
                        ?>
							<?
							if($arItem["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["DISCOUNT_PRICE"] < $arItem["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"]):?>
									<s><?=FormatCurrency($arItem["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"], $arItem["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"])?></s><span class="catalog-price"><?=FormatCurrency($arItem["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["DISCOUNT_PRICE"], $arItem["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"]);?></span>
							<?else:?>
									<span class="catalog-price"><?=FormatCurrency($arItem["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"], $arItem["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"]);?></span>
							<?endif?>
					    <?endforeach?>
                    <?endforeach?>
                    <?if($arItem["CITY"]):?>
                    <i><?=!empty($arItem["CITY"])?$arItem["CITY"]:""?></i>
                    <?endif;?>
                </li>
            <?endforeach?>
            </ul>
    </div>
</div>