<?php

namespace ArtinCMS\LFM\Controllers;

use Illuminate\Http\Request;
use ArtinCMS\LFM\Models\File;
use ArtinCMS\LFM\Models\Category;
use ArtinCMS\LFM\Helpers\Classes\Media;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Validator;


class ManagerController extends Controller
{
    private function show($id, $insert = false, $callback = false, $section = false)
    {
        $id = LFM_GetDecodeId($id);
        Cookie::queue('current_category_id', $id);
        $allCategories['root'] = LFM_BuildMenuTree(Category::where('user_id', '=', $this->getUserId())->get(), 'parent_category_id', false, $id, 0);
        $allCategories['share'] = LFM_BuildMenuTree(Category::all(), 'parent_category_id', false, $id, -2);
        $allCategories['public'] = LFM_BuildMenuTree(Category::all(), 'parent_category_id', false, $id, -1);
        if ($section)
        {
            $LFM = session()->get('LFM');
            if (isset($LFM[ $section ]))
            {
                if (isset($LFM[ $section ]['options']))
                {
                    $trueMimeType = $LFM[ $section ]['options']['true_mime_type'];
                }
                else
                {
                    $trueMimeType = false;
                }
            }
            else
            {
                $trueMimeType = false;
            }
        }
        else
        {
            $trueMimeType = false;
        }
        $result['button_upload_link'] = lfm_secure_route('LFM.FileUpload', ['category_id' => LFM_getEncodeId($id), 'callback' => 'refresh', 'section' => LFM_CheckFalseString($section)]);
        $result['allCategories'] = $allCategories;
        $result['success'] = true;
        $parent_id = $id;
        $category = Category::with('parent_category')->find((int)$id);
        $breadcrumbs = $this->getBreadcrumbs($id, $category);
        $available = LFM_CheckAllowInsert($section)['available'];
        if ($id == 0)
        {
            $files = File::get_uncategory_files($trueMimeType);
            $categories = Category::get_root_categories();
            $category = false;
            if (count($files) == 0 && count($categories) == 0)
            {
                $show_icon_folder = true;
            }
            else
            {
                $show_icon_folder = false;
            }
            $result['html'] = view('laravel_file_manager::content', compact('categories', 'files', 'category', 'breadcrumbs', 'insert', 'section', 'allCategories', 'parent_id', 'callback', 'available', 'show_icon_folder'))->render();
            $result['message'] = '';
            $result['button_upload_link'] = lfm_secure_route('LFM.FileUpload', ['category_id' => LFM_getEncodeId($id), 'callback' => 'refresh', 'section' => LFM_CheckFalseString($section), 'callback' => LFM_CheckFalseString($callback)]);
            $result['parent_category_id'] = LFM_getEncodeId($id);
            $result['parent_category_name'] = __('filemanager.root_folder');
            $result['button_category_create_link'] = lfm_secure_route('LFM.ShowCategories.Create', ['category_id' => LFM_getEncodeId($id), 'callback' => LFM_CheckFalseString($callback), 'section' => LFM_CheckFalseString($section)]);
        }
        else
        {
            if (in_array(-2, Category::getAllParentId($id)))
            {
                if ($trueMimeType)
                {
                    $files = File::where('category_id', '=', $id);
                    $files = $files->whereIn('mimeType', $trueMimeType)->get();
                }
                else
                {
                    $files = File::where('category_id', '=', $id)->get();
                }

                $categories = $category->child_categories;
                $result['parent_category_name'] = 'Share';
            }
            elseif (in_array(-1, Category::getAllParentId($id)))
            {
                if ($trueMimeType)
                {
                    $files = File::where('category_id', '=', $id);
                    $files = $files->whereIn('mimeType', $trueMimeType)->get();
                }
                else
                {
                    $files = File::where('category_id', '=', $id)->get();
                }
                $categories = $category->child_categories;
                $result['parent_category_name'] = 'Public';
            }
            else
            {
                $files = $category->UserFiles($trueMimeType);
                $categories = $category->user_child_categories;
                $result['parent_category_name'] = $category->title;
            }
            if (in_array($id, [-1, -2]))
            {
                $category = false;
            }
            $result['parent_category_id'] = LFM_getEncodeId($id);
            if (count($files) == 0 && count($categories) == 0)
            {
                $show_icon_folder = true;
            }
            else
            {
                $show_icon_folder = false;
            }
            $result['html'] = view('laravel_file_manager::content', compact('categories', 'files', 'category', 'breadcrumbs', 'insert', 'section', 'allCategories', 'parent_id', 'callback', 'available', 'show_icon_folder'))->render();
            $result['message'] = '';
            $result['button_category_create_link'] = lfm_secure_route('LFM.ShowCategories.Create', ['category_id' => LFM_getEncodeId($id), 'callback' => LFM_CheckFalseString($callback), 'section' => LFM_CheckFalseString($section)]);
        }

        return $result;
    }

