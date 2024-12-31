<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OSS-PID Configuration
    |--------------------------------------------------------------------------
    */

    'osspid_client_id' => env('osspid_client_id', 'e6bd4638df1f541a4a7136d7299b66c0bdcb7687'),
    'osspid_client_secret_key' => env('osspid_client_secret_key', 'd61a150b84ec6e7668be7e3d7d9c6b6401093d0f'),
    'osspid_base_url' => env('osspid_base_url', 'https://osspid.org'),
    'osspid_base_url_ip' => env('osspid_base_url_ip', 'https://osspid.org'),

    /*

   |--------------------------------------------------------------------------
   | OSS-PID LOG Configuration
   |--------------------------------------------------------------------------
   */

    'osspid_log_grant_type' => env('osspid_log_grant_type', 'client_credentials'),
    'osspid_log_my_client_id' => env('osspid_log_client_id', 'osspid-logger-service-bida-client'),
    'osspid_log_my_secret_key' => env('osspid_log_my_secret_key', '097c270d-51ca-49e1-b0c4-4bf1aeff2377'),
    'osspid_log_content_type' => env('osspid_log_content_type', 'application/x-www-form-urlencoded'),
    'osspid_log_token_url' => env('osspid_log_token_url', 'https://idp.oss.net.bd/auth/realms/dev/protocol/openid-connect/token'),
    'osspid_log_data_url' => env('osspid_log_data_url', 'https://osspid-loger.oss.net.bd/osspid-service/request-for-service-history'),

];
