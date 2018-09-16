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
 $ php artisan vendor:publish --provider="ArtinCMS\LFM\LFMServiceProvider"
</pre> </div>
if update package for publish vendor you should run : 
 <div class="highlight highlight-text-html-php"><pre>
 $ php artisan vendor:publish --provider="ArtinCMS\LFM\LFMServiceProvider" --force
</pre> </div>
 <h6>migrate tabales</h6>
  <div class="highlight highlight-text-html-php"><pre>
  $ php artisan migrate
  </pre> </div>
<h6>seed data to lfm_file_mime_type table</h6>
 <div class="highlight highlight-text-html-php"><pre>
  php artisan db:seed --class="ArtinCMS\LFM\Database\Seeds\FilemanagerTableSeeder"
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

 <h1>usage</h1> 
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
  {
    Manager :
        available:1,
        data :
        [{
            0 :
            {
                file :
                {
                    height : "0"
                    icon : "image"
                    id : 115
                    name : "photo2017-04-1512-45-2"
                    quality : "100"
                    size : 60187
                    type : "original"
                    user : "faramarz"
                    version : null
                    width:  "0"
                },
                full_url: "http://127.0.0.1:8000/LFM/DownloadFile/ID/115/original/404.png/100/0/0",
                message: "File with ID :115 Inserted",
                success:true
                url: "/LFM/DownloadFile/ID/115/original/404.png/100/0/0",
                full_url_large:"http://127.0.0.1:8000/LFM/DownloadFile/ID/24/small/404.png/100/300/180",
                full_url_medium:"http://127.0.0.1:8000/LFM/DownloadFile/ID/24/medium/404.png/100/300/180",
            }
        }],
        view :
        {
           'list' : 'html grid view code' ,
           'grid' : 'html grid view code' ,
           'small' :'html small view code' ,
           'medium' : 'html thumb view code' ,
           'large' : 'html large view code' 
        }
  }  
//to show above data to small view you can use this function  
function callback(result)
{
   $('#show_area_small').html(result.Manager.view.small) ;
}
  </pre> </div>
  Manager is section name . to show inserted file , this package provide 4 view . you can 
  call these view in callback function as show in above example .
inserted view is : list,grid,small,medium,large 
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
 <h2>Helper Functions</h2>
 in this section we descripe some most helpfull function . you can use this functions in your project .
 <h3>Save File</h3>
 for save file in your project you can use LFM_SaveMultiFile and LFM_SaveSingleFile . in continue we explain two functions .
 <h4>Save Multi File </h4>
 this helpers use for save multi inserted files in fileable table : 
 <div class="highlight highlight-text-html-php-blade"><pre>
  $res = LFM_SaveMultiFile($obj_model, $section, $type , $relation_name , $attach_type)
 </pre> </div>
 at first you should define your morph relation  in your model . we create files relation
 and and put it in trait .for use this relation should use it in your model .
 <div class="highlight highlight-text-html-php-blade"><pre>
   public function files()
   {
          return $this->morphToMany('ArtinCMS\LFM\Models\File' , 'fileable','lfm_fileables','fileable_id','file_id')->withPivot('type')->withTimestamps() ;
   }
 </pre> </div>
 you can use this trait or create your morph relation .
 the $obj_model is name of your model , $section is name of your section , $type is type of file (music,picture,zip,..) its optional 
 and default is null , $relation_name is name of morph relation and default is 'files' and with $attach_type you can select attach or sync file.
 <h4>Save single File</h4>
 if you want save file in one to many relation and save file_id in your table models you should use this helpers .
 <div class="highlight highlight-text-html-php-blade"><pre>
 LFM_SaveSingleFile($obj_model, $column_name, $section,$column_option_name)
 </pre> </div>
 The $obj_model is name of your model ($article = new Article), and column_name is name of 
 column you want save inserted id for example 'default_img_file_id' , $section is name of section and $column_option_name is 
  name of column that save options(for example save width,height,quality,.. in json format).
  <h4>Direct upload </h4>
  if you want upload file in specific path you can use this helpers . 
  <div class="highlight highlight-text-html-php-blade"><pre>
  LFM_CreateModalUpload($section, $callback , $options, $result_area_id , $modal_id , $header , $button_id , $button_content)
  </pre> </div>
  the input helper functions is same LFM_CreateModalFileManager with diffrent $result_area_id that define area id when 
  upload file . you should define your favorit path in options as below .
   <div class="highlight highlight-text-html-php-blade"><pre>
           $options = ['size_file' => 1000, 'max_file_number' =>5, 'min_file_number' => 2,'show_file_uploaded'=>'medium', 'true_file_extension' => ['png','jpg','zip'],'path'=>'myuploads/sdadeghi'];
   </div>
  <h3>load files</h3>
  <h4>Load Multi Files</h4>
  <div class="highlight highlight-text-html-php-blade"><pre>
  for load files (for example you can use this helper functons when you want edit) you can use this helpers function
  LFM_LoadMultiFile($obj_model, $section, $type = null, $relation_name = 'files')
 </pre> </div>
 the input this helpers funciton is same to LFM_SaveMultiFile and you can get files .Remember you should use same section
 name when you save file and load it .you can delete our insert new file after load files .
 <h4>Load Single Files </h4>
 for load single file you can use this helpers as below :
 <div class="highlight highlight-text-html-php-blade"><pre>
 LFM_loadSingleFile($obj_model, $column_name, $section, $column_option_name = false)
 </pre></div>
 the input this helpers function is same to LFM_SaveSingleFile . just remember you should same section name when you save
 and load file .you can delete our insert new file after load files .
 <h3>Show Files</h3>
 <h4>Show Multi File</h4>
 for show inserted files you can use this helpers function .
 <div class="highlight highlight-text-html-php-blade"><pre>
    LFM_ShowMultiFile($obj_model, $type, $relation_name )  
 </pre></div>
 The diffrent between this function and LFM_LoadMultiFile is , you cant delete files .
 <h4>Show Single Files</h4>
  for show single files you can this helpers function . remember you cant delete file .
