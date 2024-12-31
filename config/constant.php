<?php
return [
    'submit_limit' => 1,
    'request_limit' => 5,
    'username' => 'apiuser',
    'password' => 'a2i@a2i',
    'key' => 'ocpl@#bd@ocpl',
    'support_callback_api' => 'http://localhost:8000/',
    'access' => array(
        'enothi' => array(
            'username' => 'btrclims',
            'password' => ')#enothi#@##btrclims#',
            'privilege' => array('enothi'),
        ),
    ),
    // d-nothi integration live credentials
//    'doptor_token_url' => 'https://n-doptor-api.nothi.gov.bd/api/client/login',
//    'doptor_client_id' => 'KYTTH9',
//    'doptor_user_name' => '300000016764',
//    'doptor_password' => 'F2JV9X2P',
//    'doptor_apikey' => '26WUU9',
//
//    'doptor_office_unit_url' => 'https://n-doptor-api.nothi.gov.bd/api/v1/officeunitorganogram?office',
//    'nothi_nifi_submission_url' => 'https://nifi-btrc.oss.net.bd/btrc_lims',
//    'nothi_nifi_token' => 'YWRtaW46YnRyY2xpbXMxMjM0NQ==',
//    'nothi_nifi_office_id' => '4714',
//    'nothi_service_id' => '1122',
//    'btrc_api_client' => 'btrclimsmzu',


// local credentials

    'DOPTOR_TOKEN_URL' => env('DOPTOR_TOKEN_URL'),
    'DOPTOR_CLIENT_ID' => env('DOPTOR_CLIENT_ID'),
    'DOPTOR_USER_NAME' => env('DOPTOR_USER_NAME'),
    'DOPTOR_PASSWORD' => env('DOPTOR_PASSWORD'),
    'DOPTOR_APIKEY' => env('DOPTOR_APIKEY'),

    'DOPTOR_OFFICE_UNIT_URL' => env('DOPTOR_OFFICE_UNIT_URL'),
    'NOTHI_NIFI_SUBMISSION_URL' => env('NOTHI_NIFI_SUBMISSION_URL'),
    'NOTHI_NIFI_TOKEN' => env('NOTHI_NIFI_TOKEN'),
    'NOTHI_NIFI_OFFICE_ID' => env('NOTHI_NIFI_OFFICE_ID'),
    'NOTHI_SERVICE_ID' => env('NOTHI_SERVICE_ID'),
    'BTRC_API_CLIENT' => env('BTRC_API_CLIENT'),
    // nothi shortfall api request
    'password' => '123456a@',
    'username' => 'nothi_btrc_lims',


    // RJSC integration
    'RJSC_URL' => env('RJSC_URL'),
    'RJSC_AID' => env('RJSC_AID'),
    'RJSC_SID' => env('RJSC_SID'),


//    'RJSC_URL' => 'https://app.roc.gov.bd/psp/com_validate_entity_land_v1',
//    'RJSC_AID' => 'EYUIM74BF3UIORBBGFY',
//    'RJSC_SID' => 'UIOMWAIOPKLEOMBG',
//    'rjsc_in_corp_number' => 'c-86323',
//    'rjsc_in_corp_date' => '09/08/2010',

    'rjsc_in_corp_number' => 'gfdsfhdf',
    'rjsc_in_corp_date' => '2023-03-07',
//
//    // d-nothi generate token

//    'office_id' => '4714',
//    'doptor_username' => 'W7UK71',
//    'doptor_password' =>'YYROG0F7',
//    'doptor_client_id' =>'W7UK71',
////    'doptor_token_url' => 'https://api-stage.doptor.gov.bd/api/client/login',
//    'doptop_desk_organogram_url' => 'https://api-stage.doptor.gov.bd/api/office/unit-designation-employee-map',
//
//    'enothi_username' => 'lims-btrc',
//    'enothi_password' => 'l@b#trc&im$1!',
//    'btrc_api_client' => 'btrc_lims_1234mju',
//    'enothi_token_url' => 'http://training.nothi.gov.bd/apiAccess',
//    'enothi_submission_url' => 'http://training.nothi.gov.bd/apiOnlineApplication',
//    'nothi_nifi_submission_url' => 'https://nifi-btrc.oss.net.bd/uat_btrc_lims',
//    'nothi_nifi_token' => 'YWRtaW46YnRyY2xpbXMxMjM0NQ==',
//    'nothi_nifi_office_id' => '4714',
//    'nothi_service_id' => '1122',
//
//
//
//    // doptor credentials
//    'doptor_token_url' => 'https://apigw-stage.doptor.gov.bd/api/client/login',
////    'doptor_token_url' => 'https://apigw-stage.doptor.gov.bd/api/v1/office',
//    'doptor_client_id' => '26WUU9',
//    'doptor_user_name' => '300000016764',
//    'doptor_password' => 'R5Y4UCOI',
//    'doptor_apikey' => '26WUU9',
//    // get office unit
//    'doptor_office_unit_url' => 'https://apigw-stage.doptor.gov.bd/api/v1/officeunitorganogram?office',
];
