<?php

namespace Renepardon\LaravelCodeGeneratorSwagger;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Renepardon\LaravelCodeGeneratorSwagger\Skeleton\SkeletonClass
 */
class LaravelCodeGeneratorSwaggerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-code-generator-swagger';
    }
}
