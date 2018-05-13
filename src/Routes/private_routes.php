<?php
Route::group(['prefix' => config('laravel_file_manager.private_route_prefix'), 'namespace' => 'ArtinCMS\LFM\Controllers', 'middleware' => config('laravel_file_manager.private_middlewares')], function () {
    //show routes
    Route::get('ShowCategories/{insert?}/{callback?}/{section?}', ['as' => 'LFM.ShowCategories', 'uses' => 'ManagerController@showCategories']);
<<<<<<< HEAD
    Route::get('ShowCategories/create/{category_id?}/{callback?}/{section?}', ['as' => 'LFM.ShowCategories.Create', 'uses' => 'ManagerController@createCategory']);
=======
    Route::get('ShowCategories/Create/{category_id?}/{callback?}/{section?}', ['as' => 'LFM.ShowCategories.Create', 'uses' => 'ManagerController@createCategory']);
>>>>>>> 326cec0fce3ada34dd8a74889ece2029cc6fe191
    Route::get('EditCategory/{category_id}', ['as' => 'LFM.ShowCategories.Edit', 'uses' => 'ManagerController@editCategory']);
    Route::post('ShowCategory', ['as' => 'LFM.ShowCategory', 'uses' => 'ManagerController@showCategory']);

    Route::post('StoreCategory', ['as' => 'LFM.StoreCategory', 'uses' => 'ManagerController@storeCategory']);
    Route::post('Trash', ['as' => 'LFM.Trash', 'uses' => 'ManagerController@trash']);
    Route::post('BulkDelete', ['as' => 'LFM.BulkDelete', 'uses' => 'ManagerController@bulkDelete']);

<<<<<<< HEAD
    //file uploades
    Route::get('FileUpload/{category_id?}/{callback?}/{section?}', ['as' => 'LFM.FileUpload', 'uses' => 'UploadController@fileUpload']);
    Route::post('StoreUploads', ['as' => 'LFM.StoreUploads', 'uses' => 'UploadController@storeUploads']);
=======
    //file uploads
    Route::get('FileUpload/{category_id?}/{callback?}/{section?}', ['as' => 'LFM.FileUpload', 'uses' => 'ManagerController@fileUpload']);
    Route::post('StoreUploads', ['as' => 'LFM.StoreUploads', 'uses' => 'ManagerController@storeUploads']);
>>>>>>> 326cec0fce3ada34dd8a74889ece2029cc6fe191

    //edit photo
    Route::get('EditPicture/{file_id}', ['as' => 'LFM.EditPicture', 'uses' => 'ManagerController@editPicture']);
    Route::post('StoreCropedImage', ['as' => 'LFM.StoreCropedImage', 'uses' => 'ManagerController@storeCropedImage']);

    //refresh content page
    Route::post('RefreshPage', ['as' => 'LFM.RefreshPage', 'uses' => 'ManagerController@RefreshPage']);

    //search media
    Route::post('SearchMedia', ['as' => 'LFM.SearchMedia', 'uses' => 'ManagerController@searchMedia']);

<<<<<<< HEAD
    //display Route
=======
    //display route
>>>>>>> 326cec0fce3ada34dd8a74889ece2029cc6fe191
    Route::get('ShowList', ['as' => 'LFM.ShowList', 'uses' => 'ManagerController@ShowList']);
    Route::post('ShowListCategory', ['as' => 'LFM.ShowListCategory', 'uses' => 'ManagerController@showListCategory']);

    //get breadcrumbs
<<<<<<< HEAD
    Route::post('Breadcrumbs/{id}', ['as' => 'LFM.Breadcrumbs', 'uses' => 'ManagerController@getBreadcrumbs']);

    //create insert data
    Route::post('CreateInsertData', ['as' => 'LFM.CreateInsertData', 'uses' => 'SessionController@createInsertData']);

    //session
    Route::get('GetSession/{name}', ['as' => 'LFM.GetSession', 'uses' => 'SessionController@getSession']);
    Route::get('DeleteSessionInsertItem/{name}/{id}', ['as' => 'LFM.DeleteSessionInsertItem', 'uses' => 'SessionController@deleteSessionInsertItem']);
    Route::post('DeleteSelectedPostId', ['as' => 'LFM.DeleteSelectedPostId', 'uses' => 'SessionController@deleteSelectedPostId']);
=======
    Route::post('Breadcrumbs/{id}', ['as' => 'LFM.Breadcrumbs', 'uses' => 'ManagerController@get_breadcrumbs']);

    //create insert data
    Route::post('CreateInsertData', ['as' => 'LFM.CreateInsertData', 'uses' => 'ManagerController@CreateInsertData']);

    //session
    Route::get('GetSession/{name}', ['as' => 'LFM.GetSession', 'uses' => 'ManagerController@GetSession']);
    Route::get('DeleteSessionInsertItem/{name}/{id}', ['as' => 'LFM.DeleteSessionInsertItem', 'uses' => 'ManagerController@DeleteSessionInsertItem']);
    Route::post('DeleteSelectedPostId', ['as' => 'LFM.DeleteSelectedPostId', 'uses' => 'ManagerController@DeleteSelectedPostId']);

    //test view
    Route::get('SmallInsertedView', ['as' => 'LFM.SmallInsertedView', 'uses' => 'ManagerController@SmallInsertedView']);
    Route::get('ThumbInsertedView', ['as' => 'LFM.ThumbInsertedView', 'uses' => 'ManagerController@ThumbInsertedView']);
    Route::get('LargeInsertedView', ['as' => 'LFM.LargeInsertedView', 'uses' => 'ManagerController@LargeInsertedView']);

>>>>>>> 326cec0fce3ada34dd8a74889ece2029cc6fe191
});

