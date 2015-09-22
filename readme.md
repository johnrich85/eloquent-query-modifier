## Details
- Rest API built using Laravel 5.
- Makes use of the "pingpong/modules" package to enable modular architecture.

## Installation

- Clone Repo
- Install dependencies via composer (composer.phar install)
- Run Migrations via artisan (php artisan migrate)
- Run module migrations via artisan (php artisan module:migrate, see http://sky.pingpong-labs.com/docs/2.0/testing#example-usage)

## Tests
- Need to install SQLITE drivers for package tests (sudo apt-get install php5-sqlite)
- Module tests can be ran separately via "phpunit --configuration module-test.xml"


## TO DO
- Write tests for authentication.
- Data modelling for products & finish products controller.
- Data modelling for categories, using nested set pattern.
- Set up production site.
- Set up automated deployment.
- Add easily accessible RESTful paths. e.g products/recently_created


 ##TO DO (query string processor package)
 - Handle exceptions
 - Rename the library (query modifier doesn't communicate the purpose)
 - Outstanding Unit tests:
   - Base class?
   - Input config
   - EloquentQueryModifier

