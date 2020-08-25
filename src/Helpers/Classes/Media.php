<?php

namespace ArtinCMS\LFM\Helpers\Classes;

use Hashids\Hashids;
use ArtinCMS\LFM\Models\File;
use ArtinCMS\LFM\Models\Category;
use ArtinCMS\LFM\Models\FileMimeType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class Media
{
    static function get_file_content($not_found_default_img_path, $type = 'png', $text = '404', $bg = 'CC0099', $color = 'FFFFFF', $width = '640', $height = '480')
    {
        $size = $width . 'x' . $height;
        list($imgWidth, $imgHeight) = explode('x', $size . 'x');
        if ($imgHeight === '')
        {
            $imgHeight = $imgWidth;
        }
        $filterOptions = [
            'options' => [
                'min_range' => 0,
                'max_range' => 9999
            ]
        ];
        if (filter_var($imgWidth, FILTER_VALIDATE_INT, $filterOptions) === false)
        {
            $imgWidth = '640';
        }
        if (filter_var($imgHeight, FILTER_VALIDATE_INT, $filterOptions) === false)
        {
            $imgHeight = '480';
        }
        $encoding = mb_detect_encoding($text, 'UTF-8, ISO-8859-1');
        if ($encoding !== 'UTF-8')
        {
            $text = mb_convert_encoding($text, 'UTF-8', $encoding);
        }
        $text = mb_encode_numericentity($text,
            [0x0, 0xffff, 0, 0xffff],
            'UTF-8');
        /**
         * Handle the “bg” parameter.
         */
        list($bgRed, $bgGreen, $bgBlue) = sscanf($bg, "%02x%02x%02x");

        list($colorRed, $colorGreen, $colorBlue) = sscanf($color, "%02x%02x%02x");
        /**
         * Define the typeface settings.
         */
        $fontFile = realpath(__DIR__) . DIRECTORY_SEPARATOR . '/../../assets/fonts/IranSans/ttf/IRANSansWeb.ttf';
        if (!is_readable($fontFile))
        {
            $fontFile = 'arial';
        }
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9)
        {
            $fontSize = 9;
        }
        /**
         * Generate the image.
         */
        $image = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, $colorRed, $colorGreen, $colorBlue);
        $bgFill = imagecolorallocate($image, $bgRed, $bgGreen, $bgBlue);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        while ($textBox[4] >= $imgWidth)
        {
            $fontSize -= round($fontSize / 2);
            $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
            if ($fontSize <= 9)
            {
                $fontSize = 9;
                break;
            }
        }
        $textWidth = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX = ($imgWidth - $textWidth) / 2;
        $textY = ($imgHeight + $textHeight) / 2;
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        /**
         * Return the image and destroy it afterwards.
         */


        switch ($type)
        {
            case 'png':
                $img = Image::make($image);

                return $img->response('png');
                break;
            case 'gif':
                $img = Image::make($image);

                return $img->response('gif');
                break;
            case 'jpg':
            case 'jpeg':
                $img = Image::make($image);

                return $img->response('jpg');
                break;
        }
    }

    public static function upload($file, $CustomUID = false, $CategoryID, $FileMimeType, $original_name = 'undefined', $size, $quality = 90, $crop_type = false, $height = False, $width = false)
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
        if (in_array(-2, Category::getAllParentId($CategoryID)))
        {
            $Path = config('laravel_file_manager.main_storage_folder_name') . '/share_folder/';
        }
        elseif (in_array(-1, Category::getAllParentId($CategoryID)))
        {
            $Path = config('laravel_file_manager.main_storage_folder_name') . '/public_folder/';
        }
        else
        {
            $Path = config('laravel_file_manager.main_storage_folder_name') . '/media_folder/';
        }
        $parents = Category::all_parents($CategoryID);
        $is_picture = false;
        if ($parents)
        {
            foreach ($parents as $parent)
            {
                if ($parent->parent_category && $parent->parent_category->parent_category_id != '#')
                {
                    $Path .= $parent->parent_category->title_disc . '/';
                }
            }
            if ($parent->parent_category_id != '#')
            {
                $Path .= $parent->title_disc;
            }
        }
        $original_nameWithoutExt = substr($original_name, 0, strlen($original_name) - strlen($extension) - 1);
        $OriginalFileName = LFM_Sanitize($original_nameWithoutExt);
        $extension = LFM_Sanitize($extension);

        //save data to database
        $FileSave = new File;
        $FileSave->original_name = $OriginalFileName;
        $FileSave->extension = $extension;
        $FileSave->file_mime_type_id = $FileMimeType->id;
        $FileSave->user_id = $CustomUID;
        $FileSave->category_id = $CategoryID;
        $FileSave->mimeType = $mimeType;
        $FileSave->filename = '';
        $FileSave->file_md5 = md5_file($file);
        $FileSave->size = $file->getSize();
        $FileSave->path = $Path;
        $FileSave->created_by = $CustomUID;
        $FileSave->save();
        $filename = 'fid_' . $FileSave->id . "_v0_" . '_uid_' . $CustomUID . '_' . md5_file($file) . "_" . $time . '_' . $extension;
        $FullPath = $Path . '/files/original/' . $filename;

        //upload every files in original folder
        $file_content = \File::get($file);
        \Storage::disk(config('laravel_file_manager.driver_disk'))->put($FullPath, $file_content);

        //check file is picture
        if (in_array($mimeType, config('laravel_file_manager.allowed_pic')))
        {
            $crop_database_name = self::resizeImageUpload($file, $FileSave, $FullPath, $filename);
            $is_picture = true;
            $FileSave->file_md5 = $crop_database_name['md5'];
            $FileSave->filename = $crop_database_name['original'];
            $FileSave->version = 0;
            $FileSave->large_filename = $crop_database_name['large'];
            $FileSave->large_version = 0;
            $FileSave->small_filename = $crop_database_name['small'];
            $FileSave->small_version = 0;
            $FileSave->medium_filename = $crop_database_name['medium'];
            $FileSave->medium_version = 0;
            $FileSave->size = $crop_database_name['size_original'];
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
        $result =
            [
                'id'            => LFM_getEncodeId($FileSave->id),
                'UID'           => $CustomUID,
                'Path'          => $Path,
                'Size'          => $file->getSize(),
                'FileName'      => $filename,
                'size'          => $FileSave->size,
                'icon'          => 'fa-file-o',
                'created'       => $FileSave->created_at,
                'updated'       => $FileSave->updated_at,
                'user'          => $FileSave->user_id,
                'original_name' => $OriginalFileName,
                'is_picture'    => $is_picture
            ];

        return $result;
    }

    public static function resizeImageUpload($file, $FileSave, $FullPath, $original_name, $quality = 90)
    {
        $upload_path = \Storage::disk(config('laravel_file_manager.driver_disk'))->path(config('laravel_file_manager.main_storage_folder_name') . '/media_folder/');
        $original_file = \Storage::disk(config('laravel_file_manager.driver_disk'))->path('');
        $tmp_path = \Storage::disk(config('laravel_file_manager.driver_disk'))->path(config('laravel_file_manager.main_storage_folder_name') . '/media_tmp_folder/');
        if (config('laravel_file_manager.Optimise_image'))
        {
            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($original_file . $FullPath);
        }
        foreach (config('laravel_file_manager.crop_type') as $crop_type)
        {
            $target_path = $FileSave->path . '/files/' . $crop_type;
            if ($crop_type != 'original')
            {
                $OptionIMG = config('laravel_file_manager.size_' . $crop_type);
                $filename = 'fid_' . $FileSave->id . "_v0_" . '_uid_' . $FileSave->user_id . '_' . $crop_type . '_' . md5_file($file) . "_" . time() . '_' . $FileSave->extension;
                $crop = config('laravel_file_manager.crop_chose');

                //create directory if not exist
                if (!is_dir($tmp_path))
                {
                    \Storage::disk(config('laravel_file_manager.driver_disk'))->makeDirectory(config('laravel_file_manager.main_storage_folder_name') . '/media_tmp_folder');
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
                $opt_size = \Storage::disk(config('laravel_file_manager.driver_disk'))->size(config('laravel_file_manager.main_storage_folder_name') . '/media_tmp_folder/' . $filename);
                $opt_file = \Storage::disk(config('laravel_file_manager.driver_disk'))->move(config('laravel_file_manager.main_storage_folder_name') . '/media_tmp_folder/' . $filename, $FileSave->path . '/files/' . $crop_type . '/' . $opt_name);
                if ($opt_file)
                {
                    $name[ 'size_' . $crop_type ] = $opt_size;
                    $name[ $crop_type ] = $opt_name;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $name['md5'] = md5_file($original_file . $FullPath);
                $name['original'] = 'fid_' . $FileSave->id . "_v0_" . 'uid_' . $FileSave->user_id . '_' . $name['md5'] . "_" . time() . '_' . $FileSave->extension;
                if ($name['md5'] != $FileSave->file_md5)
                {
                    $opt_size = \Storage::disk(config('laravel_file_manager.driver_disk'))->size($FullPath);
                    $opt_file = \Storage::disk(config('laravel_file_manager.driver_disk'))->move($FullPath, $FileSave->path . '/files/' . $crop_type . '/' . $name['original']);
                    $name['size_original'] = $opt_size;
                }
                else
                {
                    $name['original'] = $original_name;
                    $name['size_original'] = $FileSave->size;
                }
            }
        }

        return $name;
    }

    public static function downloadById($file_id, $size_type = 'original', $not_found_img = '404.png', $inline_content = false, $quality = 90, $width = false, $height = False)
    {
        $base_path = \Storage::disk(config('laravel_file_manager.driver_disk'))->path('');
        $temp_path_directory = \Storage::disk(config('laravel_file_manager.driver_disk'))->path(config('laravel_file_manager.main_storage_folder_name') . '/media_tmp_folder');
        $file = File::find(LFM_GetDecodeId($file_id));
        $not_found_img_path = \Storage::disk(config('laravel_file_manager.driver_disk'))->path(config('laravel_file_manager.main_storage_folder_name') . '/System/' . $not_found_img);
        $not_found_default_img_path = base_path('vendor/artincms/laravel_file_manager/src/Storage/SystemFiles/404.png');
        //check database for check file exist
        if ($file)
        {
            $filename = $file->filename;
            if ($file->is_direct == '1')
            {
                $config = config('laravel_file_manager.driver_disk_upload');
                $base_path = \Storage::disk($config)->path('');
                $file_path = $file->path . $filename;
            }
            else
            {
                if ($size_type != 'original')
                {
                    $filename = $file[ $size_type . '_filename' ];
                }
                $config = config('laravel_file_manager.driver_disk');
                $file_path = $file->path . '/files/' . $size_type . '/' . $filename;
            }
            if (\Storage::disk(config('laravel_file_manager.driver_disk'))->has($file_path))
            {
                $md5_file = md5(\Storage::disk(config('laravel_file_manager.driver_disk'))->get($file_path));
            }
            else
            {
                $md5_file = '';
            }

            $hash = $size_type . '_' . $not_found_img . '_' . $inline_content . '_' . $quality . '_' . $width . '_' . $height . '_' . $md5_file;
            $file_name_hash = 'tmp_fid_' . $file->id . '_' . md5($hash);
            $relative_tmp_path = config('laravel_file_manager.main_storage_folder_name') . '/media_tmp_folder/' . $file_name_hash;
            $tmp_path = $base_path . $relative_tmp_path;
            $file_EXT = FileMimeType::where('mimeType', '=', $file->mimeType)->firstOrFail()->ext;
            $headers = ["Content-Type:{$file->mimeType}"];

            //check if exist in tmp folder
            if (\Storage::disk(config('laravel_file_manager.driver_disk'))->has($relative_tmp_path))
            {
                if ($inline_content)
                {
                    $file_base_path = $base_path . $file_path;
                    $file_EXT_without_dot = str_replace('.', '', $file_EXT);
                    $data = file_get_contents($file_base_path);
                    $base64 = 'data:image/' . $file_EXT_without_dot . ';base64,' . base64_encode($data);
                    file_put_contents($tmp_path, $base64);
                    $res = $base64;
                }
                else
                {
                    $res = response()->download($tmp_path, $file->original_name . '.' . $file_EXT, $headers);
                }
            }
            else
            {
                if (!is_dir($temp_path_directory))
                {
                    \Storage::disk(config('laravel_file_manager.driver_disk'))->makeDirectory(config('laravel_file_manager.main_storage_folder_name') . '/media_tmp_folder');
                }
                //check local storage for check file exist
                if (\Storage::disk($config)->has($file_path))
                {
                    $file_base_path = $base_path . $file_path;

                    if (in_array($file_EXT, ['png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG']))
                    {
                        if ($width && $height)
                        {
                            $res = Image::make($file_base_path)->fit((int)$width, (int)$height);
                            $res->save($tmp_path);
                            $res = $res->response($file_EXT, (int)$quality);
                        }
                        else
                        {
                            if ($quality < 100)
                            {
                                $res = Image::make($file_base_path);
                                $res->save($tmp_path);
                                $res = $res->response('jpg', (int)$quality);
                            }
                            else
                            {
                                $res = Image::make($file_base_path);
                                $res->save($tmp_path);
                                $res = $res->response($file_EXT, (int)$quality);
                            }
                        }
                        if ($inline_content)
                        {
                            $data = $res->getContent();
                            $base64 = 'data:image/' . 'jpg' . ';base64,' . base64_encode($data);
                            $res = $base64;
                        }
                    }
                    else
                    {
                        $res = response()->download($file_base_path, $file->filename . '.' . $file_EXT, $headers);
                    }
                }
                else
                {
                    $width = $width ? $width : '640';
                    $height = $height ? $height : '400';
                    if (file_exists($not_found_img_path))
                    {
                        $not_found_ext = pathinfo($not_found_img_path, PATHINFO_EXTENSION);
                        $ext = ($not_found_ext != '') ? $not_found_ext : 'jpg';
                        $res = Image::make($not_found_img_path)->fit((int)$width, (int)$height)->response($ext, $quality);
                    }
                    else
                    {
                        $res = self::get_file_content($not_found_default_img_path, 'png', '404', 'cecece', 'FFFFFF', $width, $height);
                    }

                    if ($inline_content)
                    {
                        $data = $res->getContent();
                        $base64 = 'data:image/' . 'jpg' . ';base64,' . base64_encode($data);
                        $res = $base64;
                    }
                }
            }
        }
        else
        {
            if ($width || $height)
            {
                if (file_exists($not_found_img_path))
                {
                    $not_found_ext = pathinfo($not_found_img_path, PATHINFO_EXTENSION);
                    $ext = ($not_found_ext != '') ? $not_found_ext : 'jpg';
                    $res = Image::make($not_found_img_path)->fit((int)$width, (int)$height)->response($ext, $quality);
                }
                else
                {
                    $res = self::get_file_content($not_found_default_img_path, 'png', '404', 'cecece', 'FFFFFF', $width, $height);
                }
            }
            else
            {
                if (file_exists($not_found_img_path))
                {
                    $not_found_ext = pathinfo($not_found_img_path, PATHINFO_EXTENSION);
                    $ext = ($not_found_ext != '') ? $not_found_ext : 'jpg';
                    $res = Image::make($not_found_img_path)->response($ext, $quality);
                }
                else
                {
                    $res = self::get_file_content($not_found_default_img_path, 'png', '404', 'cecece', 'FFFFFF');
                }
            }

            if ($inline_content)
            {
                $data = $res->getContent();
                $base64 = 'data:image/' . 'jpg' . ';base64,' . base64_encode($data);
                $res = $base64;
            }
        }

        return $res;
    }

    public static function downloadByName($FileName, $not_found_img = '404.png', $size_type = 'original', $inline_content = false, $quality = 90, $width = false, $height = False)
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
        $base_path = \Storage::disk(config('laravel_file_manager.driver_disk'))->path('');
        if (\Storage::disk('public')->has($path . '/' . $file_name . '.' . $file_EXT))
        {
            $file_path = $base_path . '/public/' . $path . '/' . $file_name . '.' . $file_EXT;

            return response()->download($file_path, $file_name . "." . $file_EXT, $headers);
        }
        else
        {
            return response()->download($base_path . '/public/flags/404.png');
        }
    }

    public static function saveCropedImageBase64($data, $FileSave, $crop_type, $is_cropped = false)
    {
        $time = time();
        $base_path = \Storage::disk(config('laravel_file_manager.driver_disk'))->path('');
        switch ($crop_type)
        {
            case "original":
                $FileSave->version++;
                $filename = 'fid_' . $FileSave->id . '_v' . $FileSave->version . '_uid_' . $FileSave->user_id . '_' . md5(base64_decode($data)) . "_" . $time . '_' . $FileSave->extension;
                $orginal_path = $is_cropped ? $base_path . '/' . $FileSave->path . '/' . $filename : $base_path . '/' . $FileSave->path . '/files/original/' . $filename;
                $orginal_path = str_replace('//', '/', $orginal_path);
                \File::put($orginal_path, base64_decode($data));
                $FileSave->filename = $filename;
                $FileSave->file_md5 = md5(base64_decode($data));
                break;
            case "large":
                $FileSave->large_version++;
                $large_filename = 'fid_' . $FileSave->id . '_v' . $FileSave->large_version . '_uid_' . $FileSave->user_id . '_large_' . md5(base64_decode($data)) . "_" . $time . '_' . $FileSave->extension;
                \File::put($base_path . '/' . $FileSave->path . '/files/large/' . $large_filename, base64_decode($data));
                $FileSave->large_filename = $large_filename;
                break;
            case "medium":
                $FileSave->medium_version++;
                $medium_filename = 'fid_' . $FileSave->id . '_v' . $FileSave->medium_version . '_uid_' . $FileSave->user_id . '_medium_' . md5(base64_decode($data)) . "_" . $time . '_' . $FileSave->extension;
                \File::put($base_path . '/' . $FileSave->path . '/files/medium/' . $medium_filename, base64_decode($data));
                $FileSave->medium_filename = $medium_filename;
                break;
            case "small" :
                $FileSave->small_version++;
                $small_filename = 'fid_' . $FileSave->id . '_v' . $FileSave->small_version . '_uid_' . $FileSave->user_id . '_small_' . md5(base64_decode($data)) . "_" . $time . '_' . $FileSave->extension;
                \File::put($base_path . '/' . $FileSave->path . '/files/small/' . $small_filename, base64_decode($data));
                $FileSave->small_filename = $small_filename;
                break;
        }
        $FileSave->save();
        $result = ['ID' => $FileSave->id, 'UID' => $FileSave->user_id, 'Path' => $FileSave->path, 'Size' => $FileSave->size, 'FileName' => $FileSave->filename, 'originalFileName' => $FileSave->OriginalFileName];

        return $result;
    }

    public static function directUpload($file, $path, $FileMimeType, $quality = 90, $crop_type = false, $height = False, $width = false)
    {
        $time = time();
        $size = $file->getSize();
        if (auth()->check())
        {
            $user_id = auth()->id();
        }
        else
        {
            $user_id = 0;
        }
        $extension = $FileMimeType->ext;
        $mimeType = $FileMimeType->mimeType;
        $path .= '/';
        $is_picture = false;
        $original_name = $file->getClientOriginalName();
        $original_nameWithoutExt = substr($original_name, 0, strlen($original_name) - strlen($extension) - 1);
        $OriginalFileName = LFM_Sanitize($original_nameWithoutExt);
        $extension = LFM_Sanitize($extension);

        //save data to database
        $FileSave = new File;
        $FileSave->original_name = $OriginalFileName;
        $FileSave->extension = $extension;
        $FileSave->file_mime_type_id = $FileMimeType->id;
        $FileSave->user_id = $user_id;
        $FileSave->category_id = -5;
        $FileSave->mimeType = $mimeType;
        $FileSave->filename = '';
        $FileSave->file_md5 = md5_file($file);
        $FileSave->size = $size;
        $FileSave->path = $path;
        $FileSave->created_by = $user_id;
        $FileSave->is_direct = '1';
        $FileSave->save();
        $filename = 'fid_' . $FileSave->id . "_v0_" . '_uid_' . $user_id . '_' . md5_file($file) . "_" . $time . '_' . $extension;
        $is_picture = true;

        //upload every files in path folder
        $file_content = \File::get($file);
        \Storage::disk(config('laravel_file_manager.driver_disk_upload'))->put($path . $filename, $file_content);
        $FileSave->file_md5 = md5_file($file);
        $FileSave->filename = $filename;
        $FileSave->save();
        if (isset($file->user->name))
        {
            $username = $FileSave->user->name;
        }
        else
        {
            $username = 'Public';
        }
        $result = ['id' => LFM_getEncodeId($FileSave->id), 'UID' => $user_id, 'Path' => $path, 'size' => $size, 'FileName' => $filename, 'created' => $FileSave->created_at, 'updated' => $FileSave->updated_at, 'user' => $username, 'original_name' => $OriginalFileName, 'is_picture' => $is_picture];
        if (in_array($FileSave->mimeType, config('laravel_file_manager.allowed_pic')))
        {
            $result['icon'] = 'image';
        }
        else
        {
            $class = $FileSave->FileMimeType->icon_class;
            if ($class)
            {
                $result['icon'] = $FileSave->FileMimeType->icon_class;
            }
            else
            {
                $result['icon'] = 'fa-file-o';
            }
        }

        return $result;
    }
}