<div class="highlight highlight-text-html-php-blade"><pre>
LFM_ShowingleFile($obj_model, $column_name, $column_option_name)
</pre></div>
 <h3>Generate Link</h3>
 <h4> Generate Download Link </h4>
  whit this below helper function you can generate download link in anywhere of your project .
  <div class="highlight highlight-text-html-php"><pre>
 LFM_GenerateDownloadLink($type, $id , $size_type, $default_img, $quality , $width, $height)
   </pre> </div>
<ul>
<li>if you want generate file link with ID the $type = 'ID' and if you want generate by name you can use $type = 'Name'
</li>
<li>$id : if you want generate link with id you should fill this item with file id and default is -1 that reffer no file id selected .</li>
<li>$size_type can pick one on of : original , large , medium , small that reffer which size you want to download</li>
<li>if image not found with $default_img you can choose image you want to show , default is '404.png'</li>
<li>$quality is quality of image , it is between 0 and 100 . </li>
<li>$width is width of result image</li>
<li>$height is height of result image</li>
</ul>  
 <h4>Generate Base64 Image</h4>
  whit this below helper function you can create Base64 Image .
   <div class="highlight highlight-text-html-php"><pre>
 LFM_GetBase64Image($file_id, $size_type, $not_found_img , $inline_content , $quality , $width , $height )    </pre> </div>
 this helpers config as above with different $inline_content that if it was true you can create base 64 Image .
 <h3>Genrate public link</h3>
 if you create shortcut from storage/public_folder in your public folder you can access file directly . as see in below box you can create public download path with this 
 helper function
 <div class="highlight highlight-text-html-php"><pre>
    LFM_GeneratePublicDownloadLink($path,$filename)     </pre> </div>
    that $psth is path to file and $filename is name of disc file . for example you want access the logo site picture you can 
    upload this file in public folder and access directly with above helper function .
 <h3>Custom config</h3>
 
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
 
<h1>Example</h1>
THe full Example : 
<h5>Example of use LFM_SaveMultiFile and LFM_SaveSingleFile <h5>
<p>
 in this example we assing zip file and user profile picture to article and save it .
 at first in Route :</p>
  
 <div class="highlight highlight-text-html-php-blade"><pre>
 Route::get('/MultiFile', 'HomeController@multiSection')->name('multiSection');
 Route::get('/MultiFile/{id}', 'HomeController@multiSectionEdit')->name('multiSectionEdit');
 Route::get('/ShowMultiFile/{id}', 'HomeController@showMultiFile')->name('showMultiFile');
 Route::post('/StoreArticle', ['as' => 'StoreArticle', 'uses' => 'HomeController@StoreArticle']);
 Route::post('/StoreEditArticle', ['as' => 'StoreEditArticle', 'uses' => 'HomeController@StoreEditArticle']);
