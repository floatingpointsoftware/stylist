<?php
namespace FloatingPoint\Stylist\Html;

use FloatingPoint\Stylist\Facades\Stylist;

class HtmlBuilder extends \Illuminate\Html\HtmlBuilder
{
    /**
     * Generate a link to a JavaScript file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     * @return string
     */
    public function script($url, $attributes = array(), $secure = null)
    {
        return parent::script($this->assetUrl($url), $attributes, $secure);
    }

    /**
     * Generate a link to a CSS file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     * @return string
     */
    public function style($url, $attributes = array(), $secure = null)
    {
        return parent::style($this->assetUrl($url), $attributes, $secure);
    }

    /**
     * Generate an HTML image element.
     *
     * @param  string  $url
     * @param  string  $alt
     * @param  array   $attributes
     * @param  bool    $secure
     * @return string
     */
    public function image($url, $alt = null, $attributes = array(), $secure = null)
    {
        return parent::image($this->assetUrl($url), $alt, $attributes, $secure);
    }

    /**
     * Generate a HTML link to an asset.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     * @param  bool    $secure
     * @return string
     */
    public function linkAsset($url, $title = null, $attributes = array(), $secure = null)
    {
        return parent::linkAsset($this->assetUrl($url), $title, $attributes, $secure);
    }

    /**
     * Do a few checks to get the theme path for a given asset url.
     *
     * @param string $url
     * @return string
     */
    protected function assetUrl($url)
    {
        if ($this->url->isValidUrl($url)) {
            return $url;
        }

        $theme = Stylist::current();

        if ($theme) {
            $themePath = $theme->getAssetPath();

            $url = "themes/$themePath/$url";
        }

        return $url;
    }
}
