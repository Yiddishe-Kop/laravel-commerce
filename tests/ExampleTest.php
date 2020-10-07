<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use Orchestra\Testbench\TestCase;
use YiddisheKop\LaravelCommerce\LaravelCommerceServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelCommerceServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
