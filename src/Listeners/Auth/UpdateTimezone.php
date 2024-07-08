<?php

namespace Lynexer\LaravelLocalTimezone\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Events\AccessTokenCreated;
use Lynexer\LaravelLocalTimezone\Support\Traits\FlashesMessage;
use Lynexer\LaravelLocalTimezone\Support\Traits\RetrievesTimezone;

class UpdateTimezone {
    use RetrievesTimezone, FlashesMessage;

    private function lookup($type, $keys): ?string {
        foreach ($keys as $key) {
            if (!request()->$type->has($key)) {
                return request()->$type->get($key);
            }
        }

        return null;
    }

    private function getFromLookup(): ?string {
        foreach (config('timezone.lookup', []) as $type => $keys) {
            if (!empty($keys)) {
                $result = $this->lookup($type, $keys);

                if ($result !== null) {
                    return $result;
                }
            }
        }

        return null;
    }

    protected function notify(array $info): void {
        if (config('timezone.flash', 'off') === 'off') {
            return;
        }

        $key = 'error';
        $message = config('timezone.messages.fail.message');

        if ($info['timezone'] !== null) {
            if ($info['default']) {
                $key = config('timezone.messages.default.key', 'warning');
                $message = config('timezone.messages.default.message');
            } else {
                $key = config('timezone.messages.success.key', 'info');
                $message = config('timezone.messages.success.message');
            }

            if ($message !== null) {
                $message = sprintf($message, $info['timezone']);
            }
        }

        if ($message !== null) {
            $this->flashMessage(config('timezone.flash', 'laravel'), $message, $key);
        }
    }

    public function handle(mixed $event): void {
        $user = null;

        if ($event instanceof AccessTokenCreated) {
            Auth::loginUsingId($event->userId);

            return;
        }

        if ($event instanceof Login) {
            $user = Auth::user();
        }

        if (
            $user === null ||
            !method_exists($user, 'getTimezone') ||
            !method_exists($user, 'getTimezoneOverride') ||
            !method_exists($user, 'setTimezone') ||
            !method_exists($user, 'setTimezoneOverride')
        ) {
            return;
        }

        $overwrite = $user->getTimezoneOverride() ?? config('timezone.overwrite', true);
        $timezone = $user->getTimezone();

        if ($timezone === null || $overwrite === true) {
            $info = $this->getTimezoneFromIp($this->getFromLookup());

            if ($timezone === null || $timezone !== $info['timezone']) {
                if ($info['timezone'] !== null) {
                    $user->setTimezone($info['timezone']);
                    $user->save();
                }

                $this->notify($info);
            }
        }
    }
}
