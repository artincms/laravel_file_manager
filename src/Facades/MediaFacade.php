<?php
namespace ArtinCMS\LFM\Facades;
use Illuminate\Support\Facades\Facade;

class LFMFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return 'FileManager';
    }
}