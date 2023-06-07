# Laravel base install

This is a starting point to set up a new laravel project.

The repository contains several branches with different functionalities added to the base install. The bare bones base
install is available on the master branch.

## Installation for your new project

-   Clone the repository
-   Remove the .git directory and this README.md (write a project specific one! ;-)
-   run `php artisan key:generate` to generate a unique application ID (to avoid all our projects have the same key)
-   run `php artisan code:set 123456` to set the code of the staging lock. This is set to **574716** (STATIK in L33T speech) by default.

**TIP:** Run `php artisan self-diagnosis` after the installation to check if your Laravel is properly configured.

## Branches

The following branches are available:

-   **master:** a versatile base install with:
    -   [Laravel Localisation](https://github.com/mcamara/laravel-localization)
    -   [Laravel Translatable](https://github.com/spatie/laravel-translatable)
    -   [Laravel-lang](https://github.com/caouecs/laravel-lang)
    -   [Telescope](https://laravel.com/docs/5.7/telescope)
    -   [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
    -   [Laravel Self-Diagnosis Tests](https://github.com/beyondcode/laravel-self-diagnosis)
    -   [SEO tools](https://github.com/artesaos/seotools)
    -   [Guzzle HTTP](https://github.com/guzzle/guzzle/)
    -   [Laravel Collective Forms & HTML](https://github.com/laravelcollective/html)
    -   [Honeypot](https://github.com/msurguy/Honeypot)
    -   [Laravel Blade Javascript](https://github.com/spatie/laravel-blade-javascript)
    -   [Spatie Permissions](https://github.com/spatie/laravel-permission)
-   **nova:** adds Laravel Nova to the base install - TODO
    -   Laravel Nova

## Installed libraries

### Laravel Localization

-   **docs:** https://github.com/mcamara/laravel-localization
-   **config:** Configure the available locales in `config/laravellocalization.php` in the array `$supportedLocales`. The
    order is important, since this is the order in which the locales will be shown in the language switch.

### Laravel Translatable

-   **docs:** https://github.com/spatie/laravel-translatable
-   **config:** The fallback locale for the model translations can be set in `config\translatable.php`.
-   **usage:** Add `HasTranslations` trait and add all database columns that need to be translated in `$translatable`.
    Also make sure the database columns are of the JSON data type.

```php
use HasTranslations;

public $translatable = ['name'];
```

### Laravel-lang

The translated lang files for Laravel.

-   **docs:** https://github.com/caouecs/laravel-lang

### Laravel debugbar

This is a package to integrate PHP Debug Bar with Laravel 5. It includes a ServiceProvider to register the debugbar and
attach it to the output. You can publish assets and configure it through Laravel. It bootstraps some Collectors to work
with Laravel and implements a couple custom DataCollectors, specific for Laravel. It is configured to display Redirects
and (jQuery) Ajax Requests.

-   **docs:** https://github.com/barryvdh/laravel-debugbar
-   **config:** The debugbar is only shown when the `APP_DEBUG` environment variable is set to `true`.

### SEO tools

SEOTools is a package for Laravel 5+ and Lumen that provides helpers for some common SEO techniques.

-   **docs:** https://github.com/artesaos/seotools
-   **config:** check `config\seotools.php`

### Guzzle HTTP

Guzzle is a PHP HTTP client that makes it easy to send HTTP requests and trivial to integrate with web services.

-   **docs:** https://github.com/guzzle/guzzle/

### Laravel Collective Forms & HTML

-   **docs:** https://laravelcollective.com/

### Honeypot

-   **docs:** https://github.com/msurguy/Honeypot
-   **usage:**

```
{!! Form::open('contact') !!}
    ...
    {!! Honeypot::generate('my_name', 'my_time') !!}
    ...
{!! Form::close() !!}
```

### Telescope

Laravel Telescope is an elegant debug assistant for the Laravel framework.

-   **docs:** https://laravel.com/docs/5.7/telescope
-   **config:**
    -   Telescope is available on local environments at `/admin/telescope`.
    -   Every day the database log of Telescope is cleaned up.

### Laravel Self-Diagnosis Tests

-   **docs:** https://github.com/beyondcode/laravel-self-diagnosis
-   **usage:** run `php artisan self-diagnosis`

### Laravel Blade Javascript

This package contains a Blade directive to export values to JavaScript.

-   **docs:** https://github.com/spatie/laravel-blade-javascript
-   **usage:**

### Spatie Permissions

This package allows to add user permissions and roles to the database.

-   **docs:** https://github.com/spatie/laravel-permission

## Frontend development

### Takeoff

Run following commands to get started with [takeoff](https://github.com/statikbe/webpack).

```
yarn run takeoff:install
yarn run watch
```

### Configure hot reloading

With hot reloading enabled, the browser updates automatically when .js or .scss/.css file changes are detected.

#### Important!

`yarn run watch` and `yarn run dev` will not work when the .env file contains a DEV_SERVER_URL

#### Add environment variables to your local .env file

```env
./.env
DEV_SERVER_URL=https://[project code].local.statik.be:3000/
DEV_SERVER_PORT=3000
DEV_SERVER_HOST=[project code].local.statik.be
```

#### Update all references to js and css in the blade templates

```php
./resources/views/layouts/base.blade.php
@if (env('DEV_SERVER_URL'))
<script type="text/javascript" src="{{env('DEV_SERVER_URL')}}js/main.js"></script>
@else
<script type="text/javascript" src="js/main.js"></script>
@endif
```

#### Start the dev server

```bash
yarn hot
```

## Environments

An environment file is provided for staging and live. These extra environment variables are available on top of the
defaults and variables provided by the installed libraries:

-   **GOOGLE_TAGMANAGER_ID:** If a GTM ID is provided, the javascript code will be included in the layouts.base template.

The `postdeploy.sh` script replaces the `.env` file with the `.env.staging` or `.env.live` file on the staging and live
servers respectively.
