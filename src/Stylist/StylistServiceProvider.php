<?php
namespace FloatingPoint\Stylist;

use Cache;
use Config;
use FloatingPoint\Stylist\Console\PublishAssetsCommand;
use FloatingPoint\Stylist\Html\ThemeHtmlBuilder;
use Illuminate\Html\HtmlServiceProvider;
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
        HtmlServiceProvider::class
    ];

    /**
     * Registers the various bindings required by other packages.
     */
    public function register()
    {
        parent::register();

        $this->registerAliases();
        $this->registerStylist();
        $this->registerThemeBuilder();
        $this->registerCommands();
    }

    /**
     * Sets up the object that will be used for theme registration calls.
     */
    private function registerStylist()
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
     * Register the comamnds available to the package.
     */
    private function registerCommands()
    {
        $this->commands(
            PublishAssetsCommand::class
        );
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
