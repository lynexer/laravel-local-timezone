<?php

namespace lynexer\LaravelLocalTimezone\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \lynexer\LaravelLocalTimezone\Timezone
 */
class LocalTimezone extends Facade {
    public static function getFacadeAcessor(): string {
        return 'timezone';
    }
}
