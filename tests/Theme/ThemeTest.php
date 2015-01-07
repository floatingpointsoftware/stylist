<?php
namespace Tests\Theme;

use FloatingPoint\Stylist\Theme\Theme;

class ThemeTest extends \Tests\TestCase
{
	public function init()
    {
        $this->theme = new Theme('name', 'description', 'path', 'parent');
    }

    public function testGetters()
    {
        $this->assertEquals('name', $this->theme->getName());
        $this->assertEquals('description', $this->theme->getDescription());
        $this->assertEquals('path', $this->theme->getPath());
        $this->assertEquals('parent', $this->theme->getParent());
    }

    public function testParentCheck()
    {
        $this->assertTrue($this->theme->hasParent());
    }
}
