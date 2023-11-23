<?php

namespace App\Configs;

class SettingConfig
{
  public static function getConfig()
  {
    return [
      'settings' => [
        'logErrors' => true,
        'logErrorDetails' => true,
        'displayErrorDetails' => true,

        'db' => [
          'driver' => 'sqlite',
          'database' => __DIR__ . '/../../database/index.db',
        ],

        'jwt' => [
          'key' => 'clRYGpjy4wWIKffjQ2oCSBBUYXMEBw7A',
        ],
      ],
    ];
  }
}
