<?php

namespace Racklin\PGSchema\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class PGSchema
 *
 * @package Racklin\PGSchema\Facades
 */
class PGSchema extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pgschema';
    }
}
