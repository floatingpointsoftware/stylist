<?php

namespace FloatingPoint\Stylist\Theme;

class ThemeNotFoundException extends \Exception
{
	public function __construct($themeName)
    {
        $this->message = "Theme [$themeName] is not registered with Stylist.";
    }
}