</pre> </div>
you should define your relation in article models :
<div class="highlight highlight-text-html-php-blade"><pre>
use ArtinCMS\LFM\Traits\lfmFillable ;
use ArtinCMS\LFM\Models\File;
class Article extends Model
{
    use lfmFillable;
}
</pre></div>
as you see multisection route use for save article with zip file and profile picture . in your controller :
<div class="highlight highlight-text-html-php-blade"><pre>
    public function multiSection()
    {
            $optionsAttach = ['size_file' => 100, 'max_file_number' => 5, 'true_file_extension' => ['zip']];
            $option_single = ['size_file' => 100, 'max_file_number' => 1, 'true_file_extension' => ['png','jpg']];
            $attach =  LFM_CreateModalFileManager('Attach',$optionsAttach , 'insert','showAttach');
            $single = LFM_CreateModalFileManager('Single',$option_single , 'insert','showSingle');
            return view('multiSection',compact('attach','single'));
    }
    </pre> 
    <pre>
     public function multiSectionEdit($id)
    {
        $optionsAttach = ['size_file' => 100, 'max_file_number' => 5, 'true_file_extension' => ['zip']];
        $option_single = ['size_file' => 100, 'max_file_number' => 1, 'true_file_extension' => ['png','jpg']];
        $attach =  LFM_CreateModalFileManager('Attach',$optionsAttach , 'insert','showAttach');
        $article = Article::find($id);
        $load_attch = LFM_LoadMultiFile($article,'Attach','zip','files') ;
        $single = LFM_CreateModalFileManager('Single',$option_single , 'insert','showSingle');
        $load_single = LFM_loadSingleFile($article,'default_img_file_id','Single');
        return view('multiSectionedit',compact('attach','single','load_attch','load_single','article'));
    }
    </pre>
    <pre>
    public function showMultiFile ($id)
    {
        $article = Article::find($id);
        $show_attch = LFM_ShowMultiFile($article,'zip','files') ;
        $show__single = LFM_ShowingleFile($article,'default_img_file_id','Single');
        return view('showMultiFile',compact('show_attch','show__single','article'));
    }
    </pre>
    <pre>
    public function StoreArticle(Request $request)
    {
        $article = new Article ;
        $article->title = $request->title ;
        $article->body = $request->body ;
        $article->save() ;
        $res['Attach'] = LFM_SaveMultiFile($article,'Attach','zip','files' , 'attach');
        $res['Single'] = LFM_SaveSingleFile($article,'default_img_file_id','Single','options');
        return $res ;
    }
    </pre>
    <pre>
    public function StoreEditArticle(Request $request)
    {
        $article =Article::find($request->id);
        $res['Attach'] = LFM_SaveMultiFile($article,'Attach','zip','files' , 'sync');
        $res['Single'] = LFM_SaveSingleFile($article,'default_img_file_id','Single','options');
        return $res ;
    }
    </pre>
     </div>

and in multisection.blade.php
 <div class="highlight highlight-text-html-php"><pre>
    {!! $attach['button'] !!}
    {!! $attach['modal_content'] !!}
   div#show_area_Attach_list
     function showAttach(res) {
        $('#show_area_Attach_list').html(res.Attach.view.list) ;
        $('#show_area_Attach_grod').html(res.Attach.view.grid) ;
        $('#show_area_Attach_small').html(res.Attach.view.small) ;
        $('#show_area_Attach_medium').html(res.Attach.view.medium) ;
        $('#show_area_Attach_large').html(res.Attach.view.large) ;
        }
</pre> </div>

and in multisectioneditl.blade.php 

<div class="highlight highlight-text-html-php"><pre>
    {!! $attach['button'] !!}
    {!! $attach['modal_content'] !!}
    div#show_area_Attach  {!! $load_attch['view']['list'] !!}
  function showAttach(res) {
        $('#show_area_Attach').html(res.Attach.view.list,'slider') ;
    }
</pre> </div>
