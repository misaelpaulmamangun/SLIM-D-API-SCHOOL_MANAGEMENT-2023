<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Configs\SettingConfig;
// use App\Middlewares\JwtMiddleware;
use App\Services\EloquentService;
use App\Services\UtilityService;


$config = SettingConfig::getConfig();
$app = new \Slim\App($config);
$container = $app->getContainer();

// Create an instance of Dependencies class and configure the dependencies
$db = new EloquentService($container->get('settings')['db']);
$dependencies = new UtilityService($container);
$dependencies->configure();

// Middleware
// $app->add(new JwtMiddleware($container));

// Routes
require __DIR__ . '/routes/auth.php';
require __DIR__ . '/routes/api.php';
