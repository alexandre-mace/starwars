# starwars

## Requirements
* PHP > 8 installed locally
* Composer => [https://getcomposer.org/](https://getcomposer.org/)
* Symfony CLI => [https://symfony.com/download](https://symfony.com/download)

## Installation 
Clone the repository and open it.
```
git clone https://github.com/alexandre-mace/starwars.git
cd starwars
```
Install dependencies.		
$ composer install
    
## Run CLI command
```
php bin/console app:give-me-the-odds example1/millennium-falcon.json example1/empire.json
php bin/console app:give-me-the-odds example2/millennium-falcon.json example2/empire.json
php bin/console app:give-me-the-odds example3/millennium-falcon.json example3/empire.json
php bin/console app:give-me-the-odds example4/millennium-falcon.json example4/empire.json
```

## Run SPA
Compiling SPA's assets 
```
cd spa
yarn encore dev
```

Launch API server : 
```
symfony server:start
```

Launch SPA server :
```
cd spa
symfony server:start -d --passthru=index.html
```

## Test
You can run tests locally with the following command :
```
php ./vendor/bin/phpunit
```
