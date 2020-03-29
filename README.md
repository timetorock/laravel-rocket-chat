laravel-rocket-chat is a rest client package for Laravel to communicate with Rocket.Chat API.

## How to install

``` bash
composer require timetorock/laravel-rocket-chat
```

## Config && Facades

Open your Laravel config file config/app.php and in the `$providers` array add the service provider for this package.

```php
\Timetorock\LaravelRocketChat\Provider\LaravelRocketChatServiceProvider::class
```

## Publish config for admin user

Generate the configuration file running in the console (only if you added LaravelRocketChatProvider) :
```
php artisan vendor:publish --tag=config
```

Write api url, admin credentials, otherwise you'll need to login as user to make requests.
By default this package login as admin to make requests.

## Example

```php
<?php

$userClient = new \Timetorock\LaravelRocketChat\Client\UserClient();
$userClient->create(new \Timetorock\LaravelRocketChat\Models\User([
        'email'=> 'test@test.com',
        'name' => 'test',
        'password' => '12345',
        'username' => 'test',
]))

?>
```
