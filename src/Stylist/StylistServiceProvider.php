<?php
namespace FloatingPoint\Stylist;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class StylistServiceProvider extends ServiceProvider
{
    /**
     * Registers the various bindings required by other packages.
     */
    public function register()
    {
        $this->registerAlias();
        $this->registerStylist();
    }

    /**
     * If certain configuration values are available and valid, Stylist will initially
     * try to discover and activate the required theme.
     */
    public function boot()
    {
        $paths = \Config::get('stylist::paths', []);

        foreach ($paths as $path) {
            \Stylist::discover($path);
        }

        $desiredTheme = Config::get('stylist::activate', null);

        if (!is_null($desiredTheme)) {
            $theme = \Stylist::get($desiredTheme);

            \Stylist::activate($theme);
        }
    }

    /**
     * Sets up the object that will be used for theme registration calls.
     */
    private function registerStylist()
    {
        $this->app->singleton('stylist', 'FloatingPoint\Stylist\Theme\Stylist');
    }

    /**
     * Stylist class should be accessible from global scope for ease of use.
     */
    private function registerAlias()
    {
        AliasLoader::getInstance()->alias('Stylist', 'FloatingPoint\Stylist\Facades\Stylist');
    }

    /**
     * An array of classes that Stylist provides.
     *
     * @return array
     */
    public function provides()
    {
        return ['Stylist'];
    }
}
