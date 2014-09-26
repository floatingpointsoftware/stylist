<?php

namespace FloatingPoint\Stylist\Theme;

class ThemeJson
{
    /**
     * Stores the theme object.
     *
     * @var Theme
     */
    private $themePath;

    /**
     * Stores the json-decoded file as an array.
     *
     * @var array
     */
    private $json = [];

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
        if (!$this->json) {
            $json = File::get($this->themePath.'/theme.json');

            $this->json = json_decode($json);
        }

        return $this->json;
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

        if (isset($json[$attribute])) {
            return $json[$attribute];
        }

        return null;
    }
} 