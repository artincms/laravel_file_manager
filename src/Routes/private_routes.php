<?php
Route::group(['prefix' => config('laravel_file_manager.private_route_prefix'), 'namespace' => 'ArtinCMS\LFM\Controllers', 'middleware' => config('laravel_file_manager.private_middlewares')], function () {
    //show routes
    Route::get('ShowCategories/{insert?}/{callback?}/{section?}', ['as' => 'LFM.ShowCategories', 'uses' => 'ManagerController@showCategories']);
    Route::get('ShowCategories/create/{category_id?}/{callback?}/{section?}', ['as' => 'LFM.ShowCategories.Create', 'uses' => 'ManagerController@createCategory']);
    Route::get('ShowCategories/Create/{category_id?}/{callback?}/{section?}', ['as' => 'LFM.ShowCategories.Create', 'uses' => 'ManagerController@createCategory']);
    Route::get('EditCategory/{category_id}', ['as' => 'LFM.ShowCategories.Edit', 'uses' => 'ManagerController@editCategory']);
    Route::get('EditFile/{file_id}', ['as' => 'LFM.EditFile', 'uses' => 'ManagerController@editFile']);

    Route::post('ShowCategory', ['as' => 'LFM.ShowCategory', 'uses' => 'ManagerController@showCategory']);
    Route::post('StoreCategory', ['as' => 'LFM.StoreCategory', 'uses' => 'ManagerController@storeCategory']);
    Route::post('UpdateCategory', ['as' => 'LFM.UpdateCategory', 'uses' => 'ManagerController@updateCategory']);
    Route::post('EditFileName', ['as' => 'LFM.EditFileName', 'uses' => 'ManagerController@editFileName']);
    Route::post('Trash', ['as' => 'LFM.Trash', 'uses' => 'ManagerController@trash']);
    Route::post('BulkDelete', ['as' => 'LFM.BulkDelete', 'uses' => 'ManagerController@bulkDelete']);

    //file uploades
    Route::get('FileUpload/{category_id?}/{callback?}/{section?}', ['as' => 'LFM.FileUpload', 'uses' => 'UploadController@fileUpload']);
    Route::post('StoreUploads', ['as' => 'LFM.StoreUploads', 'uses' => 'UploadController@storeUploads']);

    //edit photo
    Route::get('EditPicture/{file_id}', ['as' => 'LFM.EditPicture', 'uses' => 'ManagerController@editPicture']);
    Route::post('StoreCropedImage', ['as' => 'LFM.StoreCropedImage', 'uses' => 'ManagerController@storeCropedImage']);

    //refresh content page
    Route::post('RefreshPage', ['as' => 'LFM.RefreshPage', 'uses' => 'ManagerController@RefreshPage']);

    //search media
    Route::post('SearchMedia', ['as' => 'LFM.SearchMedia', 'uses' => 'ManagerController@searchMedia']);

    //display route
    Route::post('ShowListCategory', ['as' => 'LFM.ShowListCategory', 'uses' => 'ManagerController@showListCategory']);

    //get breadcrumbs
    Route::post('Breadcrumbs/{id}', ['as' => 'LFM.Breadcrumbs', 'uses' => 'ManagerController@getBreadcrumbs']);

    //create insert data
    Route::post('CreateInsertData', ['as' => 'LFM.CreateInsertData', 'uses' => 'SessionController@createInsertData']);


    //session
    Route::post('GetSessionInsertedItems}', ['as' => 'LFM.GetSession', 'uses' => 'SessionController@getSessionInsertedItems']);
    Route::post('DeleteSessionInsertItem', ['as' => 'LFM.DeleteSessionInsertItem', 'uses' => 'SessionController@deleteSessionInsertItem']);
});

