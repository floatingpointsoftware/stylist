# Stylist
## About

[![Build Status](https://img.shields.io/travis/floatingpointsoftware/stylist.svg?branch=master)](https://travis-ci.org/floatingpointsoftware/stylist)

Stylist is a Laravel 5.0+ compatible package for theming your Laravel applications.

## Installation

Via the usual composer command:

    composer require floatingpoint/stylist

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

Everytime you register a new theme and activate it, Stylist then becomes aware of a new location to search for views, stylesheets, 
javascripts and image files. Stylist has a few opinions of how to structure your theme directories as well. The reason for this is 
so that every theme follows the same approach. For example, when you point Stylist to your theme directory, it should have the 
following directories (if it needs them):

    /public/stylesheets
    /public/javascripts
    /public/images
    /views/

Then, when you make calls like the following:

    return View::make('layout.application');

It'll look in your theme's directory: /views/layout/ for application.blade.php. Simple huh?

When dealing with assets, Stylist requires the Illuminate\Html library, but instead of using the HTML class, you use Stylist's Theme facade:

    {{ Theme::image('path/to/image.png') }}

This will look for the image in your theme's directory first and foremost: /public/themes/active-theme/images/path/to/image.png

This same approach is applied to your styles, js and any other static assets. Whenever you wish to use theme assets, make sure you use the Theme class.

This means that when you make a call to say, Theme::image, the output url in your HTML will actually look like the following:

    /themes/active-theme/images/path/to/image.png

Of course, if you don't want Stylist to manage that for you, simply use the usual HTML facade.

There's one step we're still missing - and that's the publishing of your theme assets. This isn't a necessary step - you can easily 
just copy your theme's assets from it's directory, into the appropriate directory in the public directory in Laravel 5. You simply
need to ensure that before you publish, your themes are available and registered. Service providers are a great place to do this.

    public function register()
    {
        Stylist::registerPaths(Stylist::discover('/path/to/my/themes'));    
    }
 
Then simply run the publish command:
 
    php artisan stylist:publish
 
Or, if you want to publish a select theme:
 
    php artisan stylist:publish ThemeName
    
You'll then have your theme's assets published to their associated directories. It's important to note that the returned array must
contain array elements that point to the the THEME directory, not the theme's ASSETS directories. This is because stylist will try
to work with the theme and its json file, and publish the required files.

## Theme inheritance

Themes can have parent themes. What does this mean? It means that you can request a view, and Stylist will first look to the child 
theme that you have activated, and work its way up a tree. This is really great if you like a particular theme but want to customise 
just a single view file.

### Defining a parent

It's very easy to define a parent for a theme. You simply define the parent theme inside your theme.json:

    "parent": "Another theme"

This will ensure that Stylist will first look in your theme's directories for files and assets, and then look in the parent's theme 
directories. If your theme's parent also has a parent, then it will continue looking up the tree until it finds the file.

Parents do not need to be activated for your theme to make use of them. Only your theme needs to be activated. However, they do need 
to be registered. This may be handled by the package that manages your theme, or you can register it yourself.

### Stylesheets

Themes can also inherit stylesheets. In order to do this, a child theme must have a stylesheet name that is identical to its parent. If
this is the case, then the parent CSS file will be loaded first, followed by the child's css theme. This makes it very easy to create
"skins" for themes, by simply overloading certain styles.

## Helper methods

Stylist has a few helper methods as well, to ease development.

    Theme::url()

When used in a view, this method would return the relative path to a theme's public directory. You can also use it to access any file:

    Theme::url('favicon.ico')

