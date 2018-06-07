<?php

namespace ArtinCMS\LFM;

use ArtinCMS\LFM\Helpers\Classes\Media;
use Illuminate\Support\ServiceProvider;

class LFMServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */

    public function boot()
    {
    	// the main router
	    include_once __DIR__.'/Routes/private_routes.php';
	    include_once __DIR__.'/Routes/public_routes.php';
	    // the main views folder
	    $this->loadViewsFrom(__DIR__ . '/Views', 'laravel_file_manager');
	    // the main migration folder for create sms_ir tables

	    // for publish the views into main app
	    $this->publishes([
		    __DIR__ . '/Views' => resource_path('views/vendor/laravel_file_manager'),
	    ]);

	    //publish storage file
         $this->publishes([
                    __DIR__ . '/Storage/SystemFiles' => storage_path() . '/app/System',
                ]);

	    $this->publishes([
		    __DIR__ . '/Database/Migrations/' => database_path('migrations')
	    ], 'migrations');

	    // for publish the assets files into main app
	    $this->publishes([
		    __DIR__.'/assets' => public_path('vendor/laravel_file_manager'),
	    ], 'public');

	    // for publish the sms_ir config file to the main app config folder
	    $this->publishes([
		    __DIR__ . '/Config/LFM.php' => config_path('laravel_file_manager.php'),
	    ]);

	    // publish language
	    $this->publishes([
		    __DIR__ . '/Lang/En/Filemanager.php' => resource_path('lang/en/filemanager.php'),
	    ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    	// set the main config file
	    $this->mergeConfigFrom(
		    __DIR__ . '/Config/LFM.php', 'laravel_file_manager'
	    );

		// bind the LFMC Facade
	    $this->app->bind('LFMC', function () {
		    return new LFMC;
	    });
        $this->app->bind('FileManager', function () {
            return new Media;
        });
    }
}
