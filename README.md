# laravel_file_manager
laravel file manager is a package for manage file uploaded by users with check ACL and store in Sotrage folder and generate download link 

# Requiments 
<ul>
<li>
PHP >= 7.0
</li>
<li>
Laravel 5.5|5.6
</li>
</ul>
<h5>The package will use these optimizers if you wnat to enable optimization:
</h5>
<ul>
<li><a href="http://freecode.com/projects/jpegoptim" rel="nofollow">JpegOptim</a></li>
<li><a href="http://optipng.sourceforge.net/" rel="nofollow">Optipng</a></li>
<li><a href="https://pngquant.org/" rel="nofollow">Pngquant 2</a></li>
<li><a href="https://github.com/svg/svgo">SVGO</a></li>
<li><a href="http://www.lcdf.org/gifsicle/" rel="nofollow">Gifsicle</a></li>
</ul>

# Installation
<h3>Quick installation</h3> 
<div class="highlight highlight-source-shell"><pre>composer update "artincms/laravel_file_manager"</pre></div>
<h5>for laravel less than 5.5</h5>
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
    'FileManager' => ArtinCMS\LFM\Facades\FileManager::class,
]
</pre></div>
 
 
<h6>publish vendor</h6>
 <div class="highlight highlight-text-html-php"><pre>
 $ php artisan vendor:publish --provider=ArtinCMS\LFM\LFMServiceProvider
</pre> </div>
 <h6>migrate tabales</h6>
  <div class="highlight highlight-text-html-php"><pre>
  $ php artisan migrate
  </pre> </div>
<h6>seed data to tabales</h6>
 <div class="highlight highlight-text-html-php"><pre>
  </pre> </div>
  <h4>more installation details</h4>
  <p>The package will use these optimizers if they are present on your system:</p>
  <ul>
  <li><a href="http://freecode.com/projects/jpegoptim" rel="nofollow">JpegOptim</a></li>
  <li><a href="http://optipng.sourceforge.net/" rel="nofollow">Optipng</a></li>
  <li><a href="https://pngquant.org/" rel="nofollow">Pngquant 2</a></li>
  <li><a href="https://github.com/svg/svgo">SVGO</a></li>
  <li><a href="http://www.lcdf.org/gifsicle/" rel="nofollow">Gifsicle</a></li>
  </ul>
  <p>for install this package in your server : </p>
  <p>Here's how to install all the optimizers on Ubuntu:</p>
  <div class="highlight highlight-source-shell"><pre>
  sudo apt-get install jpegoptim
  sudo apt-get install optipng
  sudo apt-get install pngquant
  sudo npm install -g svgo
  sudo apt-get install gifsicle</pre></div>
  <p>And here's how to install the binaries on MacOS (using <a href="https://brew.sh/" rel="nofollow">Homebrew</a>):</p>
  <div class="highlight highlight-source-shell">
  <pre>
  brew install jpegoptim
  brew install optipng
  brew install pngquant
  brew install svgo
  brew install gifsicle</pre></div>
  <div><p>for enable optimizer in package you shoul go config/laravel_file_manager.php
   and set 'Optimise_image'= true</p></div>
  <p>for more information you can visit <a href="https://github.com/spatie/image-optimizer">image-optimizer</a>  
 
 #usage
 for use this package you should use bellow helper function anywhere in your project such as in your controller . this helper function
 <ul>
 <li>create html modal for show manager</li>
 <li>create html modal button</li>
 <li>set your options</li>
 </ul>
 
 <div class="highlight highlight-text-html-php"><pre>
    LFM_CreateModalFileManager($section, $options , $insert , $callback , $modal_id , $header , $button_id, $button_content)
  </pre> </div>
  that $section is Require and other input is optional .
  <ul>
  <li>
  $section = section name for example 'FileManager';
  </li>
  $options : is options you want to impplement in this package example :
  <div class="highlight highlight-text-html-php"><pre>
    $options = ['size_file' => 100, 'max_file_number' => 30, 'true_file_extension' => ['png','jpg']];
    </pre> </div>
  <li>
  $insert : with this option you can use this package as a file selector .
  default value is 'insert' ;
  </li>
  <li>
  $callback : this options provide  javascript callback function name .
  for example you can get selected file and show it 
  <div class="highlight highlight-text-html-php"><pre>
function show(res)
{
   $('#show_area_small').append(res.view.small) ;
}
  </pre> </div>
  to show inserted file , this package provide 4 view . you can 
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
 
 #example
 if you want assign file to articles you can use this filemanager . in your conttroller 

<div class="highlight highlight-text-html-php"><pre>
$options = ['size_file' => 100, 'max_file_number' => 30, 'true_file_extension' => ['png','jpg']];
$result = LFM_CreateModalFileManager('Manager',false , 'insert','show');
return view('article',compact('result'));
 </pre> </div>
 
 and in your view add this html  : 
<div class="highlight highlight-text-html-php-blade"><pre>
{!! $result['button'] !!}
and 
{!! $result['content'] !!}
</pre> </div>

for save inserted files you can add this code in your controller :

for save one inserted file
<div class="highlight highlight-text-html-php-blade"><pre>
LFM_SaveSingleFile($obj_model, $column_name, $section)
 
</pre> </div>
that obj_model name of model example $article = new Article . and column_name is name of 
column you want save inserted id for example 'default_img_file_id' and $section is name of section .

for save multi inserted files : 
<div class="highlight highlight-text-html-php-blade"><pre>
 $res = LFM_SaveMultiFile($obj_model, $section, $type , $relation_name , $attach_type)
</pre> </div>
that obj_model name of model example $article = new Article , $section is optional and type of file that default is null , $relation_name is name of relation default is 'files' and with $attach_type you can select attach or sync file.

  