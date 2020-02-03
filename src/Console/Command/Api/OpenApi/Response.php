<?php

namespace Renepardon\LaravelCodeGeneratorSwagger\Console\Command\Api\OpenApi;

use Illuminate\Console\Command;

class Response extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'create:openapi-response';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an OpenAPI response class';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:openapi-response';

    /**
     * Reset database configuration.
     */
    public function handle()
    {
        $this->info('create:openapi-response - not yet implemented');
    }
}
