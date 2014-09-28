<?php

namespace FloatingPoint\Stylist\Theme;

/**
 * Class Loader
 *
 * The theme loader will instantiate theme objects based on whether or not we're loading the theme
 * from a path, or from a cached value. Either method will return a new Theme object that represents
 * the theme in all its glory.
 *
 * @package FloatingPoint\Stylist\Theme
 */
class Loader
{
    /**
     * Creates a new theme based on the theme's path, fetching the theme.json file
     * and loading the necessary attributes for that theme.
     *
     * @param $path
     * @return Theme
     */
    public function fromPath($path)
    {
        $themeJson = new Json($path);

        return new Theme(
            $themeJson->getJsonAttribute('name'),
            $themeJson->getJsonAttribute('description'),
            $path,
            $themeJson->getJsonAttribute('parent')
        );
    }

    /**
     * Creates a new theme instance based on the cache object provided.
     *
     * @param stdClass $cache
     * @return Theme
     */
    public function fromCache(\stdClass $cache)
    {
        return new Theme(
            $cache->name,
            $cache->description,
            $cache->path,
            $cache->parent
        );
    }
}
