<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/alarms' => [[['_route' => 'api_alarms', '_controller' => 'App\\Controller\\AlarmController::getAlarms'], null, ['GET' => 0], null, false, false, null]],
        '/api/appointment/coming' => [[['_route' => 'api_appointment_coming', '_controller' => 'App\\Controller\\AppointmentController::comingAppointment'], null, ['GET' => 0], null, false, false, null]],
        '/api/appointment/past' => [[['_route' => 'api_appointment_past', '_controller' => 'App\\Controller\\AppointmentController::pastAppointment'], null, ['GET' => 0], null, false, false, null]],
        '/api/appointment/create' => [[['_route' => 'api_appointment_create', '_controller' => 'App\\Controller\\AppointmentController::bookAppointment'], null, ['POST' => 0], null, false, false, null]],
        '/api/all-doctors/slots' => [[['_route' => 'all_doctors_slots', '_controller' => 'App\\Controller\\AppointmentSlotController::allDoctorsSlots'], null, null, null, false, false, null]],
        '/api/centers/search' => [[['_route' => 'api_centers_search', '_controller' => 'App\\Controller\\CenterController::searchCentersWhat'], null, ['GET' => 0], null, false, false, null]],
        '/api/centers/map' => [[['_route' => 'api_centers_map', '_controller' => 'App\\Controller\\CenterController::getCentersForMap'], null, ['GET' => 0], null, false, false, null]],
        '/api/centers/change' => [[['_route' => 'api_centers_change', '_controller' => 'App\\Controller\\CenterController::changeCenter'], null, ['PUT' => 0], null, false, false, null]],
        '/api/centers/add' => [[['_route' => 'api_centers_add', '_controller' => 'App\\Controller\\CenterController::addCenter'], null, ['POST' => 0], null, false, false, null]],
        '/api/doctors/search' => [[['_route' => 'api_doctors_search', '_controller' => 'App\\Controller\\DoctorController::searchDoctors'], null, ['GET' => 0], null, false, false, null]],
        '/api/doctors/list' => [[['_route' => 'api_doctors_list', '_controller' => 'App\\Controller\\DoctorController::listDoctors'], null, ['GET' => 0], null, false, false, null]],
        '/api/doctors/results' => [[['_route' => 'api_doctors_results', '_controller' => 'App\\Controller\\DoctorController::resultsDoctors'], null, ['GET' => 0], null, false, false, null]],
        '/api/doctors/change' => [[['_route' => 'api_doctors_change', '_controller' => 'App\\Controller\\DoctorController::changeDoctor'], null, ['PUT' => 0], null, false, false, null]],
        '/api/doctors/add' => [[['_route' => 'api_doctors_add', '_controller' => 'App\\Controller\\DoctorController::addDoctor'], null, ['POST' => 0], null, false, false, null]],
        '/api/documents/upload' => [[['_route' => 'api_document_upload', '_controller' => 'App\\Controller\\DocumentController::upload'], null, ['POST' => 0], null, false, false, null]],
        '/api/documents' => [[['_route' => 'api_document_list_me', '_controller' => 'App\\Controller\\DocumentController::listMyDocuments'], null, ['GET' => 0], null, false, false, null]],
        '/api/invoices' => [[['_route' => 'api_invoices_list', '_controller' => 'App\\Controller\\InvoiceController::list'], null, ['GET' => 0], null, false, false, null]],
        '/api/login' => [[['_route' => 'api_login', '_controller' => 'App\\Controller\\LoginController::login'], null, ['POST' => 0], null, false, false, null]],
        '/api/patients/search' => [[['_route' => 'api_patients_search', '_controller' => 'App\\Controller\\PatientController::searchPatients'], null, ['GET' => 0], null, false, false, null]],
        '/api/patient/medicalRecord' => [[['_route' => 'api_patient_medicalRecord', '_controller' => 'App\\Controller\\PatientController::me'], null, ['GET' => 0], null, false, false, null]],
        '/registration' => [[['_route' => 'registration', '_controller' => 'App\\Controller\\PatientController::registration'], null, ['POST' => 0], null, false, false, null]],
        '/api/medicalrecord/change' => [[['_route' => 'api_medicalrecord_change', '_controller' => 'App\\Controller\\PatientController::changeMedicalRecord'], null, ['PUT' => 0], null, false, false, null]],
        '/api/medicalrecord/delete' => [[['_route' => 'api_medicalRecord_delete', '_controller' => 'App\\Controller\\PatientController::deleteMedicalRecord'], null, ['DELETE' => 0], null, false, false, null]],
        '/api/prescription' => [[['_route' => 'api_prescription', '_controller' => 'App\\Controller\\PrescriptionController::getPrescription'], null, ['GET' => 0], null, false, false, null]],
        '/api/treatments/list' => [[['_route' => 'api_treatments_list', '_controller' => 'App\\Controller\\TreatmentController::listTreatments'], null, ['GET' => 0], null, false, false, null]],
        '/api/treatments/search' => [[['_route' => 'api_treatments_search', '_controller' => 'App\\Controller\\TreatmentController::searchTreatments'], null, ['GET' => 0], null, false, false, null]],
        '/api/treatments/change' => [[['_route' => 'api_treatments_change', '_controller' => 'App\\Controller\\TreatmentController::changeTreatment'], null, ['PUT' => 0], null, false, false, null]],
        '/api/treatments/add' => [[['_route' => 'api_treatments_add', '_controller' => 'App\\Controller\\TreatmentController::addTreatment'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/api/(?'
                    .'|a(?'
                        .'|larms/(?'
                            .'|([^/]++)(*:36)'
                            .'|add(*:46)'
                            .'|([^/]++)(*:61)'
                        .')'
                        .'|ppointment(?'
                            .'|/(?'
                                .'|([^/]++)(*:94)'
                                .'|c(?'
                                    .'|hange/([^/]++)(*:119)'
                                    .'|ancel/([^/]++)(*:141)'
                                .')'
                                .'|results(*:157)'
                            .')'
                            .'|s/([^/]++)(*:176)'
                        .')'
                    .')'
                    .'|doc(?'
                        .'|tor(?'
                            .'|/([^/]++)/slots(*:213)'
                            .'|s/delete/([^/]++)(*:238)'
                        .')'
                        .'|uments/(?'
                            .'|patient/([^/]++)(*:273)'
                            .'|([^/]++)(?'
                                .'|/download(*:301)'
                                .'|(*:309)'
                            .')'
                        .')'
                    .')'
                    .'|centers/delete/([^/]++)(*:343)'
                    .'|invoices/([^/]++)/pay(*:372)'
                    .'|treatments/delete/([^/]++)(*:406)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        36 => [[['_route' => 'api_alarms_change', '_controller' => 'App\\Controller\\AlarmController::changeAlarm'], ['id'], ['PUT' => 0], null, false, true, null]],
        46 => [[['_route' => 'api_alarms_add', '_controller' => 'App\\Controller\\AlarmController::addAlarm'], [], ['POST' => 0], null, false, false, null]],
        61 => [[['_route' => 'api_alarms_delete', '_controller' => 'App\\Controller\\AlarmController::deleteAlarm'], ['id'], ['DELETE' => 0], null, false, true, null]],
        94 => [[['_route' => 'api_appointment_update', '_controller' => 'App\\Controller\\AppointmentController::updateAppointment'], ['id'], ['PATCH' => 0], null, false, true, null]],
        119 => [[['_route' => 'api_appointment_slot_update', '_controller' => 'App\\Controller\\AppointmentSlotController::updateAppointment'], ['id'], ['PATCH' => 0], null, false, true, null]],
        141 => [[['_route' => 'api_appointment_cancel', '_controller' => 'App\\Controller\\AppointmentSlotController::cancelAppointment'], ['id'], ['DELETE' => 0], null, false, true, null]],
        157 => [[['_route' => 'api_appointment_results', '_controller' => 'App\\Controller\\AppointmentSlotController::results'], [], ['GET' => 0], null, false, false, null]],
        176 => [[['_route' => 'api_appointment_get', '_controller' => 'App\\Controller\\AppointmentController::getAppointment'], ['id'], ['GET' => 0], null, false, true, null]],
        213 => [[['_route' => 'doctor_slots', '_controller' => 'App\\Controller\\AppointmentSlotController::slots'], ['id'], null, null, false, false, null]],
        238 => [[['_route' => 'api_doctor_delete', '_controller' => 'App\\Controller\\DoctorController::deleteDoctor'], ['id'], ['DELETE' => 0], null, false, true, null]],
        273 => [[['_route' => 'api_document_list', '_controller' => 'App\\Controller\\DocumentController::listForPatient'], ['id'], ['GET' => 0], null, false, true, null]],
        301 => [[['_route' => 'api_document_download', '_controller' => 'App\\Controller\\DocumentController::download'], ['id'], ['GET' => 0], null, false, false, null]],
        309 => [[['_route' => 'api_document_delete', '_controller' => 'App\\Controller\\DocumentController::delete'], ['id'], ['DELETE' => 0], null, false, true, null]],
        343 => [[['_route' => 'api_centers_delete', '_controller' => 'App\\Controller\\CenterController::deleteDoctor'], ['id'], ['DELETE' => 0], null, false, true, null]],
        372 => [[['_route' => 'api_invoice_pay', '_controller' => 'App\\Controller\\InvoiceController::markAsPaid'], ['id'], ['PATCH' => 0], null, false, false, null]],
        406 => [
            [['_route' => 'api_treatments_delete', '_controller' => 'App\\Controller\\TreatmentController::deleteDoctor'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
