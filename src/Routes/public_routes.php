<?php
Route::group(['prefix' => config('laravel_file_manager.public_route_prefix'), 'namespace' => 'ArtinCMS\LFM\Controllers', 'middleware' => config('laravel_file_manager.public_middlewares')], function () {
    Route::get('DownloadFile/{type}/{id?}/{size_type?}/{default_img?}/{quality?}/{width?}/{height?}', array('as' => 'LFM.DownloadFile', 'uses' => 'UploadController@download'));
});