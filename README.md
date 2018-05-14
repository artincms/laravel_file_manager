# laravel_file_manager
laravel file manager is a package for 
<ul>
<li>manage file uploaded by users with check ACL. you can upload file with custom size </li>
<li>generate download link </li>
<li>store file in Sotrage folder</li>
<li>optimize image</li>
<li>crop image when file upload , edit file or  generate link with three kind of crop .</li>
</ul> 

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
<div class="highlight highlight-source-shell"><pre>composer require "artincms/laravel_file_manager"</pre></div>
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
<h6>seed data to lfm_file_mime_type table</h6>
for windows
 <div class="highlight highlight-text-html-php"><pre>
  php artisan db:seed --class=ArtinCMS\LFM\Database\Seeds\LfmFileMimeTypesTableSeeder
  </pre> </div>
  for linux
  <div class="highlight highlight-text-html-php"><pre>
 php artisan db:seed --class=ArtinCMS\\LFM\\Database\\Seeds\\LfmFileMimeTypesTableSeeder
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
  
  that $section is Require and other input is optional .for use output of filemanager 
  you should install jquery 3 and bootstrap 4 ;
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
  //the inserted file result is 
  [
    data 
    [{
        0
        {
            file 
            {
                height : "0"
                icon : "image"
                id : 115
                name : "photo2017-04-1512-45-2"
                quality : "100"
                size : 60187
                title_file_disc : "fid_115_v0__uid_21_73cbaf0bc61f0ca763208c33312ed928_1526201846_jpeg"
                type : "orginal"
                user : "faramarz"
                version : null
                width:  "0"
            }
            full_url: "http://127.0.0.1:8000/LFM/DownloadFile/ID/115/orginal/404.png/100/0/0"
            message: "File with ID :115 Inserted"
            success:true
            url: "/LFM/DownloadFile/ID/115/orginal/404.png/100/0/0"
        }
    }],
    view
    {
       'grid' : 'html grid view code' ,
       'small' :'html small view code' ,
       'thumb' : 'html thumb view code' ,
       'large' : 'html large view code' 
    }
  ]  
//to show above data to small view you can use this function  
function callback(result)
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
  <p>  you can access to file manager with :http://www.yourdomain.com/LFM/ShowCategories</p>
 <h4> Generate Download Link </h4>
  whit this below helper function you can generate download link in anywhere of your project .
  <div class="highlight highlight-text-html-php"><pre>
 LFM_GenerateDownloadLink($type, $id , $size_type, $default_img, $quality , $width, $height)
   </pre> </div>
<ul>
<li>if you want generate file link with ID the $type = 'ID' and if you want generate by name you can use $type = 'Name'
</li>
<li>$id : if you want generate link with id you should fill this item with file id and default is -1 that reffer no file id selected .</li>
<li>$size_type can pick one on of : orginal , large , medium , small that reffer which size you want to download</li>
<li>if image not found with $default_img you can choose image you want to show , default is '404.png'</li>
<li>$quality is quality of image , it is between 0 and 100 . </li>
<li>$width is width of result image</li>
<li>$height is height of result image</li>
</ul>  
 
 <h3>custom config</h3>
 if you want to have custom config you can chage config/laravel_file_manager.php file as you want .
 <ul>
 <li>'private_middlewares' and 'public_middlewares' describe what middelware should assign to private and public route ,you can add auth and other middelware you want .</li>
 <li>with 'private_route_prefix' and 'public_route_prefix' you can change prefix of private and public route .</li>
 <li>'allowed' reffer wich mime type files can upload</li>
 <li>'allowed_pic' is reffer wich mime type is recognize as picture</li>
 <li>with 'size_large' ,'size_large' , 'size_large' and 'size_large' when upload file you can
 define size of upload deppend of type .
  </li>
  <li>'driver_disk' is deriver you use in your project </li>
<li>'user_model' is path of your user model .</li>
<li>'Optimise_image' if is true and do serve config as tell in installation section . all picture optimize before save in your storage .</li>
 <li>with 'crop_chose' you can choose wich crop type use when upload file (when create large ,medium and small size of picture). you can set it
   one of : fit,resize and smart that 'fit' is default crop and resize resize image  with due attention to config size you set and smart type is smart crop but it was slowler than other crop type .</li>
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
{!! $result['modal_content'] !!}
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

  