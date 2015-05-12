### Prismic.io Blog Engine
[![alt text](https://travis-ci.org/prismicio/blogtemplate.png?branch=master "Travis build")](https://travis-ci.org/prismicio/blogtemplate)


This application is a "batteries included", ready to use blog engine. It is designed with document types
similar to existing CMS like Wordpress, **blog posts** and **pages**.

#### Getting started

* Download the [latest release](https://github.com/prismicio/blogtemplate/releases)
* Unzip locally or on your server
* Copy `config-sample.php` to `config.php`, edit `PRISMIC_URL` and if needed `PRISMIC_TOKEN`.
* That's it!

Running on your local machine:

> `php -S localhost:8000`

Some remarks:

* Your repository must be a clone of http://blogtemplate.prismic.io/. You can modify the default document masks, but that may require adapted the php source code.
* For the best performances, it is strongly recommended to enable APC to activate the cache. You can also use another caching system such as [memcached](http://memcached.org/), see the Prismic [Developer's Manual](https://developers.prismic.io/documentation/VBgeDDYAADMAz2Rw/developers-manual#cache) for more details.
* If you cloned the repository instead of downloading the zip, you need to install dependencies using [composer](https://getcomposer.org/): `composer install`.*

#### Configuring the theme

The default theme colors and fonts can be changed directly from the Writing Room, without having to edit the theme or redeploy the application.

You can either change the default theme, or create a new one and set the active theme by pointing the "theme" bookmark to the theme you want to use.

#### Activating Disqus comments

If you want to give visitors the opportunity to leave comments on your blog posts, you can activate the Disqus comments.

* Register your site on [Disqus](https://disqus.com/admin/create/)
* Edit `config.php` to update the DISQUS related variables

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

Most of the application (routes and controllers) are located in the [app/app.php](https://github.com/prismicio/blogtemplate/blob/master/app/app.php) file.

#### License

This software is licensed under the Apache 2 license, quoted below.

Copyright 2015 Prismic.io (http://www.prismic.io).

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this project except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0.

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
