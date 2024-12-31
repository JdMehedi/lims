<?php

return [
    'spg_settings_stack_holder' => array(
        'web_service_url' => env('spg_web_service_url'),
        'web_portal_url' => env('spg_web_portal_url'),
        'user_id' => env('spg_user_id'),
        'password' => env('spg_password'),
        'SBL_account' => env('spg_SBL_account'),
        'st_code' => env('st_code'),
        'request_id_prefix' => '010',
        'return_url' => env('PROJECT_ROOT') . '/spg/stack-holder/callback',
    ),

    'spg_settings' => array(
        'payment_mode' => env('payment_mode', 'on'),
        'web_service_url' => env('spg_web_service_url', 'https://spg.com.bd:6313/SpgService.asmx'),
        'spg_web_service_url' => env('spg_web_service_url', 'https://spgapi.sblesheba.com:6314/SpgService.asmx'),
        'web_portal_url' => env('spg_web_portal_url', 'https://spg.com.bd:6313/SpgRequest/PaymentByPortal'),
        'user_id' => env('spg_user_id', 'duUser2014'),
        'password' => env('spg_password', 'duUserPayment2014'),
        'SBL_account' => env('spg_SBL_account', '0002634313655'),
        'st_code' => env('st_code', 'OSS-Framework'),
        'request_id_prefix' => env('spg_request_id_prefix', '010'),
        'return_url' => env('PROJECT_ROOT') . env('spg_callback_url', '/spg/callback'),
        'return_url_m' => env('PROJECT_ROOT') . env('spg_callback_url_m', '/spg/callbackM'),
        'single_details_url' => env('single_details_url', 'https://spg.com.bd:6313/api/SpgService/TransactionDetails')
    ),
    'online_payment' => true
];
