<?php

namespace Lynexer\LaravelLocalTimezone\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lynexer\LaravelLocalTimezone\Timezone
 */
class LocalTimezone extends Facade {
    public static function getFacadeAcessor(): string {
        return 'timezone';
    }
}
