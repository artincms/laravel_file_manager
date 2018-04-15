<?php
namespace ArtinCMS\LFM;
use Illuminate\Support\Facades\Facade;

class LFMFacade extends Facade
{
	protected static function getFacadeAccessor() {
		return 'LFMC';
	}
}