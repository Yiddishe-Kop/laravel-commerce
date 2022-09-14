<?php

namespace YiddisheKop\LaravelCommerce\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use YiddisheKop\LaravelCommerce\CommerceServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations();
    }

    protected function getPackageProviders($app)
    {
        return [CommerceServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__.'/Fixtures/create_products_table.php';
        include_once __DIR__.'/Fixtures/create_packages_table.php';

        (new \CreateProductsTable)->up();
        (new \CreatePackagesTable)->up();
    }
}
