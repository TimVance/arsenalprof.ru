<?
use Bitrix\Main\Localization\Loc;
return array(
	'block' =>
		array(
			'name' => Loc::getMessage('LANDING_BLOCK_1_BIG_WITH_TEXT_2_NAME'),
			'section' => array('cover'),
		),
	'nodes' =>
		array(
			'.landing-block-title' =>
				array(
					'name' => Loc::getMessage('LANDING_BLOCK_1_BIG_WITH_TEXT_2_NODES_LANDINGBLOCKTITLE'),
					'type' => 'text',
				),
			'.landing-block-button' =>
				array(
					'name' => Loc::getMessage('LANDING_BLOCK_1_BIG_WITH_TEXT_2_NODES_LANDINGBLOCKBUTTON'),
					'type' => 'link',
				),
			'.landing-block-img' =>
				array(
					'name' => Loc::getMessage('LANDING_BLOCK_1_BIG_WITH_TEXT_2_NODES_LANDINGBLOCKIMG'),
					'type' => 'img',
					'dimensions' => array('width' => 1920, 'height' => 800),
				),
		),
	'style' => array(
		'block' => array(
			'type' => array('block-default-wo-background'),
		),
		'nodes' => array(
			'.landing-block-title' =>
				array(
					'name' => Loc::getMessage('LANDING_BLOCK_1_BIG_WITH_TEXT_2_STYLE_LANDINGBLOCKTITLE'),
					'type' => array('typo', 'animation'),
				),
			'.landing-block-button' =>
				array(
					'name' => Loc::getMessage('LANDING_BLOCK_1_BIG_WITH_TEXT_2_STYLE_LANDINGBLOCKBUTTON'),
					'type' => array('button', 'animation'),
				),
			'.landing-block-container' =>
				array(
					'name' => Loc::getMessage('LANDING_BLOCK_1_BIG_WITH_TEXT_2_STYLE_LANDINGBLOCKBUTTON'),
					'type' => array('text-align'),
				),
		),
	),
);
?>