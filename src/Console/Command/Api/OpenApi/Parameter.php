<?php

namespace Renepardon\LaravelCodeGeneratorSwagger\Console\Command\Api\OpenApi;

use Illuminate\Console\Command;

class Parameter extends Command
{
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
    protected $signature = 'create:openapi-parameter';

    /**
     * Reset database configuration.
     */
    public function handle()
    {
        $this->info('create:openapi-parameter - not yet implemented');
    }
}
