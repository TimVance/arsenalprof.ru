<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame=$this->createFrame()->begin();
global $APPLICATION;
global $USER;
?>
<div class="panel-top" id="filter" data-url="<?=$APPLICATION->GetCurPage()?>" data-max-rating="<?=$arParams["MAX_RATING"]?>" data-id-element="<?=$arParams["ID_ELEMENT"]?>" data-site-dir="<?=SITE_DIR?>" data-template="<?=$templateName?>" >
	<div class="border">
		<p class="filter"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_FILTER")?></p>
		<div id="select-rating" class="select-rating-close">
			<div id="current-option-select-rating" data-value="<?=$arResult['FILTER_REVIEWS_VALUE']?>">
				<span><?=$arResult['FILTER_REVIEWS_TITLE']?></span>
				<b><i class="fa fa-angle-down"></i>
				</b>
			</div>

			<ul id="custom-options-select-rating">
				<li data-value="-1"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_FILTER_GENERAL_RATING")?> (<?=$arResult['SUM_CNT_REVIEWS']?>)</li>
				<?for($i=1;$i<=$arParams["MAX_RATING"];++$i):?>
					<li data-value="<?=$i?>">
						<span class="stars-in">
						<?for($k=1;$k<=$i;++$k):?>
							<span class="uni-stars"><i class="fa fa-star" aria-hidden="true"></i>
</span>
							<?endfor;?>
						</span>
						(<?=$arResult['CNT_REVIEW'][$i]?>)
					</li>
					<?endfor;?>
			</ul>
		</div>
		<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_".SITE_ID, "")=='Y'):?>
			<div class="checkbox-group">
				<p class="filter-photo"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_FILTER_PHOTO")?> (<?=$arResult['CNT_PIC']?>)</p>
				<input
					id="filter-images"
					type="checkbox"
					<?=($arResult['SORT_IMAGES']=="Y")?"checked":""?>
					data-value="N"
					/> <label for="filter-images"><span></span></label>
			</div>
			<?endif;?>
			<div class="sort-group">
			<p class="filter-sort-text"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_FILTER_SORT_TEXT")?></p>
		<div id="select-sort" class="select-sort-close">
			<div id="current-option-select-sort" data-sort-by="<?=$arResult['BY'] ?>" data-sort-order="<?$arResult['ORDER'] ?>">
				<span><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_SORT_".$arResult['TITLE'])?></span>
				<b><i class="fa fa-angle-down"></i>
				</b>
			</div>
			<ul id="custom-options-select-sort" >
				<li data-sort-by="DATE_CREATION" data-sort-order="desc"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_SORT_NEW")?></li>
				<li data-sort-by="DATE_CREATION" data-sort-order="asc"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_SORT_OLD")?></li>
				<li data-sort-by="RATING" data-sort-order="desc"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_SORT_HIGH_RATING")?></li>
				<li data-sort-by="RATING" data-sort-order="asc"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_SORT_LOW_RATING")?></li>
				<li data-sort-by="LIKES" data-sort-order="desc"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_SORT_LIKES")?></li>
			</ul>
		</div>
		</div>
	</div>
</div>
<style>
#reviews-body #filter #custom-options-select-rating li:hover,
#reviews-body #filter #custom-options-select-rating li:hover .uni-stars,
#reviews-body #filter #custom-options-select-sort li:hover{
	color:<?=$arParams["PRIMARY_COLOR"]?>;
}
</style>
<?$frame->end();?>
