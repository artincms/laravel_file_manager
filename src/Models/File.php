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
    protected $dates = ['deleted_at'];
    protected $appends =['encode_id'];
    use SoftDeletes;

    public function category()
    {
        return $this->belongsTo('ArtinCMS\LFM\Models\Category');
    }

    public function FileMimeType()
    {
        return $this->belongsTo('ArtinCMS\LFM\Models\FileMimeType', 'file_mime_type_id');
    }

    /**
     * @param array $withCount
     */
    public static function get_uncategory_files($trueMimeType = false)
    {
        if (auth()->check())
        {
            $user_id = auth()->id();
        }
        else
        {
            $user_id = 0;
        }
         $res = self::where([
            ['category_id', '=', '0'],
            ['user_id', '=',$user_id]
        ]);
        if($trueMimeType)
        {
            $res = $res->whereIn('mimeType',$trueMimeType);
        }

        return $res->get() ;
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

    public function getHummanSizeAttribute()
    {
        return LFM_FileSizeConvert($this->size) ;
    }

    public function getHummanSizeLargeAttribute()
    {
        return LFM_FileSizeConvert($this->large_size) ;
    }

    public function getHummanSizeMediumAttribute()
    {
        return LFM_FileSizeConvert($this->medium_size) ;
    }

    public function getHummanSizeSmallAttribute()
    {
        return LFM_FileSizeConvert($this->small_size) ;
    }

    public function getPublicoriginalLinkAttribute()
    {
        return LFM_GeneratePublicDownloadLink($this->path,$this->filename,'original') ;
    }

    public function getPublicLargeLinkAttribute()
    {
        return LFM_GeneratePublicDownloadLink($this->path,$this->large_filename,'large') ;
    }

    public function getPublicMediumLinkAttribute()
    {
        return LFM_GeneratePublicDownloadLink($this->path,$this->medium_filename,'medium') ;
    }

    public function getPublicSmallLinkAttribute()
    {
        return LFM_GeneratePublicDownloadLink($this->path,$this->small_filename,'small') ;
    }

    public function getEncodeIdAttribute()
    {
        return LFM_getEncodeId($this->id);
    }

    public function getCreatedAtAttribute($value)
    {
        if((string)app()->getLocale() == 'fa')
        {
            $date = LFM_Date_GtoJ($value,'Y-m-d');;
        }
        else
        {
            $date = $value ;
        }

        return $date ;
    }

    public function getUpdatedAtAttribute($value)
    {
        if((string)app()->getLocale() == 'fa')
        {
            $date = LFM_Date_GtoJ($value,'Y-m-d');;
        }
        else
        {
            $date = $value ;
        }

        return $date ;
    }
}
