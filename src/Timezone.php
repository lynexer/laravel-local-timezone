<?php

namespace Lynexer\LaravelLocalTimezone;

use Carbon\Carbon;

class Timezone {
    protected function format(Carbon $date): string {
        $timezone = $date->format('e');
        $parts = explode('/', $timezone);
        $formatted = str_replace('_', ' ', end($parts));

        if (count($parts) > 1) {
            $formatted .= ', ' . $parts[0];
        }

        return $formatted;
    }

    public function toLocal(?Carbon $date): ?Carbon {
        if ($date === null) {
            return null;
        }

        $timezone =
            auth()
                ->user()
                ->getTimezone() ??
            (config('timezone.default', null) ?? config('app.timezone'));

        return $date->copy()->setTimezone($timezone);
    }

    public function convertToLocal(?Carbon $date, ?string $format = null, bool $display_timezone = false): string {
        $date = $this->toLocal($date);

        if ($date === null) {
            return config('timezone.empty_date', 'Empty');
        }

        $formatted = $date->format($format ?? config('timezone.format', 'jS F Y g:i:a'));

        if ($display_timezone) {
            return $formatted . ' ' . $this->format($date);
        }

        return $formatted;
    }

    public function convertFromLocal(mixed $date): Carbon {
        return Carbon::parse(
            $date,
            auth()
                ->user()
                ->getTimezone()
        )->setTimezone('UTC');
    }
}
