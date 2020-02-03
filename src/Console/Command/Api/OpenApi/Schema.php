<?php

namespace Renepardon\LaravelCodeGeneratorSwagger\Console\Command\Api\OpenApi;

use Illuminate\Console\Command;

class Schema extends Command
{
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
    protected $signature = 'create:openapi-schema';

    /**
     * Reset database configuration.
     */
    public function handle()
    {
        $this->info('create:openapi-schema - not yet implemented');
    }
}
