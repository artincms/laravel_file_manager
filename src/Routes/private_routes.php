<?php
Route::group(['prefix' => config('laravel_file_manager.private_route_prefix'), 'namespace' => 'ArtinCMS\LFM\Controllers', 'middleware' => config('laravel_file_manager.private_middlewares')], function () {
    Route::get('ShowGridMyFiles', ['as' => 'LFM.ShowGridMyFiles', 'uses' => 'LFMController@ShowGridMyFiles']);
    //Route::get('DownloadFile/{type}/{id?}/{quality?}/{width?}/{height?}', ['as' => 'LFM.DownloadFile', 'uses' => 'LFMController@Download']);
    Route::post('Upload/{section}', ['as' => 'LFM.UploadWithAddToSession', 'uses' => 'LFMController@UploadWithAddToSession']);
    Route::post('AddSelectedFile/{section}', ['as' => 'LFM.AddSelectedFileToSession', 'uses' => 'LFMController@AddSelectedFileToSession']);
    Route::post('RemoveFFS/{section}/{record}', ['as' => 'LFM.RemoveFileFromSession', 'uses' => 'LFMController@RemoveFFS']);
    Route::post('GridMyFiles', ['as' => 'LFM.GridMyFiles', 'uses' => 'LFMController@GridMyFiles']);

    //new routes by sadeghi
    Route::get('ShowCategories/{insert?}/{Section?}', ['as' => 'LFM.ShowCategories', 'uses' => 'ManagerController@ShowCategories']);
    Route::get('ShowCategories/create/{category_id}/{callback?}', ['as' => 'LFM.ShowCategories.Create', 'uses' => 'ManagerController@CreateCategory']);
    Route::get('ShowCategories/Edit/{category_id}', ['as' => 'LFM.ShowCategories.Edit', 'uses' => 'ManagerController@EditCategory']);

    Route::post('StoreCategory', ['as' => 'LFM.StoreCategory', 'uses' => 'ManagerController@StoreCategory']);
    Route::post('ShowCategory', ['as' => 'LFM.ShowCategory', 'uses' => 'ManagerController@ShowCategory']);
    Route::post('Trash', ['as' => 'LFM.Trash', 'uses' => 'ManagerController@Trash']);
    Route::post('BulkDelete', ['as' => 'LFM.BulkDelete', 'uses' => 'ManagerController@BulkDelete']);
    //file uploades
    Route::get('FileUpload/{category_id}/{section?}/{callback?}', ['as' => 'LFM.FileUpload', 'uses' => 'ManagerController@FileUpload']);
    Route::post('StoreUploads', ['as' => 'LFM.StoreUploads', 'uses' => 'ManagerController@StoreUploads']);

    //edti photot
    Route::get('EditPicture/{file_id}', ['as' => 'LFM.EditPicture', 'uses' => 'ManagerController@EditPicture']);
    Route::post('StoreCropedImage', ['as' => 'LFM.StoreCropedImage', 'uses' => 'ManagerController@StoreCropedImage']);
    //refresh content page
    Route::post('RefreshPage', ['as' => 'LFM.RefreshPage', 'uses' => 'ManagerController@RefreshPage']);

    //search media
    Route::post('SearchMedia', ['as' => 'LFM.SearchMedia', 'uses' => 'ManagerController@SearchMedia']);

    //Display Route
    Route::get('ShowList', ['as' => 'LFM.ShowList', 'uses' => 'ManagerController@ShowList']);
    Route::post('ShowListCategory', ['as' => 'LFM.ShowListCategory', 'uses' => 'ManagerController@ShowListCategory']);

    //get bredcrumbs
    Route::post('Breadcrumbs/{id}', ['as' => 'LFM.Breadcrumbs', 'uses' => 'ManagerController@get_breadcrumbs']);

    //create insert data
    Route::post('CreateInsertData', ['as' => 'LFM.CreateInsertData', 'uses' => 'ManagerController@CreateInsertData']);

    //Session
    Route::get('GetSession/{name}', ['as' => 'LFM.GetSession', 'uses' => 'ManagerController@GetSession']);
    Route::get('DeleteSessionInsertItem/{name}/{id}', ['as' => 'LFM.DeleteSessionInsertItem', 'uses' => 'ManagerController@DeleteSessionInsertItem']);




});

