<?php

namespace Digkill\YooKassaLaravel;

use Illuminate\Support\Facades\Facade;

class YooKassaFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return YooKassa::class;
    }
}
