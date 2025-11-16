<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/healthnorth/api/patients' => [[['_route' => 'patients', '_controller' => 'App\\Controller\\HealthNorthController::getPatientList'], null, ['GET' => 0], null, false, false, null]],
        '/healthnorth/api/payments' => [[['_route' => 'createPayment', '_controller' => 'App\\Controller\\HealthNorthController::createPayment'], null, ['POST' => 0], null, false, false, null]],
        '/api/login' => [[['_route' => 'api_login', '_controller' => 'App\\Controller\\LoginController::login'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/healthnorth/api/pa(?'
                    .'|tient(?'
                        .'|s/([^/]++)(*:82)'
                        .'|/([^/]++)(*:98)'
                    .')'
                    .'|yments/([^/]++)(?'
                        .'|(*:124)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        82 => [[['_route' => 'patient', '_controller' => 'App\\Controller\\HealthNorthController::getPatient'], ['id'], ['GET' => 0], null, false, true, null]],
        98 => [[['_route' => 'patientName', '_controller' => 'App\\Controller\\HealthNorthController::getPatientName'], ['id'], ['GET' => 0], null, false, true, null]],
        124 => [
            [['_route' => 'payment', '_controller' => 'App\\Controller\\HealthNorthController::getPayment'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'deletePayment', '_controller' => 'App\\Controller\\HealthNorthController::deletePayment'], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'updatePayment', '_controller' => 'App\\Controller\\HealthNorthController::updatePayment'], ['id'], ['PUT' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
