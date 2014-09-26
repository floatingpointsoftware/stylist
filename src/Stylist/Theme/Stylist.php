<?php

namespace FloatingPoint\Stylist\Theme;

class Stylist
{
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
     * Register a new theme based on its name, and location (path). An optional
     * parameter allows the theme to be activated as soon as its registered.
     *
     * @param string $path
     * @param bool $activate
     */
    public function register($path, $activate = false)
    {
        $registeredTheme = new Theme($path);

        $this->themes[] = $registeredTheme;

        if ($activate) {
            $this->activate($registeredTheme);
        }
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
    }

    /**
     * Retrieves a theme based on its name. If no theme is found it'll throw a ThemeNotFoundException.
     *
     * @param string $themeName
     * @return Theme
     * @throws ThemeNotFoundException
     */
    public function getByThemeName($themeName)
    {
        foreach ($this->themes as $theme) {
            if ($theme->getName() == $themeName) {
                return $theme;
            }
        }

        throw new ThemeNotFoundException($themeName);
    }

    /**
     * Searches for theme.json files within the directory structure specified by $directory and
     * returns the theme locations found.
     *
     * @param $directory
     * @return array
     */
    public function discover($directory)
    {
        $themes = [];

        $files = scandir($directory);

        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->discover($directory);
            }

            if ($file == 'theme.json') {
                $themes[] = $directory;
            }
        }

        return array_flatten($themes);
    }
}
