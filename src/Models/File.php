<?php

namespace ArtinCMS\LFM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FileManager\FileManager
 *
 * @mixin \Eloquent
 */
class File extends Model
{
    protected $table = 'lfm_files';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo('ArtinCMS\LFM\Models\Category');
    }

    public function filemimetype()
    {
        return $this->belongsTo('ArtinCMS\LFM\Models\FileMimeType', 'file_mime_type_id');
    }

    /**
     * @param array $withCount
     */
    public static function get_uncategory_files($search = false)
    {
        if (auth()->check())
        {
            $user_id = auth()->id();
        }
        else
        {
            $user_id = 0;
        }
        return self::where([
            ['category_id', '=', '0'],
            ['user_id', '=',$user_id]
        ])->get();
    }

    public function user()
    {
        return $this->belongsTo(config('laravel_file_manager.user_model'), 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserCategoryAttribute()
    {
        return $this->category()->where('user_id','=',$this->user_id)->get();
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
