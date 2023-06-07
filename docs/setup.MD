# Initial setup

  Before you do anything on this project you should do the following:
- Clone project from the repository url `git clone ...`
- Copy .env.example to .env file and fill in the parameters for you local development
- Install dependencies with `composer install` This project runs php 8.0 for now
  - Use administration@statik.be as username and unlimited applications key on https://spatie.be/profile/purchases for Media Library Pro.
- Run `php artisan migrate` to migrate the database tables

To get some initial data you can use the following seeder:
```php
php artisan db:seed --class=InitialSetupSeeder
```

This will add a lot of data to start with:
- device types
- device type issues
- completed repair statuses
- image categories
- mail_templates
- ....


After that you can manually add the Tenants for the 2 subsites that run on this project:
You can change the domain name to your own liking.
```sql
INSERT INTO `tenants` (`name`, `code`, `domains`, `created_at`, `updated_at`) VALUES
('Repairconnects', 'replog', '[\"replog.local.dev\"]', '2022-02-01 07:53:29', '2022-02-01 07:53:29');
```
```sql
INSERT INTO `tenants` (`name`, `code`, `domains`, `created_at`, `updated_at`) VALUES
('Repair guidance tool', 'repgui', '[\"repgui.local.dev\"]', '2022-02-01 07:53:29', '2022-02-01 07:53:29');
```


## Automated processes
There are some processes that run in the background. These are pretty basic and common. They run in a crontab on the server.
- Schedule
- Queue
- Sitemap regeneration
```shell
* * * * * /usr/bin/php artisan schedule:run >> /dev/null 2>&1
* * * * * /usr/bin/php artisan queue:work --queue=default --delay=0 --memory=512 --sleep=3 --tries=3 --timeout=300 --stop-when-empty >> /storage/logs/worker.log 2>> storage/logs/worker.log

0 0 * * 0 /usr/bin/php artisan sitemap:generate >> /dev/null 2>&1
```
