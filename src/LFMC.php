<?php

namespace ArtinCMS\LFM;

use ArtinCMS\LFM\Models\File;
use ArtinCMS\LFM\Models\FileMimeType;
use Intervention\Image\Facades\Image;

class LFMC
{
    public static function smart_crop_img($file, $options = [])
    {
        $smartcrop = new \App\Helpers\Classes\SmartCropClass($file, $options);
        //Analyse the image and get the optimal crop scheme
        $res = $smartcrop->analyse();
        //Generate a crop based on optimal crop scheme
        return $smartcrop->crop($res['topCrop']['x'], $res['topCrop']['y'], $res['topCrop']['width'], $res['topCrop']['height']);
    }

    public static function save_compress_img($prepare_src = false, $file, $destination, $extension = 'jpg', $quality = 90)
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

    public static function upload($file, $CustomPath = false, $CustomUID = False, $quality = 90, $crop_type = false, $height = False, $width = false)
    {
        if (!$CustomUID)
        {
            if (auth()->check())
            {
                $CustomUID = auth()->id();
            }
            else
            {
                $CustomUID = 0;
            }
        }

        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $Path = '/uploads/';
        if ($CustomPath)
        {
            $Path = '/uploads/' . $CustomPath . '/';
        }

        $originalNameWithoutExt = substr($originalName, 0, strlen($originalName) - strlen($extension) - 1);
        $OriginalFileName = HFM_Sanitize($originalNameWithoutExt);
        $extension = HFM_Sanitize($extension);
        $filename = $CustomUID . '_' . md5_file($file) . "_" . time() . "." . $extension;

        if (in_array($extension, ['png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG']))
        {
            $FullPath = storage_path() . '/app/FileManager' . $Path;

            if ($crop_type && in_array($crop_type, ['smart', 'fit', 'resize']) && $height && $width && is_int($height) && is_int($width))
            {
                $OptionIMG = ['height' => $height, 'width' => $width];
                switch ($crop_type)
                {
                    case "smart":
                        $file_cropped = HFM_SmartCropIMG($file, $OptionIMG);
                        HFM_Save_Compress_IMG(false, $file_cropped->oImg, $FullPath . $filename, $extension, $quality);
                        break;
                    case "fit":
                        $res = Image::make($file)->fit($width, $height)->save($FullPath . $filename);
                        break;
                    case "resize":
                        $res = Image::make($file)->resize($width, $height)->save($FullPath . $filename);
                        break;
                }
            }
            else
            {
                HFM_Save_Compress_IMG(true, $file, $FullPath . $filename, $extension, $quality);
            }
        }
        else
        {
            $file_content = \File::get($file);
            \Storage::disk('FileManager')->put($Path . $filename, $file_content);
        }

        //$file->move($Path, $filename);

        $FileSave = new File;
        $FileSave->originalName = $OriginalFileName;
        $FileSave->extension = $extension;
        $FileSave->mimeType = $mimeType;
        $FileSave->filename = $filename;
        $FileSave->size = $size;
        $FileSave->path = '/uploads/' . $CustomPath;
        $FileSave->created_by = $CustomUID;
        $FileSave->save();
        $result = array('ID' => $FileSave->id, 'UID' => $CustomUID, 'Path' => $Path, 'Size' => $size, 'FileName' => $filename, 'OrginalFileName' => $OriginalFileName);
        return $result;
    }

    public static function downloadById($file_id, $not_found_img = '404.png', $inline_content = false, $quality = 10, $width = false, $height = False)
    {
        $file = File::find($file_id);
        $not_found_img_path = storage_path() . '/app/FileManager/System/' . $not_found_img;
        if ($file)
        {
            $file_EXT = FileMimeType::where('mimeType', '=', $file->mimeType)->firstOrFail()->ext;
            $headers = array("Content-Type:{$file->mimeType}");
            /* $headers =[
                 "Content-Type"=>$file->mimeType,
                 //"pragma"=> "private",
                 //"Cache-Control"=> " private, max-age=86400",
             ];*/
            if (\Storage::disk('FileManager')->has($file->path . $file->filename))
            {
                $file_path = storage_path() . '/app/FileManager' . $file->path . $file->filename;
                if ($inline_content)
                {
                    $file_EXT_without_dot = str_replace('.', '', $file_EXT);
                    $data = file_get_contents($file_path);
                    $base64 = 'data:image/' . $file_EXT_without_dot . ';base64,' . base64_encode($data);
                    return $base64;
                }

                if (in_array($file_EXT, ['.png', '.PNG', '.jpg', '.JPG', '.jpeg', '.JPEG']))
                {
                    $file_EXT = str_replace('.', '', $file_EXT);

                    if ($width && $height)
                    {
                        $res = Image::make($file_path)->fit((int)$width, (int)$height)->response($file_EXT, (int)$quality);
                        return $res;
                    }
                    else
                    {
                        //dd($file_EXT,$width,$height,$file_EXT,$quality);
                        if ($quality < 100)
                        {
                            $res = Image::make($file_path)->response('jpg', (int)$quality);
                        }
                        else
                        {
                            $res = Image::make($file_path)->response($file_EXT, (int)$quality);
                        }
                        return $res;
                    }
                }
                else
                {
                    return response()->download($file_path, $file->originalName . $file_EXT, $headers);
                }
            }
            else
            {
                return Image::make($not_found_img_path)->response('jpg', 90);
            }
        }
        else
        {
            return Image::make($not_found_img_path)->response('jpg', 90);
        }
    }

