<?php
/* Hamahang File Manager :: LFM*/

function LFM_SetSessionOption($section, $option)
{
    $LFM = session()->get('LFM');
    $mime = [];
    if (isset($option['true_file_extension']))
    {
        foreach ($option['true_file_extension'] as $ext)
        {
            $MimeType = ArtinCMS\LFM\Models\FileMimeType::where('ext', '=', $ext)->first();
            if ($MimeType)
            {
                $mime[] = $MimeType->mimeType;
            }
        }
        $option['true_mime_type'] = $mime;
    }
    $LFM[$section]['options'] = $option;
    $LFM[$section]['selected'] = ['data' => [], 'view' => []];
    session()->put('LFM', $LFM);
    return $LFM;
}

function LFM_Sanitize($string, $force_lowercase = true, $anal = false)
{
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]", "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;", "â€”", "â€“", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;
    return ($force_lowercase) ? (function_exists('mb_strtolower')) ? mb_strtolower($clean, 'UTF-8') : strtolower($clean) : $clean;
}

function LFM_SmartCropIMG($file, $options = [])
{
    $smartcrop = new \ArtinCMS\LFM\Helpers\Classes\SmartCropClass($file, $options);
    //Analyse the image and get the optimal crop scheme
    $res = $smartcrop->analyse();
    //Generate a crop based on optimal crop scheme
    return $smartcrop->crop($res['topCrop']['x'], $res['topCrop']['y'], $res['topCrop']['width'], $res['topCrop']['height']);
}

function LFM_SaveCompressImage($prepare_src = false, $file, $destination, $extension = 'jpg', $quality = 90)
{
    $res = false;
    if ($prepare_src)
    {
        switch ($extension)
        {
            case "png":
                $src = imagecreatefrompng($file);
                $res = imagepng($src, $destination, $quality);
                break;
            case "jpeg":
            case "jpg":
                $src = imagecreatefromjpeg($file);
                $res = imagejpeg($src, $destination, $quality);
                break;
        }
    }
    else
    {
        switch ($extension)
        {
            case "png":
                //$src = imagecreatefrompng($file);
                $res = imagepng($file, $destination, $quality);
                break;
            case "jpeg":
            case "jpg":
                //$src = imagecreatefromjpeg($file);
                $res = imagejpeg($file, $destination, $quality);
                break;
        }
    }

    return $res;
}

function LFM_CheckMimeType($mimetype, $items)
{
    if ($items)
    {
        foreach ($items as $item)
        {
            $file = \ArtinCMS\LFM\Models\File::find($item['id']);
            if (!in_array($file->mimeType, $mimetype))
            {
                $result['success'] = false;
                $result['error'] = 'File ' . $file->originalName . ' Not true mime type';
                $result['item_error'] = $item;
                return $result;
            }
            else
            {
                $result['success'] = true;
            }
        }
    }
    else
    {
        $result['success'] = false;
    }

    return $result;
}

function LFM_FindSessionSelectedId($selected, $id)
{
    foreach ($selected as $select)
    {
        if ($select['file']['id'] == $id)
        {
            return true;
        }
    }
    return false;
}

function LFM_CheckFalseString($input, $replace_input = "false")
{
    if ($input)
    {
        return $input;
    }
    else
    {
        return $replace_input;
    }
}

function LFM_GetSection($section)
{
    $LFM = session()->get('LFM');
    if (isset($LFM[$section]))
    {
        return $LFM[$section];
    }
    else
    {
        return false;
    }
}

function LFM_GetSectionFile($section)
{
    $sec = LFM_GetSection($section);
    if ($sec && isset($sec['selected']['data']) && count($sec['selected']['data']) >= 1)
    {
        return $sec['selected']['data'];
    }
    else
    {
        return false;
    }
}

