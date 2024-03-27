<?php

namespace Pieldefoca\Lux\Tests;

use Pieldefoca\Lux\LuxServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LuxServiceProvider::class,
        ];
    }
}