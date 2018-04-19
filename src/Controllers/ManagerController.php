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
       return view('laravel_file_manager::index' , compact('categories' ,'files','category'));
    }

    public function CreateCategory(){
        $categories = Category::all();
        return view('laravel_file_manager::category' , compact(
            'categories'
        ));
    }
    public function StoreCategory(Request $request) {
        if ($request->ajax()) {
            $date = $this->get_current_date() ;
            DB::transaction(function () use($request,$date){
                $cat = new Category ;
                $cat->title = $request->title ;
                $cat->user_id = 1 ;
                $cat->description = $request->description ;
                $cat->parent_category_id =$request->parent_category_id ;
                $cat->created_by = 1 ;
                $cat->save();
                //update title disc
                $cat->title_disc = $cat->id. '_' . md5($cat->name). '_' .$date ;
                $cat->save() ;

                //make directory
                $subcats =['orginal','large','medium' ,'small'] ;
                Storage::disk('file_manager')->makeDirectory($cat->title_disc) ;
                foreach ($subcats as $subcat) {
                    Storage::disk('file_manager')->makeDirectory($cat->title_disc. '/' .$subcat) ;
                }
            });
            return response()->json(['success' => true]);

        }
    }

    public function ShowCategory (Request $request) {
        if ($request->category_id == 0){
            $files = File::get_uncategory_files() ;
            $categories = Category::get_root_categories() ;
            $category = false ;
            $result['html'] = view('laravel_file_manager::content' , compact('categories' ,'files','category'))->render() ;
            $result['message'] = '' ;
            $result['success'] = true ;
        }else {
            $category = Category::with('files','child_categories','parent_category')->find($request->category_id) ;
            $categories = $category->child_categories ;
            $files = $category->files ;
            $result['html'] = view('laravel_file_manager::content',compact('categories' ,'files','category'))->render() ;
            $result['message'] = '' ;
            $result['success'] = true ;
        }

        return response()->json($result);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function FileUpload()
    {
        return view('laravel_file_manager::upload') ;
    }

    public function StoreUploads(Request $request) {
        if ($request->file)
        {
            $CategoryID = $request->category_id ;
            foreach ($request->file as $file)
            {
                $result = Media::upload($file, false, 1, $CategoryID);
                dd($result) ;
            }

        }

    }

    /**
     * @return string
     */
    public function get_current_date() {
        $mytime = Carbon::now();
        return $mytime->toDateString();
    }

}
