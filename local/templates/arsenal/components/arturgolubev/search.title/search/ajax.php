<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (empty($arResult["CATEGORIES"]))
	return;

IncludeTemplateLangFile(__FILE__);

$themeClass = 'theme-'.COption::GetOptionString("arturgolubev.smartsearch", 'color_theme', 'blue');
?>
<div class="bx_smart_searche <?=$themeClass?>">
	<?
	if($arResult["DEBUG_INFO"]["SHOW_DEBUG"] == 'Y')
	{
		echo '<pre>';
			echo 'Type: '; print_r($arResult["DEBUG_INFO"]["SEARCH_TYPE"]); echo "\r\n";
			echo 'Cache: '; print_r($arResult["DEBUG_INFO"]["FROM_CACHE"]); echo "\r\n";
			echo 'Time: '; print_r($arResult["DEBUG_INFO"]["TIME"]); echo "\r\n";
			echo 'Max count: '; print_r($arResult["DEBUG_INFO"]["TOP_COUNT"]); echo "\r\n";
		echo '</pre>';
		
		echo '<pre>'; print_r($arResult["DEBUG_INFO"]["REQUESTS"]); echo '</pre>';
		// echo '<pre>'; print_r($arResult["DEBUG_INFO"]["TEST"]); echo '</pre>';
		// echo '<pre>'; print_r($arResult["DEBUG_INFO"]["FINDED"]); echo '</pre>';
		// echo '<pre>'; print_r($arResult["DEBUG_INFO"]["RESULT_WORDS"]); echo '</pre>';
	}
	?>
	
	
	<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
		<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<?if(isset($arResult["SECTIONS"][$arItem["ITEM_ID"]])):
				$arElement = $arResult["SECTIONS"][$arItem["ITEM_ID"]];?>
				<a class="bx_item_block_href" href="<?echo $arItem["URL"]?>">
					<span class="bx_item_block_href_category_title"><?=GetMessage("AG_SMARTIK_SECTION_TITLE");?></span><br>
					<span class="bx_item_block_href_category_name"><?echo strip_tags($arItem["NAME"])?></span>
				</a>
				<div class="bx_item_block_hrline"></div>
			<?endif;?>
		<?endforeach;?>
	<?endforeach;?>
	
	<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
		<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<?if(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
				$arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];
			
				$arElement["PREVIEW_TEXT"] = strip_tags($arElement["PREVIEW_TEXT"]);
			
				if(is_array($arElement["PICTURE"]))
					$image_url = $arElement["PICTURE"]["src"];
				else
					$image_url = '/bitrix/components/arturgolubev/search.title/templates/.default/images/noimg.png';
			?>
				
				<a class="bx_item_block_href" href="<?echo $arItem["URL"]?>">
					<span class="bx_item_block_item_info">
						<span class="bx_item_block_item_image" style="background-image: url(<?=$image_url?>);"></span>
						
						<?
						foreach($arElement["PRICES"] as $code=>$arPrice)
						{
							if ($arPrice["MIN_PRICE"] != "Y")
								continue;

							if($arPrice["CAN_ACCESS"])
							{
								if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
									<span class="bx_item_block_item_price">
										<span class="bx_price_new">
											<?=$arPrice["PRINT_DISCOUNT_VALUE"]?>
										</span>
										<span class="bx_price_old"><?=$arPrice["PRINT_VALUE"]?></span>
									</span>
								<?else:?>
									<span class="bx_item_block_item_price bx_item_block_item_price_only_one">
										<span class="bx_price_new"><?=$arPrice["PRINT_VALUE"]?></span>
									</span>
								<?endif;
							}
							if ($arPrice["MIN_PRICE"] == "Y")
								break;
						}
						?>
						
						<span class="bx_item_block_item_name">
							<span class="bx_item_block_item_name_flex_align">
								<?echo $arItem["NAME"]?>
							</span>
						</span>
						<span class="add-over-basket" data="<?=$arItem['ITEM_ID']?>"><svg><use xlink:href="#basket"></use></svg></span>
						<span class="bx_item_block_item_clear"></span>
					</span>
					
					<?if($arElement["PREVIEW_TEXT"]):?>
						<span class="bx_item_block_item_text"><?=$arElement["PREVIEW_TEXT"]?></span>
					<?endif;?>
				</a>
			<?endif;?>
		<?endforeach;?>
	<?endforeach;?>

	<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
		<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<?if($category_id === "all"):?>
				<div class="bx_item_block all_result">
					<div class="bx_item_element bx_item_element_all_result">
						<a class="all_result_button" href="<?echo $arItem["URL"]?>"><?echo $arItem["NAME"]?></a>
					</div>
					<div style="clear:both;"></div>
				</div>
			<?
			elseif(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]]) || isset($arResult["SECTIONS"][$arItem["ITEM_ID"]])):
				continue;
			else:?>
				<a class="bx_item_block_href" href="<?echo $arItem["URL"]?>">
					<span class="bx_item_block_item_simple_name"><?echo $arItem["NAME"]?></span>
				</a>
			<?endif;?>
		<?endforeach;?>
	<?endforeach;?>
</div>