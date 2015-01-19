<?php
namespace FloatingPoint\Stylist\Console;

use FloatingPoint\Stylist\Facades\Stylist;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\Container;

class PublishAssetsCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'stylist:publish';

    /**
     * @var string
     */
    protected $description = 'Publish assets associated with Stylist themes.';

    /**
     * @var
     */
    private $app;

    /**
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        parent::__construct();
        $this->app = $app;
    }

    /**
     * Fire the command, running through the following steps:
     *
     *   1. Install the migrations table
     *   3. Migrate the shift package
     *   4. Publish any and all assets
     *   5. Rebuild the services.json file
     */
    public function handle()
    {
        $this->setupThemes();
        $this->copyAssets();

        $this->info('Assets published.');
    }

    /**
     * Fires the publishing event, then works through the array of returned paths and registers
     * themes for those which are valid (aka, contain a theme.json file).
     */
    protected function setupThemes()
    {
        $eventPaths = $this->app['events']->fire('stylist.publishing');
        $themePaths = array_flatten($eventPaths);

        foreach ($themePaths as $path) {
            if ($this->app['files']->exists($path.'assets/')) {
                Stylist::registerPath($path);
            }
        }
    }

    /**
     * Copies the assets for those themes which were successfully registered with stylist.
     */
    protected function copyAssets()
    {
        $themes = Stylist::themes();

        foreach ($themes as $theme) {
            $themePath = public_path('themes/' . $theme->getAssetPath());

            $this->app['files']->copyDirectory($theme->getPath().'/assets/', $themePath);
        }
    }
}
