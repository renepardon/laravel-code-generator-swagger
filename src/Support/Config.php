<?php

namespace Renepardon\LaravelCodeGeneratorSwagger\Support;

use Renepardon\CodeGenerator\Support\Config as BaseConfig;
use Renepardon\CodeGenerator\Support\Helpers;

class Config extends BaseConfig
{
    /**
     * @param string $file
     *
     * @return string
     */
    public static function getParametersPath(string $file = ''): string
    {
        $parametersPath = app('config')->get('laravel-code-generator-swagger.parameters_path');

        return Helpers::fixPathSeparator(Helpers::getPathWithSlash($parametersPath)) . $file . 'Parameters.php';
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public static function getSchemasPath(string $file = ''): string
    {
        $schemasPath = app('config')->get('laravel-code-generator-swagger.schemas_path');

        return Helpers::fixPathSeparator(Helpers::getPathWithSlash($schemasPath)) . $file . 'Schema.php';
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public static function getResponsePath(string $file = ''): string
    {
        $responsesPath = app('config')->get('laravel-code-generator-swagger.response_path');

        return Helpers::fixPathSeparator(Helpers::getPathWithSlash($responsesPath)) . $file;
    }
}
