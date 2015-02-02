### Prismic.io Blog Engine

This application is a "batteries included", ready to use blog engine. It is designed with document types
similar to existing CMS like Wordpress:

* Blog posts
* Pages

#### Getting started

Deploying from the zip (not available yet):

* Unzip locally or on your server
* Make sure APC is enabled. For MAMP: in Preferences->PHP->Cache, select APC
* Edit `config.php` to point to your repository (should be a clone of http://blogtemplate.prismic.io/)
* That's it!

Running locally:

* `php -S localhost:8000`

#### Writing a theme

The theme API is designed to be as close as possible as the Wordpress theme API. Themes are located in the `themes`
directory.

The following files must be present:

* index.php
* page.php
* single.php
* content.php
* author.php
* category.php (only if you use categories)
* tag.php (only if you use tags)

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
