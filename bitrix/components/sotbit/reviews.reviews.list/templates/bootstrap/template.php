<?
if(!defined( "B_PROLOG_INCLUDED" )||B_PROLOG_INCLUDED!==true)
	die();
global $APPLICATION;
global $USER;
?>
<div id="reviews-list" data-primary-color="<?=$arParams['PRIMARY_COLOR']?>" data-date-format="<?=$arParams['DATE_FORMAT']?>">
	<?$frame=$this->createFrame()->begin();?>
	<div class="row">
		<div class="col-sm-20 col-sm-offset-2 list" data-items-count="<?=$arResult["REVIEWS_FILTER_CNT"]?>">
			<?
			if(isset( $arResult['REVIEWS'] )&&is_array( $arResult['REVIEWS'] )&&count( $arResult['REVIEWS']>0 ))
			{
				foreach( $arResult['REVIEWS'] as $Review )
				{
					?>
				<div class="item" id="review-<?=$Review['ID']?>" data-id="<?=$Review['ID']?>" data-site-dir="<?=SITE_DIR?>">
				<div class="row item-row" itemscope itemtype="http://schema.org/Review">
					<span itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
						<span itemprop="name" class="dnone">
							<?=$arResult['ELEMENT']['NAME']?>
						</span>
					</span>
					<div class="col-sm-8 user-info">
						<div class="avatar">
							<div class="avatar-inner">
								<img class="img-responsive" alt="<?=$Review['NAME']?>" title="<?=$Review['NAME']?>" src="<?=$Review['PERSONAL_PHOTO']?>">
							</div>
						</div>
						<div class="username" itemprop="author" itemscope itemtype="http://schema.org/Person">
							<span itemprop="name">
								<?=$Review['NAME']?>
							</span>
						</div>
						<?if($Review['ID_USER']>0): ?>
							<?if(isset($arResult['LINK_TO_USER'][$Review['ID_USER']]) && !empty($arResult['LINK_TO_USER'][$Review['ID_USER']])): ?>
								<div class="cnt_reviews"><span><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_CNT_USER") ?></span><a href="<?=$arResult['LINK_TO_USER'][$Review['ID_USER']] ?>"> <?=$arResult['USER_REVIEWS_CNT'][$Review['ID_USER']] ?></a></div>
							<?else: ?>
								<div class="cnt_reviews"><span><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_CNT_USER") ?></span><?=$arResult['USER_REVIEWS_CNT'][$Review['ID_USER']] ?></div>
							<?endif; ?>
						<?endif; ?>
						<?=(isset($Review['AGE']) && !empty($Review['AGE']))?'<div class="age"><span>'.GetMessage(CSotbitReviews::iModuleID."_REVIEWS_AGE").'</span>'.$Review['AGE'].'</div>':''?>
						<?=(isset($Review['COUNTRY']) && !empty($Review['COUNTRY']))?'<div class="country"><span>'.GetMessage(CSotbitReviews::iModuleID."_REVIEWS_COUNTRY").'</span>'.$Review['COUNTRY'].'</div>':''?>
						<div class="clearfix"></div>
					</div>
					<div class="text col-sm-16">

					<?if($arResult['MODERATOR']=='Y')
					{?>
					<div class="menu">
					  	<div class="ban-message-success message message-success"><?=GetMessage(CSotbitReviews::iModuleID."_BAN_USER_SUCCESS") ?></div>
  						<div class="ban-message-error message message-error"><?=GetMessage(CSotbitReviews::iModuleID."_BAN_USER_ERROR") ?></div>
  						<div style="display:none" id="ban-confirm-text"><?=GetMessage(CSotbitReviews::iModuleID."_BAN_USER_CONFIRM") ?></div>
  						<i class="fa fa-cog actions"></i>
  						<ul>
    						<li data-action="ban"><?=GetMessage(CSotbitReviews::iModuleID."_BAN_USER") ?></li>
  						</ul>

					</div>
					<?} ?>

					<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_TITLE_".SITE_ID, "")=='Y')
					{?>
						<div class="row">
							<div class="col-sm-24">
								<p class="title"><?=$Review['TITLE']?></p>
							</div>
						</div>
					<?}?>
					<div class="row">
						<div class="time col-sm-24" itemprop="datePublished">
							<?=$Review['DATE_CREATION'];?>
						</div>
						<div class="time-filter">
							<?=$Review['DATE_CREATION_ORIG'];?>
						</div>



					</div>
					<div class="row">
						<div class="rating col-sm-12">
							<div class="rating-text">
								<?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_RATING_TITLE");?>
							</div>
							<div class="stars star<?=$Review['RATING']?>">
								<?for($i=1;$i<=$arParams["MAX_RATING"];++$i)
								{?>
									<i class="fa fa-star <?=($i<=$Review['RATING'])?'full':'empty'?>"></i>
								<?}?>
							</div>
							</div>
							<div class="col-sm-12 share">
						<?$APPLICATION->IncludeComponent(
                        "sotbit:reviews.share",
                        "",
                        array(
                            "TITLE" => (isset($Review['TITLE']) && !empty($Review['TITLE']))?$Review['TITLE']:'',
                            "URL" => $arResult['ELEMENT']['DETAIL_PAGE_URL'],
                            "PICTURE" => $Review['SHARE_IMAGE'],
                            "TEXT" => $Review['TEXT'],
                        	"SERVICES"=>$arResult['SHARE_SERVICES'],
                        	"FACEBOOK_APP_ID"=>$arResult['FACEBOOK_APP_ID'],
                        	"SHARE_LINK"=>$arResult['ELEMENT']['DETAIL_PAGE_URL'].'#review-'.$Review['ID'],
                        	"LINK_TITLE"=>GetMessage(CSotbitReviews::iModuleID."_LINK_TITLE_REVIEWS")
                        ),
                        false
                    );?>
                    </div>

						<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
						    <span itemprop="ratingValue" class="dnone">
						    	<?=$Review['RATING']?>
						    </span>
  						</span>
					</div>
					<div class="row">
						<div class="col-sm-24 text" itemprop="reviewBody">
							<?=$Review['TEXT']?>
						</div>
					</div>
					<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_UPLOAD_IMAGE_".SITE_ID, "")=='Y' && isset($Review['THUMB_IMAGE']) && is_array($Review['THUMB_IMAGE'])):?>
						<div class="row">
							<div class="col-sm-24">
								<p class="images-title"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_IMAGES_TITLE");?></p>
								<ul class="images-reviews">
												<?if(isset($Review['THUMB_IMAGE']) && is_array($Review['THUMB_IMAGE'])):?>
													<?foreach($Review['THUMB_IMAGE'] as $key=>$image):?>
														<a href="<?=$Review['BIG_IMAGE'][$key]?>"
										class="image-review" rel="<?=$Review['ID']?>"><img
										src="<?=$image?>" class="img-responsive"></a>
														<?endforeach;?>
													<?endif;?>
											</ul>
							</div>
						</div>
						<?endif;?>

<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_MULTIMEDIA_VIDEO_ALLOW_".SITE_ID, "")=='Y' && isset($Review['MULTIMEDIA']['VIDEO']) && !empty($Review['MULTIMEDIA']['VIDEO'])):?>
						<div class="row">
							<div class="col-sm-24">
								<p class="video-title"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_VIDEO_TITLE");?></p>
								<?=$Review['MULTIMEDIA']['VIDEO']?>
							</div>
						</div>
<?endif;?>

<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_MULTIMEDIA_PRESENTATION_ALLOW_".SITE_ID, "")=='Y' && isset($Review['MULTIMEDIA']['PRESENTATION']) && !empty($Review['MULTIMEDIA']['PRESENTATION'])):?>
						<div class="row">
							<div class="col-sm-24">
								<p class="presentation-title"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_PRESENTATION_TITLE");?></p>
								<?=$Review['MULTIMEDIA']['PRESENTATION']?>
							</div>
						</div>
<?endif;?>

								<?if(isset($Review['ADD_FIELDS']) && is_array($Review['ADD_FIELDS'])):?>
									<?foreach($Review['ADD_FIELDS'] as $key=>$value):?>
										<?if(isset($value) && !empty($value)):?>
											<div class="row add-field">
							<div class="col-sm-4">
								<p class="add-field-title <?=$key?>"><?=(isset($arResult['ADD_FIELDS'][$key]['TITLE'])&&!empty($arResult['ADD_FIELDS'][$key]['TITLE']))?$arResult['ADD_FIELDS'][$key]['TITLE']:GetMessage(CSotbitReviews::iModuleID."_REVIEWS_ADD_FIELD_TITLE_".$key)?></p>
							</div>
							<div class="col-sm-20">
								<p class="add-field-text <?=$key?>"><?=CSotbitReviews::bb2html($value)?></p>
							</div>
						</div>
											<?endif;?>
										<?endforeach;?>
									<?endif;?>

								<div class="row likes">
							<div class="col-sm-12">
								<p class="likes-title"><?=GetMessage(CSotbitReviews::iModuleID.'_REVIEWS_LIKES_TITLE')?></p>
										<?=(isset($Review['ID']) && !empty($Review['ID']) && isset($_COOKIE['LIKE']) && is_array($_COOKIE['LIKE']) && in_array($Review['ID'],$_COOKIE['LIKE']))?'<div class="voted-yes"></div>':'<div class="yes"></div>';?>
										<span class="yescnt"><?=$Review['LIKES']?></span>
										<?=(isset($Review['ID']) && !empty($Review['ID']) && isset($_COOKIE['LIKE']) && is_array($_COOKIE['LIKE']) && in_array($Review['ID'],$_COOKIE['LIKE']))?'<div class="voted-no"></div>':'<div class="no"></div>'?>
										<span class="nocnt"><?=$Review['DISLIKES']?></span>
							</div>
							<?if(isset($Review['RECOMMENDATED']) && $Review['RECOMMENDATED']=='Y'): ?>
								<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_EDITOR_".SITE_ID, "")=="Y" && COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_QUOTS_".SITE_ID, "")=="Y" && ($USER->IsAuthorized() || COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_REGISTER_USERS_".SITE_ID, "")!='Y')): ?>
									<div class="col-sm-6"><i class="fa fa-check"></i><p class="recommendated"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_I_RECOMMENDATED")?></p></div>
									<div class="col-sm-6 wrap-quote"><div class="quote"><?=GetMessage( CSotbitReviews::iModuleID . '_REVIEWS_QUOTE' )?></div></div>
								<?else: ?>
									<div class="col-sm-12"><i class="fa fa-check"></i><p class="recommendated"><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_I_RECOMMENDATED")?></p></div>
								<?endif; ?>
							<?else: ?>
								<?if(COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_EDITOR_".SITE_ID, "")=="Y" && COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_QUOTS_".SITE_ID, "")=="Y" && ($USER->IsAuthorized() || COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_REGISTER_USERS_".SITE_ID, "")!='Y')): ?>
									<div class="col-sm-12 wrap-quote"><div class="quote"><?=GetMessage( CSotbitReviews::iModuleID . '_REVIEWS_QUOTE' )?></div></div>
								<?else: ?>
									<div class="col-sm-12">



									</div>
								<?endif; ?>
							<?endif; ?>

								</div>
							</div>
			</div>
								<?if(isset($Review['ANSWER']) && !empty($Review['ANSWER'])):?>
									<div class="row shopanswer">
								<div class="avatar">
									<div class="avatar-inner">
										<img class="img-responsive" alt="<?=$arResult['SITE_NAME']?>"
											title="<?=$arResult['SITE_NAME']?>"
											src="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_ANSWER_IMAGE_".SITE_ID, "")?>">
									</div>
								</div>
								<p class="username"><?=$arResult['SITE_NAME']?></p>
								<p class="text"><?=$Review['ANSWER']?></p>
						</div>
									<?endif;?>
			</div>
			<span itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
				<span itemprop="name" class="dnone"><?=$arResult['SITE_NAME']?></span>
			</span>
					<?
				}
			}
			else
			{
				?>
				<p><?=GetMessage(CSotbitReviews::iModuleID."_REVIEWS_NO_RESULTS")?></p>
				<?}?>


			<?if($arResult['CNT_PAGES']>1):?>
				<div id="filter-pagination"
				data-cnt-left-pgn="<?=$arResult["CNT_LEFT_PGN"]?>"
				data-cnt-right-pgn="<?=$arResult["CNT_RIGHT_PGN"]?>"
				data-per-page="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "REVIEWS_COUNT_PAGE_".SITE_ID, "10")?>"
				<?=($arResult['CNT_PAGES']<=1)?'style="display:none;':''?>>
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
								<button data-number="<?=$i?>" type="button"
					<?=($i==$arResult['CURRENT_PAGE'])?'data-active="true" class="current"':''?>><?=$i?></button>
								<?endif?>
							<?else:?>
							<?if((int) ceil($arResult['CURRENT_PAGE'] / $arResult["CNT_LEFT_PGN"])==(int) ceil($i / ($arResult["CNT_LEFT_PGN"]))):?>
								<button data-number="<?=$i?>" type="button"
					<?=($i==$arResult['CURRENT_PAGE'])?'data-active="true" class="current"':''?>><?=$i?></button>
								<?endif;?>


						<?if((int) ceil($arResult['CURRENT_PAGE'] / $arResult["CNT_LEFT_PGN"])*$arResult["CNT_LEFT_PGN"]+1 == $i):?>
							<button data-number="<?=$i?>" type="button" class="dots">...</button>
							<?endif;?>
						<?endif;?>
						<?if($i>$arResult['CNT_PAGES']-$arResult["CNT_RIGHT_PGN"]):?>
							<button data-number="<?=$i?>" type="button"
					<?=($i==$arResult['CURRENT_PAGE'])?'data-active="true" class="current"':''?>><?=$i?></button>
							<?endif;?>

						<?endfor;?>
					<?if($arResult['CURRENT_PAGE']<>$arResult['CNT_PAGES']):?>
						<div class="right-arrows">
					<button data-number="<?=$arResult['CURRENT_PAGE']+1?>"
						type="button" class="next">
						<i class="fa fa-angle-right"></i>
					</button>
					<button data-number="<?=$arResult['CNT_PAGES']?>" type="button"
						class="last">
						<i class="fa fa-angle-double-right"></i>
					</button>
				</div>
						<?endif;?>
				</div>
				<?endif;?>


		</div>

	</div>
	<style>
#reviews-body #filter-pagination button.current {
	color: <?=$arParams['PRIMARY_COLOR']?>
}
</style>

<div id="idsReviews" style="display:none" data-site-dir="<?=SITE_DIR?>"><?=$arResult['REVIEWS_IDS']?></div>


	<?$frame->end();?>
</div>
<?$APPLICATION->ShowViewContent('sotbit_reviews_pagination');?>
<script>
	$(".image-review").colorbox();
</script>
