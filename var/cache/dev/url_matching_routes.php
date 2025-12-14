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
        '/api/appointment/create' => [[['_route' => 'api_appointment_create', '_controller' => 'App\\Controller\\AppointmentController::bookAppointment'], null, ['POST' => 0], null, false, false, null]],
        '/api/all-doctors/slots' => [[['_route' => 'all_doctors_slots', '_controller' => 'App\\Controller\\AppointmentSlotController::allDoctorsSlots'], null, null, null, false, false, null]],
        '/api/appointment/change' => [[['_route' => 'api_appointment_change', '_controller' => 'App\\Controller\\AppointmentSlotController::appointmentChange'], null, ['GET' => 0], null, false, false, null]],
        '/api/appointment/results' => [[['_route' => 'api_appointment_results', '_controller' => 'App\\Controller\\AppointmentSlotController::results'], null, ['GET' => 0], null, false, false, null]],
        '/api/centers/search' => [[['_route' => 'api_centers_search', '_controller' => 'App\\Controller\\CenterController::searchCentersWhat'], null, ['GET' => 0], null, false, false, null]],
        '/api/centers/map' => [[['_route' => 'api_centers_map', '_controller' => 'App\\Controller\\CenterController::getCentersForMap'], null, ['GET' => 0], null, false, false, null]],
        '/api/doctors/search' => [[['_route' => 'api_doctors_search', '_controller' => 'App\\Controller\\DoctorController::searchDoctors'], null, ['GET' => 0], null, false, false, null]],
        '/api/doctors/list' => [[['_route' => 'api_doctors_list', '_controller' => 'App\\Controller\\DoctorController::listDoctors'], null, ['GET' => 0], null, false, false, null]],
        '/api/doctors/results' => [[['_route' => 'api_doctors_results', '_controller' => 'App\\Controller\\DoctorController::resultsDoctors'], null, ['GET' => 0], null, false, false, null]],
        '/api/doctors/change' => [[['_route' => 'api_doctors_change', '_controller' => 'App\\Controller\\DoctorController::changeDoctor'], null, ['PUT' => 0], null, false, false, null]],
        '/api/doctors/add' => [[['_route' => 'api_doctors_add', '_controller' => 'App\\Controller\\DoctorController::addDoctor'], null, ['POST' => 0], null, false, false, null]],
        '/api/login' => [[['_route' => 'api_login', '_controller' => 'App\\Controller\\LoginController::login'], null, ['POST' => 0], null, false, false, null]],
        '/api/patient/medicalRecord' => [[['_route' => 'api_patient_medicalRecord', '_controller' => 'App\\Controller\\PatientController::me'], null, ['GET' => 0], null, false, false, null]],
        '/registration' => [[['_route' => 'registration', '_controller' => 'App\\Controller\\PatientController::registration'], null, ['POST' => 0], null, false, false, null]],
        '/api/medicalrecord/change' => [[['_route' => 'api_medicalrecord_change', '_controller' => 'App\\Controller\\PatientController::changeMedicalRecord'], null, ['PUT' => 0], null, false, false, null]],
        '/api/medicalrecord/delete' => [[['_route' => 'api_medicalRecord_delete', '_controller' => 'App\\Controller\\PatientController::deleteMedicalRecord'], null, ['DELETE' => 0], null, false, false, null]],
        '/api/prescription' => [[['_route' => 'api_prescription', '_controller' => 'App\\Controller\\PrescriptionController::getPrescription'], null, ['GET' => 0], null, false, false, null]],
        '/api/treatments/list' => [[['_route' => 'api_treatments_list', '_controller' => 'App\\Controller\\TreatmentController::listTreatments'], null, ['GET' => 0], null, false, false, null]],
        '/api/treatments/search' => [[['_route' => 'api_treatments_search', '_controller' => 'App\\Controller\\TreatmentController::searchTreatments'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/(?'
                    .'|doctor(?'
                        .'|/([^/]++)/slots(*:74)'
                        .'|s/delete/([^/]++)(*:98)'
                    .')'
                    .'|appointment/c(?'
                        .'|hange/([^/]++)(*:136)'
                        .'|ancel/([^/]++)(*:158)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        74 => [[['_route' => 'doctor_slots', '_controller' => 'App\\Controller\\AppointmentSlotController::slots'], ['id'], null, null, false, false, null]],
        98 => [[['_route' => 'api_doctor_delete', '_controller' => 'App\\Controller\\DoctorController::deleteDoctor'], ['id'], ['DELETE' => 0], null, false, true, null]],
        136 => [[['_route' => 'api_appointment_update', '_controller' => 'App\\Controller\\AppointmentSlotController::updateAppointment'], ['id'], ['PATCH' => 0], null, false, true, null]],
        158 => [
            [['_route' => 'api_appointment_cancel', '_controller' => 'App\\Controller\\AppointmentSlotController::cancelAppointment'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
