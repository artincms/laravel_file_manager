<?php

namespace ArtinCMS\LFM\Controllers;

use ArtinCMS\LFM\Helpers\Classes\Media;
use ArtinCMS\LFM\Models\FileMimeType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DirectUploadController extends Controller
{
    public function directUpload($section = false,$callback)
    {
        $result=LFM_GetSection($section)['options'] ;
        if ($result)
        {
            $options = $result ;
        }
        else
        {
            $options = [];
        }
        return view('laravel_file_manager::upload.upload_form', compact('category_id', 'callback', 'section','options'));
    }

    public function storeDirectUploads(Request $request)
    {
        if ($request->file)
        {
            $CategoryID = $request->category_id;
            $result = [];
            foreach ($request->file as $file)
            {
                try {
                    $mimeType = $file->getMimeType();
                    $FileMimeType = FileMimeType::where('mimeType', '=', $mimeType)->first();
                    $originalName = $file->getClientOriginalName();
                }
                catch (Exception $e)
                {
                    return $e->getMessage();
                }
                if (in_array($mimeType, config('laravel_file_manager.allowed')) === true && $FileMimeType)
                {
                    $section = LFM_GetSection($request->section);
                    if($section)
                    {
                        if ($section['options']['path'])
                        {
                            $path = $section['options']['path'] ;
                        }
                        else
                        {
                            $path = '';
                        }
                    }
                    if(LFM_CheckAllowInsert($request->section)['available'] > 0)
                    {
                        $result[] = \DB::transaction(function () use ($file, $path,$FileMimeType) {
                            $res = Media::directUpload($file,$path ,$FileMimeType);
                            $result['success'] = true;
                            $result['file'] = $res;
                            $result['full_url'] = LFM_GenerateDownloadLink('ID',$res['id'],'orginal');
                            return $result;
                        });
                    }
                    else
                    {
                        $result[]= ['successs'=>false , 'originalName' =>$originalName];
                    }

                }
                else
                {
                    $result[]= ['successs'=>false , 'originalName' =>$originalName];
                }
            }
            $prefix = config('laravel_file_manager.upload_route_prefix');
            $set =LFM_SetSelectedFileToSession($request,$request->section,$result);
            $data[$request->section]['available'] = LFM_CheckAllowInsert($request->section)['available'] ;
            $data[$request->section]['data'] = $result ;
            $data[$request->section]['view']=LFM_GetSection($request->section)['selected']['view'] ;

            return response()->json($data);
        }
    }


}
