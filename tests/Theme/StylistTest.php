<?php
namespace Tests\Theme;

use FloatingPoint\Stylist\Theme\Loader;
use FloatingPoint\Stylist\Theme\Stylist;
use FloatingPoint\Stylist\Theme\Theme;

class StylistTest extends \Tests\TestCase
{
    public function testThemeRegistration()
    {
        $stylist = new Stylist(new Loader, $this->app);
        $theme = new Theme('n', 'd', 'path');

        $stylist->register($theme, true);

        $this->assertEquals($stylist->get('n'), $theme);
        $this->assertEquals($stylist->current(), $theme);
    }

    public function testThemeDiscovery()
    {
        $stylist = new Stylist(new Loader, $this->app);
        $themes = $stylist->discover(__DIR__.'/../Stubs');

        $this->assertCount(3, $themes);
    }

    public function testCacheManagement()
    {
        $stylist = new Stylist(new Loader, $this->app);
        $theme = new Theme('name', 'desc', 'path');

        $stylist->cache([$theme]);

        // To test cache, we setup a new stylist instance
        $stylist = new Stylist(new Loader, $this->app);
        $stylist->setupFromCache();

        $this->assertEquals($theme, $stylist->get('name'));
    }

    public function testPathRegistration()
    {
        $stylist = new Stylist(new Loader, $this->app);

        $stylist->registerPath(__DIR__.'/../Stubs/Themes/Parent');

        $this->assertEquals('Parent theme', $stylist->get('Parent theme')->getName());
    }

    public function testMultiplePathRegistrations()
    {
        $stylist = new Stylist(new Loader, $this->app);
        $paths = $stylist->discover(__DIR__.'/../Stubs');

        $stylist->registerPaths($paths);
        $stylist->activate($stylist->get('Child theme'));

        $view = $this->app->make('view');

        $this->assertEquals('Parent theme', $stylist->get('Parent theme')->getName());
        $this->assertEquals('Child theme', $stylist->get('Child theme')->getName());
        $this->assertTrue($view->exists('partials.menu')); // should pull this from the child theme
        $this->assertTrue($view->exists('layouts.application')); // should pull this from the parent theme
    }

    /**
     * @expectedException FloatingPoint\Stylist\Theme\Exceptions\ThemeNotFoundException
     */
    public function testInvalidTheme()
    {
        $stylist = new Stylist(new Loader, $this->app);
        $stylist->get('invalidtheme');
    }

    public function testThemeViewIsOverloadable()
    {
        $stylist = new Stylist(new Loader, $this->app);
        $paths = $stylist->discover(__DIR__.'/../Stubs');

        $stylist->registerPaths($paths);
        $stylist->activate($stylist->get('Overloader'));

        $view = $this->app->make('view');

        $this->assertTrue($view->exists('partials.menu'));
        $this->assertSame('Overload', $view->make('partials.menu')->render());
    }
}
