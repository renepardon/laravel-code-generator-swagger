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

class ListResponse extends Command
{
    use ScaffoldTrait, CommonCommand, OpenApi;

    const TEMPLATE = 'openapi-list-response';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'create:openapi-list-response';

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
    protected $signature = 'create:openapi-list-response
                            {model-name : The model name that this resource will represent.}
                            {--resource-file= : The name of the resource-file to import from.}
                            {--template-name= : The template name to use when generating the code.}
                            {--force : This option will override the schema file if one already exists.}';

    public function handle()
    {
        $input = $this->getCommandInput();

        $resource = Resource::fromFile($input->resourceFile, $input->languageFileName ?: 'lcg');

        $this->printInfo('Scaffolding OpenAPI "list" Response for ' . $this->modelNamePlainEnglish($input->modelName) . '...');
        $this->createResponse($resource, $input);
        $this->info('Done!');
    }

    /**
     * @param \Renepardon\CodeGenerator\Models\Resource                   $resource
     * @param \Renepardon\LaravelCodeGeneratorSwagger\Models\OpenApiInput $input
     *
     * @return bool|null
     */
    protected function createResponse(Resource $resource, OpenApiInput $input)
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
        $this->replaceTemplate('model_name', $input->modelName, $stub);

        $responseObject = <<<RESPONSE_OBJECT
        \$response = Schema::object()->properties(
            Schema::array('data')
                ->items((new {$input->modelName}Schema())->build()),
            (new MetaSchema())->build()
        );
RESPONSE_OBJECT;

        $this->replaceTemplate('response_object', $responseObject, $stub);

        $returnObject = <<<RETURN_OBJECT
        return Response::create('List{$input->modelName}Response')
            ->description('Successful response')
            ->content(
                MediaType::json()->schema(\$response)
            );
RETURN_OBJECT;

        $this->replaceTemplate('return_statement', $returnObject, $stub);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function replaceClassName(&$stub, $modelName)
    {
        $replacement = sprintf('List%sResponse', $modelName);

        return $this->replaceTemplate('openapi_list_response_class', $replacement, $stub);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDestinationFile(string $name, string $path = null)
    {
        if (! empty($path)) {
            $path = Helpers::getPathWithSlash($path);
        }

        return app_path(Config::getResponsePath($path . sprintf('List%sResponse.php', $name)));
    }
}
