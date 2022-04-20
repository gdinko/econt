<?php

namespace Gdinko\Econt\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Gdinko\Econt\Skeleton\SkeletonClass
 */
class Econt extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'econt';
    }
}
