### Prismic.io Blog Engine
[![alt text](https://travis-ci.org/prismicio/blogtemplate.png?branch=master "Travis build")](https://travis-ci.org/prismicio/blogtemplate)


This application is a "batteries included", ready to use blog engine. It is designed with document types
similar to existing CMS like Wordpress:

* Blog posts
* Pages

#### Getting started

* Download the [latest release](https://github.com/prismicio/blogtemplate/releases)
* Unzip locally or on your server
* Install dependencies using [composer](https://getcomposer.org/): `composer install`. Be sure to have the last version of composer: `composer self-update`.
* Edit `config.php` to point `PRISMIC_URL` to your repository (should be a clone of http://blogtemplate.prismic.io/), and adjust `PRISMIC_TOKEN`.
* That's it!

Running locally:

* `php -S localhost:8000`

*Note: for the best performances, it is strongly recommended to enable APC to activate the cache.*

#### Selecting a theme

This blog template comes with 4 standard themes, inspired from Wordpress themes: `default`, `twentytwelve`, `twentythirteen` and `twentyfourteen`.

The theme can be switched from the default one to another provided theme, by setting `PI_THEME` in `config.php` to the name of choosen one.

#### Writing a theme

The theme API is designed to be as close as possible as the Wordpress theme API. Themes are located in the `themes` directory.

The following files must be present in each theme directory:

* index.php
* page.php
* single.php
* content.php
* author.php
* category.php (only if you use categories)
* tag.php (only if you use tags)

> You can copy the `default` theme directory to a `custom` directory, to write your theme by customizing the default one.

As for the provided themes, the name of your custom theme is to be set as `PI_THEME` to switch it on.

#### Customizing the blog engine

The best way to adapt the blog engine to your need is to simply fork the code base and make the modifications
you need, directly in the code.

The application is based on the [Slim microframework](http://www.slimframework.com/).

Most of the application (routes and controllers) are located in the [app/index.php](https://github.com/prismicio/blogtemplate/blob/master/app/app.php) file.

#### License

This software is licensed under the Apache 2 license, quoted below.

Copyright 2015 Prismic.io (http://www.prismic.io).

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this project except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0.

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
