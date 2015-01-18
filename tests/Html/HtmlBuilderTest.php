<?php
namespace Tests\Html;

use FloatingPoint\Stylist\Facades\Stylist;
use Tests\TestCase;

class HtmlBuilderTest extends TestCase
{
    private $builder;

    public function init()
    {
        $this->builder = $this->app->make('html');

        Stylist::registerPath(__DIR__.'/../Stubs/Themes/Parent');
        Stylist::activate('Parent theme');
    }

    public function testScriptUrlCreation()
    {
        $script = $this->builder->script('script.js');

        $this->assertContains('/themes/parent-theme/script.js', $script);
    }

    public function testStyleUrlCreation()
    {
        $style = $this->builder->script('css/app.css');

        $this->assertContains('/themes/parent-theme/css/app.css', $style);
    }

    public function testImageUrlCreation()
    {
        $image = $this->builder->image('images/my-image.png');

        $this->assertContains('/themes/parent-theme/images/my-image.png', $image);
    }

    public function testHtmlLinkAssetCreation()
    {
        $flashLink = $this->builder->linkAsset('swf/video.swf');

        $this->assertContains('/themes/parent-theme/swf/video.swf', $flashLink);
    }
}
