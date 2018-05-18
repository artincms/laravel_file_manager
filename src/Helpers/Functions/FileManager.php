<?php
/* Hamahang File Manager :: LFM*/

function LFM_SetSessionOption($name, $option)
{
    $mime = [];
    foreach ($option['true_file_extension'] as $ext)
    {
        $MimeType = ArtinCMS\LFM\Models\FileMimeType::where('ext', '=', $ext)->first();
        if ($MimeType)
        {
            $mime[] = $MimeType->mimeType;
        }
    }
    $option['true_mime_type'] = $mime;
    $LFM[$name]['options'] = $option;
    $LFM[$name]['selected'] = [];
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

function LFM_CreateModalFileManager($section, $options = false, $insert = 'insert', $callback = false, $modal_id = 'FileManager', $header = 'File manager', $button_id = 'show_modal', $button_content = 'input file')
{
    if ($options)
    {
        $session_option = LFM_SetSessionOption($section, $options);
    }
    //create html content and button
    $src = route('LFM.ShowCategories', ['section' => $section, 'insert' => $insert, 'callback' => LFM_CheckFalseString($callback)]);
    $result['modal_content'] = view("laravel_file_manager::create_modal", compact("src", "modal_id", 'header', 'button_content', 'section', 'callback','button_id'))->render();
    $result['button'] = '<button class="btn btn-default"  id="' . $button_id . '">' . $button_content . '</button>';
    return $result;
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
    if ($sec && isset($sec['selected']) && count($sec['selected']) >= 1)
    {
        return $sec['selected'];
    }
    else
    {
        return false;
    }
}

function LFM_SaveSingleFile($obj_model, $column_name, $section)
{
    $files = LFM_GetSectionFile($section);
    if ($files)
    {
        if (isset($files[0]) && isset($files[0]['id']))
        {
            $obj_model->$column_name = $files[0]['id'];//first select
            $obj_model->save();
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
        LFM_DestroySection($section);
        return $res;
    }
    else
    {
        return false;
    }
}

function LFM_DestroySection($section)
{
    if (session()->has('LFM'))
    {
        $LFM = session()->get('LFM');
        if (isset($LFM[$section]))
        {
            unset($LFM[$section]);
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
    $res =\ArtinCMS\LFM\Helpers\Classes\Media::downloadById($file_id, $size_type, $not_found_img, true, $quality, $width, $height);
    return $res ;
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
