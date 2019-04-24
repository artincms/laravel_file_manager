<?php

namespace ArtinCMS\LFM\Controllers;

use Illuminate\Http\Request;
use ArtinCMS\LFM\Models\FileMimeType;
use ArtinCMS\LFM\Helpers\Classes\Media;
use Illuminate\Routing\Route;
use Mockery\Exception;

class UploadController extends ManagerController
{
    public function fileUpload($category_id = 0, $callback = false, $section = false)
    {
        $category_id = LFM_getEncodeId($category_id);
        if ($section and $section != 'false')
        {
            $options = $this->getSectionOptions($section);
            if ($options['success'])
            {
                $options = $options['options'];
            }
            else
            {
                $options = false;
            }
        }
        else
        {
            $options = false;
        }
        return view('laravel_file_manager::upload.upload', compact('category_id', 'callback', 'options', 'section'));
    }

    public function storeUploads(\ArtinCMS\LFM\Requests\UploadFile $request)
    {
        {
            $CategoryID = LFM_GetDecodeId($request->category_id);
            $result = [];
            if (!config('laravel_file_manager.allow_upload_private_file'))
            {
                if (!in_array($request->category_id, LFM_CreateArrayId(LFM_GetChildCategory([-2, -1]))))
                {
                    $result[] = ['successs' => false, 'original_name' => __('filemanager.error_not_allow_permition_to_create_category')];
                    return response()->json($result);
                }
            }
            foreach ($request->file as $file)
            {
                try
                {
                    $mimeType = $file->getMimeType();
                    $FileMimeType = FileMimeType::where('mimeType', '=', $mimeType)->first();
                    $original_name = $file->getClientOriginalName();

                } catch (Exception $e)
                {
                    return $e->getMessage();
                }
                if (in_array($mimeType, config('laravel_file_manager.allowed')) && $FileMimeType)
                {
                    $result[] = \DB::transaction(function () use ($file, $CategoryID, $FileMimeType, $original_name) {
                        $res = Media::upload($file,false, $CategoryID, $FileMimeType, $original_name,$file->getSize());
                        $result['success'] = true;
                        $result['file'] = $res;
                        return $result;
                    });
                }
                else
                {
                    $result[] = ['successs' => false, 'original_name' => $original_name];
                }
            }
            return response()->json($result);
        }
    }

    public function download($type = "ID", $id = -1, $size_type = 'original', $default_img = '404.png', $quality = 100, $width = false, $height = false)
    {
        if ($id == -1)
        {
            return Media::downloadById(-1, 'original', $default_img,false, $quality, $width, $height);//"Not Valid Request";
        }
        switch ($type)
        {
            case "ID":
                return Media::downloadById($id, $size_type, $default_img, false, $quality, $width, $height);
                break;
            case "Name":
                return Media::downloadByName($id, $size_type, $default_img, false, $quality, $width, $height);
                break;
            case "flag":
                return Media::downloadFromPublicStorage($id, 'flags');
                break;
            default:
                return Media::downloadById(-1, 'original', $default_img);
        }
    }
}
