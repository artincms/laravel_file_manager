# laravel_file_manager
laravel file manager is a package for manage file uploaded by users with check ACL and store in Sotrage folder and generate download link 

# Requiments 
PHP >= 7.0
Laravel 5.5|5.6

# Quick Installation
add this following code to composer.json :

"repositories": [
        {
            "type": "path",
            "url": "../test_packages_raheli/artincms/laravel_file_manager",
            "options": {
                "symlink": true
            }
        }
    ],
    
 "require": {
 "artincms/laravel_file_manager": "dev-master"
 }
 #Configuration 
 php artisan vendor:publish
 
 and publish artincms packages .
 #usage
 
 
 