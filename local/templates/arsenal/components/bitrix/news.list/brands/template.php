<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
?>

<section class="company">
    <div class="container">

        <div class="company__wrap">



            <div class="brand-list frame" id="basic">
                <ul>
                    <?foreach($arResult["ITEMS"] as $arItem):?>
                        <?
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                        ?>
                        <li class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                            <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
                                <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
                                            class="preview_picture"
                                            border="0"
                                            src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                                            width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                                            height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                                            alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                            title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                                            style="float:left"
                                            /></a>
                                <?else:?>
                                    <img
                                        class="preview_picture"
                                        border="0"
                                        src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                                        width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                                        height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                                        alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                        title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                                        style="float:left"
                                        />
                                <?endif;?>
                            <?endif?>
                            <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                                <?echo $arItem["PREVIEW_TEXT"];?>
                            <?endif;?>
                            <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
                                <div style="clear:both"></div>
                            <?endif?>
                            <?foreach($arItem["FIELDS"] as $code=>$value):?>
                                <small>
                                <?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
                                </small><br />
                            <?endforeach;?>
                            <?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
                                <small>
                                <?=$arProperty["NAME"]?>:&nbsp;
                                <?if(is_array($arProperty["DISPLAY_VALUE"])):?>
                                    <?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
                                <?else:?>
                                    <?=$arProperty["DISPLAY_VALUE"];?>
                                <?endif?>
                                </small><br />
                            <?endforeach;?>
                        </li>
                    <?endforeach;?>
                </ul>
            </div>




            <div class="scrollbar">
                <div class="handle">
                    <div class="mousearea"></div>
                </div>
            </div>

            <button class="js-prev-page">
                <svg>
                    <use xlink:href="#arrow"/>
                </svg>
            </button>
            <button class="js-next-page">
                <svg>
                    <use xlink:href="#arrow"/>
                </svg>
            </button>

        </div>

    </div>
</section>