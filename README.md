<img src="sharepair-logo.png"/>

# REPAIR PROJECT

This project contains a total of 4 different projects. These are explained bellow.
Being a multi tenancy project there are some domain names you need to configure.

## Ecosystem
- Backend: [PHP Laravel](https://laravel.com/)
- Frontend: [Inertia with VueJs](https://inertiajs.com/) (REPLOG) and [Blade](https://laravel.com/docs/9.x/blade) (REPGUI)
- Admin system: [Laravel Nova](https://nova.laravel.com/)
- API v1: REST API documented using swagger
- CSS Framework: [Tailwindcss](https://tailwindcss.com/)
- Components pack: [repair components](https://www.npmjs.com/package/@statikbe/repair-components)
- Mail driver: [Postmark](https://postmarkapp.com/)

## General installation.
This project currently runs on `PHP 8.0`.

You can use your own development environment (Valet, Mamp, DDEV, ...)<br/>
Default parameters for the use of [DDEV](https://ddev.com/) are present. And are easily setup this way.

_You can read the [setup.md](docs/setup.md) file in the docs folder which is more detailed or follow the next steps._
- Clone project `git clone url`
- Install dependencies `composer install`
- Run `artisan migrate` to migrate the database


### General setup
More info can be read in [the setup docs](docs/setup.md)


---
As stated previously this project consists of four projects.

## 1. Repair connects
This is the main project and is [Repair connects](https://www.repairconnects.org).
This is where all the repairers and organisations come together to share their experiences and knowledge.
Everyone can register their devices and get in contact with repairers and organisations, in person or on events.

#### Backend
Routes can be found in `routes/web/replog.php`.

### Frontend
The content related to REPLOG is provided to the frontend using [Inertia](https://inertiajs.com/).
That means you can write PHP as if you are sending data to your blade views and Inertia will make sure the frontend in VueJS is fully interactive.

The frontend assets are located in the `/resources` folder.
For installing packages and assets run the following commands in the root:
```shell
nvm use
yarn
yarn dev / yarn watch
```

NOTE: Assets are build on the server for this project so no built assets are committed to the git repo. 

Stack:
- TailwindCSS 2.1.*
- PostCSS / Sass
- Vue 2
- Webpack

We extend the tailwind config with the repair components package, so you can use vue/tailwindcss components in this project.
For more information look [here](https://github.com/statikbe/repair-components).

## 2. guidance sharepair
This is the guidance tool and is mainly a static page website with dynamic content based on some actions.
This is currently used for [Guidance tool](https://www.guidance.sharepair.org)

#### Backend
Routes can be found in `routes/web/repgui.php`.

#### Frontend
This project is made with Blade and [Livewire](https://laravel-livewire.com/).

The frontend assets are located in the `/repgui` folder. So navigate there `cd repgui`.
After that run the following commands:

```shell
nvm use
yarn
yarn dev / yarn watch
```

Everything gets build in the `/public` under the map `/repgui`.
So don't forget to prefix your assets with `/repgui`.

The templates of this project are located in the `resources/views/repgui` folder.
Only the livewire components are in the `resources/views/livewire` folder this is because of livewire itself.

NOTE: Assets are build on the server.

Stack:

- TailwindCSS 1.7.*
- PostCSS / No Sass
- TypeScript
- Webpack


## 3. API

The [api](https://repairconnects.org/api/v1/documentation) is mostly used by [REPMAP](https://mapping.sharepair.org) and the ORDP.
But anyone can access the data in the api. 

### Backend
Api routes can be found in `routes/api.php`. Everything is returned as json in a REST API fashion (using [Laravel Resources](https://laravel.com/docs/9.x/eloquent-resources)). 

### Documentation
Documentation is generated and shown using swagger.

To generate updated swagger docs use the following command:
```php artisan l5-swagger:generate```


## 4. Statistics Embed
Repair connects also has some statistics templates that can be included on other websites.

The following url is used for getting data `https://www.repairconnects.org/api/repair/statistics`

For now there are 4 different views that can be used
- Data based on the location UUID
  - `?location={uuid}`
- Data based on the Organisation UUID
  - `?organisation={uuid}`
- Data based on a specific bbox geocoordinates
  - eg Leuven: `?bbox=50.74275597758379%2C4.576492309570313%2C51.02520102968731%2C4.834671020507813`
- Data based on a specific Event
  - `?event={slug}`
- General data about all Organisations/Locations/events

## 5. Tutorials Embed

#### Backend
#### Frontend

The frontend build is located in the `repgui` folder.

Currently there are 3 endpoints made for the tutorials:

- `tailoff/js/tutorialsEmbed.ts` This is the default one of our own theme from the repair projects: https://www.guidance.sharepair.org/nl
- `tailoff/js/repairtogetherEmbed.ts` This one is made for external use: https://repairtogether.be/nl/
- `tailoff/js/testaankoopEmbed.ts` This one is made for external use: https://www.test-aankoop.be/

The external embeds include their own custom css to rebrand the embed.

- `tailoff/css/repairtogetherEmbed.css`
- `tailoff/css/testaankoopEmbed.css`

These files use css variables the make easy changes on colors, etc...

##### Creating a new theme for external partners
To create a new theme for an external partner you can copy the `tailoff/js/repairtogetherEmbed.ts` file and rename it.
This as well for the css, copy `tailoff/css/repairtogetherEmbed.css` and rename the file.

NOTE: The endpoint has to be added in the webpackconfig in the repgui folder. Add the new endpoint to the `entry` object on line 38. Example: `newtheme: getSourcePath('js/newthemeEmbed.ts')`

Some extra imports can be added in the layout file: `resources/views/repguiembed/layouts/base.blade.php`.
Here is also some logic to use the right assets for the right theme.


## Generate php helper
The entire application had ide helper data stored in PHPDoc. These can be regenerated using the following command:
```php artisan ide-helper:models -M```


## Generate ERD model
If you want an overview of the data structure and all the relations you can run the following command.
(This package is only installed with the composer dev dependencies)
``` php artisan generate:erd output.svg --format=svg```
