<?php
namespace FloatingPoint\Stylist;

use Cache;
use Config;
use FloatingPoint\Stylist\Html\ThemeHtmlBuilder;
use FloatingPoint\Stylist\Theme\Loader;
use FloatingPoint\Stylist\Theme\Stylist;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Foundation\AliasLoader;

class StylistServiceProvider extends AggregateServiceProvider
{
    /**
     * Stylist provides the HtmlServiceProvider for ease-of-use.
     *
     * @var array
     */
    protected $providers = [
        'Collective\Html\HtmlServiceProvider'
    ];

    /**
     * Registers the various bindings required by other packages.
     */
    public function register()
    {
        parent::register();

        $this->registerConfiguration();
        $this->registerStylist();
        $this->registerAliases();
        $this->registerThemeBuilder();
        $this->registerCommands();
    }

    /**
     * Boot the package, in this case also discovering any themes required by stylist.
     */
    public function boot()
    {
        $this->bootThemes();
    }

    /**
     * Once the provided has booted, we can now look at configuration and see if there's
     * any paths defined to automatically load and register the required themes.
     */
    protected function bootThemes()
    {
        $stylist = $this->app['stylist'];
        $paths = $this->app['config']->get('stylist.themes.paths', []);

        foreach ($paths as $path) {
            $themePaths = $stylist->discover($path);
            $stylist->registerPaths($themePaths);
        }

        $theme = $this->app['config']->get('stylist.themes.activate', null);

        if (!is_null($theme)) {
            $stylist->activate($theme, true);
        }
    }

    /**
     * Sets up the object that will be used for theme registration calls.
     */
    protected function registerStylist()
    {
        $this->app->singleton('stylist', function($app)
        {
            return new Stylist(new Loader, $app);
        });
    }

    /**
     * Create the binding necessary for the theme html builder.
     */
    protected function registerThemeBuilder()
    {
        $this->app->singleton('stylist.theme', function($app)
        {
            return new ThemeHtmlBuilder($app['html'], $app['url']);
        });
    }

    /**
     * Stylist class should be accessible from global scope for ease of use.
     */
    private function registerAliases()
    {
        $aliasLoader = AliasLoader::getInstance();

        $aliasLoader->alias('Stylist', 'FloatingPoint\Stylist\Facades\StylistFacade');
        $aliasLoader->alias('Theme', 'FloatingPoint\Stylist\Facades\ThemeFacade');

        $this->app->alias('stylist', 'FloatingPoint\Stylist\Theme\Stylist');
    }

    /**
     * Register the commands available to the package.
     */
    private function registerCommands()
    {
        $this->commands(
            'FloatingPoint\Stylist\Console\PublishAssetsCommand'
        );
    }

    /**
     * Setup the configuration that can be used by stylist.
     */
    protected function registerConfiguration()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('stylist.php')
        ]);
    }

    /**
     * An array of classes that Stylist provides.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge(parent::provides(), [
            'Stylist',
            'Theme'
        ]);
    }

}
