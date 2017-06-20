# BBC Alpha

The [alpha.bbc](https://alpha.bbc/) platform.

[![Current build status][travis]](https://travis-ci.org/bbc/alpha)
[![Apache 2.0 licensed][license]](#license)
![Implemented in PHP][language]
[![Follow @BBC][twitter]](https://twitter.com/BBC)

## Table of contents

* [Introduction](#introduction)
* [Requirements](#requirements)
* [Building Alpha](#building-alpha)
* [Serving Alpha](#serving-alpha)
* [Architecture](#architecture)
  * [Experience layer](#experience-layer)
  * [Component layer](#component-layer)
  * [Service layer](#service-layer)
* [Contributing](#contributing)
* [Information for BBC Staff](#information-for-bbc-staff)
* [License](#license)

## Introduction

BBC Alpha is a platform which is intended to do a number of things:—

* find out what a "minimum viable BBC Online" might *really* look like and be capable of;
* provide a place to try out new ideas, and to perform rapid innovation, by being able to switch key components (such as media players) for alternative implementations easily, including depending upon user characteristics, location, and so on;
* to provide a single codebase which is able to interact with the [service layer](#service-layer) in a uniform fashion;
* to provide new features for editorial teams, such as deep links into content management tools (and eventually, potentially in-place editing); and
* to allow users and developers alike to try things out using real content served by BBC Online today.

There are no plans for a "BBC Beta" at this time.

**This is an experiment, and it may fail completely**.

## Requirements

You will need:

* A Unix-like build environment (Linux, macOS, FreeBSD, and so on). [Cygwin](https://www.cygwin.com) may work but is untested. If you’re on Windows, you may find it easiest to work in a Linux [virtual machine](https://www.virtualbox.org), or via [SSH](https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html) to a remote host.
* A web server capable of performing rewrites and invoking [PHP](http://php.net); Alpha is mainly used with [Apache](http://httpd.apache.org) and [`mod_php5`](http://php.net/manual/en/install.unix.apache2.php), but other servers such as Lighttpd and Nginx ought to work fine if appropriately configured.
* If you're working with [Git](https://git-scm.com) clones (rather than distribution tarballs), or if you need to modify the build logic for any reason, then you'll need GNU [Autoconf](https://www.gnu.org/software/autoconf/) and [Automake](https://www.gnu.org/software/automake/).

All of the above can be installed on most Linux distributions using your system's package manager.

On macOS, Apache and PHP 5 are provided out of the box (see [this article](https://apple.stackexchange.com/questions/153774/start-apache-httpd-on-boot) for instructions on enabling Apache), but Autoconf, Automake, and their own dependencies will require installation—either by building from source, or by using distributions such as [MacPorts](https://www.macports.org).

## Building Alpha

Alpha uses GNU Autoconf and Automake, a build system which has is very popular throughout the Unix world for distributing open source software, because the scripts and Makefiles it generates have minimal external dependencies and can be updated easily using tools available in nearly every distribution in common use. Autoconf and Automake also ensure that packaging is straightforward, because it supports well-known configuration parameters and things like the `DESTDIR` variable for installing to a staging root.

If you’re going to build a Git clone, you will need to perform slightly different inital steps to if you’re working with a distribution tarball:

```
$ git clone https://github.com/bbc/alpha
$ cd alpha
$ git submodule update --init --recursive
$ autoreconf -i
```

whereas if you've downloaded a tarball:

```
$ tar zxvf /path/to/alpha-X.Y.Z.tar.gz
$ cd alpha-X.Y.Z
```

Then, you can invoke the `configure` script, and build the tree:

```
$ ./configure
$ make
```

Finally, you can install a built tree to a local directory (defaulting to `/opt/bbc/share/alpha`):

```
$ sudo make install
```

You can alter the installation prefix by providing the usual Autoconf options:

```
$ ./configure --prefix=/usr
$ make
$ sudo make install
```

Alpha has some specific options for overriding its installation paths (see also <q>[Serving Alpha](#serving-alpha)</q>, below):
    
Option                 |     Meaning
-----------------------|-------------------------------------------------------
`--with-webdir`        | Install web-facing resources to the provided path instead of `${prefix}/share/alpha`
`--with-componentsdir` | Install bundled components to the provided path instead of `${webdir}/components`
`--with-staticdir`     | Install static resources to the provided path instead of `${webdir}/static`

Note that where command-line tools or daemons are built, they will be installed to `${bindir}`, `${sbindir}`, and so on: that is, their installation locations will be affected by `--prefix`, but not by the above three options.

## Serving Alpha

To aid in development, a [built](#building-alpha) tree can be served directly, simply by pointing a virtual host at the `experience/public` directory. The build logic creates symbolic links within the tree to ensure that the web server can find the right resources in the right locations. If they don’t exist already, the build logic will also create `config.php` files for you so that you can serve the site straight away. A sample configuration for Apache 2.x is provided in [`experience/config/apache2.conf`](experience/config/apache2.conf), and contributions of similar configurations for other web servers are welcome.

If you run `sudo make install` from the top-level directory, by default Alpha will install to `/opt/bbc/share/alpha`, which will contain three key subdirectories, amongst others:

* `public` contains the [Experience Layer](#experience-layer) document root
* `static` contains the Experience Layer static resources
* `components` contains any [components](#component-layer), each of which has its own `public` and `static` directories.

* You can use symbolic links or `mod_alias` (or your web server’s equivalent) to serve all three sets of resources from a single virtual host—this is the way that the source tree is arranged once it’s been built.
* You can serve any combination of the three on separate virtual hosts, or even physical hosts—just ensure that the `config.php` files are updated to specify the correct `STATIC_ROOT` and `COMPONENTS_ROOT` definitions.

By default the static resources for each component are bundled into the component directory itself. If you specify `--with-staticroot=/path/to/directory` when you invoke the `configure` script, *all* of the static resources will be installed into this directory instead of their normal locations.

You might then choose, for example, to serve all of these static resources from a low-specification virtual machine or an Amazon S3 bucket, with suitable caching in front of them.

## Architecture

The Alpha architecture consists of several layers:

### Experience layer

The [experience layer](experience) is responsible for HTML page generation and fetching data from the [service layer](#service-layer) in order to do that.

Generated pages include server- or edge-side includes (SSIs and ESIs), depending upon configuration, which are calls to the [component layer](#component-layer) to generate page fragments based upon specific fragments.

The experience layer includes templates for the different *kinds* of pages that Alpha serves: for example, there’s a template for clips, another for news stories, one for the homepage, and so on.

### Component layer

[Components](components) are small, focussed services whose purpose is to generate page fragments which can be incorporated into an Alpha web page either through server-side incudes (SSIs) or edge-side includes (ESIs). 

An example of a component is the BBC Media Player, which is used by News, iPlayer, and so on to provide audio and video playback in the browser. By encapsulating it as a component, the page template doesn’t have to worry about embed codes, and so on—instead, it can provide the programme ID (PID) to the media player component and incorporate whatever HTML it returns. If we then wish to try out an alternative media player—for example one which provides virtual reality capabilities—we can do that within Alpha by switching to a different component on those pages with minimal complication.

Components can be written in any language, provided they have an endpoint which will emit HTML page fragments in response to an HTTP request.

An example (written in PHP) can be found in the [boilerplate](components/boilerplate) component.

### Service layer

The service layer already exists at the BBC, and it powers the live BBC Online site, as well as the various mobile and digital TV apps. Alpha re-uses these existing services in order to be able to serve the same content to users as BBC Online does today.

Examples of existing services include PIPs (which keeps track of programme information), Electron (which looks after content-managed pages), and CPS (the BBC News content management system).

## Bugs and enhancements

Bug reports and feature requests relating to Alpha _specifically_ should be filed via [Github issues](https://github.com/bbc/alpha/issues).

Please do not file requests asking about programmes, news reporting, or similar: they're not specific to BBC Alpha, and the team has no control over these sorts of things.

Issues with the live BBC Online site should be submitted using the <q>[How can I report a technical fault on BBC Online?](http://www.bbc.co.uk/faqs/report_fault)</q> links.

## Contributing

This project has been released by the BBC as [open source](#license) software. A team within the BBC is responsible for its day-to-day maintenance, but contributions from others are welcome.

If you’d like to contribute to Alpha, [fork this repository](https://github.com/bbc/alpha/fork) and commit your changes to the
`develop` branch.

For larger changes, you should create a feature branch with
a meaningful name, for example one derived from the [issue number](https://github.com/bbc/alpha/issues/).

Once you are satisfied with your contribution, open a pull request and describe
the changes you’ve made and a member of the development team will take a look.

## Information for BBC Staff

This is an open source project which is actively maintained and developed
by a team within Design and Engineering. Please bear in mind the following:—

* Bugs and feature requests **must** be filed in [GitHub Issues](https://github.com/bbc/alpha/issues): this is the authoratitive list of backlog tasks.
* [Forking](https://github.com/bbc/alpha/fork) is encouraged! See the “[Contributing](#contributing)” section, above.

## License

Except where otherwise stated, this project is licensed under the terms of the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0)

* Copyright © 2017 BBC

[travis]: https://img.shields.io/travis/bbc/alpha.svg
[license]: https://img.shields.io/badge/license-Apache%202.0-blue.svg
[language]: https://img.shields.io/badge/implemented%20in-PHP-yellow.svg 
[twitter]: https://img.shields.io/twitter/url/http/shields.io.svg?style=social&label=Follow%20@BBC
