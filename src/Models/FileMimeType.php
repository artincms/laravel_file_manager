<?php
namespace ArtinCMS\LFM\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FileManager\FileMimeType
 *
 * @mixin \Eloquent
 */
class FileMimeType extends Model
{
    use SoftDeletes ;
    protected $table = 'lfm_file_mime_types';

}