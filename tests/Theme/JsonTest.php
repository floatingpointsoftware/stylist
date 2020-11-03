<?php
namespace Tests\Theme;

use FloatingPoint\Stylist\Theme\Json;
use FloatingPoint\Stylist\Theme\Exceptions\ThemeJsonNotFoundException;

class JsonTest extends \Tests\TestCase
{
    private $themeJson;

    public function init()
    {
        $this->themeJson = new Json(__DIR__.'/../Stubs/Themes/Parent');
    }

	public function testJsonRetrievalForExistingTheme()
    {
        $json = $this->themeJson->getJson();

        $this->assertEquals('Parent theme', $json->name);
    }

    public function testJsonAttributeRetrieval()
    {
        $this->themeJson->getJson();

        $this->assertEquals('Parent theme', $this->themeJson->getJsonAttribute('name'));
        $this->assertEquals('This is a parent theme.', $this->themeJson->getJsonAttribute('description'));
    }

    /**
     * @expectException FloatingPoint\Stylist\Theme\Exceptions\ThemeJsonNotFoundException
     */
    public function testThemeFileMissing()
    {
        $this->expectException(ThemeJsonNotFoundException::class);

        $json = new Json('path/that/doesnt/exist');

        $json->getJson();
    }
}
