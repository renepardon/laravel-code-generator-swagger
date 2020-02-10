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

class Schema extends Command
{
    use ScaffoldTrait, CommonCommand, OpenApi;

    const TEMPLATE = 'openapi-schema';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'create:openapi-schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an OpenAPI schema class';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:openapi-schema
                            {model-name : The model name that this resource will represent.}
                            {--resource-file= : The name of the resource-file to import from.}
                            {--template-name= : The template name to use when generating the code.}
                            {--force : This option will override the schema file if one already exists.}';

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
            ->replaceSchemaObject($stub, $resource->fields, $input)
            ->createFile($destinationFile, $stub)
            ->info('An OpenApi parameter class was crafted successfully.');
    }

    /**
     * @param string $stub
     * @param array  $fields
     *
     * @return $this
     */
    protected function replaceSchemaObject(string &$stub, array $fields, OpenApiInput $input)
    {
        $objectParts = [sprintf('return Schema::object(\'%s\')->properties(', $input->modelName)];

        /** @var \Renepardon\CodeGenerator\Models\Field $field */
        foreach ($fields as $field) {
            $default = $field->htmlValue ?? null;
            $dataType = $this->mapDataType($field->dataType);

            $schema = sprintf('Schema::%s(\'%s\')', $dataType, $field->name);

            if (preg_match('/(.+)_at$/', $field->name)) {
                $schema .= '->format(Schema::FORMAT_DATE_TIME)';
            }

            if ($default) {
                $format = is_numeric($default) || is_bool($default) ? '->default(%s)' : '->default(\'%s\')';

                if (is_bool($default)) {
                    $default = $default ? 'true' : 'false';
                }

                $schema .= sprintf($format, $default);
            }

            $schema .= ',';
            $objectParts[] = $schema;
        }

        $lastIndex = count($objectParts) - 1;
        $objectParts[$lastIndex] = trim($objectParts[$lastIndex], ',');
        $objectParts[] = ');';

        $this->replaceTemplate('schema_object', join(PHP_EOL, $objectParts), $stub);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function replaceClassName(&$stub, $modelName)
    {
        $replacement = $modelName . 'Schema';

        return $this->replaceTemplate('openapi_schema_class', $replacement, $stub);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDestinationFile(string $name, string $path = null)
    {
        if (! empty($path)) {
            $path = Helpers::getPathWithSlash($path);
        }

        return app_path(Config::getSchemasPath($path . $name));
    }
}
