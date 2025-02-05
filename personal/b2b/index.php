<?
use Bitrix\Main\Loader;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Мой кабинет");
if(!$USER->IsAuthorized())
{
	?>
	<div class="personal_block_title personal_block_title_personal">
		<h1 class="text"><?
			$APPLICATION->ShowTitle(false); ?></h1>
	</div>
	<?php
	$APPLICATION->AuthForm('', false, false, 'N', false);
}
else
{
	if(Loader::includeModule('sotbit.cabinet') && Loader::includeModule('sotbit.b2bshop'))
	{
		$opt = new \Sotbit\B2BShop\Client\Shop\Opt();
		$menu = new \Sotbit\B2BShop\Client\Personal\Menu();
		?>
		<div class="sotbit_cabinet_wrapper <?= (!$opt->hasAccess()) ? 'sotbit-cabinet-access-no' : 'personal-wrapper-access' ?>">
			<div class="personal_block_title">
				<h1 class="text"><?=$APPLICATION->ShowTitle(false)?></h1>
			</div>
			<?
			$Template = new \Sotbit\B2BShop\Client\Template\Main();
			$Template->includeBlock('template/personal/tabs.php');
			?>
			<div class="row sm-margin-no personal-widget-content">
				<?php
				$Template->includeBlock('template/personal/leftblock.php');
				?>
				<div class="col-sm-19 sm-padding-right-no blank_right-side <?= (!$menu->isOpen()) ? 'blank_right-side_full' : '' ?>"
					 id="blank_right_side">
					<div id="wrapper_blank_resizer" class="wrapper_blank_resizer">
						<div class="blank_resizer">
							<div class="blank_resizer_tool <?= (!$menu->isOpen()) ? 'blank_resizer_tool_open': '' ?>"></div>
						</div>
						<?
						$APPLICATION->IncludeComponent(
	"bitrix:desktop", 
	"sotbit_cabinet", 
	array(
		"CAN_EDIT" => "N",
		"COLUMNS" => "2",
		"COLUMN_WIDTH_0" => "50%",
		"COLUMN_WIDTH_1" => "50%",
		"COLUMN_WIDTH_2" => "33%",
		"GADGETS" => array(
			0 => "BLANK",
			1 => "HTML_AREA",
			2 => "FAVORITES",
			3 => "REVIEWS",
			4 => "BASKET",
			5 => "BUYERS",
			6 => "DELAYBASKET",
			7 => "PROFILE",
			8 => "SUBSCRIBE",
			9 => "WEATHER",
			10 => "ORDERS",
			11 => "BUYORDER",
		),
		"GU_ACCOUNTPAY_TITLE_STD" => "",
		"GU_BASKET_TITLE_STD" => "",
		"GU_BLANK_TITLE_STD" => "",
		"GU_DELAYBASKET_TITLE_STD" => "",
		"GU_FAVORITES_TITLE_STD" => "",
		"GU_HTML_AREA_TITLE_STD" => "",
		"GU_ORDERS_LIMIT" => "2",
		"GU_ORDERS_STATUS" => "ALL",
		"GU_ORDERS_TITLE_STD" => "",
		"GU_PROBKI_CITY" => "c213",
		"GU_PROBKI_TITLE_STD" => "",
		"GU_PROFILE_TITLE_STD" => "",
		"GU_REVIEWS_TITLE_STD" => "",
		"GU_RSSREADER_CNT" => "10",
		"GU_RSSREADER_IS_HTML" => "N",
		"GU_RSSREADER_RSS_URL" => "",
		"GU_RSSREADER_TITLE_STD" => "",
		"GU_SUBSCRIBE_TITLE_STD" => "",
		"GU_WEATHER_CITY" => "c213",
		"GU_WEATHER_COUNTRY" => "Россия",
		"GU_WEATHER_TITLE_STD" => "",
		"G_ACCOUNTPAY_PATH_TO_BASKET" => COption::GetOptionString("sotbit.b2bshop","URL_CART",""),
		"G_ACCOUNTPAY_PATH_TO_PAYMENT" => SITE_DIR."personal/b2b/order/payment/",
		"G_ACCOUNTPAY_PERSON_TYPE_ID" => COption::GetOptionString("sotbit.b2bshop","PERSONAL_PERSON_TYPE",""),
		"G_BASKET_PATH_TO_BASKET" => COption::GetOptionString("sotbit.b2bshop","URL_CART",""),
		"G_BLANK_INIT_JQUERY" => "N",
		"G_BLANK_PATH_TO_BLANK" => SITE_DIR."personal/b2b/blank_zakaza/",
		"G_BUYERS_PATH_TO_BUYER_DETAIL" => SITE_DIR."personal/b2b/profile/buyer/?id=#ID#",
		"G_BUYORDER_ORG_PROP" => array(
		),
		"G_BUYORDER_PATH_TO_ORDER_DETAIL" => SITE_DIR."personal/b2b/order/detail/#ID#/",
		"G_BUYORDER_PATH_TO_PAY" => SITE_DIR."personal/order/payment/",
		"G_DISCOUNT_ID_DISCOUNT" => "1",
		"G_DISCOUNT_PATH_TO_PAGE" => "",
		"G_DELAYBASKET_PATH_TO_BASKET" => SITE_DIR."personal/cart/?delay=1",
		"G_ORDERS_PATH_TO_ORDERS" => SITE_DIR."personal/b2b/order/",
		"G_ORDERS_PATH_TO_ORDER_DETAIL" => SITE_DIR."personal/b2b/order/detail/#ID#/",
		"G_PROBKI_CACHE_TIME" => "3600",
		"G_PROBKI_SHOW_URL" => "N",
		"G_PROFILE_PATH_TO_PROFILE" => SITE_DIR."personal/b2b/profile/",
		"G_REVIEWS_MAX_RATING" => "5",
		"G_REVIEWS_PATH_TO_REVIEWS" => SITE_DIR."personal/reviews/",
		"G_RSSREADER_CACHE_TIME" => "3600",
		"G_RSSREADER_PREDEFINED_RSS" => "",
		"G_RSSREADER_SHOW_URL" => "N",
		"G_SUBSCRIBE_PATH_TO_SUBSCRIBES" => SITE_DIR."personal/subscribe/",
		"G_WEATHER_CACHE_TIME" => "3600",
		"G_WEATHER_SHOW_URL" => "N",
		"ID" => "holder2",
		"COMPONENT_TEMPLATE" => "sotbit_cabinet",
		"GU_REVIEWS_CNT" => "1",
		"GU_REVIEWS_TYPE" => "ALL",
		"GU_BUYERS_TITLE_STD" => "",
		"GU_BUYORDER_TITLE_STD" => ""
	),
	false
);
						?>
					</div>
				</div>
			</div>
		</div>
		<?
	}
	else
	{
		$APPLICATION->IncludeComponent(
			"bitrix:menu",
			"ms_personal",
			array(
				"ROOT_MENU_TYPE" => "personal",
				"MAX_LEVEL" => "2",
				"CHILD_MENU_TYPE" => "personal_inner",
				"USE_EXT" => "Y",
				"DELAY" => "N",
				"ALLOW_MULTI_SELECT" => "N",
				"MENU_CACHE_TYPE" => "A",
				"MENU_CACHE_TIME" => "3600",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"MENU_CACHE_GET_VARS" => array(),
				"MENU_THEME" => "site",
				"DISPLAY_USER_NANE" => "Y",
				"PROFILE_URL" => "/personal/"
			),
			false
		);


		$APPLICATION->IncludeComponent(
			"bitrix:main.profile",
			"ms_profile_watch",
			array(
				"SET_TITLE" => "Y",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"USER_PROPERTY" => array(),
				"SEND_INFO" => "N",
				"CHECK_RIGHTS" => "N",
				"USER_PROPERTY_NAME" => "",
				"AJAX_OPTION_ADDITIONAL" => ""
			),
			false
		);
	}
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>