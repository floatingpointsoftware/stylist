<?php

namespace Tests;

use Mockery as m;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    protected function getPackageProviders()
    {
        return [
            'FloatingPoint\Stylist\StylistServiceProvider'
        ];
    }
}
