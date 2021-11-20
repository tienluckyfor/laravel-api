
#Part 1. Codeby/Laravel-API

A simple server API helper RestFull GET, PUT, DELETE, POST requests and store it cloud server.

## Installation
Install via composer

```bash
composer require codeby/laravel-api
```

### Register Service Provider
    
**Note! This and next step are optional if you use laravel>=8.0 with package auto discovery feature.**

Add service provider to **config/app.php** in **providers** section

```php
Codeby\LaravelApi\APIServiceProvider::class,
```

### Register Facade
Register package facade in **config/app.php** in **aliases** section

```php
'LaravelApi' => \Codeby\LaravelApi\LaravelApi::class,
```

### Publish Configuration File
```bash
php artisan laravel-api:publish
```

#Part 2. Setup remote api

***Step 1***: Register free account at https://api.codeby.com
![](https://imgur.com/ywQHjqP.png)

***Step 2***: Add new an `Api` object then `submit the form`
![](https://imgur.com/h9o8E9N.png)

***Step 3***: Add new a `Resource` object then `submit the form`,
with field name is `users`
![](https://imgur.com/f2LbNsp.png)

***Step 4***: Add new a `Dataset` object then `submit the form`
with field name is `setup-laravel.dev`
![](https://imgur.com/K3y84kF.png)

***Step 5***: Choose `setup-laravel.dev` from `Rallydata's` list,
then click info button to get `TOKEN key`
![](https://imgur.com/uu87r3v.png)
![](https://imgur.com/45Y9roK.png)

***Step 6***: Import Postman copy `Postman Collection` and `Postman Environment`
then paste to `Import` Postman
![](https://imgur.com/YbuKNAx.png)
