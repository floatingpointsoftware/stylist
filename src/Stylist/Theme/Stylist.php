<?php

namespace FloatingPoint\Stylist\Theme;

use Cache;
use FloatingPoint\Stylist\Theme\Exceptions\ThemeNotFoundException;
use Log;

/**
 * Class Stylist
 *
 * Manages a repository of themes that are registered. Can be used to activate specific themes,
 * search for a given theme, register new ones or even search for themes within your application
 * directory.
 *
 * @package FloatingPoint\Stylist\Theme
 */
class Stylist
{
    /**
     * The cache key is used for setting and retrieving the stylist theme cache.
     *
     * @var string
     */
    private $cacheKey = 'stylist.themes';

    /**
     * An array of registered themes.
     *
     * @var array Theme
     */
    protected $themes = [];

    /**
     * The currently activated theme.
     *
     * @var Theme
     */
    protected $activeTheme;

    /**
     * Manages the loading of themes via various mechanisms.
     *
     * @var Loader
     */
    private $themeLoader;

    /**
     * @param Loader $themeLoader
     */
    public function __construct(Loader $themeLoader)
    {
        $this->themeLoader = $themeLoader;
    }

    /**
     * Register a new theme based on its path. An optional
     * parameter allows the theme to be activated as soon as its registered.
     *
     * @param string $path
     * @param bool $activate
     */
    public function register(Theme $theme, $activate = false)
    {
        $this->themes[] = $theme;

        if ($activate) {
            $this->activate($theme);
        }
    }

    /**
     * Register a theme with Stylist based on its path.
     *
     * @param string $path
     * @param boolean $activate
     */
    public function registerPath($path, $activate = false)
    {
        $theme = $this->themeLoader->fromPath($path);

        $this->register($theme, $activate);
    }

    /**
     * Activate a theme by its name.
     *
     * @param Theme $theme
     * @throws ThemeNotFoundException
     */
    public function activate(Theme $theme)
    {
        $this->activeTheme = $theme;

        Log::info("Using theme [{$theme->getName()}]");
    }

    /**
     * Returns the currently active theme.
     *
     * @return Theme
     */
    public function current()
    {
        return $this->activeTheme;
    }

    /**
     * Retrieves a theme based on its name. If no theme is found it'll throw a ThemeNotFoundException.
     *
     * @param string $themeName
     * @return Theme
     * @throws ThemeNotFoundException
     */
    public function get($themeName)
    {
        foreach ($this->themes as $theme) {
            if ($theme->getName() === $themeName) {
                return $theme;
            }
        }

        throw new ThemeNotFoundException($themeName);
    }

    /**
     * Searches for theme.json files within the directory structure specified by $directory and
     * returns the theme locations found. This method means that themes do not need to be manually
     * registered, however - it is a costly operation, and should be cached once you've found the
     * themes.
     *
     * @param $directory
     * @return array Returns an array of theme directory locations
     */
    public function discover($directory)
    {
        $themeLocations = [];
        $files = scandir($directory);

        foreach ($files as $file) {
            $location = "$directory/$file";

            if (false === in_array($file, ['.', '..']) && is_dir($location)) {
                $themeLocations[] = $this->discover($location);
            }

            if ($file == 'theme.json') {
                $themeLocations[] = $directory;
                break;
            }
        }

        return array_flatten($themeLocations);
    }

    /**
     * Caches the themes provide. This is particularly handy if you use the discover method
     * to search your entire installation for themes. Whenever this method is called, it
     * will wipe the old cache file and re-write the new cache.
     *
     * @param array $themes Must consist of Theme objects
     */
    public function cache(array $themes = [])
    {
        $cacheJson = [];

        foreach ($themes as $theme) {
            $cacheJson[] = $theme->toArray();
        }

        Cache::forever($this->cacheKey, json_encode($cacheJson));
    }

    /**
     * Sets up stylist to use themes from the cache. Stylist uses Laravel's own caching
     * mechanisms, so this could be stored on the disk, in memcache or elsewhere.
     */
    public function setupFromCache()
    {
        if (Cache::has($this->cacheKey)) {
            $this->themes = [];

            $cachedThemes = json_decode(Cache::get($this->cacheKey));

            foreach ($cachedThemes as $cachedTheme) {
                $this->themes[] = $this->themeLoader->fromCache($cachedTheme);
            }
        }
    }
}
