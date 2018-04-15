<?php
Route::group(['prefix' => config('laravel_file_manager.private_route_prefix'), 'namespace' => 'ArtinCMS\LFM\Controllers', 'middleware' => config('laravel_file_manager.private_middlewares')], function () {
    Route::get('ShowGridMyFiles', ['as' => 'LFM.ShowGridMyFiles', 'uses' => 'LFMController@ShowGridMyFiles']);
    //Route::get('DownloadFile/{type}/{id?}/{quality?}/{width?}/{height?}', ['as' => 'LFM.DownloadFile', 'uses' => 'LFMController@Download']);
    Route::post('Upload/{section}', ['as' => 'LFM.UploadWithAddToSession', 'uses' => 'LFMController@UploadWithAddToSession']);
    Route::post('AddSelectedFile/{section}', ['as' => 'LFM.AddSelectedFileToSession', 'uses' => 'LFMController@AddSelectedFileToSession']);
    Route::post('RemoveFFS/{section}/{record}', ['as' => 'LFM.RemoveFileFromSession', 'uses' => 'LFMController@RemoveFFS']);
    Route::post('GridMyFiles', ['as' => 'LFM.GridMyFiles', 'uses' => 'LFMController@GridMyFiles']);
});

