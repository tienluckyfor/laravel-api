
# Codeby/Laravel-API

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
