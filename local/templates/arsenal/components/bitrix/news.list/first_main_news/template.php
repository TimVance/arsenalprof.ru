<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news__item">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news__card" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
            <div class="news__card-img">
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<img
                    class="preview_picture"
                    src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                    alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                    title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                    />
			<?else:?>
				<img
					class="preview_picture"
					src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
					alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
					/>
			<?endif;?>
            </div>
		<?endif?>
		<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<div class="news__card-title"><?echo $arItem["NAME"]?></div>
			<?else:?>
                <div class="news__card-title"><?echo $arItem["NAME"]?></div>
			<?endif;?>
		<?endif;?>
		<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
            <div class="news__description"><?echo $arItem["PREVIEW_TEXT"];?></div>
		<?endif;?>
<!--		<?/*foreach($arItem["FIELDS"] as $code=>$value):*/?>
			<small>
			<?/*=GetMessage("IBLOCK_FIELD_".$code)*/?>:&nbsp;<?/*=$value;*/?>
			</small><br />
		--><?/*endforeach;*/?>
        <? $itemDate = CIBlockFormatProperties::DateFormat('j F Y', strtotime($arItem["DATE_CREATE"])); ?>
        <span class="news__card-date"><?echo $itemDate;?></span>
<!--		<?/*foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):*/?>
			<small>
			<?/*=$arProperty["NAME"]*/?>:&nbsp;
			<?/*if(is_array($arProperty["DISPLAY_VALUE"])):*/?>
				<?/*=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);*/?>
			<?/*else:*/?>
				<?/*=$arProperty["DISPLAY_VALUE"];*/?>
			<?/*endif*/?>
			</small><br />
		--><?/*endforeach;*/?>
	</a>
<?endforeach;?>
</div>
