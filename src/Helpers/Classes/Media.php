<?php
/**
 * Created by PhpStorm.
 * User: Hamahang
 * Date: 4/19/2018
 * Time: 10:11 AM
 */

namespace ArtinCMS\LFM\Helpers\Classes;

use ArtinCMS\LFM\Models\Category;
use ArtinCMS\LFM\Models\File;
use ArtinCMS\LFM\Models\FileMimeType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class Media
{
    public static function upload($file, $CustomPath = false, $CustomUID = False, $CategoryID, $FileMimeType, $originalName = 'undefined', $size, $quality = 90, $crop_type = false, $height = False, $width = false)
    {
        $time = time();
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
        $extension = $FileMimeType->ext;
        $mimeType = $FileMimeType->mimeType;
        $Path = 'uploads/';
        $parents = Category::all_parents($CategoryID);
        $is_picture = false;
        if ($parents)
        {
            foreach ($parents as $parent)
            {
                if ($parent->parent_category)
                {
                    $Path .= $parent->parent_category->title_disc . '/';
                }

            }
            $Path .= $parent->title_disc;
        }
        $originalNameWithoutExt = substr($originalName, 0, strlen($originalName) - strlen($extension) - 1);
        $OriginalFileName = LFM_Sanitize($originalNameWithoutExt);
        $extension = LFM_Sanitize($extension);
        //save data to database
        $FileSave = new File;
        $FileSave->originalName = $OriginalFileName;
        $FileSave->extension = $extension;
        $FileSave->file_mime_type_id = $FileMimeType->id;
        $FileSave->user_id = $CustomUID;
        $FileSave->category_id = $CategoryID;
        $FileSave->mimeType = $mimeType;
        $FileSave->filename = '';
        $FileSave->file_md5 = md5_file($file);
        $FileSave->size = $size;
        $FileSave->path = $Path;
        $FileSave->created_by = $CustomUID;
        $FileSave->save();
        $filename = 'fid_' . $FileSave->id . "_v0_" . '_uid_' . $CustomUID . '_' . md5_file($file) . "_" . $time . '_' . $extension;
        $FullPath = $Path . '/files/orginal/' . $filename;
        //upload every files in orginal folder
        $file_content = \File::get($file);
        \Storage::disk(config('laravel_file_manager.driver_disk'))->put($FullPath, $file_content);
        //check file is picture
        if (in_array($mimeType, config('laravel_file_manager.allowed_pic')))
        {
            $crop_database_name = self::resizeImageUpload($file, $FileSave, $FullPath, $filename);
            $is_picture = true;
            $FileSave->file_md5 = $crop_database_name['md5'];
            $FileSave->filename = $crop_database_name['orginal'];
            $FileSave->version = 0;
            $FileSave->large_filename = $crop_database_name['large'];
            $FileSave->large_version = 0;
            $FileSave->small_filename = $crop_database_name['small'];
            $FileSave->small_version = 0;
            $FileSave->medium_filename = $crop_database_name['medium'];
            $FileSave->medium_version = 0;
            $FileSave->size = $crop_database_name['size_orginal'];
            $FileSave->large_size = $crop_database_name['size_large'];
            $FileSave->medium_size = $crop_database_name['size_medium'];
            $FileSave->small_size = $crop_database_name['size_small'];
            $FileSave->save();
        }
        else
        {
            $is_picture = false;
            $FileSave->filename = $filename;
            $FileSave->save();

        }
        $result = array('ID' => $FileSave->id, 'UID' => $CustomUID, 'Path' => $Path, 'Size' => $size, 'FileName' => $filename, 'OrginalFileName' => $OriginalFileName, 'is_picture' => $is_picture);
        return $result;
    }

    public static function resizeImageUpload($file, $FileSave, $FullPath, $orginal_name, $quality = 90)
    {
        $upload_path = \Storage::disk(config('laravel_file_manager.driver_disk'))->path('uploads/');
        $orginal_file = \Storage::disk(config('laravel_file_manager.driver_disk'))->path('');
        $tmp_path = $upload_path . 'tmp/';
        if (config('laravel_file_manager.Optimise_image'))
        {
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($FullPath);
        }
        foreach (config('laravel_file_manager.crop_type') as $crop_type)
        {
            $target_path = $FileSave->path . '/files/' . $crop_type;
            if ($crop_type != 'orginal')
            {
                $OptionIMG = config('laravel_file_manager.size_' . $crop_type);
                $filename = 'fid_' . $FileSave->id . "_v0_" . '_uid_' . $FileSave->user_id . '_' . $crop_type . '_' . md5_file($file) . "_" . time() . '_' . $FileSave->extension;
                $crop = config('laravel_file_manager.crop_chose');
                //create directory if not exist
                if (!is_dir($tmp_path))
                {
                    \Storage::disk(config('laravel_file_manager.driver_disk'))->makeDirectory('uploads/tmp');
                }
                switch ($crop)
                {
                    case "smart":
                        $file_cropped = LFM_SmartCropIMG($file, $OptionIMG);
                        LFM_SaveCompressImage(false, $file_cropped->oImg, $tmp_path . '/' . $filename, $FileSave->extension, $quality);
                        break;
                    case "fit":
                        $res = Image::make($file)->fit($OptionIMG['height'], $OptionIMG['width'])->save($tmp_path . '/' . $filename);
                        break;
                    case "resize":
                        $res = Image::make($file)->resize($OptionIMG['height'], $OptionIMG['width'])->save($tmp_path . '/' . $filename);
                        break;
                }
                if (config('laravel_file_manager.Optimise_image'))
                {
                    $optimizerChain->optimize($tmp_path . '/' . $filename);
                }
                $opt_name = 'fid_' . $FileSave->id . "_v0_" . 'uid_' . $FileSave->user_id . '_' . $crop_type . '_' . md5_file($tmp_path . '/' . $filename) . "_" . time() . '_' . $FileSave->extension;
                $opt_size = \Storage::disk(config('laravel_file_manager.driver_disk'))->size('uploads/tmp/' . $filename);
                $opt_file = \Storage::disk(config('laravel_file_manager.driver_disk'))->move('uploads/tmp/' . $filename, $FileSave->path . '/files/' . $crop_type . '/' . $opt_name);
                if ($opt_file)
                {
                    $name['size_' . $crop_type] = $opt_size;
                    $name[$crop_type] = $opt_name;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $name['md5'] = md5_file($orginal_file . $FullPath);
                $name['orginal'] = 'fid_' . $FileSave->id . "_v0_" . 'uid_' . $FileSave->user_id . '_' . $name['md5'] . "_" . time() . '_' . $FileSave->extension;
                if ($name['md5'] != $FileSave->file_md5)
                {
                    $opt_size = \Storage::disk(config('laravel_file_manager.driver_disk'))->size($FullPath);
                    $opt_file = \Storage::disk(config('laravel_file_manager.driver_disk'))->move($FullPath, $FileSave->path . '/files/' . $crop_type . '/' . $name['orginal']);
                    $name['size_orginal'] = $opt_size;
                }
                else
                {
                    $name['orginal'] = $orginal_name;
                    $name['size_orginal'] = $FileSave->size;
                }
            }
        }
        return $name;
    }

    public static function downloadById($file_id, $size_type = 'orginal', $not_found_img = '404.png', $inline_content = false, $quality = 90, $width = false, $height = False)
    {
        $file = File::find($file_id);
        $not_found_img_path = storage_path() . '/app/System/' . $not_found_img;
        //check database for check file exist
        if ($file)
        {
            $hash = $file_id . '_' . $size_type . '_' . $not_found_img . '_' . $inline_content . '_' . $quality . '_' . $width . '_' . $height;
            $file_name_hash = 'tmp_fid_' . $file->id . '_' . md5($hash);
            $tmp_path = storage_path() . '/app/uploads/tmp/' . $file_name_hash;
            $file_EXT = FileMimeType::where('mimeType', '=', $file->mimeType)->firstOrFail()->ext;
            if (\Storage::disk(config('laravel_file_manager.driver_disk'))->has($tmp_path))
            {
                $res = Image::make($tmp_path)->response($file_EXT, (int)$quality);
            }
            else
            {
                $headers = array("Content-Type:{$file->mimeType}");

                //check local storage for check file exist
                if ($size_type == 'orginal')
                {
                    $filename = $file->filename;
                }
                else
                {
                    $filename = $file[$size_type . '_filename'];
                }
                if (\Storage::disk(config('laravel_file_manager.driver_disk'))->has($file->path . '/files/' . $size_type . '/' . $filename))
                {
                    $file_path = storage_path() . '/app/' . $file->path . 'files/' . $size_type . '/' . $filename;
                    if ($inline_content)
                    {
                        $file_EXT_without_dot = str_replace('.', '', $file_EXT);
                        $data = file_get_contents($file_path);
                        $base64 = 'data:image/' . $file_EXT_without_dot . ';base64,' . base64_encode($data);
                        file_put_contents($tmp_path, $base64);
                        $res = $base64;
                    }
                    else
                    {
                        if (in_array($file_EXT, ['png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG']))
                        {
                            if ($width && $height)
                            {
                                $res = Image::make($file_path)->fit((int)$width, (int)$height);
                                $res->save($tmp_path);
                                $res = $res->response($file_EXT, (int)$quality);
                            }
                            else
                            {
                                if ($quality < 100)
                                {
                                    $res = Image::make($file_path);
                                    $res->save($tmp_path);
                                    $res = $res->response('jpg', (int)$quality);
                                }
                                else
                                {
                                    $res = Image::make($file_path);
                                    $res->save($tmp_path);
                                    $res = $res->response($file_EXT, (int)$quality);
                                }
                            }
                        }
                        else
                        {
                            $res = response()->download($file_path, $file->filename . '.' . $file_EXT, $headers);
                        }
                    }
                }
                else
                {
                    if ($width or $height)
                    {
                        $res = Image::make($not_found_img_path)->fit((int)$width, (int)$height)->response('jpg', $quality);
                    }
                    else
                    {
                        $res = Image::make($not_found_img_path)->response('jpg', $quality);
                    }
                }
            }
        }
        else
        {
            if ($width or $height)
            {
                $res = Image::make($not_found_img_path)->fit((int)$width, (int)$height)->response('jpg', $quality);
            }
            else
            {
                $res = Image::make($not_found_img_path)->response('jpg', $quality);
            }
        }
        return $res;
    }

    public static function downloadByName($FileName, $not_found_img = '404.png', $size_type = 'orginal', $inline_content = false, $quality = 90, $width = false, $height = False)
    {
        $file = File::where('filename', '=', $FileName)->first();
        if ($file)
        {
            $id = $file->id;
        }
        else
        {
            $id = -1;
        }
        $download = self::downloadById($id, $not_found_img, $size_type, $inline_content, $quality, $width, $height);
        return $download;
    }

    public static function downloadFromPublicStorage($file_name, $path = "", $file_EXT = 'png', $headers = ["Content-Type: image/png"])
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

    public static function saveCropedImageBase64($data, $FileSave, $crop_type)
    {
        $time = time();
        switch ($crop_type)
        {
            case "orginal":

                $FileSave->version++;
                $filename = 'fid_' . $FileSave->id . '_v' . $FileSave->version . '_uid_' . $FileSave->user_id . '_' . md5(base64_decode($data)) . "_" . $time . '_' . $FileSave->extension;
                \File::put(storage_path() . '/app/' . $FileSave->path . '/files/orginal/' . $filename, base64_decode($data));
                $FileSave->filename = $filename;
                $FileSave->file_md5 = md5(base64_decode($data));
                break;
            case "large":
                $FileSave->large_version++;
                $large_filename = 'fid_' . $FileSave->id . '_v' . $FileSave->large_version . '_uid_' . $FileSave->user_id . '_large_' . md5(base64_decode($data)) . "_" . $time . '_' . $FileSave->extension;
                \File::put(storage_path() . '/app/' . $FileSave->path . '/files/large/' . $large_filename, base64_decode($data));
                $FileSave->large_filename = $large_filename;
                break;
            case "medium":
                $FileSave->medium_version++;
                $medium_filename = 'fid_' . $FileSave->id . '_v' . $FileSave->medium_version . '_uid_' . $FileSave->user_id . '_medium_' . md5(base64_decode($data)) . "_" . $time . '_' . $FileSave->extension;
                \File::put(storage_path() . '/app/' . $FileSave->path . '/files/medium/' . $medium_filename, base64_decode($data));
                $FileSave->medium_filename = $medium_filename;
                break;
            case "small" :
                $FileSave->small_version++;
                $small_filename = 'fid_' . $FileSave->id . '_v' . $FileSave->small_version . '_uid_' . $FileSave->user_id . '_small_' . md5(base64_decode($data)) . "_" . $time . '_' . $FileSave->extension;
                \File::put(storage_path() . '/app/' . $FileSave->path . '/files/small/' . $small_filename, base64_decode($data));
                $FileSave->small_filename = $small_filename;
                break;
        }
        $FileSave->save();
        $result = array('ID' => $FileSave->id, 'UID' => $FileSave->user_id, 'Path' => $FileSave->path, 'Size' => $FileSave->size, 'FileName' => $FileSave->filename, 'OrginalFileName' => $FileSave->OriginalFileName);
        return $result;
    }
}

