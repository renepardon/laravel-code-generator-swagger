<?php

namespace Renepardon\LaravelCodeGeneratorSwagger\Support;

use Renepardon\CodeGenerator\Models\Bases\ScaffoldInputBase;
use Renepardon\CodeGenerator\Models\Resource;
use Renepardon\LaravelCodeGeneratorSwagger\Models\OpenApiInput;

/**
 * Trait OpenApi
 *
 * @package Renepardon\LaravelCodeGeneratorSwagger\Support
 */
trait OpenApi
{
    /**
     * Fix for OpenApi data types
     *
     * @param string $type
     *
     * @return string
     */
    protected function mapDataType(string $type): string
    {
        switch ($type) {
            case'int':
                return 'integer';
            default:
                return $type;
        }
    }

    /**
     * @param \Renepardon\CodeGenerator\Models\Resource $resource
     * @param string                                    $destinationFile
     *
     * @return bool
     */
    protected function hasErrors(Resource $resource, string $destinationFile): bool
    {
        $hasErrors = false;

        if ($this->alreadyExists($destinationFile)) {
            $this->error('The file already exists!');

            $hasErrors = true;
        }

        return $hasErrors;
    }

    protected function getCommandInput()
    {
        $base = new ScaffoldInputBase(trim($this->argument('model-name')));
        $base->resourceFile = $this->option('resource-file');
        $base->template = $this->option('template-name');
        $base->force = $this->option('force');

        $input = new OpenApiInput($base);

        return $input;
    }

    /**
     * @param string $file
     *
     * @return bool
     */
    abstract protected function alreadyExists($file);

    /**
     * @param $stub
     * @param $modelName
     *
     * @return \Renepardon\LaravelCodeGeneratorSwagger\Support\OpenApi
     */
    abstract protected function replaceClassName(&$stub, $modelName): self;

    /**
     * @param string      $name
     * @param string|null $path
     *
     * @return string
     */
    abstract protected function getDestinationFile(string $name, string $path = null): string;
}
