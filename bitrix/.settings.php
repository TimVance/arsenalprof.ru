<?php
return array (
  'pull_s1' => 'BEGIN GENERATED PUSH SETTINGS. DON\'T DELETE COMMENT!!!!',
  'pull' => 
  array (
    'value' => 
    array (
      'path_to_listener' => 'http://#DOMAIN#/bitrix/sub/',
      'path_to_listener_secure' => 'https://#DOMAIN#/bitrix/sub/',
      'path_to_modern_listener' => 'http://#DOMAIN#/bitrix/sub/',
      'path_to_modern_listener_secure' => 'https://#DOMAIN#/bitrix/sub/',
      'path_to_mobile_listener' => 'http://#DOMAIN#:8893/bitrix/sub/',
      'path_to_mobile_listener_secure' => 'https://#DOMAIN#:8894/bitrix/sub/',
      'path_to_websocket' => 'ws://#DOMAIN#/bitrix/subws/',
      'path_to_websocket_secure' => 'wss://#DOMAIN#/bitrix/subws/',
      'path_to_publish' => 'http://135082.local:8895/bitrix/pub/',
      'path_to_publish_web' => 'http://#DOMAIN#/bitrix/rest/',
      'path_to_publish_web_secure' => 'https://#DOMAIN#/bitrix/rest/',
      'nginx_version' => '4',
      'nginx_command_per_hit' => '100',
      'nginx' => 'Y',
      'nginx_headers' => 'N',
      'push' => 'Y',
      'websocket' => 'Y',
      'signature_key' => 'FjqgGge3QrMw4KTderguVx5CDH4VQ3mtyvKQmk8vafbyT6VF40C1wZLtMx9DTtH7aLIaCVSEDS2Ai8O6u1DYO16doolC0TtQkGPXqN56d5sKrIVO8Ht0Rpm3FZ6IPN7Q',
      'signature_algo' => 'sha1',
      'guest' => 'N',
    ),
  ),
  'pull_e1' => 'END GENERATED PUSH SETTINGS. DON\'T DELETE COMMENT!!!!',
  'utf_mode' => 
  array (
    'value' => true,
    'readonly' => true,
  ),
  'cache_flags' => 
  array (
    'value' => 
    array (
      'config_options' => 3600,
      'site_domain' => 3600,
    ),
    'readonly' => false,
  ),
  'cookies' => 
  array (
    'value' => 
    array (
      'secure' => false,
      'http_only' => true,
    ),
    'readonly' => false,
  ),
  'exception_handling' => 
  array (
    'value' => 
    array (
      'debug' => true,
      'handled_errors_types' => 4437,
      'exception_errors_types' => 4437,
      'ignore_silence' => false,
      'assertion_throws_exception' => true,
      'assertion_error_type' => 256,
      'log' => 
      array (
        'settings' => 
        array (
          'file' => '/var/log/php/exceptions.log',
          'log_size' => 1000000,
        ),
      ),
    ),
    'readonly' => false,
  ),
  'connections' => 
  array (
    'value' => 
    array (
      'default' => 
      array (
        'className' => '\\Bitrix\\Main\\DB\\MysqliConnection',
        'host' => 'localhost',
        'database' => 'arsenal',
        'login' => 'arsenal',
        'password' => '@G1#b0#S1&c6',
        'options' => 2,
      ),
    ),
    'readonly' => true,
  ),
);
