<?php

namespace LaravelKlarna\KlarnaPayments\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelKlarna\KlarnaPayments\KlarnaPayments
 */
class KlarnaPayments extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LaravelKlarna\KlarnaPayments\KlarnaPayments::class;
    }
}
