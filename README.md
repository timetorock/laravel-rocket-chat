laravel-rocket-chat is a rest client package for Laravel 6.x, 7.x, 8.x, 9.x to communicate with Rocket.Chat API.

## How to install

``` bash
composer require timetorock/laravel-rocket-chat
```

## Config && Facades

Open your Laravel config file config/app.php and in the `$providers` array add the service provider for this package.

```php
\Timetorock\LaravelRocketChat\Provider\LaravelRocketChatServiceProvider::class
```

## Publish a config for admin user

Generate the configuration file running in the console (only if you added LaravelRocketChatProvider) :
```
php artisan vendor:publish --tag=config
```

It is possible to use Admin API token or admin user/password pair to make requests. 
My advice is to create admin API token and make requests with it. 

###  Warning:

User/password will create token on each request, this way is not scalable at all and this behaviour will be removed in next versions.

## Generate admin API Token

To generate admin API userID/token pair you can use command delivered with this package:

`php artisan rc:admin:generate`

By default, it will set `RC_ADMIN_ID` and `RC_ADMIN_TOKEN` variables.

You can use `--show` option just to fetch a new pair of credentials.
You can use `--force` to force the operation to run when in production.

Command will also use env `RC_ADMIN_USERNAME` and `RC_ADMIN_PASSWORD` parameters if they exist in env file.
This option is useful when you want for example to run a command on a schedule.

## Example

```php
<?php

use Timetorock\LaravelRocketChat\Client\UserClient;
use Timetorock\LaravelRocketChat\Models\User;

$userClient = new UserClient();
$userObject = $userClient->create(new User([
        'email'=> 'test@test.com',
        'name' => 'test',
        'password' => '12345',
        'username' => 'test',
]));

$userID = $userObject->getId();
$user = $userObject->getAuthToken();

?>
```
