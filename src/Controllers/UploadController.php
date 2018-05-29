<?php

namespace ArtinCMS\LFM\Controllers;

use Illuminate\Http\Request;
use ArtinCMS\LFM\Models\FileMimeType;
use ArtinCMS\LFM\Helpers\Classes\Media;
use Illuminate\Routing\Route;

class UploadController extends ManagerController
{
    public function fileUpload($category_id = 0, $callback = false, $section = false)
    {
        if ($section and $section != 'false')
        {
            $options = $this->getSectionOptions($section);
            if ($options['success'])
            {
                $options = $options['options'];
            }
            else
            {
                $options = false ;
            }
        }
        else
        {
            $options = false;
        }
        return view('laravel_file_manager::upload.upload', compact('category_id', 'callback', 'options', 'section'));
    }

    public function storeUploads(Request $request)
    {
        if ($request->file)
        {
            $CategoryID = $request->category_id;
            $result = [];
            foreach ($request->file as $file)
            {
                $mimeType = $file->getMimeType();
                $FileMimeType = FileMimeType::where('mimeType', '=', $mimeType)->first();
                $originalName = $file->getClientOriginalName();
                $size = $file->getSize();
                if (in_array($mimeType, config('laravel_file_manager.allowed')) === true && $FileMimeType)
                {
                    $result[] = \DB::transaction(function () use ($file, $CategoryID, $FileMimeType, $originalName, $size) {
                        $res = Media::upload($file, false, false, $CategoryID, $FileMimeType, $originalName, $size);
                        $result['success'] = true;
                        $result['file'] = $res;
                        return $result;
                    });
                }
                else
                {
                    $result[]= ['successs'=>false , 'originalName' =>$originalName];
                }
            }

            return response()->json($result);
        }
    }

    public function download($type = "ID", $id = -1, $size_type = 'orginal', $default_img = '404.png', $quality = 100, $width = false, $height = false)
    {
        if ($id == -1)
        {
            return Media::downloadById(-1, 'orginal', $default_img);//"Not Valid Request";
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
                return Media::downloadById(-1, $default_img);
        }
    }
}
