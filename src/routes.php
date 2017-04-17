<?php

use src\controllers\APIController;

// Routes

/**
 * API group
 */
$app->group('/api', function () use ($app) {
    /**
     * Version group (we will assume that there will be more versions in the future)
     */
    $app->group('/v1', function () use ($app) {
        $app->get('/payments', APIController::class . ':getPayments');
        $app->get('/payments/{id}', APIController::class . ':getPayments');
        $app->post('/payments', APIController::class . ':addPayment');
        $app->put('/payments/{id}', APIController::class . ':updatePayment');
        $app->delete('/payments/{id}', APIController::class . ':deletePayment');
    });
});
