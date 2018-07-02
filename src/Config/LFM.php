<?php

return [

    /* Important Settings */
    'private_middlewares' => ['web'],
    'upload_middlewares' => ['web'],
    'public_middlewares' => ['web'],
    // you can change default route from sms-admin to anything you want
    'private_route_prefix' => 'LFM',
    'public_route_prefix' => 'LFM',
    'upload_route_prefix' => 'UFM',
    // SMS.ir Api Key
    'api-key' => env('SMSIR-API-KEY','Your api key'),
    // ======================================================================
    //allow user to upload private file in filemanager

    'allow_upload_private_file' => true ,
    // set true if you want log to the database
    'db-log' => true,
    'allowed' =>['application/zip', 'application/pdf', 'image/jpeg', 'image/png', 'image/x-icon', 'application/x-rar', 'application/vnd.ms-word.document.macroenabled.12','application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-excel.sheet.binary.macroenabled.12','application/vnd.ms-excel.sheet.macroenabled.12','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/plain','audio/mpeg'] ,
    'allowed_pic' => ['image/jpeg', 'image/png'] ,
    'crop_type' => ['large', 'medium', 'small','orginal'],
    'size_large' =>['height' =>1024 ,'width' =>768],
    'size_small' =>['height' =>347 ,'width' =>247 ],
    'size_medium' =>['height' =>800 ,'width' =>600 ],
    'crop_chose' => 'fit' ,
    'driver_disk' => 'local' ,
    'driver_disk_upload' => 'local' ,
    'user_model' => 'App\User' ,
    //optomise Image
    'Optimise_image' => false,
    'symlink_public_folder_name' => 'public',
    'insertde_view_theme' =>'bootstrap_v4', //you can select inline or bootstrap_v3 or bootstrap_v4
     'lang_rtl'    => [
        'ae',	/* Avestan */
        'ar',   /* 'العربية', Arabic */
        'arc',  /* Aramaic */
        'bcc',  /* 'بلوچی مکرانی', Southern Balochi */
        'bqi',  /* 'بختياري', Bakthiari */
        'ckb',  /* 'Soranî / کوردی', Sorani */
        'dv',   /* Dhivehi */
        'fa',   /* 'فارسی', Persian */
        'glk',  /* 'گیلکی', Gilaki */
        'he',   /* 'עברית', Hebrew */
        'ku',   /* 'Kurdî / كوردی', Kurdish */
        'mzn',  /* 'مازِرونی', Mazanderani */
        'nqo',  /* N'Ko */
        'pnb',  /* 'پنجابی', Western Punjabi */
        'ps',   /* 'پښتو', Pashto, */
        'sd',   /* 'سنڌي', Sindhi */
        'ug',   /* 'Uyghurche / ئۇيغۇرچە', Uyghur */
        'ur',   /* 'اردو', Urdu */
        'yi'    /* 'ייִדיש', Yiddish */
    ],
];