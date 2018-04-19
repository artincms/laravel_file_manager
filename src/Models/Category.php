<?php

namespace ArtinCMS\LFM\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FileManager\FileManager
 *
 * @mixin \Eloquent
 */
class Category extends Model
{
	protected $table = 'lfm_categories';

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

	public static function get_root_categories() {
	    $cat = Category::with(['child_categories' , 'parent_category' , 'users','files'])->where('parent_category_id' , '=' , '0')->get()->toArray() ;
	    return $cat ;
    }
    public function child_categories() {
        return $this->hasMany('ArtinCMS\LFM\Models\Category' , 'parent_category_id' , 'id') ;
    }
    public function parent_category() {
        return $this->belongsTo('ArtinCMS\LFM\Models\Category' , 'parent_category_id' , 'id') ;
    }

   /* public function getParentCategoryIdAttribute($value)
    {
        $id = $value ;
        while ($value !=0){
            $cat = Category::with('parent_category')->find($id);
            $id = $cat->id ;
            $value = $cat->parent_category_id ;



        }
        return $value ;
    }*/
}
