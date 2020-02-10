<?php

namespace Renepardon\LaravelCodeGeneratorSwagger\Console\Command\Api\OpenApi;

use Illuminate\Console\Command;
use Renepardon\CodeGenerator\Models\Resource;
use Renepardon\CodeGenerator\Support\Helpers;
use Renepardon\CodeGenerator\Traits\CommonCommand;
use Renepardon\CodeGenerator\Traits\ScaffoldTrait;
use Renepardon\LaravelCodeGeneratorSwagger\Models\OpenApiInput;
use Renepardon\LaravelCodeGeneratorSwagger\Support\Config;
use Renepardon\LaravelCodeGeneratorSwagger\Support\OpenApi;

/**
 * Class Parameter
 *
 * @package Renepardon\LaravelCodeGeneratorSwagger\Console\Command\Api\OpenApi
 */
class Parameter extends Command
{
    use ScaffoldTrait, CommonCommand, OpenApi;

    const TEMPLATE = 'openapi-parameter';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'create:openapi-parameter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an OpenAPI parameter class';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:openapi-parameter
                            {model-name : The model name that this resource will represent.}
                            {--resource-file= : The name of the resource-file to import from.}
                            {--template-name= : The template name to use when generating the code.}
                            {--force : This option will override the parameter file if one already exists.}';

    public function handle()
    {
        $input = $this->getCommandInput();

        $resource = Resource::fromFile($input->resourceFile, $input->languageFileName ?: 'lcg');

        $this->printInfo('Scaffolding OpenAPI parameter for ' . $this->modelNamePlainEnglish($input->modelName) . '...');
        $this->createParameter($resource, $input);
        $this->info('Done!');
    }

    /**
     * @param \Renepardon\CodeGenerator\Models\Resource                   $resource
     * @param \Renepardon\LaravelCodeGeneratorSwagger\Models\OpenApiInput $input
     *
     * @return bool|null
     */
    protected function createParameter(Resource $resource, OpenApiInput $input)
    {
        $destinationFile = $this->getDestinationFile($input->modelName, $input->modelDirectory);

        if ($this->hasErrors($resource, $destinationFile)) {
            return false;
        }

        $stub = $this->getStubContent(static::TEMPLATE);

        return $this->replaceClassName($stub, $input->modelName)
            ->replaceParameterList($stub, $resource->fields)
            ->createFile($destinationFile, $stub)
            ->info('An OpenApi parameter class was crafted successfully.');
    }

    /**
     * @param string $stub
     * @param array  $fields
     *
     * @return $this
     */
    protected function replaceParameterList(string &$stub, array $fields)
    {
        $parameters = [];
        $parameters[] = 'return [';

        /** @var \Renepardon\CodeGenerator\Models\Field $field */
        foreach ($fields as $field) {
            if ($field->name === 'id') {
                continue;
            }

            $required = $field->isRequired() ? 'true' : 'false';
            $dataType = $this->mapDataType($field->dataType);

            $parameters[] = <<<EOF
            Parameter::query()
                ->name('{$field->name}')
                ->description('{$field->name}')
                ->required({$required})
                ->schema(Schema::{$dataType}()),
EOF;
        }

        $parameters[] = '];';

        $this->replaceTemplate('parameter_list', join(PHP_EOL, $parameters), $stub);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function replaceClassName(&$stub, $modelName)
    {
        $replacement = $modelName . 'Parameters';

        return $this->replaceTemplate('openapi_parameter_class', $replacement, $stub);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDestinationFile(string $name, string $path = null)
    {
        if (! empty($path)) {
            $path = Helpers::getPathWithSlash($path);
        }

        return app_path(Config::getParametersPath($path . $name));
    }
}
