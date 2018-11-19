<?php

namespace Piclou\Ikcms\Facades;

use Illuminate\Support\Facades\Facade;

class IkForm extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ikform';
    }
}
