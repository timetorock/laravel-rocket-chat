<?php

namespace Timetorock\LaravelRocketChat\Provider;

use Illuminate\Support\ServiceProvider;
use Timetorock\LaravelRocketChat\Client\ChannelClient;
use Timetorock\LaravelRocketChat\Client\ChatClient;
use Timetorock\LaravelRocketChat\Client\GroupClient;
use Timetorock\LaravelRocketChat\Client\ImClient;
use Timetorock\LaravelRocketChat\Client\IntegrationClient;
use Timetorock\LaravelRocketChat\Client\LivechatClient;
use Timetorock\LaravelRocketChat\Client\RocketChatClient;
use Timetorock\LaravelRocketChat\Client\SettingClient;
use Timetorock\LaravelRocketChat\Client\UserClient;
use Timetorock\LaravelRocketChat\Console\Commands\GenerateRocketChatAdminToken;

class LaravelRocketChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateRocketChatAdminToken::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/laravel-rocket-chat.php' => config_path('laravel-rocket-chat.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('laravel-rocket-chat-client', RocketChatClient::class);
        $this->app->bind('rc-user-client', UserClient::class);
        $this->app->bind('rc-setting-client', SettingClient::class);
        $this->app->bind('rc-channel-client', ChannelClient::class);
        $this->app->bind('rc-group-client', GroupClient::class);
        $this->app->bind('rc-im-client', ImClient::class);
        $this->app->bind('rc-chat-client', ChatClient::class);
        $this->app->bind('rc-integration-client', IntegrationClient::class);
        $this->app->bind('rc-livechat-client', LivechatClient::class);
    }
}
