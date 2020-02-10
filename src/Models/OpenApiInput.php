<?php

namespace Renepardon\LaravelCodeGeneratorSwagger\Models;

use Renepardon\CodeGenerator\Models\Bases\ScaffoldInputBase;

class OpenApiInput
{
    /**
     * @var string
     */
    public $modelName;

    /**
     * @var string
     */
    public $modelDirectory;

    /**
     * Creates a new class instance.
     *
     * @param \Renepardon\CodeGenerator\Models\Bases\ScaffoldInputBase $model
     *
     * @return void
     */
    public function __construct(ScaffoldInputBase $model)
    {
        foreach ($model as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
