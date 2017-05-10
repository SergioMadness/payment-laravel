<?php namespace professionalweb\payment\facades;

use Illuminate\Support\Facades\Facade;

/**
 * Static proxy for \professionalweb\payment\Payment
 * @package professionalweb\payment\facades
 */
class Payment extends Facade
{
    protected static function getFacadeAccessor()
    {
        return '\Payment';
    }
}