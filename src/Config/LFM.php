<?php

return [

    /* Important Settings */
    'private_middlewares'  => explode(',', env('LFM_PRIVATE_MIDDLEWARES', 'web')),
	'session_middlewares'  => explode(',', env('LFM_PRIVATE_MIDDLEWARES', 'web')),
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
    'allowed'                    => explode(',', env('LFM_ALLOWED', 'image/png,application/zip,application/pdf,image/jpeg,image/x-icon,application/x-rar,application/vnd.ms-word.document.macroenabled.12,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/vnd.ms-excel,application/vnd.ms-excel.sheet.binary.macroenabled.12,application/vnd.ms-excel.sheet.macroenabled.12,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,audio/mpeg,video/mp4,video/webm,video/ogg,audio/ogg,audio/x-wav,image/svg+xml')),
    'allowed_pic'                => explode(',', env('LFM_ALLOWED_PIC', 'image/jpeg,image/png')),
    'crop_type'                  => explode(',', env('LFM_CROP_TYPE', 'large,medium,small,original')),
    'size_large'                 => LFM_explode_2d_array(env('LFM_SIZE_LARGE', 'height:1024,width:768')),
    'size_small'                 => LFM_explode_2d_array(env('LFM_SIZE_SMALL', 'height:347,width:247')),
    'size_medium'                => LFM_explode_2d_array(env('LFM_SIZE_MEDIUM', 'height:800,width:600')),
    'crop_chose'                 => env('LFM_CROP_CHOSE', 'fit'),
    'driver_disk'                => env('LFM_DRIVER_DISK', 'local'),
    'driver_disk_upload'         => env('LFM_DRIVER_DISK_UPLOAD', 'local'),
    'user_model'                 => env('LFM_USER_MODEL', 'App\User'),
    'user_name_column'           => env('LFM_NAME_COLUMN', 'username'),
    'user_email_column'          => env('LFM_EMAIL_COLUMN', 'email'),
    'count_total_mime_type'          => env('LFM_COUNT_TOTAL_MIME_TYPE', 678),
    //optomise Image
    'Optimise_image'             => env('LFM_OPTIMISE_IMAGE', false),
    'symlink_public_folder_name' => env('LFM_SYMLINK_PUBLIC_FOLDER_NAME', 'public'),
    'main_storage_folder_name'   => env('MAIN_STORAGE_FOLDER_NAME', 'Filemanager'),
    'lfm_default_true_extension' => explode(',', env('LFM_ALLOWED_PIC', 'jpg,jpeg,png')),
    'insertde_view_theme'        => env('LFM_INSERTED_VIEW_THEME', 'inline'),
    'lang_rtl'                   => explode(',', env('LFM_ALLOWED_PIC', 'ae,ar,arc,bcc,bqi,ckb,dv,fa,glk,he,ku,mzn,nqo,pnb,ps,sd,ug,ur,yi')),
	'is_use_secure_route'        => env('LFM_IS_USE_SECURE_ROUTE', false),
    /*  'lang_rtl'                   => env('LFM_LANG_RTL', [
          'ae',    Avestan
          'ar',    'العربية', Arabic
          'arc',   Aramaic
          'bcc',   'بلوچی مکرانی', Southern Balochi
          'bqi',   'بختياري', Bakthiari
          'ckb',   'Soranî / کوردی', Sorani
          'dv',    Dhivehi
          'fa',    'فارسی', Persian
          'glk',   'گیلکی', Gilaki
          'he',    'עברית', Hebrew
          'ku',    'Kurdî / كوردی', Kurdish
          'mzn',   'مازِرونی', Mazanderani
          'nqo',   Ko
          'pnb',   'پنجابی', Western Punjabi
          'ps',    'پښتو', Pashto,
          'sd',    'سنڌي', Sindhi
          'ug',    'Uyghurche / ئۇيغۇرچە', Uyghur
          'ur',    'اردو', Urdu
          'yi'     'ייִדיש', Yiddish
      ]),*/
];