    private function delete_category($id)
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
                $this->delete_category($id);
            }
        }
    }

    //categories
    public function showCategories($insert = false, $callback = false, $section = false)
    {
        if ($insert && !$callback)
        {
            abort(404);
        }
        if ($section)
        {
            $LFM = session()->get('LFM');
            if ($LFM[ $section ])
            {
                if ($LFM[ $section ]['options']['true_mime_type'])
                {
                    $trueMimeType = $LFM[ $section ]['options']['true_mime_type'];
                }
                else
                {
                    $trueMimeType = false;
                }
            }
            else
            {
                $trueMimeType = false;
            }
            $available = LFM_CheckAllowInsert($section)['available'];
        }
        else
        {
            $trueMimeType = false;
            $available = 'true';

        }
        //check user can upload private file
        if (config('laravel_file_manager.allow_upload_private_file'))
        {
            $files = File::get_uncategory_files($trueMimeType);
            $categories = Category::get_root_categories();
            $parent_id = 0;
            $breadcrumbs[] = ['id' => 0, 'title' => __('filemanager.root_folder'), 'type' => 'Enable'];
            $result['parent_category_name'] = __('filemanager.root_folder');
        }
        else
        {
            $files = File::where('category_id', '=', -2)->get();
            $categories = Category::where('parent_category_id', '=', -2)->get();
            $parent_id = -2;
            $breadcrumbs[] = ['id' => -2, 'title' => __('filemanager.share_folder'), 'type' => 'Enable'];
            $result['parent_category_name'] = __('filemanager.root_folder');
        }

        $id = Cookie::get('current_category_id', 0);
        $button_upload_link = lfm_secure_route('LFM.FileUpload', ['category_id' => LFM_getEncodeId($id), 'callback' => 'refresh', 'section' => LFM_CheckFalseString($section)]);

        if ($id == 0)
        {
            $allCategories['share'] = LFM_BuildMenuTree(Category::all(), 'parent_category_id', false, false, -2);
            $allCategories['public'] = LFM_BuildMenuTree(Category::all(), 'parent_category_id', false, false, -1);
            $allCategories['root'] = LFM_BuildMenuTree(Category::where('user_id', '=', $this->getUserId())->get(), 'parent_category_id', false, false, 0);
            $category = false;
            $allCategories = json_encode($allCategories);
            if (count($files) == 0 && count($categories) == 0)
            {
                $show_icon_folder = true;
            }
            else
            {
                $show_icon_folder = false;
            }
            return view('laravel_file_manager::index', compact('categories', 'files', 'category', 'breadcrumbs', 'insert', 'section', 'callback', 'allCategories', 'parent_id', 'available', 'show_icon_folder', 'button_upload_link'));
        }
        else
        {
            $allCategories['root'] = LFM_BuildMenuTree(Category::where('user_id', '=', $this->getUserId())->get(), 'parent_category_id', false, $id, 0);
            $allCategories['share'] = LFM_BuildMenuTree(Category::all(), 'parent_category_id', false, $id, -2);
            $allCategories['public'] = LFM_BuildMenuTree(Category::all(), 'parent_category_id', false, $id, -1);
            $allCategories = json_encode($allCategories);
            $parent_id = $id;
            $category = Category::with('parent_category')->find((int)$id);
            $breadcrumbs = $this->getBreadcrumbs($id, $category);
            $available = LFM_CheckAllowInsert($section)['available'];
            if (in_array(-2, Category::getAllParentId($id)))
            {
                if ($trueMimeType)
                {
                    $files = File::where('category_id', '=', $id);
                    $files = $files->whereIn('mimeType', $trueMimeType)->get();
                }
                else
                {
                    $files = File::where('category_id', '=', $id)->get();
                }

                $categories = $category->child_categories;
                $result['parent_category_name'] = 'Share';
            }
            elseif (in_array(-1, Category::getAllParentId($id)))
            {
                if ($trueMimeType)
                {
                    $files = File::where('category_id', '=', $id);
                    $files = $files->whereIn('mimeType', $trueMimeType)->get();
                }
                else
                {
                    $files = File::where('category_id', '=', $id)->get();
                }
                $categories = $category->child_categories;
                $parent_category_name = 'Public';
            }
            else
            {
                $files = $category->UserFiles($trueMimeType);
                $categories = $category->user_child_categories;
                $parent_category_name = $category->title;
            }
            if (in_array($id, [-1, -2]))
            {
                $category = false;
            }
            $parent_category_id = LFM_getEncodeId($id);
            if (count($files) == 0 && count($categories) == 0)
            {
                $show_icon_folder = true;
            }
            else
            {
                $show_icon_folder = false;
            }

            return view('laravel_file_manager::index', compact('categories', 'files', 'category', 'breadcrumbs', 'insert', 'section', 'callback', 'allCategories', 'parent_id', 'available', 'show_icon_folder', 'button_upload_link'));
        }

    }

    public function createCategory($category_id = 0, $callback = false, $section = false)
    {
        if (!config('laravel_file_manager.allow_upload_private_file'))
        {
            $categories = LFM_GetChildCategory([-1, -2]);
        }
        else
        {
            if (in_array(-2, Category::getAllParentId($category_id)))
            {
                $categories = Category::all();
            }
            elseif (in_array(-1, Category::getAllParentId($category_id)))
            {
                $categories = Category::all();
            }
            else
            {
                $categories = Category::where('user_id', '=', (auth()->check()) ? auth()->id() : 0)->orWhere('id', '=', 0)->get();
            }
        }
        $messages = [];

        return view('laravel_file_manager::category', compact('categories', 'category_id', 'messages', 'callback', 'section'));
    }

    public function editCategory($category_id)
    {
        $messages = [];
        $category = Category::find($category_id);
        if (!config('laravel_file_manager.allow_upload_private_file'))
        {
            $categories = LFM_GetChildCategory([-1, -2]);
        }
        else
        {
            if (in_array(-2, Category::getAllParentId($category_id)))
            {
                $categories = Category::all();
            }
            elseif (in_array(-1, Category::getAllParentId($category_id)))
            {
                $categories = Category::all();
            }
            else
            {
                $categories = Category::where('user_id', '=', (auth()->check()) ? auth()->id() : 0)->orWhere('id', '=', 0)->get();
            }
        }
        $parent_category_id = $category->parent_category_id;

        return view('laravel_file_manager::edit_category', compact('categories', 'category', 'category_id', 'parent_category_id'));
    }

    public function storeCategory(Request $request)
    {

        if ($request->ajax())
        {
            if (auth()->check())
            {
                $user_id = auth()->id();
            }
            else
            {
                $user_id = 0;
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|max:255',
            ]);
            $check = Category::where([
                ['parent_category_id', '=', $request->parent_category_id],
                ['title', '=', $request->title]
            ])->get();
            if (count($check) != 0)
            {
                $validator->after(function ($validator) {
                    $validator->errors()->add(__('filemanager.error'), __('filemanager.error_title_repeated'));
                });
            }
            if (!config('laravel_file_manager.allow_upload_private_file'))
            {
                if (!in_array($request->parent_category_id, LFM_CreateArrayId(LFM_GetChildCategory([-2, -1]))))
                {
                    $validator->after(function ($validator) {
                        $validator->errors()->add(__('filemanager.error'), __('filemanager.error_not_allow_permition_to_create_category'));
                    });
                }
            }
            $validator->validate();
            $result = \DB::transaction(function () use ($request, $user_id) {
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
                if (in_array(-2, Category::getAllParentId($request->parent_category_id)))
                {
                    $path = 'share_folder/';
                }
                elseif (in_array(-1, Category::getAllParentId($request->parent_category_id)))
                {
                    $path = 'public_folder/';
                }
                else
                {
                    $path = 'media_folder/';
                }
                foreach (Category::all_parents($cat->id) as $parent)
                {
                    if ($parent->parent_category && $parent->parent_category->parent_category_id != '#')
                    {
                        $path .= $parent->parent_category->title_disc . '/';
                    }
                }
                $path .= $cat->title_disc;
                foreach ($subcats as $subcat)
                {
                    Storage::disk(config('laravel_file_manager.driver_disk'))->makeDirectory(config('laravel_file_manager.main_storage_folder_name') . '/' . $path . '/files/' . $subcat);
                }
                $category_id = $request->parent_category_id;
                $categories = Category::where('user_id', '=', (auth()->check()) ? auth()->id() : 0)->get();
                $result['success'] = true;

                return $result;
            });

            return response()->json($result);
        }
    }

    public function updateCategory(Request $request)
    {
        $validator = $this->validate($request, [
            'title'       => 'required|max:255',
            'description' => 'required'
        ]);
        if (!config('laravel_file_manager.allow_upload_private_file'))
        {
            if (!in_array(LFM_GetDecodeId($request->id), LFM_CreateArrayId(LFM_GetChildCategory([-2, -1]))))
            {
                $validator->after(function ($validator) {
                    $validator->errors()->add(__('filemanager.error'), __('filemanager.error_not_allow_permition_to_create_category'));
                });
            }
            $validator->validate();
        }
        $cat = Category::find(LFM_GetDecodeId($request->id));
        $cat->title = $request->title;
        $cat->description = $request->description;
        $cat->save();
        $result['success'] = true;

        return response()->json($result);
    }

    public function editFileName(Request $request)
    {
        $validatedData = $this->validate($request, [
            'name' => 'required|max:255',
        ]);
        $file = File::find($request->id);
        $file->original_name = $request->name;
        $file->save();
        $result['success'] = true;
        $messages[] = "Your Category is created";

        return response()->json($result);
    }

    public function showListCategory(Request $request)
    {
        if ($request->section != null && $request->section != false && $request->section != 'false')
        {
            $LFM = session()->get('LFM');
            $trueMimeType = $LFM[ $request->section ]['options']['true_mime_type'];
        }
        else
        {
            $trueMimeType = false;
        }
        $file = [];
        $cat = [];
        if ($request->id == 0)
        {
            $categories = Category::with(['child_categories', 'parent_category', 'user'])->where([
                ['parent_category_id', '=', '0'],
                ['user_id', '=', $this->getUserId()]
            ])->get()->toArray();

            if ($trueMimeType)
            {
                $files = File::with('user', 'FileMimeType')->where([
                    ['category_id', '=', '0'],
                    ['user_id', '=', $this->getUserId()]
                ])->whereIn('mimeType', $trueMimeType)->get()->toArray();
            }
            else
            {
                $files = File::with('user', 'FileMimeType')->where([
                    ['category_id', '=', '0'],
                    ['user_id', '=', $this->getUserId()]
                ])->get()->toArray();
            }
        }
        else
        {
            $category = Category::with('files', 'child_categories', 'parent_category')->find($request->id);
            $categories = $category->user_child_categories->toArray();
            $files = $category->UserFiles($trueMimeType)->toArray();
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

        return datatables()->of(array_merge($cat, $file))->toJson();
    }

    public function showCategory(Request $request)
    {
        $result = $this->show($request->category_id, $request->insert, $request->callback, $request->section);

        return response()->json($result);
    }

    public function editFile($file_id)
    {
        $file = File::find(LFM_GetDecodeId($file_id));

        return view('laravel_file_manager::edit_file_name', compact('file'));
    }

    public function trash(Request $request)
    {
        if ($request->type == "file")
        {
            $file = File::find((int)LFM_GetDecodeId($request->id));
            if ($file)
            {
                $file->delete();
            }
        }
        else
        {
            $this->delete_category(LFM_GetDecodeId($request->id));
        }
        $result = $this->show($request->parent_id, $request->insert, $request->callback, $request->section);

        return response()->json($result);
    }

    public function bulkDelete(Request $request)
    {
        foreach ($request["items"] as $item)
        {
            if ($item['type'] == "file")
            {
                $file = File::find(LFM_GetDecodeId($item['id']));
                $file->delete();
            }
            else
            {
                $this->delete_category(LFM_GetDecodeId($item['id']));
            }
        }
        $result = $this->show($item['parent_id'], $request->insert, $request->callback, $request->section);

        return response()->json($result);
    }

    public function storeCropedImage(Request $request)
    {
        $data = str_replace('data:image/png;base64,', '', $request->crope_image);
        $data = str_replace(' ', '+', $data);
        $file = File::find($request->file_id);
        $res = Media::saveCropedImageBase64($data, $file, $request->crop_type);
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

    public function editPicture($file_id)
    {
        $file = File::find(LFM_GetDecodeId($file_id));

        return view('laravel_file_manager::edit_picture', compact('file'));
    }

    public function storeEditPictureName(Request $request)
    {
        $validatedData = $this->validate($request, [
            'name' => 'required|max:255',
        ]);
        $file = File::find(LFM_GetDecodeId($request->id));
        $file->original_name = $request->name;
        $file->save();
        $result['success'] = true;
        $result['name'] = $request->name;
        $result['message'] = "pictrue renamed";

        return response()->json($result);
    }

    public function getBreadcrumbs($id, $category)
    {
        if (in_array(-2, Category::getAllParentId($id)))
        {
            $breadcrumbs[] = ['id' => -2, 'title' => __('filemanager.share_folder'), 'type' => 'Enable'];
        }
        elseif (in_array(-1, Category::getAllParentId($id)))
        {
            $breadcrumbs[] = ['id' => -1, 'title' => __('filemanager.public_folder'), 'type' => 'Enable'];
        }
        else
        {
            $breadcrumbs[] = ['id' => 0, 'title' => __('filemanager.root_folder'), 'type' => 'Enable'];
        }
        $parents = Category::all_parents($id);
        if ($parents)
        {
            foreach ($parents as $parent)
            {
                if ($parent->parent_category && $parent->parent_category->parent_category_id != '#')
                {
                    $breadcrumbs[] = ['id' => $parent->parent_category->id, 'title' => $parent->parent_category->title, 'type' => 'Enable'];
                }
            }
        }
        if (!in_array($id, [-2, -1, 0]))
        {
            $breadcrumbs[] = ['id' => $category->id, 'title' => $category->title, 'type' => 'DisableLink'];
        }

        return $breadcrumbs;
    }

    public function getUserId()
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

    public function getSectionOptions($section)
    {
        if (session()->has('LFM'))
        {
            $LFM = session()->get('LFM');
            if (isset($LFM[ $section ]))
            {
                //check options
                if ($LFM[ $section ]['options'])
                {
                    $result['options'] = $LFM[ $section ]['options'];
                    $result['success'] = true;

                    return $result;
                }
                else
                {
                    $result['success'] = false;

                    return $result;
                }
            }
            else
            {
                $result['success'] = false;
                $result['error'] = '';

                return $result;
            }
        }
        else
        {
            $result['success'] = false;
            $result['error'] = '';

            return $result;
        }
    }

    public function searchMedia(Request $request)
    {
        $categories = [];
        $files = [];
        if (auth()->check())
        {
            $user_id = auth()->id();
        }
        else
        {
            $user_id = 0;
        }
        $LFM = Session::get('LFM');
        $section = $request->section;
        if (isset($LFM[ $section ]['options']['true_mime_type']))
        {
            $true_myme_types = $LFM[ $section ]['options']['true_mime_type'];
        }
        else
        {
            $true_myme_types = false;
        }
        $true_cat_id = LFM_CreateArrayId(LFM_GetChildCategory([0]));
        if (config('laravel_file_manager.allow_upload_private_file'))
        {
            $breadcrumbs = [['id' => 0, 'title' => __('filemanager.root_folder'), 'type' => 'Enable'], ['id' => 0, 'title' => __('filemanager.search') . ' : ' . $request->search, 'type' => 'DisableLink']];
            $subcategories = Category::with('user')->where([
                ['title', 'like', '%' . $request->search . '%'],
                ['id', '!=', -5]
            ])->whereIn('id', $true_cat_id)->where('user_id', $this->getUserId())->get();
            $categories = Category::with('user')->where([
                ['title', 'like', '%' . $request->search . '%'],
                ['id', '!=', -5]
            ])->whereNotIn('id', $true_cat_id)->get();
            $categories = $categories->merge($subcategories);
            $subfiles = File::with('user', 'FileMimeType')->where([
                ['original_name', 'like', '%' . $request->search . '%'],
                ['category_id', '!=', -5]
            ])->whereIn('category_id', $true_cat_id)->where('user_id', $this->getUserId());
            if ($true_myme_types)
            {
                $subfiles->whereIn('mimeType', $true_myme_types);
            }
            $subfiles = $subfiles->get();
            $files = File::with('user', 'FileMimeType')->where([
                ['original_name', 'like', '%' . $request->search . '%'],
                ['category_id', '!=', -5]
            ])->whereNotIn('category_id', $true_cat_id);
            if ($true_myme_types)
            {
                $files->whereIn('mimeType', $true_myme_types);
            }
            $files = $files->get();
            $files = $files->merge($subfiles);
        }
        else
        {
            $breadcrumbs = [['id' => -2, 'title' => __('filemanager.share_folder'), 'type' => 'Enable'], ['id' => -2, 'title' => 'search : ' . $request->search, 'type' => 'DisableLink']];
            $LFM = Session::get('LFM');
            $section = $request->section;
            if (isset($LFM[ $section ]['options']['true_mime_type']))
            {
                $true_myme_types = $LFM[ $section ]['options']['true_mime_type'];
            }
            else
            {
                $true_myme_types = false;
            }
            $categories = Category::with('user')->where([
                ['title', 'like', '%' . $request->search . '%'],
                ['id', '!=', -5]
            ])->whereNotIn('id', $true_cat_id)->get();
            $files = File::with('user', 'FileMimeType')->where([
                ['original_name', 'like', '%' . $request->search . '%'],
                ['category_id', '!=', -5]
            ])->whereNotIn('category_id', $true_cat_id);
            if ($true_myme_types)
            {
                $files->whereIn('mimeType', $true_myme_types);
            }
            $files = $files->get();
        }
        $result['html'] = view('laravel_file_manager::search', compact('categories', 'files', 'breadcrumbs'))->render();
        $result['success'] = true;

        return response()->json($result);
    }

    public function trashTempFolder(Request $request)
    {
        $tmp = \Storage::disk(config('laravel_file_manager.driver_disk'));
        if ($tmp->has(config('laravel_file_manager.main_storage_folder_name') . '/media_tmp_folder/'))
        {
            $tmp->deleteDirectory(config('laravel_file_manager.main_storage_folder_name') . '/media_tmp_folder/');
            $message = __('filemanager.deleted_temp_folder_success');
            $success = true;
            $type = 'success';
        }
        else
        {
            $success = false;
            $message = __('filemanager.deleted_temp_folder_error');
            $type = 'error';
        }

        return [
            'success' => $success,
            'message' => $message,
            'type'    => $type,
        ];
    }
}
