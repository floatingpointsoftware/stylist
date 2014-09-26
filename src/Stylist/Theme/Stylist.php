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
     * @param string $theme
     * @param string $path
     * @param bool $active
     */
    public function register($theme, $path, $active = false)
    {
        $this->themes[] = new Theme($theme, $path);

        if ($active) {
            $this->activate($theme);
        }
    }

    /**
     * Activate a theme by its name.
     *
     * @param $themeName
     * @throws ThemeNotFoundException
     */
    public function activate($themeName)
    {
        $this->activeTheme = $this->getByThemeName($themeName);
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
}
