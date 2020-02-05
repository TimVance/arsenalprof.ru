<?php
$arUrlRewrite=array (
  17 => 
  array (
    'CONDITION' => '#^/novinki/([\\.\\-\\_0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/novinki/index.php',
    'SORT' => 100,
  ),
  19 => 
  array (
    'CONDITION' => '#^/skidki/([\\.\\-\\_0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/skidki/index.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  18 => 
  array (
    'CONDITION' => '#^/hits/([\\.\\-\\_0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/hits/index.php',
    'SORT' => 100,
  ),
  37 => 
  array (
    'CONDITION' => '#^={SITE_DIR."b2bcabinet/order/"}#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.order',
    'PATH' => '/b2bcabinet/orders/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
  26 => 
  array (
    'CONDITION' => '#^/personal/b2b/blank_zakaza/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/personal/b2b/blank_zakaza/index.php',
    'SORT' => 100,
  ),
  35 => 
  array (
    'CONDITION' => '#^/b2bcabinet/personal/buyer/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.profile',
    'PATH' => '/b2bcabinet/personal/buyer/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  13 => 
  array (
    'CONDITION' => '#^/personal/b2b/documents/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/personal/b2b/documents/index.php',
    'SORT' => 100,
  ),
  38 => 
  array (
    'CONDITION' => '#^/b2bcabinet/documents/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/b2bcabinet/documents/index.php',
    'SORT' => 100,
  ),
  30 => 
  array (
    'CONDITION' => '#^/personal/b2b/order/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.order',
    'PATH' => '/personal/b2b/order/index.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  380 => 
  array (
    'CONDITION' => '#^/b2bcabinet/order/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.order',
    'PATH' => '/b2bcabinet/orders/index.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/stssync/calendar/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar/index.php',
    'SORT' => 100,
  ),
  28 => 
  array (
    'CONDITION' => '#^/articles/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/articles/index.php',
    'SORT' => 100,
  ),
  41 => 
  array (
    'CONDITION' => '#^/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  381 => 
  array (
    'CONDITION' => '#^/brands/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/brands/index.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/sales/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/sales/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
  31 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
);
