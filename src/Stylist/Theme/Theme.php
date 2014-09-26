<?php

namespace FloatingPoint\Stylist\Theme;

class Theme
{
    private $name;
    private $path;

    public function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    /**
     * Return the name of the theme.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
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
}
