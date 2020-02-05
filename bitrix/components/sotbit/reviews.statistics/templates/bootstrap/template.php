<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame=$this->createFrame()->begin();
global $APPLICATION;
	global $USER;
?>
<div class="row">
	<div class="col-sm-16 col-sm-offset-4 text-center">
		<h3><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_HEADER_TITLE")?>&nbsp;(<?=$arResult['SUM_CNT_REVIEWS']?>)</h3>
	</div>
</div>
<div class="row grey-bg">
	<div class="col-sm-16 col-sm-offset-4 text-center border">
		<?if($arResult['SUM_CNT_REVIEWS']>0):?>
			<h5><?=$arResult['RECOMMENDATED']?>% <?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_HEADER_RECOMMENDATED")?></h5>
			<p class="text-fixed"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_HEADER_FIXED")?> <?=$arResult['SUM_CNT_REVIEWS']?> <?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_HEADER_FIXED2")?></p>
			<p class="mid-rating"><?=$arResult['MID_REVIEW']?> <?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_HEADER_FROM")?> <?=$arParams["MAX_RATING"]?></p>
			<?endif;?>
		<div class="empty-stars" style="width:<?=$arParams["MAX_RATING"]*27?>px;">
			<div class="full-stars" style="width:<?=$arResult['MID_REVIEW_PROC']?>%"></div>
		</div>
		<?if($arResult['SUM_CNT_REVIEWS']>0):?>
			<?for($i=$arParams["MAX_RATING"];$i>=1;--$i):?>
				<div class="row review-line">
					<div class="col-sm-3 col-sm-offset-5 col cnt-stars">
						<?=$i?> <?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_HEADER_STAR".$arResult['TRANSLATE_REVIEW'][$i])?>
					</div>
					<div class="col-sm-8 col">
						<div class="reviews-scale-empty">
							<div class="reviews-scale-full" style="width:<?=$arResult['PROC_REVIEWS'][$i]?>%;">
							</div>
						</div>
					</div>
					<div class="col-sm-4 col">
						<div class="reviews-count-block"><?=$arResult['CNT_REVIEW'][$i]?></div>
					</div>
				</div>
				<?endfor;?>
			<?else:?>
			<p class="no-reviews-title1"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_NO_REVIEW_TITLE1")?> </p>
			<p class="no-reviews-title2"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_NO_REVIEW_TITLE2")?> </p>
			<?endif;?>
	</div>
</div>
<style>
 #reviews-statistics h3{color:<?=$arParams['PRIMARY_COLOR']?>}
 #reviews-statistics .reviews-scale-full{background:<?=$arParams['PRIMARY_COLOR']?>;}
</style>
<?$frame->end();?>