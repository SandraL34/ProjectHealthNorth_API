<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/appointment/coming' => [[['_route' => 'api_appointment_coming', '_controller' => 'App\\Controller\\AppointmentController::comingAppointment'], null, ['GET' => 0], null, false, false, null]],
        '/api/appointment/past' => [[['_route' => 'api_appointment_past', '_controller' => 'App\\Controller\\AppointmentController::pastAppointment'], null, ['GET' => 0], null, false, false, null]],
        '/api/centers/search' => [[['_route' => 'api_centers_search', '_controller' => 'App\\Controller\\CenterController::searchCentersWhat'], null, ['GET' => 0], null, false, false, null]],
        '/api/doctors/search' => [[['_route' => 'api_doctors_search', '_controller' => 'App\\Controller\\DoctorController::searchDoctors'], null, ['GET' => 0], null, false, false, null]],
        '/api/login' => [[['_route' => 'api_login', '_controller' => 'App\\Controller\\LoginController::login'], null, ['POST' => 0], null, false, false, null]],
        '/api/patient/medicalRecord' => [[['_route' => 'api_patient_medicalRecord', '_controller' => 'App\\Controller\\PatientController::me'], null, ['GET' => 0], null, false, false, null]],
        '/api/prescription' => [[['_route' => 'api_prescription', '_controller' => 'App\\Controller\\PrescriptionController::getPrescription'], null, ['GET' => 0], null, false, false, null]],
        '/api/treatments/list' => [[['_route' => 'api_treatments_list', '_controller' => 'App\\Controller\\TreatmentController::listTreatments'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [
            [['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
