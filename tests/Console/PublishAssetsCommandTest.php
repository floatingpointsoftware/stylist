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

        File::shouldReceive('exists')->andReturn(true)->times(8);
        File::shouldReceive('get')->times(6);
        File::shouldReceive('copyDirectory')->times(3);

        // Action
        $artisan->call('stylist:publish');

        // Assert
//        $this->assertTrue($this->app['files']->exists(public_path('themes/child-theme')));
//        $this->assertFalse($this->app['files']->exists(public_path('themes/parent-theme')));
    }
}
