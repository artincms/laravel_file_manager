<?php

return [

    /* Important Settings */
    'private_middlewares'  => explode(',', env('LFM_PRIVATE_MIDDLEWARES', 'web')),
    'upload_middlewares'   => explode(',', env('LFM_UPLOAD_MIDDLEWARES', 'web')),
    'public_middlewares'   => explode(',', env('LFM_PUBLIC_MIDDLEWARES', 'web')),
    // you can change default route from sms-admin to anything you want
    'private_route_prefix' => env('LFM_PRIVATE_ROUTE_PREFIX', 'LFM'),
    'public_route_prefix'  => env('LFM_PUBLIC_ROUTE_PREFIX', 'LFM'),
    'upload_route_prefix'  => env('LFM_UPLOAD_ROUTE_PREFIX', 'UFM'),

    // ======================================================================
    //allow user to upload private file in filemanager

    'allow_upload_private_file'  => env('LFM_ALLOW_UPLOAD_PRIVATE_FILE', true),
    // set true if you want log to the database
    'db-log'                     => env('LFM_DB_LOG', true),
    'allowed'                    => env('LFM_ALLOWED', ['application/zip', 'application/pdf', 'image/jpeg', 'image/png', 'image/x-icon', 'application/x-rar', 'application/vnd.ms-word.document.macroenabled.12', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-excel.sheet.binary.macroenabled.12', 'application/vnd.ms-excel.sheet.macroenabled.12', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/plain', 'audio/mpeg', 'video/mp4', 'video/webm', 'video/ogg', 'audio/ogg', 'audio/x-wav']),
    'allowed_pic'                => env('LFM_ALLOWED_PIC', ['image/jpeg', 'image/png']),
    'crop_type'                  => env('LFM_CROP_TYPE', ['large', 'medium', 'small', 'original']),
    'size_large'                 => env('LFM_SIZE_LARGE', ['height' => 1024, 'width' => 768]),
    'size_small'                 => env('LFM_SIZE_SMALL', ['height' => 347, 'width' => 247]),
    'size_medium'                => env('LFM_SIZE_MEDIUM', ['height' => 800, 'width' => 600]),
    'crop_chose'                 => env('LFM_CROP_CHOSE', 'fit'),
    'driver_disk'                => env('LFM_DRIVER_DISK', 'local'),
    'driver_disk_upload'         => env('LFM_DRIVER_DISK_UPLOAD', 'local'),
    'user_model'                 => env('LFM_USER_MODEL', 'App\User'),
    //optomise Image
    'Optimise_image'             => env('LFM_OPTIMISE_IMAGE', false),
    'symlink_public_folder_name' => env('LFM_SYMLINK_PUBLIC_FOLDER_NAME', 'public'),
    'main_storage_folder_name'   => env('MAIN_STORAGE_FOLDER_NAME', 'Filemanager'),
    'lfm_default_true_extension' => env('LFM_DEFAULT_TRUE_EXTENSION', ['jpg', 'jpeg', 'png']),
    'insertde_view_theme'        => env('LFM_INSERTED_VIEW_THEME', 'inline'),
    'lang_rtl'                   => env('LFM_LANG_RTL', [
        'ae',   /* Avestan */
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
    ]),
];