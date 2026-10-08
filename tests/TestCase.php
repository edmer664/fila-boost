<?php

namespace FilaBoost\FilamentBlueprint\Tests;

use FilaBoost\FilamentBlueprint\FilaBoostServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            FilaBoostServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        // Setup temporary directories or environment defaults if needed
    }
}
