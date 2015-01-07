<?php

namespace FloatingPoint\Stylist\Theme;

use File;
use FloatingPoint\Stylist\Theme\Exceptions\ThemeJsonNotFoundException;

class Json
{
    /**
     * Stores the theme object.
     *
     * @var Theme
     */
    private $themePath;

    /**
     * Stores the json-decoded file as an array.
     */
    private $json;

    /**
     * @param Theme $theme
     */
    public function __construct($themePath)
    {
        $this->themePath = $themePath;
    }

    /**
     * Retrieves the JSON-decoded json file.
     *
     * @return array
     */
    public function getJson()
    {
        if ($this->json) {
            return $this->json;
        }

        $themeJsonPath = $this->themePath.'/theme.json';

        if (!File::exists($themeJsonPath)) {
            throw new ThemeJsonNotFoundException($this->themePath);
        }

        return $this->json = json_decode(File::get($themeJsonPath));
    }

    /**
     * Returns the value for a specific json attribute.
     *
     * @param string $attribute
     * @return mixed
     */
    public function getJsonAttribute($attribute)
    {
        $json = $this->getJson();

        if (isset($json->$attribute)) {
            return $json->$attribute;
        }

        return null;
    }
}
