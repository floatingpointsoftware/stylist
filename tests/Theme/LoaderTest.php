<?php
namespace Tests\Theme;

use FloatingPoint\Stylist\Theme\Loader;

class LoaderTest extends \Tests\TestCase
{
    private $loader;

	public function init()
    {
        $this->loader = new Loader();
    }

    public function testFromPath()
    {
        $theme = $this->loader->fromPath(__DIR__.'/../Stubs/Themes/Parent/');

        $this->assertEquals('Parent theme', $theme->getName());
        $this->assertEquals('This is a parent theme.', $theme->getDescription());
    }

    public function testFromCache()
    {
        $cachedTheme = new \stdClass;
        $cachedTheme->name = 'name';
        $cachedTheme->description = 'description';
        $cachedTheme->path = 'path';
        $cachedTheme->parent = 'parent';

        $theme = $this->loader->fromCache($cachedTheme);

        $this->assertEquals('name', $theme->getName());
        $this->assertEquals('description', $theme->getDescription());
        $this->assertEquals('path', $theme->getPath());
        $this->assertEquals('parent', $theme->getParent());
    }
}
