<?php

namespace Renepardon\LaravelCodeGeneratorSwagger\Tests;

use Orchestra\Testbench\TestCase;
use Renepardon\LaravelCodeGeneratorSwagger\LaravelCodeGeneratorSwaggerServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelCodeGeneratorSwaggerServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
