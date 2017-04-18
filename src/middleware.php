<?php
// Application middleware

use src\middlewares\AuthTokenMiddleware;

$app->add(new AuthTokenMiddleware($settings));