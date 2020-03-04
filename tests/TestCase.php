<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Extracts the resolved class name from a given contracts.
     *
     * @param  string  $abstract
     *
     * @return string
     */
    protected function getResolvedClassName(string $abstract): string
    {
        return get_class($this->app->make($abstract));
    }
}
