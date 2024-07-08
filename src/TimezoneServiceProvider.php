<?php

namespace lynexer\LaravelLocalTimezone;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use lynexer\LaravelLocalTimezone\Listeners\Auth\UpdateTimezone;

class TimezoneServiceProvider extends ServiceProvider {
    protected $defer = false;

    public function boot(): void {
        if (!class_exists('AddTimezoneToUsersTable')) {
            $this->publishes(
                [
                    __DIR__ . '/database/migrations/add_timezone_to_users_table.php.stub' => database_path(
                        '/migrations/' . date('Y_m_d_His') . '_add_timezone_to_users_table.php'
                    )
                ],
                'migrations'
            );
        }

        AliasLoader::getInstance()->alias(
            'LocalTimezone',
            \lynexer\LaravelLocalTimezone\Support\Facades\LocalTimezone::class
        );

        $this->registerEventListener();

        $this->publishes([__DIR__ . '/config/timezone.php' => config_path('timezone.php')], 'config');
    }

    public function register(): void {
        $this->app->bind('timezone', Timezone::class);

        $this->mergeConfigFrom(__DIR__ . '/config/timezone.php', 'timezone');
    }

    private function registerEventListener(): void {
        Event::listen(
            config('timezone.timezone_check.events', null) ?? [
                \Illuminate\Auth\Events\Login::class,
                \Laravel\Passport\Events\AccessTokenCreated::class
            ],
            config('timezone.timezone_check.listener', null) ?? UpdateTimezone::class
        );
    }
}
