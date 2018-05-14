# laravel_file_manager
laravel file manager is a package for manage file uploaded by users with check ACL and store in Sotrage folder and generate download link 

# Requiments 
PHP >= 7.0

Laravel 5.5|5.6

# Quick Installation
<div class="highlight highlight-source-shell"><pre>composer update "artincms/laravel_file_manager"</pre></div>
<h3>for laravel less than 5.5</h3>
Register provider and facade on your config/app.php file.
<div class="highlight highlight-text-html-php"><pre>
'providers' => [
    ...,
    ArtinCMS\LFM\LFMServiceProvider::class,
]
</pre>
<pre>
'aliases' => [
    ...,
    'Media' => ArtinCMS\LFM\Facades\Media::class,
]
</pre></div>
 
 
 #Configuration 
<h6>publish vendor</h6>
 <div class="highlight highlight-text-html-php"><pre>
 $ php artisan vendor:publish --provider=ArtinCMS\LFM\LFMServiceProvider
</pre> </div>
 <h5>migrate tabales</h5>
  <div class="highlight highlight-text-html-php"><pre>
  $ php artisan migrate
  </pre> </div>
<h5>seed data to tabales</h5>
 <div class="highlight highlight-text-html-php"><pre>
  </pre> </div>
  <h5> if you want enable optimize your image you should add this command in ssh:
  <div class="highlight highlight-source-shell">
  <pre>
  brew install jpegoptim
  brew install optipng
  brew install pngquant
  brew install svgo
  brew install gifsicle</pre></div>
  <ul><li>set Optimise_image to true in laravel_file_manager config</li></ul>
  <p>for more information you can visit<a href="https://github.com/spatie/image-optimizer">image-optimizer</a>

  and the end update your composer
  <div class="highlight highlight-text-html-php"><pre>
    $ composer update
   </pre> </div>
  
 #usage
 for use this package you should use bellow function in your controller .
 
 <div class="highlight highlight-text-html-php"><pre>
    LFM_CreateModalFileManager($section, $options , $insert , $callback , $modal_id , $header , $button_id, $button_content)
  </pre> </div>
  that $sectin is Require and other input is optional .
  <ul>
  <li>
  $section = section name for example 'FileManager';
  </li>
  $options : is options you want to impplement in this package example :
  <div class="highlight highlight-text-html-php"><pre>
    $options = ['size_file' => 100, 'max_file_number' => 30, 'true_file_extension' => ['png','jpg']];
    </pre> </div>
  <li>
  $insert : if you want this package as select file to add to section you can use this 
  default value is 'insert' ;
  </li>
  <li>
  $callback : callback function you want call it after inserted file ;
  example you can get selected file and show it with callback show 
  <div class="highlight highlight-text-html-php"><pre>
function show(res)
{
   $('#show_area_small').append(res.view.small) ;
}
  </pre> </div>
  to show inserted file , this package provide 4 view you can 
  call these view in callback function as show in above example .
  inserted view is : small,thumb,large,grid 
  </li>
   <li>
    $modal_id : this option use to assign id to main modal and default is 'FileManager' .
   </li>
   <li>
        $header : this option use to set modal header .and default is 'File manager' .
   </li>
   <li>
   $button_id :this option use to assign id to button create modal and default is 'show_modal' .
    </li>
   <li>
   $button_content : this option use to assign name of button create modal and default is 'input file' .
   </li>
  </ul>
 
 