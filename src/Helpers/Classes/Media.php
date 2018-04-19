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
use Intervention\Image\Facades\Image;

class Media
{
    public static function upload($file, $CustomPath = false, $CustomUID = False,$CategoryID, $quality = 90, $crop_type = false, $height = False, $width = false)
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
        if ($CategoryID == 0)
        {
            $Path = '' ;
        }
        else
        {
            $Path = Category::select('title_disc')->find($CategoryID) ;
        }

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
            \Storage::disk('file_manager')->put($Path->title_disc . '/' . $filename, $file_content);
        }

        //$file->move($Path, $filename);

        $FileSave = new File;
        $FileSave->originalName = $OriginalFileName;
        $FileSave->extension = $extension;
        $FileSave->user_d = $CustomUID;
        $FileSave->category_id = $CategoryID;
        $FileSave->mimeType = $mimeType;
        $FileSave->filename = $filename;
        $FileSave->size = $size;
        $FileSave->path = '/uploads/' . $CustomPath;
        $FileSave->created_by = $CustomUID;
        $FileSave->save();
        $result = array('ID' => $FileSave->id, 'UID' => $CustomUID, 'Path' => $Path, 'Size' => $size, 'FileName' => $filename, 'OrginalFileName' => $OriginalFileName);
        return $result;
    }

}

