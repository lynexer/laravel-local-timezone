<?php

namespace lynexer\LaravelLocalTimezone\Support\Traits;

trait RetrievesTimezone {
    protected function getTimezoneFromIp(?string $ip): array {
        $info = geoip()->getLocation($ip);

        return [
            'timezone' => ($info['time_zone'] ?? [])['name'] ?? ($info['timezone'] ?? null),
            'default' => $info['default'] ?? false
        ];
    }
}
