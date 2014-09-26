<?php

namespace FloatingPoint\Stylist\Theme;

use File;

class Theme
{
    /**
     * Stores the path to the theme location.
     *
     * @var
     */
    private $path;

    /**
     * Theme just needs to know one thing - where the theme is found. It'll do the rest.
     *
     * @param string $path Absolute path on the filesystem to the theme.
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Return the path to the theme.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Determines whether or not a theme has a parent.
     *
     * @return bool
     */
    public function hasParent()
    {
        return !!$this->getParent();
    }

    /**
     * Returns the JSON for a theme, or if the attribute parameter is provided, returns the value
     * for that specific attribute within the theme.json file.
     *
     * @param string $attribute
     * @return array|mixed
     */
    public function getJson($attribute = null)
    {
        static $themeJson = null;

        if (is_null($themeJson)) {
            $themeJson = new ThemeJson($this->getPath());
        }
        
        if (!is_null($attribute)) {
            return $themeJson->getJsonAttribute($attribute);
        }

        return $themeJson->getJson();
    }

    /**
     * A lovely magic method for handling specific JSON attribute calls.
     *
     * @param $params
     * @throws \Exception
     */
    public function __call($method, $arguments = [])
    {
        if (substr($method, 0, 3) == 'get') {
            $attribute = strtolower($arguments[0]);

            return $this->getJson($attribute);
        }

        throw new \Exception('Tried to call unknown method '.get_class($this).'::'.$method);
    }
}
