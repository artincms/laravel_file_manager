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
    $LFM[ $section ]['options'] = $option;
    $LFM[ $section ]['selected'] = ['data' => [], 'view' => []];
    session()->put('LFM', $LFM);

    return $LFM;
}

function LFM_Sanitize($string, $force_lowercase = true, $anal = false)
{
    $strip = ["~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]", "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;", "â€”", "â€“", ",", "<", ".", ">", "/", "?"];
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
            $file = \ArtinCMS\LFM\Models\File::find(LFM_GetDecodeId($item['id']));
            if (!in_array($file->mimeType, $mimetype))
            {
                $result['success'] = false;
                $result['error'] = 'File ' . $file->original_name . ' Not true mime type';
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

function LFM_CheckFalseString($input, $replace_input = "false", $remove_underline = false)
{
    if ($input)
    {
        if ($remove_underline)
        {
            $input = str_replace('_', '', $input);
        }

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
    if (isset($LFM[ $section ]))
    {
        return $LFM[ $section ];
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

function LFM_SaveSingleFile($obj_model, $column_name, $section, $column_option_name = false)
{
    $files = LFM_GetSectionFile($section);
    if ($files)
    {
        if (isset($files[0]['file']) && isset($files[0]['file']['id']))
        {
            $obj_model->$column_name = LFM_GetDecodeId($files[0]['file']['id']);//first select
            if ($column_option_name)
            {
                $obj_model->$column_option_name = json_encode($files[0]['file']);//first select
            }
            $res = $obj_model->save();
            if ($res)
            {
                LFM_DestroySection($section);
            }

            return $obj_model;
        }
        else
        {
            return false;
        }
    }
    else
    {
        $obj_model->$column_name = null;
        $obj_model->$column_option_name;
        $obj_model->save();

        return false;
    }
}

function LFM_SaveMultiFile($obj_model, $section, $type = null, $relation_name = 'files', $attach_type = 'attach')
{
    if ($attach_type != 'attach')
    {
        $attach_type = 'sync';
    }
    $arr_ids = [];
    $files = LFM_GetSectionFile($section);
    if ($files)
    {
        foreach ($files as $file)
        {
            if (isset($file['file']['id']))
            {
                $arr_ids[ LFM_GetDecodeId($file['file']['id']) ] = ['type' => $type];
            }
        }
        if ($arr_ids)
        {
            $obj_model->$relation_name()->wherePivot('type', '=', $type)->$attach_type($arr_ids);
            LFM_DestroySection($section);
            $result['success'] = true;
            $result['data'] = $arr_ids;
        }
        else
        {
            $result['success'] = false;
            $result['data'] = $arr_ids;
        }

        return $result;
    }
    else
    {
        $res = $obj_model->$relation_name()->wherePivot('type', '=', $type)->$attach_type($arr_ids);

        return false;
    }
}

function LFM_LoadMultiFile($obj_model, $section, $type = null, $relation_name = 'files')
{
    if ($obj_model)
    {
        $files = $obj_model->$relation_name()->where('type', '=', $type)->get();
        $LFM = session()->get('LFM');
        if ($LFM)
        {
            if (isset($LFM[ $section ]))
            {
                $data = [];
                $LFM[ $section ]['selected'] = ['data' => [], 'view' => []];
                foreach ($files as $file)
                {
                    $res['file'] = [
                        'id'            => LFM_getEncodeId($file->id),
                        'original_name' => $file->original_name,
                        'type'          => 'original',
                        'size'          => $file->size,
                    ];
                    if (in_array($file->mimeType, config('laravel_file_manager.allowed_pic')))
                    {
                        $res['file']['icon'] = 'image';
                    }
                    else
                    {
                        $class = $file->FileMimeType->icon_class;
                        if ($class)
                        {
                            $res['file']['icon'] = $file->FileMimeType->icon_class;
                        }
                        else
                        {
                            $res['file']['icon'] = 'fa-file-o';
                        }
                    }
                    $res['file']['created'] = $file->created_at;
                    $res['file']['updated'] = $file->updated_at;
                    if (isset($file->user->name))
                    {
                        $res['file']['user'] = $file->user->name;
                    }
                    else
                    {
                        $res['file']['user'] = 'Public';
                    }
                    $res['success'] = true;
                    $res['full_url'] = LFM_GenerateDownloadLink('ID', $file->id, 'original');
                    $res['full_url_medium'] = LFM_GenerateDownloadLink('ID', $file->id, 'medium');
                    $res['full_url_large'] = LFM_GenerateDownloadLink('ID', $file->id, 'large');
                    $data[] = $res;
                }
                $LFM[ $section ]['selected']['data'] = $data;
                session()->put('LFM', $LFM);
                $view = LFM_SetInsertedView($section, $data);
                $LFM[ $section ]['selected']['view'] = $view;
                session()->put('LFM', $LFM);
                $result['data'] = $data;
                $result['view'] = $view;
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
    else
    {
        abort(404);
    }

    return $result;
}

function LFM_ShowMultiFile($obj_model, $type = null, $relation_name = 'files')
{
    //if $direct is true your disc is driver_disk_upload
    $data = [];
    $view = ['list' => '', 'grid' => '', 'large' => '', 'medium' => '', 'small' => ''];
    if ($obj_model)
    {
        $files = $obj_model->$relation_name()->where('type', '=', $type)->get();
        if ($files)
        {
            foreach ($files as $file)
            {
                $res['file'] = $file;
                if (in_array($file->mimeType, config('laravel_file_manager.allowed_pic')))
                {
                    $res['file']['icon'] = 'image';
                }
                else
                {
                    $class = $file->FileMimeType->icon_class;
                    if ($class)
                    {
                        $res['file']['icon'] = $file->FileMimeType->icon_class;
                    }
                    else
                    {
                        $res['file']['icon'] = 'fa-file-o';
                    }
                }
                $res['file']['created'] = $file->created_at;
                $res['file']['updated'] = $file->updated_at;
                if (isset($file->user->name))
                {
                    $res['file']['user'] = $file->user->name;
                }
                else
                {
                    $res['file']['user'] = 'Public';
                }
                $res['full_url'] = LFM_GenerateDownloadLink('ID', $file->id, 'original');
                $res['full_url_medium'] = LFM_GenerateDownloadLink('ID', $file->id, 'medium');
                $res['full_url_large'] = LFM_GenerateDownloadLink('ID', $file->id, 'large');
                $data[] = $res;
            }
            $view = LFM_SetInsertedView('Show', $data, true);
        }
    }
    else
    {
        abort(404);
    }
    $result['data'] = $data;
    $result['view'] = $view;

    return $result;
}

function LFM_loadSingleFile($obj_model, $column_name, $section, $column_option_name = false)
{
    $data = [];
    $view = ['list' => '', 'grid' => '', 'large' => '', 'medium' => '', 'small' => ''];
    if ($obj_model)
    {
        $files[] = \ArtinCMS\LFM\Models\File::find($obj_model->$column_name);
        $LFM = session()->get('LFM');
        if ($files[0])
        {
            if ($LFM)
            {
                if (isset($LFM[ $section ]))
                {
                    $data = [];
                    $LFM[ $section ]['selected'] = ['data' => [], 'view' => []];
                    foreach ($files as $file)
                    {
                        $res['file'] = [
                            'id'            => LFM_getEncodeId($file->id),
                            'original_name' => $file->original_name,
                            'type'          => 'original',
                            'size'          => $file->size,
                        ];
                        if (in_array($file->mimeType, config('laravel_file_manager.allowed_pic')))
                        {
                            $res['file']['icon'] = 'image';
                        }
                        else
                        {
                            $class = $file->FileMimeType->icon_class;
                            if ($class)
                            {
                                $res['file']['icon'] = $file->FileMimeType->icon_class;
                            }
                            else
                            {
                                $res['file']['icon'] = 'fa-file-o';
                            }
                        }
                        $res['file']['created'] = $file->created_at;
                        $res['file']['updated'] = $file->updated_at;
                        if (isset($file->user->name))
                        {
                            $res['file']['user'] = $file->user->name;
                        }
                        else
                        {
                            $res['file']['user'] = 'Public';
                        }
                        $res['full_url'] = LFM_GenerateDownloadLink('ID', $file->id, 'original');
                        $res['full_url_medium'] = LFM_GenerateDownloadLink('ID', $file->id, 'medium');
                        $res['full_url_large'] = LFM_GenerateDownloadLink('ID', $file->id, 'large');
                        $data[] = $res;
                    }
                    $LFM[ $section ]['selected']['data'] = $data;
                    session()->put('LFM', $LFM);
                    $view = LFM_SetInsertedView($section, $data);
                    $LFM[ $section ]['selected']['view'] = $view;
                    session()->put('LFM', $LFM);

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
    }

    $result['data'] = $data;
    $result['view'] = $view;

    return $result;
}

function LFM_ShowingleFile($obj_model, $column_name, $column_option_name = false)
{
    $data = [];
    $view = ['list' => '', 'grid' => '', 'large' => '', 'medium' => '', 'small' => ''];
    if ($obj_model)
    {
        $files[] = \ArtinCMS\LFM\Models\File::find($obj_model->$column_name);
        if (isset($files[0]))
        {
            foreach ($files as $file)
            {
                $res['file'] = $file;
                if (in_array($file->mimeType, config('laravel_file_manager.allowed_pic')))
                {
                    $res['file']['icon'] = 'image';
                }
                else
                {
                    $class = $file->FileMimeType->icon_class;
                    if ($class)
                    {
                        $res['file']['icon'] = $file->FileMimeType->icon_class;
                    }
                    else
                    {
                        $res['file']['icon'] = 'fa-file-o';
                    }
                }
                $res['file']['created'] = $file->created_at;
                $res['file']['updated'] = $file->updated_at;
                if (isset($file->user->name))
                {
                    $res['file']['user'] = $file->user->name;
                }
                else
                {
                    $res['file']['user'] = 'Public';
                }
                $res['full_url'] = LFM_GenerateDownloadLink('ID', $file->id, 'original');
                $res['full_url_medium'] = LFM_GenerateDownloadLink('ID', $file->id, 'medium');
                $res['full_url_large'] = LFM_GenerateDownloadLink('ID', $file->id, 'large');
                $data[] = $res;
            }
            $view = LFM_SetInsertedView('Show', $data, true);
        }
    }
    else
    {
        abort(404);
    }
    $result['data'] = $data;
    $result['view'] = $view;

    return $result;
}

function LFM_DestroySection($section)
{
    if (session()->has('LFM'))
    {
        $LFM = session()->get('LFM');
        if (isset($LFM[ $section ]))
        {
            $LFM[ $section ]['selected'] = ['data' => [], 'view' => []];
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

function LFM_GenerateDownloadLink($type = "ID", $id = -1, $size_type = 'original', $default_img = '404.png', $quality = 100, $width = false, $height = false, $check_version = false)
{
    if ($type == 'ID' && isset($id) && !empty($id))
    {
        $id = LFM_getEncodeId($id);
    }
    elseif (!isset($id) || !empty($id))
    {
        $id = -1;
    }
    if ($check_version)
    {
        return lfm_secure_route('LFM.DownloadFile', ['type' => $type, 'id' => $id, 'size_type' => $size_type, 'default_img' => $default_img, 'quality' => $quality, 'width' => $width, 'height' => $height, 'v' => $check_version]);
    }
    else
    {
        return lfm_secure_route('LFM.DownloadFile', ['type' => $type, 'id' => $id, 'size_type' => $size_type, 'default_img' => $default_img, 'quality' => $quality, 'width' => $width, 'height' => $height]);
    }
}

function LFM_GetBase64Image($file_id, $size_type = 'original', $not_found_img = '404.png', $inline_content = false, $quality = 90, $width = false, $height = False)
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
    $text = '';
    foreach ($mimeTypes as $mime)
    {
        $extensions = \ArtinCMS\LFM\Models\FileMimeType::select('ext')->where('mimeType', '=', $mime)->get();
        foreach ($extensions as $ex)
        {
            $res .= "'$ex->ext',";
            $text .= ".$ex->ext,";
        }

    }
    $result['ext'] = rtrim($res, ',');
    $result['accept'] = $text;

    return $result;
}

function LFM_BuildMenuTree($flat_array, $pidKey, $openNodes = true, $selectedNode = false, $parent = 0, $idKey = 'id', $children_key = 'children')
{
    $grouped = [];
    foreach ($flat_array as $sub)
    {
        $sub['text'] = $sub['title'];
        $sub['a_attr'] = ['class' => 'link_to_category jstree_a_tag', 'data-id' => LFM_getEncodeId($sub['id'])];
        if ((int)$sub['id'] == (int)$selectedNode)
        {
            $sub['state'] = ['selected' => true, 'opened' => true];

        }
        $grouped[ $sub[ $pidKey ] ][] = $sub;

    }
    $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey, $children_key) {
        foreach ($siblings as $k => $sibling)
        {
            $id = $sibling[ $idKey ];
            if (isset($grouped[ $id ]))
            {
                $sibling[ $children_key ] = $fnBuilder($grouped[ $id ]);
            }
            $siblings[ $k ] = $sibling;
        }

        return $siblings;
    };
    if (isset($grouped[ $parent ]))
    {
        $tree = $fnBuilder($grouped[ $parent ]);
    }
    else
    {
        $tree = [];
    }

    return $tree;
}

function LFM_GetChildCategory($array_id)
{
    $category = [];
    $array_parent_id = [];
    $cats = \ArtinCMS\LFM\Models\Category::all();
    foreach ($cats as $cat)
    {
        //get parent
        $array_parent_id = [];
        $parent_id = $cat->id;
        while ($parent_id != '#')
        {
            $subcat = \ArtinCMS\LFM\Models\Category::find($parent_id);
            if (isset($subcat))
            {
                $array_parent_id[] = $subcat->id;
                $parent_id = $subcat->parent_category_id;
            }
            else
            {
                $parent_id = '#';
            }
        }
        foreach ($array_id as $id)
        {
            if (in_array($id, $array_parent_id))
            {
                $category[] = $cat;
            }
        }
    }
    if (in_array(0, $array_id))
    {
        $category[] = \ArtinCMS\LFM\Models\Category::find(0);
    }

    return $category;
}

function LFM_CreateArrayId($array)
{
    $array_id = [];
    foreach ($array as $value)
    {
        if (isset($value))
        {
            $array_id[] = $value->id;
        }
    }

    return $array_id;
}

function LFM_GeneratePublicDownloadLink($path, $filename, $type = 'original')
{
    $p = str_replace('public_folder/', '', $path);
    $path = str_replace('//', '/', config('laravel_file_manager.symlink_public_folder_name') . '/' . $p . '/files/' . $type . '/' . $filename);

    return url($path);
}

function LFM_GetAllParentId($id)
{
    $parrents_id = [];
    $cats = \ArtinCMS\LFM\Models\Category::all_parents($id);
    foreach ($cats as $cat)
    {
        $parrents_id[] = $cat->id;
    }

    return $parrents_id;
}

function LFM_GetFoolderPath($id, $cat_name = 'undefined', $file_name = false)
{
    $result = [];
    $path = '';
    while ($id != 0)
    {
        $cat = \ArtinCMS\LFM\Models\Category::with('parent_category')->find($id);
        if ($id == -2 || $id == -1)
        {
            $id = 0;
        }
        else
        {
            if (isset($cat->parent_category_id))
            {
                $result[] = $cat;
                $id = $cat->parent_category_id;
            }
            else
            {
                $id = 0;
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
                $path .= $parent->parent_category->title . '/';
            }
        }
    }
    $path .= $cat_name;
    if ($file_name)
    {
        $path .= '/' . $file_name;
    }

    return $path;
}

function LFM_CheckAllowInsert($section_name)
{
    $section = LFM_GetSection($section_name);
    if (isset($section['options']['max_file_number']))
    {
        $available = $section['options']['max_file_number'] - count($section['selected']['data']);
        if ($available == 0)
        {
            $result['success'] = false;
            $result['available'] = 0;
        }
        else
        {
            $result['success'] = true;
            $result['available'] = $available;
        }
    }
    else
    {
        $result['success'] = false;
        $result['available'] = 'undefined';
    }

    return $result;
}

function LFM_checkCatSeed()
{
    $category_id = [-2, -1, 0, -5];
    foreach ($category_id as $cat_id)
    {
        $res = \ArtinCMS\LFM\Models\Category::find($cat_id);
        if (!$res)
        {
            $html = '<h4>' . __('filemanager.please_run_seed_at_first') . '</h4><br/> ';
            if (app()->getLocale() == "fa")
            {
                $html .= '<div style="direction: rtl;text-align: right;">';
            }
            else
            {
                $html .= '<div>';
            }
            $html .= '<pre style="padding: 16px;overflow: auto;font-size: 85%;line-height: 1.45;background-color: #f6f8fa;border-radius: 3px;">  
                            php artisan db:seed --class="ArtinCMS\LFM\Database\Seeds\LFM_CategoryTableSeeder"
                        </pre>
                    </div>';

            return [
                'success' => false,
                'html'    => $html
            ];
        }
    }

    return [
        'success' => true,
    ];

}

function LFM_checkMymeTypeSeed()
{
    $mimetypes = \ArtinCMS\LFM\Models\FileMimeType::all();
    if (count($mimetypes) < 685)
    {
        $html = '<h4>' . __('filemanager.please_run_seed_at_first') . '</h4><br/> ';
        if (app()->getLocale() == "fa")
        {
            $html .= '<div style="direction: rtl;text-align: right;">';
        }
        else
        {
            $html .= '<div>';
        }
        $html .= '<pre style="padding: 16px;overflow: auto;font-size: 85%;line-height: 1.45;background-color: #f6f8fa;border-radius: 3px;">  
                            php artisan db:seed --class="ArtinCMS\LFM\Database\Seeds\LFM_MimeTypeTableSeeder"
                        </pre>
                    </div>';

        return [
            'success' => false,
            'html'    => $html
        ];
    }
    else
    {
        return [
            'success' => true,
        ];
    }
}

function LFM_checkSeed()
{
    $res_cat = LFM_checkCatSeed();
    $res_mime = LFM_checkMymeTypeSeed();
    if ($res_cat['success'] && $res_mime['success'])
    {
        return [
            'success' => true,
        ];
    }
    else
    {
        if (!$res_cat['success'] && !$res_mime['success'])
        {
            $html = '<h4>' . __('filemanager.please_run_seed_at_first') . '</h4><br/> ';
            if (app()->getLocale() == "fa")
            {
                $html .= '<div style="direction: rtl;text-align: right;">';
            }
            else
            {
                $html .= '<div>';
            }
            $html .= '<pre style="padding: 16px;overflow: auto;font-size: 85%;line-height: 1.45;background-color: #f6f8fa;border-radius: 3px;">  
                            php artisan db:seed --class="ArtinCMS\LFM\Database\Seeds\FilemanagerTableSeeder"
                        </pre>
                    </div>';

            return [
                'success' => false,
                'html'    => $html
            ];

            return [
                'success' => false,
                'html'    => $html
            ];
        }
        elseif (!$res_cat['success'])
        {
            return [
                'success' => false,
                'html'    => $res_cat['html']
            ];
        }
        else
        {
            return [
                'success' => false,
                'html'    => $res_mime['html']
            ];
        }
    }
}

function LFM_CreateModalFileManager($section, $options = false, $insert = 'insert', $callback = false, $modal_id = false, $header = false, $button_id = false, $button_content = false, $button_class = 'btn-default', $font_button_class = false)
{
    if (!$header)
    {
        $header = __('filemanager.filemanager');
    }
    if (!$button_id)
    {
        $button_id = 'show_modal_' . $section;
    }
    if (!$modal_id)
    {
        $modal_id = 'FileManager_' . $section;
    }
    if (!$button_content)
    {
        $button_content = __('filemanager.browse_file');
    }
    if ($options)
    {
        if (!isset($options['true_file_extension']))
        {
            $options['true_file_extension'] = config('laravel_file_manager.lfm_default_true_extension');
        }
        if (!isset($options['max_file_number']))
        {
            $options['max_file_number'] = 1;
        }
        if (!isset($options['size_file']))
        {
            $options['size_file'] = 2000;
        }
        $session_option = LFM_SetSessionOption($section, $options);
    }
    else
    {
        $options = ['size_file' => 2000, 'max_file_number' => 1, 'true_file_extension' => config('laravel_file_manager.lfm_default_true_extension')];
    }
    $true_myme_type = $options['true_file_extension'];
    $header = $header . '( <span style="font-size:80%"> پسوند های قابل استفاده : ' . implode(' , ', $true_myme_type) . '</span> )';
    //create html content and button
    $src = lfm_secure_route('LFM.ShowCategories', ['section' => $section, 'insert' => $insert, 'callback' => LFM_CheckFalseString($callback)]);
    $available = LFM_CheckAllowInsert($section)['available'];
    $check_seed = LFM_checkSeed();
    if ($check_seed['success'])
    {
        $options = isset(LFM_GetSection($section)['options']) ? LFM_GetSection($section)['options'] : [];
        $json = array_merge($options, ['section' => $section, 'callback' => $callback, 'upload_route' => lfm_secure_route('LFM.StoreUploads'), 'delete_session_route' => lfm_secure_route('LFM.DeleteSessionInsertItem')]);
        $result['modal_content'] = view("laravel_file_manager::create_modal", compact("src", "modal_id", 'header', 'button_content', 'section', 'callback', 'button_id', 'available', 'true_myme_type'))->render();
        $result['modal_content_html'] = view("laravel_file_manager::create_modal_html", compact("src", "modal_id", 'header', 'button_content', 'section', 'callback', 'button_id', 'available', 'true_myme_type'))->render();
        $result['script'] = view("laravel_file_manager::create_modal_script", compact("src", "modal_id", 'header', 'button_content', 'section', 'callback', 'button_id', 'available', 'true_myme_type'))->render();
        $result['button'] = '<button data-href="' . $src . '" type="button" class="btn ' . $button_class . '"  id="' . $button_id . '"><i class="' . $font_button_class . '"></i>' . $button_content . '</button>';
        $result['src'] = $src;
        $result['json'] = json_encode($json);
    }
    else
    {

        $result['modal_content'] = $check_seed['html'];
        $result['button'] = '';
    }

    return $result;
}

function LFM_CreateModalUpload($section, $callback = 'show_upload_file', $options = [], $result_area_id = false, $modal_id = 'UploadFileManager', $header = 'Upload_FileManager', $button_id = 'ShowModalUpload', $button_content = 'Upload', $button_class = 'btn-default', $font_button_class = false)
{
    $session = LFM_SetSessionOption($section, $options, config('laravel_file_manager.upload_route_prefix'));
    $available = LFM_CheckAllowInsert($section)['available'];
    $src = lfm_secure_route('LFM.DirectUpload', ['section' => $section, 'callback' => $callback]);
    $category_id = -5;
    $options = isset(LFM_GetSection($section)['options']) ? LFM_GetSection($section)['options'] : [];
    $json = array_merge($options, ['section' => $section, 'callback' => $callback, 'upload_route' => lfm_secure_route('LFM.StoreDirectUploads'), 'delete_session_route' => lfm_secure_route('LFM.DeleteSessionInsertItem')]);
    $result['modal_content'] = view("laravel_file_manager::upload.create_uplod_modal", compact("src", "modal_id", 'category_id', 'header', 'button_content', 'section', 'callback', 'button_id', 'available', 'result_area_id', 'options'))->render();
    $result['modal_content_html'] = view("laravel_file_manager::upload.create_uplod_modal_html", compact("src", "modal_id", 'category_id', 'header', 'button_content', 'section', 'callback', 'button_id', 'available', 'result_area_id', 'options'))->render();
    $result['script'] = view("laravel_file_manager::upload.create_uplod_modal_script", compact("src", "modal_id", 'category_id', 'header', 'button_content', 'section', 'callback', 'button_id', 'available', 'result_area_id', 'options'))->render();
    $result['button'] = '<button type="button" class="btn ' . $button_class . '"  id="' . $button_id . '" data-toggle="modal" data-href="' . $src . '"> <i class="' . $font_button_class . '"></i>' . $button_content . '</button>';
    $result['src'] = $src;
    $result['json'] = json_encode($json);

    return $result;
}

function LFM_SetInsertedView($section, $data, $show = false)
{
    switch (config('laravel_file_manager.insertde_view_theme'))
    {
        case 'bootstrap_v4' :
            $path = 'selected.bootstrap.bootstrap_v_4';
            break;
        case 'bootstrap_v3' :
            $path = 'selected.bootstrap.bootstrap_v_3';
            break;
        case 'inline' :
            $path = 'selected.inline';
            break;

        default :
            $path = 'selected.bootstrap.bootstrap_v_4';
            break;
    }
    $view['list'] = view('laravel_file_manager::' . $path . '.list_inserted_view', compact('data', 'section', 'show'))->render();
    $view['grid'] = view('laravel_file_manager::' . $path . '.grid_inserted_view', compact('data', 'section', 'show'))->render();
    $view['small'] = view('laravel_file_manager::' . $path . '.small_inserted_view', compact('data', 'section', 'show'))->render();
    $view['medium'] = view('laravel_file_manager::' . $path . '.medium_inserted_view', compact('data', 'section', 'show'))->render();
    $view['large'] = view('laravel_file_manager::' . $path . '.large_inserted_view', compact('data', 'section', 'show'))->render();

    return $view;
}

function LFM_SetSelectedFileToSession($request, $section, $data)
{
    if ($request->has('section'))
    {
        if (session()->has('LFM'))
        {
            $LFM = session()->get('LFM');
            if (isset($LFM[ $request->section ]))
            {
                $result['success'] = true;
                $LFM[ $section ]['selected']['data'] = array_merge($LFM[ $section ]['selected']['data'], $data);
                $LFM[ $section ]['selected']['view'] = LFM_SetInsertedView($request->section, $LFM[ $section ]['selected']['data'], false);
                session()->put('LFM', $LFM);

                return $result;
            }
            else
            {
                $result['success'] = false;
            }
        }
        else
        {
            $result['success'] = false;
        }
    }
    else
    {
        $result['success'] = false;
    }

    return $result;
}

function LFM_getEncodeId($id)
{
    if ($id < 0)
    {
        return $id;
    }
    else
    {
        $hashids = new \Hashids\Hashids(md5('sadeghi'));

        return $hashids->encode($id);
    }

}

function LFM_GetDecodeId($id, $route = false)
{
    $my_routes = [
        'LFM.DownloadFile',
        'LFM.ShowCategories',
        'LFM.ShowCategories.Create',
        'LFM.ShowCategories.Edit',
        'LFM.EditFile',
        'LFM.FileUpload',
        'LFM.FileUploadForm',
        'LFM.EditPicture',
        'LFM.Breadcrumbs',
    ];
    if ((int)$id < 0)
    {
        return (int)$id;
    }
    else
    {
        $hashids = new \Hashids\Hashids(md5('sadeghi'));
        if ($route)
        {
            if (in_array($route->getName(), $my_routes))
            {
                if ($hashids->decode($id) != [])
                {
                    return $hashids->decode($id)[0];
                }
                else
                {
                    return $id;
                }
            }
            else
            {
                return $id;
            }
        }
        else
        {
            if (isset($hashids->decode($id)[0]))
            {
                return $hashids->decode($id)[0];
            }
            else
            {
                return $id;
            }
        }
    }

}

function LFM_uploadFile($file, $CustomUid = false, $CategoryID, $FileMimeType, $original_name)
{
    \ArtinCMS\LFM\Helpers\Classes\Media::upload($file, $CustomUid = false, $CategoryID, $FileMimeType, $original_name);
}

function LFM_Date_GtoJ($GDate = null, $Format = "Y/m/d-H:i", $convert = true)
{
    if ($GDate == '-0001-11-30 00:00:00' || $GDate == null)
    {
        return '--/--/----';
    }
    $date = new ArtinCMS\LFM\Helpers\Classes\jDateTime($convert, true, 'Asia/Tehran');
    $time = is_numeric($GDate) ? strtotime(date('Y-m-d H:i:s', $GDate)) : strtotime($GDate);

    return $date->date($Format, $time);

}

function LFM_Date_JtoG($jDate, $delimiter = '/', $to_string = false, $with_time = false, $input_format = 'Y/m/d H:i:s')
{
    $jDate = ConvertNumbersFatoEn($jDate);
    $parseDateTime = ArtinCMS\LFM\Helpers\Classes\jDateTime::parseFromFormat($input_format, $jDate);
    $r = ArtinCMS\LFM\Helpers\Classes\jDateTime::toGregorian($parseDateTime['year'], $parseDateTime['month'], $parseDateTime['day']);
    if ($to_string)
    {
        if ($with_time)
        {
            $r = $r[0] . $delimiter . $r[1] . $delimiter . $r[2] . ' ' . $parseDateTime['hour'] . ':' . $parseDateTime['minute'] . ':' . $parseDateTime['second'];
        }
        else
        {
            $r = $r[0] . $delimiter . $r[1] . $delimiter . $r[2];
        }
    }

    return ($r);
}

function LFM_explode_2d_array($strVar)
{
    $result = [];
    $arr = explode(',', $strVar);
    array_walk($arr, function (&$value, $key) use (&$result) {
        list($k, $v) = explode(':', $value);
        $result[ $k ] = $v;
    });

    return $result;
}

if (!function_exists('lfm_secure_route'))

{
    function lfm_secure_route($route_name, $params = [], $relative = true, $secure = false)
    {
        if (config('laravel_file_manager.is_use_secure_route'))
        {
            if ($relative)
            {
                return route($route_name, $params, false);
            }
            else
            {
                return secure_url(route($route_name, $params, $secure));
            }
        }
        else
        {
            return route($route_name, $params);
        }
    }
}