function LFM_SaveSingleFile($obj_model, $column_name, $section,$column_option_name=false)
{
    $files = LFM_GetSectionFile($section);
    if ($files)
    {
        if (isset($files[0]['file']) && isset($files[0]['file']['id']))
        {
            $obj_model->$column_name = $files[0]['file']['id'];//first select
            if($column_option_name)
            {
                $obj_model->$column_option_name = json_encode($files[0]['file']);//first select
            }
            $res = $obj_model->save();
            if($res)
            {
                LFM_DestroySection($section);
            }
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function LFM_SaveMultiFile($obj_model, $section, $type = null, $relation_name = 'files', $attach_type = 'attach')
{
    if ($attach_type != 'attach')
    {
        $attach_type = 'sync';
    }
    $files = LFM_GetSectionFile($section);
    if ($files)
    {
        $arr_ids = [];
        foreach ($files as $file)
        {
            if (isset($file['file']['id']))
            {
                $arr_ids[$file['file']['id']] = ['type' => $type];
            }
        }
        $res = $obj_model->$relation_name()->$attach_type($arr_ids);
        if($res)
        {
            LFM_DestroySection($section);
        }
        return $res;
    }
    else
    {
        return false;
    }
}

function LFM_LoadMultiFile($obj_model, $section, $type = null, $relation_name = 'files')
{
    $files = $obj_model->$relation_name()->where('type','=',$type)->get() ;
    $LFM = session()->get('LFM') ;
    if($LFM)
    {
        if (isset($LFM[$section]))
        {
            $data = [] ;
            $LFM[$section]['selected'] = ['data' => [], 'view' => []];
            foreach ($files as $file)
            {
                $res['file']=$file ;
                $res['full_url']=LFM_GenerateDownloadLink('ID',$file->id,'orginal') ;
                $data[] = $res ;
            }
            $view = LFM_SetInsertedView($section,  $data) ;
            $LFM[$section]['selected']['data'] = $data ;
            $LFM[$section]['selected']['view'] = $view ;
            session()->put('LFM',$LFM) ;
            $result['data']= $data ;
            $result['view']=$view ;
        }
    }
    return  $result ;
}

function LFM_loadSingleFile($obj_model, $column_name, $section,$column_option_name=false)
{
    $files[] = \ArtinCMS\LFM\Models\File::find($obj_model->$column_name);
    $LFM = session()->get('LFM') ;
    if($LFM)
    {
        if (isset($LFM[$section]))
        {
            $data = [] ;
            $LFM[$section]['selected'] = ['data' => [], 'view' => []];
            foreach ($files as $file)
            {
                $res['file']=$file ;
                $res['full_url']=LFM_GenerateDownloadLink('ID',$file->id,'orginal') ;
                $data[] = $res ;
            }
            $view = LFM_SetInsertedView($section,  $data) ;
            $LFM[$section]['selected']['data'] = $data ;
            $LFM[$section]['selected']['view'] = $view ;
            session()->put('LFM',$LFM) ;
            $result['data']= $data ;
            $result['view']=$view ;
        }
    }
    return  $result ;
}

function LFM_DestroySection($section)
{
    if (session()->has('LFM'))
    {
        $LFM = session()->get('LFM');
        if (isset($LFM[$section]))
        {
            $LFM[$section]['selected'] = ['data' => [], 'view' => []];
            session()->put('LFM', $LFM);
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function LFM_GenerateDownloadLink($type = "ID", $id = -1, $size_type = 'orginal', $default_img = '404.png', $quality = 100, $width = false, $height = false)
{
    return route('LFM.DownloadFile', ['type' => 'ID', 'id' => $id, 'size_type' => $size_type, 'default_img' => $default_img, 'quality' => $quality, 'width' => $width, 'height' => $height]);
}

function LFM_GetBase64Image($file_id, $size_type = 'orginal', $not_found_img = '404.png', $inline_content = false, $quality = 90, $width = false, $height = False)
{
    $res = \ArtinCMS\LFM\Helpers\Classes\Media::downloadById($file_id, $size_type, $not_found_img, true, $quality, $width, $height);
    return $res;
}

function LFM_FileSizeConvert($bytes)
{
    $result = "";
    $bytes = floatval($bytes);
    $arBytes =
        [
            0 => ["UNIT" => "TB", "VALUE" => pow(1024, 4)],
            1 => ["UNIT" => "GB", "VALUE" => pow(1024, 3)],
            2 => ["UNIT" => "MB", "VALUE" => pow(1024, 2)],
            3 => ["UNIT" => "KB", "VALUE" => 1024],
            4 => ["UNIT" => "B", "VALUE" => 1]
        ];

    foreach ($arBytes as $arItem)
    {
        if ($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", ",", strval(round($result, 1))) . " " . $arItem["UNIT"];
            break;
        }
    }
    return $result;
}

function LFM_ConvertMimeTypeToExt($mimeTypes)
{
    $res = '';
    $text = '' ;
    foreach ($mimeTypes as $mime)
    {
        $extensions = \ArtinCMS\LFM\Models\FileMimeType::select('ext')->where('mimeType', '=', $mime)->get();
        foreach($extensions as $ex)
        {
            $res .= "'$ex->ext',";
            $text .=".$ex->ext,";
        }

    }
    $result['ext'] = rtrim($res,',');
    $result['accept'] = $text;
    return $result;
}

function LFM_BuildMenuTree($flat_array, $pidKey, $openNodes = true, $selectedNode = false, $parent = 0 , $idKey = 'id', $children_key = 'children')
{
    $grouped = array();
    foreach ($flat_array as $sub)
    {
        $sub['text'] = $sub['title'];
        $sub['a_attr']=['class'=> 'link_to_category jstree_a_tag','data-id'=>$sub['id']];
        if ($sub['id'] == (int)$selectedNode)
        {
            $sub['state'] = ['selected' => true , 'opened' =>true] ;

        }
        $grouped[$sub[$pidKey]][] = $sub;

    }
    $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey, $children_key)
    {
        foreach ($siblings as $k => $sibling)
        {
            $id = $sibling[$idKey];
            if (isset($grouped[$id]))
            {
                $sibling[$children_key] = $fnBuilder($grouped[$id]);
            }
            $siblings[$k] = $sibling;
        }
        return $siblings;
    };
    if (isset($grouped[$parent]))
    {
        $tree = $fnBuilder($grouped[$parent]);
    }
    else
    {
        $tree = [];
    }
    return $tree;
}

function LFM_GeneratePublicDownloadLink($path,$filename,$type='orginal')
{
    $p = str_replace('public_folder/','',$path);
    $path = str_replace('//','/',config('laravel_file_manager.symlink_public_folder_name').'/'.$p.'/files/'.$type.'/'.$filename );
    return url($path) ;
}

function LFM_GetAllParentId($id)
{
    $parrents_id = [] ;
    $cats = \ArtinCMS\LFM\Models\Category::all_parents($id) ;
    foreach($cats as $cat){
        $parrents_id[]=$cat->id ;
    }
    return $parrents_id ;
}

function LFM_GetFoolderPath($id,$cat_name='undefined',$file_name=false)
{
    $result = [];
    $path = '' ;
    while ($id != 0)
    {
        $cat = \ArtinCMS\LFM\Models\Category::with('parent_category')->find($id);
        if ($id == -2 || $id == -1)
        {
          $id = 0 ;
        }
        else
        {
            if(isset($cat->parent_category_id))
            {
                $result[] = $cat;
                $id = $cat->parent_category_id;
            }
            else
            {
                $id = 0 ;
            }

        }

    }
    $parents = array_reverse($result);
    if ($parents)
    {
        foreach ($parents as $parent)
        {
            if ($parent->parent_category)
            {
                $path .=  $parent->parent_category->title.'/';
            }
        }
    }
    $path .=$cat_name ;
    if ($file_name)
    {
        $path .=  '/'.$file_name;
    }
   return $path ;
}

function LFM_CheckAllowInsert($section_name)
{
    $section = LFM_GetSection($section_name) ;
    if ($section['options']['max_file_number'] !=false)
    {
        $available = $section['options']['max_file_number'] - count($section['selected']['data']) ;
        if ($available == 0)
        {
            $result['success'] = false ;
            $result['available'] = 0;
        }
        else
        {
            $result['success'] = true ;
            $result['available'] = $available;
        }
    }
    else
    {
        $result['success'] = false ;
        $result['available'] = 'undefined' ;
    }
    return $result;
}

function LFM_CreateModalFileManager($section, $options = false, $insert = 'insert', $callback = false, $modal_id = false, $header = false, $button_id = false, $button_content = 'input file')
{
    if(!$header)
    {
        $header = 'File Manager '.$section ;
    }
    if(!$button_id)
    {
        $button_id = 'show_modal_'.$section;
    }
    if (!$modal_id)
    {
        $modal_id = 'FileManager_'.$section ;
    }
    if ($options)
    {
        $session_option = LFM_SetSessionOption($section, $options);
    }
    //create html content and button
    $src = route('LFM.ShowCategories', ['section' => $section, 'insert' => $insert, 'callback' => LFM_CheckFalseString($callback)]);
    $available = LFM_CheckAllowInsert($section)['available'] ;
    $result['modal_content'] = view("laravel_file_manager::create_modal", compact("src", "modal_id", 'header', 'button_content', 'section', 'callback', 'button_id','available'))->render();
    $result['button'] = '<button type="button" class="btn btn-default"  id="' . $button_id . '">' . $button_content . '</button>';
    return $result;
}

function LFM_CreateModalUpload($section,$category_id,$callback='show_upload_file',$options=[],$result_area_id = false,$modal_id = 'UploadFileManager', $header = 'Upload_FileManager',$button_id = 'ShowModalUpload',$button_content = 'Upload'){
    LFM_SetSessionOption($section,$options) ;
    $available = LFM_CheckAllowInsert($section)['available'] ;
    $src = route('LFM.FileUploadForm', ['section' => $section, 'callback' => $callback,'category_id'=> $category_id] );
    $result['modal_content'] = view("laravel_file_manager::upload.create_uplod_modal", compact("src", "modal_id", 'category_id' ,'header', 'button_content', 'section', 'callback', 'button_id','available','result_area_id','options'))->render();
    $result['button'] = '<button type="button" class="btn btn-default"  id="' .$button_id . '"  data-toggle="modal">' . $button_content . '</button>';
    return $result;
}

function LFM_SetInsertedView($section, $data)
{
    $view['list'] = LFM_ListInsertedView($data, $section);
    $view['grid'] =LFM_GridInsertedView($data, $section);
    $view['small'] = LFM_SmallInsertedView($data, $section);
    $view['medium'] = LFM_MediumInsertedView($data, $section);
    $view['large'] = LFM_LargeInsertedView($data, $section);
    return $view;
}
function LFM_ListInsertedView($data, $section = false)
{
    return view('laravel_file_manager::selected.list_inserted_view', compact('data', 'section'))->render();
}

function LFM_GridInsertedView($data, $section = false)
{
    return view('laravel_file_manager::selected.grid_inserted_view', compact('data', 'section'))->render();
}

function LFM_SmallInsertedView($data, $section = false)
{
    return view('laravel_file_manager::selected.small_inserted_view', compact('data', 'section'))->render();
}

function LFM_MediumInsertedView($data, $section = false)
{
    return view('laravel_file_manager::selected.medium_inserted_view', compact('data', 'section'))->render();
}

function LFM_LargeInsertedView($data, $section = false)
{
    return view('laravel_file_manager::selected.large_inserted_view', compact('data', 'section'))->render();
}




