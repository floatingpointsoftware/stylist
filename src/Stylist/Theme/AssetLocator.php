<?php
namespace FloatingPoint\Stylist\Theme;

class AssetLocator
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct($url, UrlGenerator $urlGenerator)
    {
        $this->url = $url;
        $this->urlGenerator = $urlGenerator;
    }


}
