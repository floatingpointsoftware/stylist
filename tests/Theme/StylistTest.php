<?php

namespace Tests\Theme;

use FloatingPoint\Stylist\Theme\Loader;
use FloatingPoint\Stylist\Theme\Stylist;
use FloatingPoint\Stylist\Theme\Theme;
use Tests\TestCase;

class StylistTest extends TestCase
{
    private $stylist;

	public function setUp()
    {
        parent::setUp();

        $this->stylist = new Stylist(new Loader);
    }

    public function testThemeRegistration()
    {
        $theme = new Theme('n', 'd', 'path', 'p');

        $this->stylist->register($theme, true);

        $this->assertEquals($this->stylist->get('n'), $theme);
        $this->assertEquals($this->stylist->current(), $theme);
    }

    public function testThemeDiscovery()
    {
        $themes = $this->stylist->discover(__DIR__.'/../Stubs');

        $this->assertCount(2, $themes);
    }

    public function testCacheManagement()
    {
        $theme = new Theme('name', 'desc', 'path');

        $this->stylist->cache([$theme]);

        $stylist = new Stylist(new Loader);
        $stylist->setupFromCache();

        $this->assertEquals($theme, $stylist->get('name'));
    }

    /**
     * @expectedException FloatingPoint\Stylist\Theme\Exceptions\ThemeNotFoundException
     */
    public function testInvalidTheme()
    {
        $this->stylist->get('invalidtheme');
    }
}
