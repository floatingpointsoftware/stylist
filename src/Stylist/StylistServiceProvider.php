<?php
namespace FloatingPoint\Stylist;

use Cache;
use Config;
use FloatingPoint\Stylist\Html\ThemeHtmlBuilder;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Foundation\AliasLoader;

class StylistServiceProvider extends AggregateServiceProvider
{
    /**
     * Only boot when we need to.
     *
     * @var bool
     */
    public $defer = true;

    /**
     * Stylist provides the HtmlServiceProvider for ease-of-use.
     *
     * @var array
     */
    protected $providers = [
        'Illuminate\Html\HtmlServiceProvider'
    ];

    /**
     * Registers the various bindings required by other packages.
     */
    public function register()
    {
        parent::register();

        $this->registerConfiguration();
        $this->registerAliases();
        $this->registerStylist();
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
        $paths = $this->app['config']->get('stylist.paths', []);

        foreach ($paths as $path) {
            $themePaths = Stylist::discover($path);
            Stylist::registerPaths($themePaths);
        }

        $theme = $this->app['config']->get('stylist.activate', null);

        if (!is_null($theme)) {
            Stylist::activate($theme);
        }
    }

    /**
     * Sets up the object that will be used for theme registration calls.
     */
    protected function registerStylist()
    {
        $this->app->singleton('stylist', 'FloatingPoint\Stylist\Theme\Stylist');
    }

    /**
     * Create the binding necessary for the theme html builder.
     */
    protected function registerThemeBuilder()
    {
        $this->app->bindShared('stylist.theme', function($app)
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
        return [
            'Stylist',
            'Theme'
        ];
    }

}
