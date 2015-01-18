<?php
namespace Tests\Console;

use FloatingPoint\Stylist\Console\PublishAssetsCommand;
use FloatingPoint\Stylist\Facades\Stylist;
use Tests\TestCase;

class PublishAssetsCommandTest extends TestCase
{
    private $command;

    public function init()
    {
        // Clear the public path for each run - this will touch the orchestral public
        // path fixture - not laravel's public folder.
        $this->app['files']->cleanDirectory(public_path());

        $this->command = $this->app->make(PublishAssetsCommand::class);
    }

    public function testAssetPublishing()
    {
        // Setup our lisener that will discover our available themes and return the paths
        $this->app['events']->listen('stylist.publishing', function() {
            return Stylist::discover(__DIR__.'/../Stubs/Themes');
        });

        // Action
        $this->command->handle();

        // Assert
        $this->assertTrue($this->app['files']->exists(public_path('themes/child-theme')));
        $this->assertFalse($this->app['files']->exists(public_path('themes/parent-theme')));
    }
}
