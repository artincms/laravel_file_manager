<?php

return [

    /* Important Settings */
    'private_middlewares' => ['web'],
    'public_middlewares' => ['web'],
    // you can change default route from sms-admin to anything you want
    'private_route_prefix' => 'LFM',
    'public_route_prefix' => 'LFM',
    // SMS.ir Api Key
    'api-key' => env('SMSIR-API-KEY','Your api key'),
    // SMS.ir Secret Key
    'secret-key' => env('SMSIR-SECRET-KEY','Your secret key'),
    // Your sms.ir line number
    'line-number' => env('SMSIR-LINE-NUMBER','Your Sms.ir Line Number'),
    // ======================================================================
    // set true if you want log to the database
    'db-log' => true,
    'allowed' =>['application/zip', 'application/pdf', 'image/jpeg', 'image/png', 'image/x-icon', 'application/x-rar', 'application/vnd.ms-word.document.macroenabled.12','application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-excel.sheet.binary.macroenabled.12','application/vnd.ms-excel.sheet.macroenabled.12','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/plain'] ,
    'allowed_pic' => ['image/jpeg', 'image/png'] ,
    'crop_type' => ['large', 'medium', 'small','orginal'],
    'size_large' =>['height' =>1024 ,'width' =>768],
    'size_small' =>['height' =>347 ,'width' =>247 ],
    'size_medium' =>['height' =>800 ,'width' =>600 ],
    'crop_chose' => 'fit' ,
    'driver_disk' => 'local' ,
    'user_model' => 'App\User' ,
    /* Admin Panel Title */
    'title' => 'مدیریت پیامک ها',
    // How many log you want to show in sms-admin panel ?
    'in-page' => '15' ,
    //optomise Image
    'Optimise_image' => false
];