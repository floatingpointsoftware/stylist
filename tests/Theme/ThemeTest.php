<?php

namespace Tests\Theme;

use FloatingPoint\Stylist\Theme\Theme;
use Tests\TestCase;

class ThemeTest extends TestCase
{
	public function setUp()
    {
        parent::setUp();

        $this->theme = new Theme(__DIR__.'/../Stubs/Themes/Parent');
    }

    public function testGetPath()
    {
        $this->assertSame(__DIR__.'/../Stubs/Themes/Parent', $this->theme->getPath());
    }

    public function testHasParentAsParent()
    {
        $this->assertFalse($this->theme->hasParent());
    }

    public function testHasParentAsChild()
    {
        $theme = new Theme(__DIR__.'/../Stubs/Themes/Child');

        $this->assertTrue($theme->hasParent());
    }

    /**
     * @expectedException \Exception
     */
    public function testCallingAnInvalidJMethod()
    {
        $this->theme->gesInvalid();
    }
}
