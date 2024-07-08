<?php

namespace Lynexer\LaravelLocalTimezone\Support\Traits;

use InvalidArgumentException;

trait FlashesMessage {
    protected function flashMessage(string $type, string $message, ?string $key): void {
        match ($type) {
            'laravel' => $this->flashLaravelMessage($message, $key),
            'laracasts' => $this->flashLaracastsMessage($message),
            'mercuryseries' => $this->flashMercuryseriesMessage($message),
            'spatie' => $this->flashSpatieMessage($message),
            'mckenziearts' => $this->flashMckenzieartsMessage($message),
            default => throw new InvalidArgumentException("Invalid message type: $type")
        };
    }

    protected function flashLaravelMessage(string $message, ?string $key): void {
        request()
            ->session()
            ->flash($key ?? config('timezone.messages.default.key', 'warning'), $message);
    }

    protected function flashLaracastsMessage(string $message): void {
        flash()->success($message);
    }

    protected function flashMercuryseriesMessage(string $message): void {
        flashy()->success($message);
    }

    protected function flashSpatieMessage(string $message): void {
        flash()->success($message);
    }

    protected function flashMckenzieartsMessage(string $message): void {
        notify()->success($message);
    }
}
