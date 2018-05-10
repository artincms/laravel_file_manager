<?php
namespace ArtinCMS\LFM\Traits ;

trait lfmFillable {
    public function files()
    {
        return $this->morphToMany('ArtinCMS\LFM\Models\File' , 'fileable','fm_fileables','fileable_id','file_id') ;
    }

}
