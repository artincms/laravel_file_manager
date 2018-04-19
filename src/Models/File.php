<?php

namespace ArtinCMS\LFM\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FileManager\FileManager
 *
 * @mixin \Eloquent
 */
class File extends Model
{
	protected $table = 'lfm_files';

    public function category()
    {
        return $this->belongsTo('ArtinCMS\LFM\Models\Category');
    }

    /**
     * @param array $withCount
     */
    public static function get_uncategory_files()
    {
       return  self::where('category_id' , '=' , '0')->get() ;
    }

}
