<?php

namespace Digkill\YooKassaLaravel\Facades;

use Digkill\YooKassaLaravel\YooKassa;
use Illuminate\Support\Facades\Facade;

class YooKassaFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return YooKassa::class;
    }
}