    public static function download_by_name($FileName, $not_found_img = '404.png')
    {
        $file = File::where('filename', '=', $FileName)->firstOrFail();
        $file_EXT = FileMimeType::where('mimeType', '=', $file->mimeType)->firstOrFail()->ext;


        $headers = array("Content-Type:{$file->mimeType}");

        if (\Storage::disk('FileManager')->has($file->path . $file->filename))
        {
            $file_path = storage_path() . '/app/FileManager' . $file->path . $file->filename;
            return response()->download($file_path, $file->originalName . "." . $file_EXT, $headers);
        }
        else
        {
            return response()->download(storage_path() . '/app/FileManager/System/' . $not_found_img);
        }
    }

    public static function check_file_type($AllowedMimeType = array(), $UploadedFiles = array())
    {
        foreach ($UploadedFiles as $key => $value)
        {
            if (!(isset($value['MimeType']) && in_array($value['MimeType'], $AllowedMimeType)))
            {
                unset($UploadedFiles[$key]);
            }
        }
        return $UploadedFiles;
    }

    public static function file_size_convert($bytes)
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

    public static function sanitize($string, $force_lowercase = true, $anal = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]", "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;", "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean;

        return ($force_lowercase) ? (function_exists('mb_strtolower')) ? mb_strtolower($clean, 'UTF-8') : strtolower($clean) : $clean;
    }

    public static function mime_type_icon($mime)
    {
        $image_mime_type = array(
            'image/bmp' => True,
            'image/gif' => True,
            'image/png' => True,
            'image/tiff' => True,
            'image/jpeg' => True,
            'image/x-rgb' => True,
            'image/x-icon' => True,
            'image/svg+xml' => True,
            'image/x-portable-bitmap' => True
        );

        switch (true)
        {
            case isset($image_mime_type[$mime]):
                $icon = 'glyphicon glyphicon-picture';
                break;
            default:
                $icon = 'glyphicon glyphicon-file';
        }
        return $icon;
    }

    public static function set_multi_file($Section, $MultiFile = "Multi")
    {
        $SetMultiFile = [];
        $SetMultiFile["$Section"] = $MultiFile;
        if (session()->has('SetMultiFile'))
        {
            $SetMultiFile_session = session('SetMultiFile');
            if (isset($SetMultiFile_session["$Section"]))
            {
                unset($SetMultiFile_session["$Section"]);
            }
            $SetMultiFile = $SetMultiFile_session + $SetMultiFile;
            session()->forget("SetMultiFile");
        }
        session()->put('SetMultiFile', $SetMultiFile);
    }

    public static function set_true_mime_type($Section, $TrueType)
    {
        $TrueTypeUpload = [];
        $TrueTypeUpload["$Section"] = $TrueType;
        if (session()->has('TrueTypeUpload'))
        {
            $TrueTypeUpload_session = session('TrueTypeUpload');
            if (isset($TrueTypeUpload_session["$Section"]))
            {
                unset($TrueTypeUpload_session["$Section"]);
            }
            $TrueTypeUpload = $TrueTypeUpload_session + $TrueTypeUpload;
            session()->forget("TrueTypeUpload");
        }
        session()->put('TrueTypeUpload', $TrueTypeUpload);
    }

    public static function generate_upload_form($Sections)
    {
        if (isset($Sections) && is_array($Sections))
        {
            foreach ($Sections as $key => $value)
            {
                if (isset($value[0]) && !empty($value[0]))
                {
                    $section = $value[0];
                    /*--------------- Set True Type ---------------*/
                    if (isset($value[1]) && !empty($value[1]))
                    {
                        HFM_SetTrueMimeType($section, $value[1]);

                        HFM_SetMultiFile($section, $value[2]);
                        /*-----------------FilesInSession------------------*/

                        if (session()->has('Files'))
                        {
                            $FilesInSession = session('Files');
                            if (isset($FilesInSession[$section]))
                            {
                                unset($FilesInSession[$section]);
                                session()->put('Files', $FilesInSession);

                                /*foreach ($FilesInSession as $file_key=>$file)
                                {
                                    $FilesInSession[$file_key]['ID'] = enCode($file['ID']);
                                    $FilesInSession[$file_key]['FileName'] = enCode($file['FileName']);
                                }*/
                            }
                        }
                        $result['MultiFile'][$section] = $value[2];
                        $result['Buttons'][$section] = view('hamahang.FileManager.helper.Buttons')->with('section', enCode($section))->with('multi_file', $value[2]);
                        $result['ShowResultArea'][$section] = view('hamahang.FileManager.helper.ShowResultArea')->with('section', enCode($section));
                    }
                }
            }

            $result['UploadForm'] = view('hamahang.FileManager.helper.modal_upload_form');
            $result['JavaScripts'] = view('hamahang.FileManager.helper.JavaScripts');
        }
        return $result;
    }

    public static function save_multi_files($section, $Model, $ColumnName, $RecordID, $additional_fields = [])
    {
        if (session()->has('Files'))
        {
            $files = session('Files');
            if (isset($files[$section]) && is_array($files[$section]))
            {
                $last_file = 0;
                $task_files = $files[$section];
                foreach ($task_files as $key => $value)
                {
                    $f = new $Model;
                    if (sizeof($additional_fields) != 0)
                    {
                        foreach ($additional_fields as $k => $v)
                        {
                            $f->$k = $v;
                        }
                    }
                    $f->$ColumnName = $RecordID;
                    $f->file_id = $key;
                    $f->save();
                    $last_file = $f->file_id;
                }
                unset($files[$section]);
                session()->put('Files', $files);
                return $last_file;
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

    public static function save_single_file($section, $Model, $ColumnName, $RecordID)
    {
        if (session()->has('Files'))
        {
            $files = session('Files');
            if (isset($files[$section]) && is_array($files[$section]))
            {
                $last_file = 0;
                $task_files = $files[$section];
                foreach ($task_files as $key => $value)
                {
                    $f = $Model::find($RecordID);
                    $f->$ColumnName = $key;
                    $f->save();
                    $last_file = $f->$ColumnName;
                    break;
                }
                unset($files[$section]);
                session()->put('Files', $files);
                return $last_file;
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

    public static function check_multi_file($section, $input)
    {
        $output['result'] = false;
        $output['error_msg'] = trans('filemanager.acceptable_multi_or_single_file_not_selected');
        if (session()->has('SetMultiFile'))
        {
            $SetMultiFile = Session::get('SetMultiFile');
            if (isset($SetMultiFile["$section"]))
            {
                if ($SetMultiFile["$section"] == "Multi")
                {
                    $output['result'] = true;
                    $output['error_msg'] = "";
                    return $output;
                }
                elseif ($SetMultiFile["$section"] == "Single")
                {
                    if (sizeof($input) > 1)
                    {
                        $output['result'] = false;
                        $output['error_msg'] = "فقط یک فایل می توانید آپلود کنید.";
                        return $output;
                    }
                    if (session()->has('Files'))
                    {
                        $files = session('Files');
                        if (isset($files[$section]) && is_array($files[$section]))
                        {
                            $task_files = $files[$section];
                            if (sizeof($task_files) > 0)
                            {
                                $output['result'] = false;
                                $output['error_msg'] = "فقط یک فایل می توانید آپلود کنید.";
                                return $output;
                            }
                            else
                            {
                                $output['result'] = true;
                                $output['error_msg'] = "";
                                return $output;
                            }
                        }
                        else
                        {
                            $output['result'] = true;
                            $output['error_msg'] = "";
                            return $output;
                        }
                    }
                    else
                    {
                        $output['result'] = true;
                        $output['error_msg'] = "";
                        return $output;
                    }
                }
                else
                {
                    $output['result'] = false;
                    $output['error_msg'] = "نوع آپلود فایل نامعتبر است.";
                    return $output;
                }
            }
            else
            {
                return $output;
            }
        }
        else
        {
            return $output;
        }
    }

    public static function download_from_public_storage($file_name, $path = "", $file_EXT = 'png', $headers = ["Content-Type: image/png"])
    {
        if (\Storage::disk('public')->has($path . '/' . $file_name . '.' . $file_EXT))
        {
            $file_path = storage_path() . '/app/public/' . $path . '/' . $file_name . '.' . $file_EXT;
            return response()->download($file_path, $file_name . "." . $file_EXT, $headers);
        }
        else
        {
            return response()->download(storage_path() . '/app/public/flags/404.png');
        }
    }

}