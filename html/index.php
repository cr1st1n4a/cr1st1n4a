<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
require __DIR__ . '/../app/helpers/settings.php';
require __DIR__ . '/../app/routes/routes.php';
$app->run();
