# Stylist
## About

[![Build Status](https://travis-ci.org/floatingpointsoftware/stylist.svg?branch=master)](https://travis-ci.org/floatingpointsoftware/stylist)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/floatingpointsoftware/stylist/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/floatingpointsoftware/stylist/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/floatingpointsoftware/stylist/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/floatingpointsoftware/stylist/?branch=master)

Stylist is a Laravel 5.0+ compatible package for theming your Laravel applications.

## Installation

Via the usual composer command:

    composer require floatingpoint/stylist dev-master

Then, make sure the Stylist service provider is made available to your application by updating your config/app.php:

    'FloatingPoint\Stylist\StylistServiceProvider',

You're now ready to go!

## Setting up a theme

In order for Stylist to start using themes, you must register at least one theme with the package, and activate it.

    Stylist::registerPath('/absolute/path/to/theme', true);

Your theme should contain a theme.json file, which contains some basic information:

    {
        "name": "My theme",
        "description": "This is my theme. There are many like it, but this one is mine."
    }

Only one theme can be activated at a time, so multiple calls to activate themes, will simply deactivate the previously activated theme.

So, what happens when you now load views?

## How Stylist works

Everytime you register a new theme and activate it, Stylist then becomes aware of a new location to search for views, stylesheets, javascripts and image files. Stylist has a few opinions of how to structure your theme directories as well. The reason for this is so that every theme follows the same approach. For example, when you point Stylist to your theme directory, it should have the following directories (if it needs them):

    /public/stylesheets
    /public/javascripts
    /public/images
    /views/

Then, when you make calls like the following:

    return View::make('layout.application');

It'll look in your theme's directory: /views/layout/ for application.blade.php. Simple huh?

When dealing with assets, you can use Stylist's own Asset management library:

    {{ Asset::image('path/to/image.png') }}

This will look for the image in your theme's directory first and foremost: /public/images/path/to/image.png

This same approach is applied to your styles, js and any other static assets.

In order for this approach to work, Stylist registers specific routes and manages those requests. It registers the following routes:

    /assets/*

This means that when you make a call to say, Asset::image, the output url in your HTML will actually look like the following:

    /assets/images/path/to/image.png

If you do actually want custom images, stylesheets.etc - the Asset manager that ships with Stylist can handle that as well, via magic method calls. Let's say for example that you REALLY want your stylesheets to be loaded via /css/ instead (we don't encourage this, but you can do it), then do the following:

    Asset::css('stylesheet.css')

This will then produce the following url:

    /assets/css/stylesheet.css

and be loaded from your theme as you'd expect (inside /public/css).

## Theme inheritance

Themes can have parent themes. What does this mean? It means that you can request a view, and Stylist will first look to the child theme that you have activated, and work its way up a tree. This is really great if you like a particular theme but want to customise just a single view file.

### Defining a parent

It's very easy to define a parent for a theme. You simply define the parent theme inside your theme.json:

    "parent": "Another theme"

This will ensure that Stylist will first look in your theme's directories for files and assets, and then look in the parent's theme directories. If your theme's parent also has a parent, then it will continue looking up the tree until it finds the file.

Parents do not need to be activated for your theme to make use of them. Only your theme needs to be activated. However, they do need to be registered. This may be handled by the package that manages your theme, or you can register it yourself.
