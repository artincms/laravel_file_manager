<?php

namespace ArtinCMS\LFM\Controllers;

use ArtinCMS\LFM\Helpers\Classes\Media;
use ArtinCMS\LFM\Models\Category;
use Carbon\Carbon;
use Validator;
use ArtinCMS\LFM\LFMC;
use ArtinCMS\LFM\Models\File;
use ArtinCMS\LFM\Models\FileMimeType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use Illuminate\Support\Facades\Storage;


class ManagerController extends Controller
{
    public function ShowCategories()
    {
        $files = File::get_uncategory_files() ;
        $categories = Category::get_root_categories() ;
        $category = false ;
        $breadcrumbs[] = ['id'=> 0 , 'title'=>'media'] ;
        return view('laravel_file_manager::index' , compact('categories' ,'files','category','breadcrumbs'));
    }

    public function CreateCategory($id){
        $messages = [] ;
        $category_id = $id ;
        $categories = Category::where('user_id','=',(auth()->check())?auth()->id():0)->get() ;
        return view('laravel_file_manager::category' , compact('categories' ,'category_id','messages'));
    }
    public function StoreCategory(Request $request) {
        if ($request->ajax()) {
            $date = $this->get_current_date() ;
            if (auth()->check())
            {
                $user_id = auth()->id();
            }
            else
            {
                $user_id = 0;
            }
            DB::transaction(function () use($request,$date,$user_id){
                $cat = new Category ;
                $cat->title = $request->title ;
                $cat->user_id =$user_id ;
                $cat->description = $request->description ;
                $cat->parent_category_id =$request->parent_category_id ;
                $cat->created_by = $user_id ;
                $cat->save();
                //update title disc
                $cat->title_disc = 'cid_' .$cat->id. '_uid_'. $user_id.'_'. md5($cat->name). '_' .time() ;
                $cat->save() ;

                //make directory
                $subcats =config('laravel_file_manager.crop_type');
                $path = 'uploads/' ;
                foreach(Category::all_parents($cat->id) as $parent)
                {
                    if ($parent->parent_category)
                    {
                        $path .= $parent->parent_category->title_disc . '/' ;
                    }
                }
                $path .= $cat->title_disc ;
                foreach ($subcats as $subcat) {
                    Storage::disk(config('laravel_file_manager.driver_disk'))->makeDirectory($path. '/files/' .$subcat) ;
                }

            });
            $category_id = $request->parent_category_id ;
            $categories = Category::where('user_id','=',(auth()->check())?auth()->id():0)->get() ;
            $result['success'] = true ;
            $messages[] = "Your Category is created" ;
            $result['html'] = view('laravel_file_manager::category',compact('categories' ,'category_id' , 'messages'))->render() ;
            return response()->json($result);

        }
    }

    public function ShowCategory (Request $request) {
        $result = $this->show($request->category_id) ;
        return response()->json($result);

    }

    public function Trash (Request $request)
    {
        if ($request->type == "file")
        {
            $file = File::find($request->id);
            $file->delete() ;
        }
        else
        {
            $category = Category::with('files','child_categories','parent_category' )->find($request->id);
            $category->delete() ;
        }

        $result = $this->show($request->parent_id) ;
        return response()->json($result);

    }

    public function BulkDelete(Request $request)
    {

        foreach ($request["items"] as $item)
        {
            if ($item['type'] == "file")
            {
                $file = File::find($item['id']);
                $file->delete() ;
            }
            else
            {
                $category = Category::with('files','child_categories','parent_category' )->find($item['id']);
                $category->delete() ;
            }
        }
        $result = $this->show($item['parent_id']) ;
        return response()->json($result);
    }


