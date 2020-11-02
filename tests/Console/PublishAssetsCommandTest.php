<?php
namespace Tests\Console;

use Illuminate\Support\Facades\File;
use Stylist;
use Tests\TestCase;

class PublishAssetsCommandTest extends TestCase
{
    public function testAssetPublishing()
    {
        Stylist::registerPaths(Stylist::discover(__DIR__.'/../Stubs/Themes'));

        $artisan = $this->app->make('Illuminate\Contracts\Console\Kernel');

        // Action
        $artisan->call('stylist:publish');

        // Assert
       $this->assertTrue($this->app['files']->isDirectory(public_path('themes/child-theme')));
       $this->assertTrue($this->app['files']->isDirectory(public_path('themes/overloader')));
       $this->assertTrue($this->app['files']->isDirectory(public_path('themes/parent-theme')));

    }
}
