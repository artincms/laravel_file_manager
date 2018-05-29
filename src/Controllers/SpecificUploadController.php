<?php

namespace ArtinCMS\LFM\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function singleUpload($category_id = 0 , $section = false,$callback)
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

    public function storeSingleUploads(Request $request)
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
                    if(LFM_CheckAllowInsert($request->section)['available'] > 0)
                    {
                        $result[] = \DB::transaction(function () use ($file, $CategoryID, $FileMimeType, $originalName, $size) {
                            $res = Media::upload($file, false, false, $CategoryID, $FileMimeType, $originalName, $size);
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
            $r =$this->setSelectedFileToSession($request,$request->section,$result);
            $data['data'] = $result ;
            $data['view']=LFM_SetInsertedView($request->section,  LFM_GetSection($request->section)['selected']['data']) ;
            $data['available'] = LFM_CheckAllowInsert($request->section)['available'] ;
            return response()->json($data);
        }
    }


}