    /**
     * id is category id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function FileUpload($id)
    {
        return view('laravel_file_manager::upload',compact('id')) ;
    }

    /**
     * should send category_id and token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function StoreUploads(Request $request) {

        if ($request->file)
        {
            $CategoryID = $request->category_id ;
            $res = [] ;
            foreach ($request->file as $file)
            {
                $mimeType = $file->getMimeType();
                $FileMimeType = FileMimeType::where('mimeType' ,'=',$mimeType)->first();
                $originalName = $file->getClientOriginalName();
                $size = $file->getSize();
                if (in_array($mimeType,config('laravel_file_manager.allowed')) === true && $FileMimeType )
                {
                    /*DB::transaction(function () use($file,$CategoryID,$FileMimeType,$originalName,$size){*/
                        $res = Media::upload($file, false, false, $CategoryID,$FileMimeType,$originalName,$size);
                        $message['success'] = true ;
                        $message['result'] = $res ;
                    /*});*/
                    $message['success'] = true ;

                }
                else
                {
                    $message['success'] = false ;
                }
            }
            return response()->json($message) ;
        }

    }

    public function StoreCropedImage(Request $request)
    {

        $data = str_replace('data:image/png;base64,', '', $request->crope_image);
        $data = str_replace(' ', '+', $data);
        $file = File::find($request->file_id) ;
        $res = Media::save_croped_image_base64($data , $file , $request->crop_type);
        if ($res)
            $message['success'] = true ;
        else
            $message['success'] =  false ;
        $message['result'] = $res ;
        return response()->json($message) ;
    }

    public function EditPicture($file_id)
    {
        $file = File::find($file_id) ;
        return view('laravel_file_manager::edit_picture',compact('file')) ;
    }

    public function Download($type = "ID", $id = -1, $size_type = 'orginal' ,$default_img = '404.png', $quality = 100, $width = false, $height = false)
    {
        if ($id == -1)
        {
            return Media::download_by_id(-1,'orginal' ,$default_img);//"Not Valid Request";
        }

//        $id = deCode($id);
        switch ($type)
        {
            case "ID":
                return Media::download_by_id($id,$size_type ,$default_img ,false, $quality, $width, $height);
                break;
            case "Name":
                return Media::download_by_name($id, $size_type ,$default_img ,false, $quality, $width, $height);
                break;
            case "flag":
                return Media::download_from_public_storage($id, 'flags');
                break;
            default:
                return Media::download_by_id(-1, $default_img);//"Not Valid Request";
        }

    }

    public function SearchMedia(Request $request)
    {
        $result = $this->show($request->id , $request->search) ;
        return response()->json($result);
    }
    /**
     * @return string
     */
    public function get_current_date() {
        $mytime = Carbon::now();
        return $mytime->toDateString();
    }

    public function ShowList(Request $request)
    {
        $id = $request->id ;
        $breadcrumbs = $this->get_breadcrumbs($id) ;
        if ($id == 0){
            $files = File::get_uncategory_files() ;
            $categories = Category::get_root_categories() ;
            $category = false ;
            $result['html'] = view('laravel_file_manager::content_list' , compact('categories' ,'files','category','breadcrumbs'))->render() ;
            $result['message'] = '' ;
            $result['parent_category_id'] = $id;
            $result['button_upload_link'] = route('LFM.FileUpload' ,['category_id' =>$id] ) ;
            $result['button_category_create_link'] = route('LFM.ShowCategories.Create' ,['category_id' =>$id] ) ;
            $result['success'] = true ;
        }else {
            $category = Category::with('files','child_categories','parent_category' )->find($id) ;
            $breadcrumbs[] = ['id'=> $category->id , 'title'=>$category->title] ;
            $categories = $category->user_child_categories ;
            $files = $category->user_files ;
            $result['html'] = view('laravel_file_manager::content_list',compact('categories' ,'files','category','breadcrumbs'))->render() ;
            $result['message'] = '' ;
            $result['parent_category_id'] = $id;
            $result['button_upload_link'] = route('LFM.FileUpload' ,['category_id' =>$id] ) ;
            $result['button_category_create_link'] = route('LFM.ShowCategories.Create' ,['category_id' =>$id] ) ;
            $result['success'] = true ;
        }
        return $result ;
    }

    /**
     * @param $id
     * @return array
     */
    public function get_breadcrumbs($id)
    {
        $breadcrumbs[] = ['id'=> 0 , 'title'=>'media'] ;
        $parents = Category::all_parents($id) ;
        if ($parents)
        {
            foreach ($parents as $parent)
            {
                if ($parent->parent_category)
                {
                    $breadcrumbs[] =['id' =>$parent->parent_category->id , 'title'=>$parent->parent_category->title] ;
                }

            }

        }
        return $breadcrumbs ;
    }
    /**
     * @param $id
     * @return mixed
     */
    private function show($id , $search=false)
    {
        $breadcrumbs = $this->get_breadcrumbs($id) ;
        if ($id == 0){
            $files = File::get_uncategory_files($search) ;
            $categories = Category::get_root_categories($search) ;
            $category = false ;
            $result['html'] = view('laravel_file_manager::content' , compact('categories' ,'files','category','breadcrumbs'))->render() ;
            $result['message'] = '' ;
            $result['parent_category_id'] = $id;
            $result['button_upload_link'] = route('LFM.FileUpload' ,['category_id' =>$id] ) ;
            $result['button_category_create_link'] = route('LFM.ShowCategories.Create' ,['category_id' =>$id] ) ;
            $result['success'] = true ;
        }else {
            $category = Category::with('files','child_categories','parent_category' )->find($id) ;
            $breadcrumbs[] = ['id'=> $category->id , 'title'=>$category->title] ;
            $categories = $category->user_child_categories ;
            $files = $category->user_files ;
            $result['html'] = view('laravel_file_manager::content',compact('categories' ,'files','category','breadcrumbs'))->render() ;
            $result['message'] = '' ;
            $result['parent_category_id'] = $id;
            $result['button_upload_link'] = route('LFM.FileUpload' ,['category_id' =>$id] ) ;
            $result['button_category_create_link'] = route('LFM.ShowCategories.Create' ,['category_id' =>$id] ) ;
            $result['success'] = true ;
        }
        return $result ;

    }

}
