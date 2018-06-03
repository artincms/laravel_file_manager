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

    public function downloadDirect( $id = -1, $default_img = '404.png', $quality = 100, $width = false, $height = false)
    {
        if ($id == -1)
        {
            return Media::downloadDirectById(-1, 'orginal', $default_img);//"Not Valid Request";
        }
        else
        {
            return Media::downloadDirectById($id, $default_img, false, $quality, $width, $height);
        }
    }
    public function storeDirectUploads(Request $request)
    {
        if ($request->file)
        {
            $CategoryID = $request->category_id;
            $result = [];
            $data = [];
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
                        if (isset($section['options']['path']))
                        {
                            $path = $section['options']['path'] ;
                            if(LFM_CheckAllowInsert($request->section)['available'] > 0)
                            {
                                $result[] = \DB::transaction(function () use ($file, $path,$FileMimeType) {
                                    $res = Media::directUpload($file,$path ,$FileMimeType);
                                    $result['success'] = true;
                                    $result['file'] = $res;
                                    $result['full_url'] = LFM_GenerateDownloadLink('ID',$res['id'],'orginal') ;
                                    $result['full_url_medium']=LFM_GenerateDownloadLink('ID',$res['id'],'orginal','404.png',100,170,120) ;
                                    $result['full_url_large']=LFM_GenerateDownloadLink('ID',$res['id'],'orginal') ;
                                    return $result;
                                });
                            }
                            else
                            {
                                $result[]= ['successs'=>false , 'originalName' =>$originalName,'error' => 'You Reach Maximum Upload'];
                            }
                        }
                        else
                        {
                            $data[$request->section]['data'][]=['successs'=>false , 'originalName' =>$originalName,'error' =>'Your Upload Path not define'];
                            $data[$request->section]['available'] = LFM_CheckAllowInsert($request->section)['available'] ;
                            $data[$request->section]['view'] = ['list'=>'','grid'=>'','large'=>'','medium'=>'','small'=>''] ;
                            return response()->json($data);
                        }
                    }
                    else
                    {
                        $data[$request->section]['data'][]=['successs'=>false , 'originalName' =>$originalName,'error' =>'Your Section Not Define'];
                        $data[$request->section]['available'] = LFM_CheckAllowInsert($request->section)['available'] ;
                        $data[$request->section]['view'] = ['list'=>'','grid'=>'','large'=>'','medium'=>'','small'=>''] ;
                        return response()->json($data);
                    }
                }
                else
                {
                    $result[]= ['successs'=>false , 'originalName' =>$originalName,'error' => 'Your Myme Type Is Not Allowed'];

                }
            }
            LFM_SetSelectedFileToSession($request,$request->section,$result);
            $data[$request->section]['available'] = LFM_CheckAllowInsert($request->section)['available'] ;
            $data[$request->section]['data'] = $result ;
            $data[$request->section]['view']=LFM_GetSection($request->section)['selected']['view'] ;
            return response()->json($data);
        }
    }


}
