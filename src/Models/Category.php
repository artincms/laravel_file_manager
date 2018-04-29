<?php

namespace ArtinCMS\LFM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FileManager\FileManager
 *
 * @mixin \Eloquent
 */
class Category extends Model
{

	protected $table = 'lfm_categories';
    use SoftDeletes ;
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany('ArtinCMS\LFM\Models\File');
    }

    public function users()
    {
        return $this->belongsTo('App\User') ;
    }

	public static function get_root_categories($search = false) {
        if (auth()->check())
        {
            $user_id = auth()->id();
        }
        else
        {
            $user_id = 0;
        }
            $cat = Category::with(['child_categories' , 'parent_category'])->where([
                ['parent_category_id' , '=' , '0' ],
                ['user_id','=' , $user_id]
                ])->get();

	    return $cat ;
    }
    public function child_categories() {
        return $this->hasMany('ArtinCMS\LFM\Models\Category' , 'parent_category_id' , 'id') ;
    }


    public function parent_category() {
        return $this->belongsTo('ArtinCMS\LFM\Models\Category' , 'parent_category_id' , 'id') ;
    }

    public static function all_parents($id)
    {
        $result = [] ;
        while ($id != 0)
        {
            $cat = Category::with('parent_category')->find($id);
            $result[] =$cat;
            $id  = $cat->parent_category_id ;
        }
         return array_reverse($result) ;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserParentCategoryAttribute()
    {
        return $this->parent_category()->where('user_id','=',$this->user_id)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserChildCategoriesAttribute()
    {
        return $this->child_categories()->where('user_id','=',$this->user_id)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserFilesAttribute()
    {

        return $this->files()->where('user_id','=',$this->user_id)->get();
    }


    /**
     * @return int|null
     */
    public function getUserIdAttribute()
    {
        if (auth()->check())
        {
            $user_id = auth()->id();
        }
        else
        {
            $user_id = 0;
        }
        return $user_id ;
    }


}
