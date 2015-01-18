<?php
namespace FloatingPoint\Stylist\Theme;

/**
 * Class UrlGenerator
 *
 * The sole purpose of this class is to ensure that any asset requests go via the appropriate
 * theme directory, rather than to the usual css/js.etc. locations.
 *
 * @package FloatingPoint\Stylist\Theme
 */
class UrlGenerator extends \Illuminate\Routing\UrlGenerator
{
    /**
     * Generate a URL to an application asset.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    public function asset($path, $secure = null)
    {
        if ($this->isValidUrl($path)) return $path;

        // Once we get the root URL, we will check to see if it contains an index.php
        // file in the paths. If it does, we will remove it since it is not needed
        // for asset paths, but only for routes to endpoints in the application.
        $root = $this->getRootUrl($this->getScheme($secure));

        $theme = Stylist::current();

        return $this->removeIndex($root).'/themes/'.$theme->getPath().'/'.trim($path, '/');
    }
}
