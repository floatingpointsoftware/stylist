<?php
namespace Tests\Html;

use FloatingPoint\Stylist\Facades\StylistFacade;
use FloatingPoint\Stylist\Html\ThemeHtmlBuilder;
use Tests\TestCase;

class ThemeHtmlBuilderTest extends TestCase
{
    private $builder;

    public function init()
    {
        $this->builder = new ThemeHtmlBuilder($this->app['html'], $this->app['url']);;

        StylistFacade::registerPath(__DIR__.'/../Stubs/Themes/Parent');
        StylistFacade::activate('Parent theme');
    }

    public function testScriptUrlCreation()
    {
        $script = $this->builder->script('script.js');

        $this->assertContains('/themes/parent-theme/script.js', (string) $script);
    }

    public function testStyleUrlCreation()
    {
        $style = $this->builder->script('css/app.css');

        $this->assertContains('/themes/parent-theme/css/app.css', (string) $style);
    }

    public function testImageUrlCreation()
    {
        $image = $this->builder->image('images/my-image.png');

        $this->assertContains('/themes/parent-theme/images/my-image.png', (string) $image);
    }

    public function testHtmlLinkAssetCreation()
    {
        $flashLink = $this->builder->linkAsset('swf/video.swf');

        $this->assertContains('/themes/parent-theme/swf/video.swf', (string) $flashLink);
    }

    public function testAssetUrlResponse()
    {
        $this->assertEquals(url('themes/parent-theme/'), $this->builder->url());
        $this->assertEquals(url('themes/parent-theme/favicon.ico'), $this->builder->url('favicon.ico'));
    }
    
    public function testSubfolderAssets()
    {
        // set subfolder
        config(['app.url' => 'http://localhost/subfolder']);
        config(['app.asset_url' => 'http://localhost/subfolder']);
        $this->app['url']->forceRootUrl('/subfolder');

        // check script in subfolder
        $script = $this->builder->script('js/app.js');
        $this->assertContains('/subfolder/themes/parent-theme/js/app.js', (string) $script);

        // check style in subfolder
        $style = $this->builder->style('css/app.css');
        $this->assertContains('/subfolder/themes/parent-theme/css/app.css', (string) $style);

        // check asset url in subfolder
        $url = $this->builder->url('css/app.css');
        $this->assertContains('/subfolder/themes/parent-theme/css/app.css', (string) $url);

        // check link in subfolder
        $link = $this->builder->linkAsset('css/app.css');
        $this->assertContains('/subfolder/themes/parent-theme/css/app.css', (string) $link);
    }
}
