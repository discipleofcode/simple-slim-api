<?php

use src\controllers\APIController;
use src\services\PaymentMethodService;

// DIC configuration

$container = $app->getContainer();

$container['paymentMethodService'] = function ($container) {
    return new PaymentMethodService();
};

$container[APIController::class] = function ($c) {
    return new APIController($c->get('paymentMethodService'));
};

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
