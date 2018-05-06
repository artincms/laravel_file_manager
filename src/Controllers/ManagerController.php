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
    public function ShowCategories($insert = false, $section = false)
    {
        $session_option = SetSessionOption($section ,['size_file'=>100,'max_file_number' => 5 , 'true_file_extension' =>['png','jpeg']]);
        $files = File::get_uncategory_files();
        $categories = Category::get_root_categories();
        $category = false;
        $breadcrumbs[] = ['id' => 0, 'title' => 'media', 'type' => 'Enable'];
        $result['parent_category_name'] = 'media';
        return view('laravel_file_manager::index', compact('categories', 'files', 'category', 'breadcrumbs', 'insert', 'section'));
    }

    public function CreateCategory($id, $callback = false)
    {
        $messages = [];
        $category_id = $id;
        $categories = Category::where('user_id', '=', (auth()->check()) ? auth()->id() : 0)->get();
        return view('laravel_file_manager::category', compact('categories', 'category_id', 'messages', 'callback'));
    }

    public function EditCategory($id)
    {
        $messages = [];
        $category = Category::find($id);
        $category_id = $id;
        $categories = Category::where('user_id', '=', (auth()->check()) ? auth()->id() : 0)->get();
        return view('laravel_file_manager::edit_category', compact('categories', 'category', 'category_id', 'messages'));
    }

    public function StoreCategory(Request $request)
    {
        if ($request->ajax())
        {
            $date = $this->get_current_date();
            if (auth()->check())
            {
                $user_id = auth()->id();
            }
            else
            {
                $user_id = 0;
            }
            DB::transaction(function () use ($request, $date, $user_id) {
                $cat = new Category;
                $cat->title = $request->title;
                $cat->user_id = $user_id;
                $cat->description = $request->description;
                $cat->parent_category_id = $request->parent_category_id;
                $cat->created_by = $user_id;
                $cat->save();
                //update title disc
                $cat->title_disc = 'cid_' . $cat->id . '_uid_' . $user_id . '_' . md5($cat->name) . '_' . time();
                $cat->save();

                //make directory
                $subcats = config('laravel_file_manager.crop_type');
                $path = 'uploads/';
                foreach (Category::all_parents($cat->id) as $parent)
                {
                    if ($parent->parent_category)
                    {
                        $path .= $parent->parent_category->title_disc . '/';
                    }
                }
                $path .= $cat->title_disc;
                foreach ($subcats as $subcat)
                {
                    Storage::disk(config('laravel_file_manager.driver_disk'))->makeDirectory($path . '/files/' . $subcat);
                }

            });
            $category_id = $request->parent_category_id;
            $categories = Category::where('user_id', '=', (auth()->check()) ? auth()->id() : 0)->get();
            $result['success'] = true;
            $messages[] = "Your Category is created";
            return response()->json($result);

        }
    }

    public function ShowCategory(Request $request)
    {
        $result = $this->show($request->category_id,$request->insert,$request->section);
        return response()->json($result);

    }

    public function Trash(Request $request)
    {
        if ($request->type == "file")
        {
            $file = File::find($request->id);
            $file->delete();
        }
        else
        {
            $this->delete($request->id);
        }

        $result = $this->show($request->parent_id,$request->insert,$request->section);
        return response()->json($result);

    }

    private function delete($id)
    {
        $category = Category::with('files', 'child_categories', 'parent_category')->find($id);
        $category->delete();
        if ($category->files)
        {
            foreach ($category->files as $file)
            {
                $file->delete();
            }

        }
        if ($category->child_categories != null)
        {
            foreach ($category->child_categories as $child)
            {
                $id = $child->id;
                $this->delete($id);
            }
        }


    }

    public function BulkDelete(Request $request)
    {

        foreach ($request["items"] as $item)
        {
            if ($item['type'] == "file")
            {
                $file = File::find($item['id']);
                $file->delete();
            }
            else
            {
                $this->delete($item['id']);
            }
        }
        $result = $this->show($item['parent_id'],$request->insert,$request->section);
        return response()->json($result);
    }

    /**
     * id is category id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function FileUpload($id, $callback = false)
    {
        return view('laravel_file_manager::upload', compact('id', 'callback'));
    }

    /**
     * should send category_id and token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function StoreUploads(Request $request)
    {

        if ($request->file)
        {
            $CategoryID = $request->category_id;
            $res = [];
            foreach ($request->file as $file)
            {
                $mimeType = $file->getMimeType();
                $FileMimeType = FileMimeType::where('mimeType', '=', $mimeType)->first();
                $originalName = $file->getClientOriginalName();
                $size = $file->getSize();
                if (in_array($mimeType, config('laravel_file_manager.allowed')) === true && $FileMimeType)
                {
                    /*DB::transaction(function () use($file,$CategoryID,$FileMimeType,$originalName,$size){*/
                    $res = Media::upload($file, false, false, $CategoryID, $FileMimeType, $originalName, $size);
                    $message['success'] = true;
                    $message['result'] = $res;
                    /*});*/
                    $message['success'] = true;

                }
                else
                {
                    $message['success'] = false;
                }
            }
            return response()->json($message);
        }

    }

    public function StoreCropedImage(Request $request)
    {

        $data = str_replace('data:image/png;base64,', '', $request->crope_image);
        $data = str_replace(' ', '+', $data);
        $file = File::find($request->file_id);
        $res = Media::save_croped_image_base64($data, $file, $request->crop_type);
        if ($res)
        {
            $message['success'] = true;
        }
        else
        {
            $message['success'] = false;
        }
        $message['result'] = $res;
        return response()->json($message);
    }

    public function EditPicture($file_id)
    {
        $file = File::find($file_id);
        return view('laravel_file_manager::edit_picture', compact('file'));
    }

    public function Download($type = "ID", $id = -1, $size_type = 'orginal', $default_img = '404.png', $quality = 100, $width = false, $height = false)
    {
        if ($id == -1)
        {
            return Media::download_by_id(-1, 'orginal', $default_img);//"Not Valid Request";
        }

//        $id = deCode($id);
        switch ($type)
        {
            case "ID":
                return Media::download_by_id($id, $size_type, $default_img, false, $quality, $width, $height);
                break;
            case "Name":
                return Media::download_by_name($id, $size_type, $default_img, false, $quality, $width, $height);
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
        $file = [];
        $cat = [];
        $categories = Category::with('user')->select('id', 'title as name', 'user_id', 'parent_category_id', 'description', 'created_at', 'updated_at')
            ->where([
                ['user_id', '=', $this->get_user_id()],
                ['title', 'like', '%' . $request->search . '%']
            ])->get()->toArray();
        $files = File::with('user', 'FileMimeType')->select('id', 'originalName as name', 'user_id', 'file_mime_type_id', 'category_id', 'extension', 'mimeType', 'path', 'created_at', 'updated_at')
            ->where([
                ['user_id', '=', $this->get_user_id()],
                ['originalName', 'like', '%' . $request->search . '%']
            ])->get()->toArray();

        foreach ($files as $f)
        {
            if ($f['file_mime_type']['icon_class'])
            {
                $f['icon'] = $f['file_mime_type']['icon_class'];
            }
            else
            {
                $f['icon'] = 'fa-file-o';
            }
            $f['type'] = 'file';

            $f['Path'] = $this->get_breadcrumbs($f['category_id']);
            if ($f['category_id'] != 0)
            {
                $file_cat = Category::find($f['category_id']);
                $f['Path'][] = ['id' => $file_cat->id, 'title' => $file_cat->title, 'type' => 'Enable'];
            }

            $file[] = $f;
        }

        foreach ($categories as $category)
        {
            $category['type'] = 'category';
            $category['icon'] = 'fa-folder';
            $category['Path'] = $this->get_breadcrumbs($category['id']);
            $cat[] = $category;
        }

        return datatables()->of(array_merge($cat, $file))->toJson();


    }

    /**
     * @return string
     */
    public function get_current_date()
    {
        $mytime = Carbon::now();
        return $mytime->toDateString();
    }

    public function ShowList(Request $request)
    {
        return view('laravel_file_manager::content_list');
    }

    public function ShowListCategory(Request $request)
    {
        $file = [];
        $cat = [];
        if ($request->id == 0)
        {
            $categories = Category::with(['child_categories', 'parent_category', 'user'])->select('id', 'title as name', 'user_id')->where([
                ['parent_category_id', '=', '0'],
                ['user_id', '=', $this->get_user_id()]
            ])->get()->toArray();


            $files = File::with('user', 'FileMimeType')->select('id', 'originalName as name', 'user_id', 'mimeType', 'category_id', 'file_mime_type_id', 'created_at', 'updated_at')->where([
                ['category_id', '=', '0'],
                ['user_id', '=', $this->get_user_id()]
            ])->get()->toArray();

        }
        else
        {
            $category = Category::with('files', 'child_categories', 'parent_category')->find($request->id);
            $categories = $category->user_child_categories->toArray();
            $files = $category->user_files->toArray();
        }
        foreach ($categories as $category)
        {
            $category['type'] = 'category';
            $category['icon'] = 'fa-folder';
            $cat[] = $category;
        }
        foreach ($files as $f)
        {
            if ($f['file_mime_type']['icon_class'])
            {
                $f['icon'] = $f['file_mime_type']['icon_class'];
            }
            else
            {
                $f['icon'] = 'fa-file-o';
            }
            $f['type'] = 'file';
            $file[] = $f;
        }

        //dd(array_merge($file, $cat);
        return datatables()->of(array_merge($cat, $file))->toJson();

    }

    /**
     * @param $id
     * @return array
     */
    public function get_breadcrumbs($id)
    {
        $breadcrumbs[] = ['id' => 0, 'title' => 'media', 'type' => 'Enable'];
        $parents = Category::all_parents($id);
        if ($parents)
        {
            foreach ($parents as $parent)
            {
                if ($parent->parent_category)
                {
                    $breadcrumbs[] = ['id' => $parent->parent_category->id, 'title' => $parent->parent_category->title, 'type' => 'Enable'];
                }

            }

        }

        return $breadcrumbs;

    }

    /**
     * @param $id
     * @return mixed
     */
    private function show($id, $insert = false , $section= false)
    {
        $breadcrumbs = $this->get_breadcrumbs($id);
        if ($id == 0)
        {
            $files = File::get_uncategory_files();
            $categories = Category::get_root_categories();
            $category = false;
            $result['html'] = view('laravel_file_manager::content', compact('categories', 'files', 'category', 'breadcrumbs', 'insert','section'))->render();
            $result['message'] = '';
            $result['parent_category_id'] = $id;
            $result['parent_category_name'] = 'media';
            $result['button_upload_link'] = route('LFM.FileUpload', ['category_id' => $id, 'callback' => 'refresh']);
            $result['button_category_create_link'] = route('LFM.ShowCategories.Create', ['category_id' => $id, 'callback' => 'refresh']);
            $result['success'] = true;
        }
        else
        {
            $category = Category::with('files', 'child_categories', 'parent_category')->find($id);
            $breadcrumbs[] = ['id' => $category->id, 'title' => $category->title, 'type' => 'DisableLink'];
            $categories = $category->user_child_categories;
            $files = $category->user_files;
            $result['html'] = view('laravel_file_manager::content', compact('categories', 'files', 'category', 'breadcrumbs', 'insert','section'))->render();
            $result['message'] = '';
            $result['parent_category_id'] = $id;
            $result['parent_category_name'] = $category->title;
            $result['button_upload_link'] = route('LFM.FileUpload', ['category_id' => $id, 'callback' => 'refresh']);
            $result['button_category_create_link'] = route('LFM.ShowCategories.Create', ['category_id' => $id, 'callback' => 'refresh']);
            $result['success'] = true;
        }
        return $result;

    }

    public function CreateInsertData(Request $request)
    {
        $options = $this->get_session_options($request,$request->section);
        if ($options)
        {
            $datas = $this->create_all_insert_data($request) ;
            $result_session = $this->set_selected_file_to_session($request, $request->section, $datas);
            $datas['success'] = true;
        }
        else
        {
            $datas['success'] = false;
        }
        return response()->json($datas);


    }

    private function get_session_options($request,$section)
    {
        if ($request->has('section'))
        {
            if (session()->has('LFM'))
            {
                $LFM = session()->get('LFM');
                if (in_array($request->$section, $LFM))
                {
                    //check options
                    if ($LFM[$section]['options'])
                    {
                        //check options
                        dd(in_array($request->$section, $LFM)) ;
                    }
                }
            }
            else
            {
                return false;
            }

        }
    }

    private function create_all_insert_data($request)
    {
        $datas = [];

        foreach ($request->items as $item)
        {
            $id = $item['id'];
            $full_url = route('LFM.DownloadFile', ['type' => 'ID', 'id' => $id, 'size_type' => $request->type, 'default_img' => '404.png'
                , 'quality' => $request->quality, 'width' => $request->width, 'height' => $request->height
            ]);
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
            $url = str_replace($protocol, '', $full_url);
            $url = str_replace('://', '', $url);
            $url = str_replace($_SERVER['HTTP_HOST'], '', $url);
            $file = File::find($id);
            switch ($request->type)
            {
                case "orginal":
                    $file_title_disc = $file->filename;
                    $version = $file->versioin;
                    break;
                case "large":
                    $file_title_disc = $file->large_filename;
                    $version = $file->large_version;
                    break;
                case "medium":
                    $file_title_disc = $file->medium_filename;
                    $version = $file->medium_version;
                    break;
                case "small":
                    $file_title_disc = $file->small_filename;
                    $version = $file->small_version;
                    break;
                default:
                    $file_title_disc = $file->filename;
                    $version = $file->versioin;
                    break;
            }
            $data['full_url'] = $full_url;
            $data['url'] = $url;
            $data['file'] = [
                'id' => $file->id,
                'type' => $request->type,
                'width' => $request->width,
                'height' => $request->height,
                'quality' => $request->quality,
                'title_file_disc' => $file_title_disc,
                'version' => $version
            ];

            $datas[] = $data;

        }
        return $datas ;
    }

    public function get_user_id()
    {
        if (auth()->check())
        {
            $user_id = auth()->id();
        }
        else
        {
            $user_id = 0;
        }
        return $user_id;

    }

    private function set_selected_file_to_session($request, $section, $data)
    {
        if ($request->has('section'))
        {
            if (session()->has('LFM'))
            {
                $LFM = session()->get('LFM');
                if (in_array($request->$section, $LFM))
                {
                    //check options
                    $LFM[$section]['selected'] = array_merge($LFM[$section]['selected'], $data);
                    session()->put('LFM', $LFM);
                    return $LFM[$section]['selected'] ;
                }
            }
            else
            {
                return false;
            }

        }
    }


}
