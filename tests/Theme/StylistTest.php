<?php
namespace Tests\Theme;

use FloatingPoint\Stylist\Theme\Loader;
use FloatingPoint\Stylist\Theme\Stylist;
use FloatingPoint\Stylist\Theme\Theme;

class StylistTest extends \Tests\TestCase
{
    public function testThemeRegistration()
    {
        $stylist = new Stylist(new Loader);
        $theme = new Theme('n', 'd', 'path', 'p');

        $stylist->register($theme, true);

        $this->assertEquals($stylist->get('n'), $theme);
        $this->assertEquals($stylist->current(), $theme);
    }

    public function testThemeDiscovery()
    {
        $stylist = new Stylist(new Loader);
        $themes = $stylist->discover(__DIR__.'/../Stubs');

        $this->assertCount(2, $themes);
    }

    public function testCacheManagement()
    {
        $stylist = new Stylist(new Loader);
        $theme = new Theme('name', 'desc', 'path');

        $stylist->cache([$theme]);

        // To test cache, we setup a new stylist instance
        $stylist = new Stylist(new Loader);
        $stylist->setupFromCache();

        $this->assertEquals($theme, $stylist->get('name'));
    }

    public function testPathRegistration()
    {
        $stylist = new Stylist(new Loader);

        $stylist->registerPath(__DIR__.'/../Stubs/Themes/Parent');

        $this->assertEquals('Parent theme', $stylist->get('Parent theme')->getName());
    }

    public function testMultiplePathRegistrations()
    {
        $stylist = new Stylist(new Loader);
        $paths = $stylist->discover(__DIR__.'/../Stubs');

        $stylist->registerPaths($paths);

        $this->assertEquals('Parent theme', $stylist->get('Parent theme')->getName());
        $this->assertEquals('Child theme', $stylist->get('Child theme')->getName());
    }

    /**
     * @expectedException FloatingPoint\Stylist\Theme\Exceptions\ThemeNotFoundException
     */
    public function testInvalidTheme()
    {
        $stylist = new Stylist(new Loader);
        $stylist->get('invalidtheme');
    }
}
