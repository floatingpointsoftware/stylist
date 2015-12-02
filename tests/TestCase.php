<?php
namespace Tests;

use Mockery as m;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function tearDown()
    {
        parent::tearDown();
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
            'Collective\Html\HtmlServiceProvider',
            'FloatingPoint\Stylist\StylistServiceProvider',
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Stylist' => 'FloatingPoint\Stylist\Facades\StylistFacade',
            'Theme' => 'FloatingPoint\Stylist\Facades\ThemeFacade',
        ];
    }

    protected function getApplicationAliases($app)
    {
        $aliases = parent::getApplicationAliases($app);
        
        $aliases['Stylist'] = 'FloatingPoint\Stylist\Facades\StylistFacade';

        return $aliases;
    }
}
