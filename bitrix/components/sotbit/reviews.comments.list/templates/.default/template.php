<?
if(!defined( "B_PROLOG_INCLUDED" )||B_PROLOG_INCLUDED!==true)
	die();
global $USER;
global $APPLICATION;
$frame = $this->createFrame()->begin();
?>
<div class="list">
	<div class="list-rows">
	<?if(isset($arResult['COMMENTS']) && is_array($arResult['COMMENTS'])&&count($arResult['COMMENTS'])>0):?>
		<?foreach($arResult['COMMENTS'] as $Comment):?>
		<div data-site-dir="<?=SITE_DIR?>" id="comment-<?=$Comment['ID']?>" data-id="<?=$Comment['ID']?>" class="item level level-<?=$Comment['LEVEL']?> <?=($Comment['SHOP_ADMIN']=="Y")?"shopanswer":""?>" itemprop="comment" itemscope itemtype="http://schema.org/UserComments">
		<span class="dnone" itemprop="interactionCount"><?=$arResult["CNT_COMMENTS"] ?></span>
		<?
			if($Comment['SHOP_ADMIN']=="N")
			{
				?>
			<div class="table">
				<div class="user-info">
					<div class="avatar">
						<div class="avatar-inner">
							<img class="img-responsive" alt="<?=$Comment['NAME']?>" title="<?=$Comment['NAME']?>" src="<?=$Comment['PERSONAL_PHOTO']?>">
						</div>
					</div>
					<div class="username" itemprop="creator">
						<?=$Comment['NAME']?>
					</div>
										<?if($Comment['ID_USER']>0): ?>
							<?if(isset($arResult['LINK_TO_USER'][$Comment['ID_USER']]) && !empty($arResult['LINK_TO_USER'][$Comment['ID_USER']])): ?>
								<div class="cnt_comments"><span><?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_CNT_USER") ?></span><a href="<?=$arResult['LINK_TO_USER'][$Comment['ID_USER']] ?>"> <?=$arResult['USER_COMMENTS_CNT'][$Comment['ID_USER']] ?></a></div>
							<?else: ?>
								<div class="cnt_comments"><span><?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_CNT_USER") ?></span><?=$arResult['USER_COMMENTS_CNT'][$Comment['ID_USER']] ?></div>
							<?endif; ?>
						<?endif; ?>
					<?=(isset($Comment['AGE']) && !empty($Comment['AGE']))?'<div class="age"><span>'.GetMessage(CSotbitReviews::iModuleID."_COMMENTS_AGE").'</span>'.$Comment['AGE'].'</div>':''?>
					<?=(isset($Comment['COUNTRY']) && !empty($Comment['COUNTRY']))?'<div class="country"><span>'.GetMessage(CSotbitReviews::iModuleID."_COMMENTS_COUNTRY").'</span>'.$Comment['COUNTRY'].'</div>':''?>
			</div>
				<div class="comments">
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

					<div><div class="time" itemprop="commentTime"><?=$Comment['DATE_CREATION'];?></div>

															<?$APPLICATION->IncludeComponent(
                        "sotbit:reviews.share",
                        "",
                        array(
                            "TITLE" => '',
                            "URL" => $arResult['ELEMENT']['DETAIL_PAGE_URL'],
                            "PICTURE" => $Question['SHARE_IMAGE'],
                            "TEXT" => $Question['QUESTION'],
                        	"SERVICES"=>$arResult['SHARE_SERVICES'],
                        	"FACEBOOK_APP_ID"=>$arResult['FACEBOOK_APP_ID'],
                        	"SHARE_LINK"=>$arResult['ELEMENT']['DETAIL_PAGE_URL'].'#comment-'.$Comment['ID'],
                        	"LINK_TITLE"=>GetMessage(CSotbitReviews::iModuleID."_LINK_TITLE_COMMENTS")
                        ),
                        false
                    );?>

</div>
					<p class="text" itemprop="commentText"><?=$Comment['TEXT']?></p>
				</div>
			</div>
						<?
				$APPLICATION->IncludeComponent( "sotbit:reviews.comments.add", "default-answer", array(
						'TEXTBOX_MAXLENGTH' => $arParams['TEXTBOX_MAXLENGTH'],
						'ID_ELEMENT' => $arParams['ID_ELEMENT'],
						"PRIMARY_COLOR" => $arParams['PRIMARY_COLOR'],
						"BUTTON_BACKGROUND" => $arParams['BUTTON_BACKGROUND'],
						'AJAX' => $arParams["AJAX"],
						'PARENT' => $Comment['ID'],
						"NOTICE_EMAIL" => $arParams['NOTICE_EMAIL'],
						'CACHE_TIME' => $arParams["CACHE_TIME"],
						'CACHE_GROUPS' => $arParams["CACHE_GROUPS"]
				), $component );
				?>
			<div class="border-bottom" style="clear: both"></div>

			<?
			}
			else
			{
				?>
			<div class="user-info">
				<div class="avatar">
					<div class="avatar-inner">
						<img class="img-responsive" alt="<?=$Comment['NAME']?>" title="<?=$Comment['NAME']?>" src="<?=$Comment['PERSONAL_PHOTO']?>">
					</div>
				</div>
			</div>
			<div class="text">
				<div class="username" itemprop="creator">
						<?=(isset($Comment['NAME']) && !empty($Comment['NAME']))?$Comment['NAME']:'';?>
					</div>
				<span class="text" itemprop="commentText"><?=$Comment['TEXT']?></span>
			</div>
			<div style="clear: both;"></div>

					<?
			}
			?>
			</div>
			<?endforeach;?>
			<?else:?>
			<p><?=GetMessage(CSotbitReviews::iModuleID."_COMMENTS_NO_RESULTS")?></p>
						<?
		if($arParams['AJAX']=='Y')
		{
			$APPLICATION->IncludeComponent( "sotbit:reviews.comments.add", "default-answer", array(
					'TEXTBOX_MAXLENGTH' => $arParams['TEXTBOX_MAXLENGTH'],
					'ID_ELEMENT' => $arParams['ID_ELEMENT'],
					"PRIMARY_COLOR" => $arParams['PRIMARY_COLOR'],
					"BUTTON_BACKGROUND" => $arParams['BUTTON_BACKGROUND'],
					'AJAX' => $arParams["AJAX"],
					'PARENT' => $Comment['ID'],
					"NOTICE_EMAIL" => $arParams['NOTICE_EMAIL'],
					'CACHE_TIME' => $arParams["CACHE_TIME"],
					'CACHE_GROUPS' => $arParams["CACHE_GROUPS"]
			) );
		}
		?>
			<?endif;?>

		<?if($arResult['CNT_PAGES']>1):?>
			<div id="filter-pagination-comments"
			     data-url="<?=$APPLICATION->GetCurPage()?>"
			     data-id-element="<?=$arParams["ID_ELEMENT"]?>"
			     data-site-dir="<?=SITE_DIR?>"
			     data-primary-color="<?=$arParams['PRIMARY_COLOR']?>"
			     data-template="<?=$templateName?>"
			     data-date-format="<?=$arParams['DATE_FORMAT']?>"
			     data-cnt-left-pgn="<?=$arResult["CNT_LEFT_PGN"]?>"
			     data-cnt-right-pgn="<?=$arResult["CNT_RIGHT_PGN"]?>"
			     data-per-page="<?=COption::GetOptionString(CSotbitReviews::iModuleID, "COMMENTS_COUNT_PAGE_"
					 .SITE_ID, "10")?>"
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
			<div id="idsComments" style="display:none" data-site-dir="<?=SITE_DIR?>"><?=$arResult['COMMENTS_IDS']?></div>
</div>
<?$frame->end();?>