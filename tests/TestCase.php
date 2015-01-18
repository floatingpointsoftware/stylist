<?php
namespace Tests;

use Mockery as m;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function setUp()
    {
        parent::setUp();

        $this->init();
    }

    protected function init()
    {
        // Stub/template method - overloadable by children
    }

    protected function getPackageProviders($app)
    {
        return [
            'FloatingPoint\Stylist\StylistServiceProvider',
            'FloatingPoint\Stylist\Html\HtmlServiceProvider',
        ];
    }
}
