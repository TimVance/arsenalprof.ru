<?
if(!defined( "B_PROLOG_INCLUDED" )||B_PROLOG_INCLUDED!==true)
	die();
use Bitrix\Main\Localization\Loc;
global $APPLICATION;
global $USER;
?>
<?$frame=$this->createFrame()->begin();?>
	<div class="row reviews-user-list" id="reviews-user-list" data-site-dir="<?=SITE_DIR?>">
		<div class="col-sm-24">
			<?
			if(isset( $arResult['REVIEWS'] ) && is_array( $arResult['REVIEWS'] ) && $arResult["REVIEWS_CNT"]>0 )
			{
				foreach( $arResult['REVIEWS'] as $Review )
				{
			?>
				<div class="item row">
					<div class="col-sm-24 item-col">
					<div class="border">
						<div class="row">
							<div class="col-sm-24">
								<div class="element">
									<?if($Review['ELEMENT_URL']):?>
										<a href="<?=$Review['ELEMENT_URL'] ?>"><?=$Review['ELEMENT_NAME'] ?></a>
									<?else:?>
										<a href="javascript:void(0)" style="cursor: default;"><?=$Review['ELEMENT_NAME'] ?></a>
									<?endif?>
								</div>
							</div>
						</div>

					<?if($arResult['USE_TITLE'] && !empty($Review['TITLE']))
					{?>
						<div class="row">
							<div class="col-sm-24">
								<p class="title"><?=$Review['TITLE']?></p>
							</div>
						</div>
					<?}?>
					<div class="row">
						<div class="date-creation col-sm-24">
							<?=$Review['DATE_CREATION'];?>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-24 rating">
							<p class="rating-text">
								<?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_RATING_TITLE");?>
							</p>
							<div class="stars star<?=$Review['RATING']?>">
								<?for($i=1;$i<=$arParams["MAX_RATING"];++$i)
								{?>
									<i class="fa fa-star <?=($i<=$Review['RATING'])?'full':'empty'?>"></i>
								<?}?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-24 text">
							<?=$Review['TEXT']?>
						</div>
					</div>
					<?if($arResult['USE_IMAGES'] && isset($Review['THUMB_IMAGE']) && is_array($Review['THUMB_IMAGE'])):?>
						<div class="row">
							<div class="col-sm-24">
								<p class="images-title"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_IMAGES_TITLE");?></p>
								<ul class="images">
									<?if(isset($Review['THUMB_IMAGE']) && is_array($Review['THUMB_IMAGE'])):?>
										<?foreach($Review['THUMB_IMAGE'] as $key=>$image):?>
											<a href="<?=$Review['BIG_IMAGE'][$key]?>" class="image" rel="<?=$Review['ID']?>">
												<img src="<?=$image?>" class="img-responsive">
											</a>
										<?endforeach;?>
									<?endif;?>
								</ul>
							</div>
						</div>
					<?endif;?>
					<?if($arResult['USE_VIDEO']=='Y' && isset($Review['MULTIMEDIA']['VIDEO']) && !empty($Review['MULTIMEDIA']['VIDEO'])):?>
						<div class="row">
							<div class="col-sm-24">
								<p class="video-title"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_VIDEO_TITLE");?></p>
								<?=$Review['MULTIMEDIA']['VIDEO']?>
							</div>
						</div>
					<?endif;?>
					<?if($arResult['USE_PRESENTATION']=='Y' && isset($Review['MULTIMEDIA']['PRESENTATION']) && !empty($Review['MULTIMEDIA']['PRESENTATION'])):?>
						<div class="row">
							<div class="col-sm-24">
								<p class="presentation-title"><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_PRESENTATION_TITLE");?></p>
								<?=$Review['MULTIMEDIA']['PRESENTATION']?>
							</div>
						</div>
					<?endif;?>
					<?if(isset($Review['ADD_FIELDS']) && is_array($Review['ADD_FIELDS'])):?>
						<?foreach($Review['ADD_FIELDS'] as $key=>$value):?>
							<?if(isset($value) && !empty($value)):?>
								<div class="row add-field">
									<div class="col-sm-4">
										<p class="add-field-title <?=$key?>"><?=(isset($arResult['ADD_FIELDS'][$key]['TITLE'])&&!empty($arResult['ADD_FIELDS'][$key]['TITLE']))?$arResult['ADD_FIELDS'][$key]['TITLE']:Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_ADD_FIELD_TITLE_".$key)?></p>
									</div>
									<div class="col-sm-20">
										<p class="add-field-text <?=$key?>"><?=CSotbitReviews::bb2html($value)?></p>
									</div>
								</div>
							<?endif;?>
						<?endforeach;?>
					<?endif;?>
					<div class="row">
						<div class="col-sm-24 likes">
							<div class="voted-yes"></div>
							<span class="yescnt"><?=$Review['LIKES']?></span>
							<div class="voted-no"></div>
							<span class="nocnt"><?=$Review['DISLIKES']?></span>

						<?if(isset($Review['RECOMMENDATED']) && $Review['RECOMMENDATED']=='Y'): ?>
							<i class="fa fa-check"></i>
							<p class="recommendated">
								<?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_I_RECOMMENDATED")?>
							</p>
						<?endif; ?>
						</div>
					</div>
				<?if(isset($Review['ANSWER']) && !empty($Review['ANSWER'])):?>
					<div class="row shopanswer">
						<div class="col-sm-24 col-shopanswer">
							<div class="avatar">
								<div class="avatar-inner">
									<img class="img-responsive" alt="<?=$arResult['SITE_NAME']?>" title="<?=$arResult['SITE_NAME']?>" src="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_ANSWER_IMAGE_".SITE_ID, "")?>">
								</div>
							</div>
							<div class="shopanswer-text">
								<p class="username"><?=$arResult['SITE_NAME']?></p>
								<p class="text"><?=$Review['ANSWER']?></p>
							</div>
						</div>
					</div>
				<?endif;?>
				</div>
</div>
			</div>
					<?
				}
			}
			else
			{
				?>
				<p><?=Loc::getMessage(CSotbitReviews::iModuleID."_REVIEWS_NO_RESULTS")?></p>
				<?}?>
			<?if($arResult['CNT_PAGES']>1):?>
				<div class="pagination" <?=($arResult['CNT_PAGES']<=1)?'style="display:none;':''?> data-url="<?=$APPLICATION->GetCurPage()?>"  data-site-dir="<?=SITE_DIR?>" data-template="<?=$templateName?>">
					<?if($arResult['CURRENT_PAGE']>1):?>
						<div class="left-arrows">
					<button data-number="1" type="button" class="first">
						<i class="fa fa-angle-double-left"></i>
					</button>
					<button data-number="<?=$arResult['CURRENT_PAGE']-1?>"
						type="button" class="prev">
						<i class="fa fa-angle-left"></i>
					</button>
				</div>
						<?endif;?>

				<?for($i=1;$i<=$arResult['CNT_PAGES'];++$i):?>
					<?if($arResult['CNT_PAGES']-$arResult["CNT_LEFT_PGN"]-$arResult["CNT_RIGHT_PGN"]<$arResult['CURRENT_PAGE']):?>
						<?if($i>=$arResult['CNT_PAGES']-$arResult["CNT_LEFT_PGN"]-$arResult["CNT_RIGHT_PGN"] && $i<=$arResult['CNT_PAGES']-$arResult["CNT_RIGHT_PGN"]):?>
							<button data-number="<?=$i?>" type="button" <?=($i==$arResult['CURRENT_PAGE'])?'data-active="true" class="current"':''?>><?=$i?></button>
						<?endif?>
					<?else:?>
						<?if((int) ceil($arResult['CURRENT_PAGE'] / $arResult["CNT_LEFT_PGN"])==(int) ceil($i / ($arResult["CNT_LEFT_PGN"]))):?>
							<button data-number="<?=$i?>" type="button"	<?=($i==$arResult['CURRENT_PAGE'])?'data-active="true" class="current"':''?>><?=$i?></button>
						<?endif;?>
						<?if((int) ceil($arResult['CURRENT_PAGE'] / $arResult["CNT_LEFT_PGN"])*$arResult["CNT_LEFT_PGN"]+1 == $i):?>
							<button data-number="<?=$i?>" type="button" class="dots">...</button>
						<?endif;?>
					<?endif;?>
					<?if($i>$arResult['CNT_PAGES']-$arResult["CNT_RIGHT_PGN"]):?>
						<button data-number="<?=$i?>" type="button" <?=($i==$arResult['CURRENT_PAGE'])?'data-active="true" class="current"':''?>><?=$i?></button>
					<?endif;?>
				<?endfor;?>
				<?if($arResult['CURRENT_PAGE']<>$arResult['CNT_PAGES']):?>
					<div class="right-arrows">
						<button data-number="<?=$arResult['CURRENT_PAGE']+1?>" type="button" class="next">
							<i class="fa fa-angle-right"></i>
						</button>
						<button data-number="<?=$arResult['CNT_PAGES']?>" type="button" class="last">
							<i class="fa fa-angle-double-right"></i>
						</button>
					</div>
				<?endif;?>
			</div>
		<?endif;?>
		</div>
	</div>
	<style>
.reviews-user-list .pagination button.current, .reviews-user-list .element a {
	color: <?=$arParams['PRIMARY_COLOR']?>
}
</style>
	<?$frame->end();?>

<?$APPLICATION->ShowViewContent('sotbit_reviews_pagination');?>
<script>
	$(".sotbit_reviews_user_rcq .images").colorbox();
</script>
<div id="ReviewsParams" style="display:none;"><?=serialize(array_merge($arParams,array('TEMPLATE'=>$templateName)))?></div>
